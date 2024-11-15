<?php
session_start(); // Démarre une session

// Inclure le fichier de configuration pour la connexion à la base de données
include('../db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire de connexion
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Vérifier si l'email existe dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM client WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Si l'email et le mot de passe sont valides, ouvrir une session
            $_SESSION['user_id'] = $user['id_client'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];

            // Rediriger l'utilisateur vers la page d'accueil ou une autre page
            header("Location: ../index.php");
            exit();
        } else {
            echo "Identifiants invalides.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
