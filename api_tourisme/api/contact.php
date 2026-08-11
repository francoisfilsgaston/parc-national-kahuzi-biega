<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../config/db.php";


// ==========================================
// MÉTHODE HTTP
// ==========================================

$method = $_SERVER["REQUEST_METHOD"];


// ==========================================
// POST : ENVOYER UN MESSAGE
// ==========================================

if ($method === "POST") {

    // Récupérer les données JSON
    $data = json_decode(
        file_get_contents("php://input"),
        true
    );


    // Vérifier les données
    if (!$data) {

        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Données invalides."
        ]);

        exit();
    }


    // ======================================
    // VÉRIFIER LES CHAMPS
    // ======================================

    if (
        empty($data["nom"]) ||
        empty($data["email"]) ||
        empty($data["message"])
    ) {

        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Tous les champs sont obligatoires."
        ]);

        exit();
    }


    // ======================================
    // NETTOYER LES DONNÉES
    // ======================================

    $nom =
        trim($data["nom"]);

    $email =
        trim($data["email"]);

    $message =
        trim($data["message"]);


    // ======================================
    // VÉRIFIER L'EMAIL
    // ======================================

    if (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Adresse email invalide."
        ]);

        exit();
    }


    // ======================================
    // INSERTION
    // ======================================

    try {

        $sql = "
            INSERT INTO contacts
            (nom, email, message)
            VALUES (?, ?, ?)
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $nom,
            $email,
            $message
        ]);


        // ==================================
        // SUCCÈS
        // ==================================

        http_response_code(201);

        echo json_encode([
            "success" => true,
            "message" => "Votre message a été envoyé avec succès."
        ]);
    } catch (PDOException $e) {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Impossible d'envoyer le message."
        ]);
    }

    exit();
}


// ==========================================
// GET : ADMINISTRATEUR
// ==========================================

if ($method === "GET") {

    try {

        $sql = "
            SELECT *
            FROM contacts
            ORDER BY id DESC
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        $messages =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        echo json_encode([
            "success" => true,
            "data" => $messages
        ]);
    } catch (PDOException $e) {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Impossible de récupérer les messages."
        ]);
    }

    exit();
}


// ==========================================
// MÉTHODE NON AUTORISÉE
// ==========================================

http_response_code(405);

echo json_encode([
    "success" => false,
    "message" => "Méthode HTTP non autorisée."
]);
