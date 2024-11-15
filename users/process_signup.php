<?php
session_start(); // Démarre une session

// Inclure le fichier de configuration pour la connexion à la base de données
include('../db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire d'inscription
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $No_cni = $_POST['No_cni'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

    try {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT * FROM client WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            echo "L'email existe déjà. Veuillez en choisir un autre.";
        } else {
            // Insérer les données dans la table client
            $stmt = $pdo->prepare("INSERT INTO client (nom, prenom, adresse, telephone, No_cni, email, password) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $adresse, $telephone, $No_cni, $email, $password]);

            // Ouvrir une session et stocker les informations de l'utilisateur
            $_SESSION['user_id'] = $pdo->lastInsertId(); // Récupérer l'ID du client inséré
            $_SESSION['email'] = $email;
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;

            // Rediriger l'utilisateur vers la page d'accueil ou une autre page
            header("Location: ../index.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
