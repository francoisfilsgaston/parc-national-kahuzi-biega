<?php

header("Content-Type: application/json");

include "../config/db.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    // GET : Lire les parcs
    case "GET":
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM parc WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $parc = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($parc) {
                echo json_encode([
                    "success" => true,
                    "data" => $parc
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Parc introuvable"
                ]);
            }
        } else {
            $sql = "SELECT * FROM parc ORDER BY id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $parcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                "success" => true,
                "data" => $parcs
            ]);
        }
        break;

    // POST : Ajouter un parc
    case "POST":
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        if (empty($data['nom']) || empty($data['description'])) {
            echo json_encode([
                "success" => false,
                "message" => "Nom et description obligatoires"
            ]);
            exit();
        }

        $sql = "INSERT INTO parc(nom, description, histoire, localisation, superficie, faune, image_principale)VALUES(?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nom'],
            $data['description'],
            $data['histoire'] ?? null,
            $data['localisation'] ?? null,
            $data['superficie'] ?? null,
            $data['faune'] ?? null,
            $data['image_principale'] ?? null
        ]);
        echo json_encode([
            "success" => true,
            "message" => "Parc ajouté",
            "id" => $pdo->lastInsertId()
        ]);
        break;

    // PUT : Modifier un parc
    case "PUT":
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        if (!isset($data['id'])) {
            echo json_encode([
                "success" => false,
                "message" => "ID obligatoire"
            ]);
            exit();
        }

        $sql = "UPDATE parc SET nom=?, description=?, histoire=?, localisation=?, superficie=?, faune=?, image_principale=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['nom'],
            $data['description'],
            $data['histoire'],
            $data['localisation'],
            $data['superficie'],
            $data['faune'],
            $data['image_principale'],
            $data['id']
        ]);

        echo json_encode([
            "success" => true,
            "message" => "Parc modifié"
        ]);
        break;


    // DELETE : Supprimer
    case "DELETE":
        if (!isset($_GET['id'])) {
            echo json_encode([
                "success" => false,
                "message" => "ID obligatoire"
            ]);
            exit();
        }

        $id = $_GET['id'];
        $sql = "DELETE FROM parc WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode([
            "success" => true,
            "message" => "Parc supprimé"
        ]);
        break;

    default:
        echo json_encode([
            "success" => false,
            "message" => "Méthode non autorisée"
        ]);
}
