<?php
require_once '../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['nom']) && !empty($data['email']) && !empty($data['password'])) {
    $nom = trim($data['nom']);
    $email = trim($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // verifier si l'email existe
    $checkEmail = $pdo->prepare("SELECT *from utilisateurs where email = ? ");
    $checkEmail->execute([$email]);

    if ($checkEmail->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "cet Email existe deja"]);
        exit();
    }
}
try {
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password) VALUE (?, ?, ?)");
    $stmt->execute([$nom, $email, $password]);

    http_response_code(201);
    echo json_encode(["status" => "success", "message" => "Utilisateur inscrit avec succes"]);
} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Donnees incompletes"]);
}
