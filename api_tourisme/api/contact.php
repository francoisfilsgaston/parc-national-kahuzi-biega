<?php

header("Content-Type: application/json");

include "../config/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // ENVOYER UN MESSAGE
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['nom']) || empty($data['email']) || empty($data['message'])) {
            echo json_encode([
                "success" => false,
                "message" => "Tous les champs sont obligatoires"
            ]);
            exit();
        }

        $sql = "INSERT INTO contacts (nom,email,message)VALUES(?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['message']
        ]);
        echo json_encode([
            "success" => true,
            "message" => "Message envoyé avec succès"
        ]);
        break;

    // ADMIN : VOIR LES MESSAGES
    case "GET":
        $sql = "SELECT * FROM contacts ORDER BY id DESC ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "data" => $messages
        ]);
        break;

    default:
        echo json_encode([
            "success" => false,
            "message" => "Méthode non autorisée"
        ]);
}
