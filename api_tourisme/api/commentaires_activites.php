<?php

header("Content-Type: application/json");
require_once "../config/db.php";

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    // GET : Récupérer les commentaires
    case "GET":

        if (empty($_GET["activite_id"])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "activite_id est obligatoire"
            ]);
            exit();
        }

        $activite_id = intval($_GET["activite_id"]);

        try {

            $stmt = $pdo->prepare("SELECT c.id, c.message, c.date_creation, u.nom FROM commentaires_activites c
                INNER JOIN utilisateurs u ON c.utilisateur_id = u.id WHERE c.activite_id = ? ORDER BY c.date_creation DESC");

            $stmt->execute([$activite_id]);

            $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                "status" => "success",
                "data" => $commentaires
            ]);
        } catch (PDOException $e) {

            http_response_code(500);

            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }

        break;

    // POST : Ajouter un commentaire
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data["activite_id"]) || empty($data["utilisateur_id"]) || empty(trim($data["message"]))) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Données incomplètes"
            ]);
            exit();
        }

        $activite_id = intval($data["activite_id"]);
        $utilisateur_id = intval($data["utilisateur_id"]);
        $message = trim($data["message"]);

        try {
            $stmt = $pdo->prepare("INSERT INTO commentaires_activites(activite_id, utilisateur_id, message)VALUES (?, ?, ?)");
            $stmt->execute([
                $activite_id,
                $utilisateur_id,
                $message
            ]);

            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "Commentaire ajouté avec succès."
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }

        break;

    // Méthode non autorisée
    default:
        http_response_code(405);
        echo json_encode([
            "status" => "error",
            "message" => "Méthode non autorisée"
        ]);
}
