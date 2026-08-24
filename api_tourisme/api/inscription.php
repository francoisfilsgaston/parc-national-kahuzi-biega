<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../config/db.php";

// AUTORISER UNIQUEMENT POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Méthode HTTP non autorisée."
    ]);

    exit();
}

// RÉCUPÉRER LES DONNÉES
$data = json_decode(file_get_contents("php://input"), true);
// Vérifier que les données sont bien reçues
if (!$data) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Données invalides."
    ]);

    exit();
}
// VÉRIFIER LES CHAMPS
if (empty($data["nom"]) || empty($data["email"]) || empty($data["password"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Tous les champs sont obligatoires."
    ]);

    exit();
}

// NETTOYER LES DONNÉES
$nom = trim($data["nom"]);
$email = trim($data["email"]);
$password = $data["password"];

// VÉRIFIER L'EMAIL
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Adresse email invalide."
    ]);

    exit();
}

// VÉRIFIER LA LONGUEUR DU MOT DE PASSE
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Le mot de passe doit contenir au moins 6 caractères."
    ]);

    exit();
}

// VÉRIFIER SI L'EMAIL EXISTE
try {
    $checkEmail = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $checkEmail->execute([
        $email
    ]);

    if ($checkEmail->fetch()) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Cet email existe déjà."
        ]);

        exit();
    }


    // HASHER LE MOT DE PASSE
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);


    // INSÉRER L'UTILISATEUR
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password) VALUES (?, ?, ?)");
    $stmt->execute([
        $nom,
        $email,
        $passwordHash
    ]);


    // RÉPONSE
    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "message" => "Inscrit avec succès."
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Une erreur est survenue lors de l'inscription."
    ]);
}
