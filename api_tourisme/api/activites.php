<?php

header("Content-Type: application/json; charset=UTF-8");

include "../config/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // GET : Récupérer les activités
    case "GET":
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM activites WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $activite = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($activite) {
                echo json_encode([
                    "success" => true,
                    "data" => $activite
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Activité introuvable"
                ]);
            }
        } elseif (isset($_GET['parc_id'])) {
            $parc_id = $_GET['parc_id'];
            $sql = "SELECT * FROM activites WHERE parc_id = ? ORDER BY id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$parc_id]);
            $activites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                "success" => true,
                "data" => $activites
            ]);
        } else {
            $sql = "SELECT * FROM activites ORDER BY id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $activites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                "success" => true,
                "data" => $activites
            ]);
        }
        break;

    // POST : Ajouter une activité

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['parc_id']) || empty($data['titre']) || empty($data['description'])) {
            echo json_encode([
                "success" => false,
                "message" => "Les champs parc_id, titre et description sont obligatoires."
            ]);
            exit();
        }
        $sql = "INSERT INTO activites(parc_id, titre, description, image, duree, prix) VALUES (?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['parc_id'],
            $data['titre'],
            $data['description'],
            $data['image'] ?? null,
            $data['duree'] ?? null,
            $data['prix'] ?? null
        ]);

        echo json_encode([
            "success" => true,
            "message" => "Activité ajoutée avec succès.",
            "id" => $pdo->lastInsertId()

        ]);
        break;

    // PUT : Modifier une activité
    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['id'])) {
            echo json_encode([
                "success" => false,
                "message" => "ID obligatoire."
            ]);
            exit();
        }

        $sql = "UPDATE activites SET titre=?, description=?, image=?, duree=?, prix=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['image'] ?? null,
            $data['duree'] ?? null,
            $data['prix'] ?? null,
            $data['id']
        ]);
        echo json_encode([
            "success" => true,
            "message" => "Activité modifiée avec succès."
        ]);
        break;

    // DELETE : Supprimer une activité
    case "DELETE":
        if (!isset($_GET['id'])) {
            echo json_encode([
                "success" => false,
                "message" => "ID obligatoire."
            ]);
            exit();
        }

        $id = $_GET['id'];
        $sql = "DELETE FROM activites WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode([
            "success" => true,
            "message" => "Activité supprimée avec succès."
        ]);
        break;

    default:
        echo json_encode([
            "success" => false,
            "message" => "Méthode HTTP non autorisée."
        ]);
}
