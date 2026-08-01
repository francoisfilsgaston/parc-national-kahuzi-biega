<?php
$host = "localhost";
$user = "root";
$password = "francis@+243";
$dbName = "kahuzi_biega";
$charset = "utf8mb4";

// La construction de la chaîne de connexion
$dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

// On crée un tableau d'options pour personnaliser le comportement de PDO
$options = [
    // Demande à PDO de lever une Exception (une erreur bloquante propre) dès qu'une requête SQL échoue.
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // Par défaut, quand tu récupéreras des données de la BDD, PHP te renverra un tableau associatif propre (ex: $row['nom']).
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Force MySQL à exécuter les vraies requêtes préparées nativement au lieu de les simuler, ce qui améliore la sécurité contre les injections SQL.
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    // On instancie l'objet PDO en lui transmettant nos 4 éléments. 
    $pdo = new PDO($dsn, $user, $password, $options);
    // echo "Connexion BD reussi ";
    // PHP saute directement ici et capture l'erreur
} catch (\PDOException $e) {
    die("Erreur de connexion a la base de donnees :" . $e->getMessage());
}
