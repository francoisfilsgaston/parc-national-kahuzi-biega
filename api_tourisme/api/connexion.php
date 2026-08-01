<?php

header("Content-Type: application/json");

require_once '../config/db.php';


// Récupérer les données envoyées par Postman
$data = json_decode(file_get_contents("php://input"), true);

// Vérifier que les champs existent
if (empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Email et mot de passe sont obligatoires"
    ]);

    exit();
}


// Nettoyer les données
$email = trim($data['email']);
$password = $data['password'];


try {

    // Rechercher l'utilisateur avec son email
    $stmt = $pdo->prepare("SELECT id, nom, email, password FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);

    // Récupérer l'utilisateur
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe
    if (!$user) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Email ou mot de passe incorrect"
        ]);

        exit();
    }

    // Vérifier le mot de passe
    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Email ou mot de passe incorrect"
        ]);
        exit();
    }

    // Supprimer le mot de passe de la réponse
    unset($user['password']);

    // Connexion réussie
    http_response_code(200);

    echo json_encode([
        "status" => "success",
        "message" => "Connexion réussie",
        "user" => $user
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erreur serveur"
    ]);
}
