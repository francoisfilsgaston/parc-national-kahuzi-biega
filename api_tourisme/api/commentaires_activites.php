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
            // Vérifier que l'activité existe
            $check = $pdo->prepare("SELECT id FROM activites WHERE id = ?");
            $check->execute([$activite_id]);

            if ($check->rowCount() == 0) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Activité introuvable."
                ]);
                exit();
            }
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

    // DELETE : Supprimer un commentaire
    case "DELETE":

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data["commentaire_id"]) || empty($data["utilisateur_id"])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Données incomplètes"
            ]);

            exit();
        }

        $commentaire_id = intval($data["commentaire_id"]);
        $utilisateur_id = intval($data["utilisateur_id"]);

        try {
            // Vérifier que le commentaire appartient bien à l'utilisateur
            $check = $pdo->prepare("SELECT id FROM commentaires_activites WHERE id = ? AND utilisateur_id = ?");
            $check->execute([
                $commentaire_id,
                $utilisateur_id
            ]);

            if ($check->rowCount() == 0) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Vous ne pouvez supprimer que vos propres commentaires."
                ]);

                exit();
            }

            $stmt = $pdo->prepare("DELETE FROM commentaires_activites WHERE id = ?");
            $stmt->execute([$commentaire_id]);
            echo json_encode([
                "status" => "success",
                "message" => "Commentaire supprimé."
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
