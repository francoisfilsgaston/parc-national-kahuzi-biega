<?php

header("Content-Type: application/json");
require_once "../config/db.php";

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    // GET : Nombre de likes
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
            $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM likes_activites WHERE activite_id = ?");
            $stmt->execute([$activite_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode([
                "status" => "success",
                "likes" => intval($result["total"])
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
        break;



    // POST : Ajouter un like
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data["activite_id"]) || empty($data["utilisateur_id"])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Données incomplètes"
            ]);
            exit();
        }

        $activite_id = intval($data["activite_id"]);
        $utilisateur_id = intval($data["utilisateur_id"]);

        try {
            // Vérifier si l'utilisateur a déjà aimé
            $check = $pdo->prepare(" SELECT id FROM likes_activites WHERE activite_id = ? AND utilisateur_id = ?");
            $check->execute([
                $activite_id,
                $utilisateur_id
            ]);
            if ($check->rowCount() > 0) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Vous avez déjà aimé cette activité."
                ]);
                exit();
            }

            // Ajouter le like
            $stmt = $pdo->prepare("INSERT INTO likes_activites(activite_id, utilisateur_id)VALUES (?, ?)");
            $stmt->execute([
                $activite_id,
                $utilisateur_id
            ]);
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "J'aime ajouté avec succès."
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
        break;

    // DELETE : Supprimer un like
    case "DELETE":
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data["activite_id"]) || empty($data["utilisateur_id"])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Données incomplètes"
            ]);
            exit();
        }
        $activite_id = intval($data["activite_id"]);
        $utilisateur_id = intval($data["utilisateur_id"]);
        try {

            $stmt = $pdo->prepare(" DELETE FROM likes_activites WHERE activite_id = ? AND utilisateur_id = ?");
            $stmt->execute([
                $activite_id,
                $utilisateur_id
            ]);
            echo json_encode([
                "status" => "success",
                "message" => "J'aime supprimé avec succès."
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
