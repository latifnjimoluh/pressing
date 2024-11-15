<?php
// Vérifie si la session est déjà démarrée, sinon démarre la session
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà active
}

$isLoggedIn = isset($_SESSION['user_id']); // Vérifie si l'utilisateur est connecté

// Définir la base URL de votre application
$baseUrl = "http://localhost/pressing"; // Remplacez avec l'URL de votre projet
?>

<header>
    <nav class="navbar">
        <div class="logo">Pressing Pro</div>
        <ul class="nav-links">
            <li><a href="<?php echo $baseUrl; ?>#about">À propos</a></li>
            <li><a href="<?php echo $baseUrl; ?>#services">Services</a></li>
            <li><a href="<?php echo $baseUrl; ?>#contact">Contact</a></li>
            
            <?php if ($isLoggedIn): ?>
                <!-- Si l'utilisateur est connecté, afficher le bouton Déconnexion -->
                <li><a href="<?php echo $baseUrl; ?>/logout.php" class="btn btn-logout">Déconnexion</a></li>
            <?php else: ?>
                <!-- Si l'utilisateur n'est pas connecté, afficher les boutons Connexion et Inscription -->
                <li><a href="<?php echo $baseUrl; ?>/users/login.php" class="btn btn-login">Connexion</a></li>
                <li><a href="<?php echo $baseUrl; ?>/users/signup.php" class="btn btn-register">Inscription</a></li>
            <?php endif; ?>
        </ul>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>
