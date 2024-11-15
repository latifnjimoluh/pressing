<?php
$host = "localhost"; // Hôte de la base de données
$db_name = "pressing"; // Nom de la base de données
$username = "root"; // Nom d'utilisateur MySQL
$password = ""; // Mot de passe MySQL (s'il y en a un)

// Créer une connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Définir l'attribut pour lancer les exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
