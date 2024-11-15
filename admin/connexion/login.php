<?php
// Inclure la configuration de la base de données
require_once '../../db_config.php';
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Rediriger vers le tableau de bord si déjà connecté
    exit;
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Rechercher l'utilisateur dans la base de données
    $sql = "SELECT * FROM admin WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) {
        // L'utilisateur est authentifié, définir les variables de session
        $_SESSION['user_id'] = $user['id_admin'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Rediriger l'utilisateur vers le tableau de bord
        header("Location: ../index.php");
        exit;
    } else {
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Pressing Pro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">Pressing Pro</div>
            <ul class="nav-links">
                <li><a href="#">Accueil</a></li>
                <li><a href="#">À propos</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="login-form">
            <h2>Connexion</h2>

            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">Se connecter</button>
                <p>Pas encore inscrit ? <a href="register.php">Inscrivez-vous ici</a>.</p>
            </form>

        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
