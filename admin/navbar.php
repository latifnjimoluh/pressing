<?php
// Vérifier si une session est déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Vérifie si l'utilisateur est un administrateur

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

            <?php if ($isLoggedIn && $isAdmin): ?>
                <!-- Si l'utilisateur est administrateur -->
                <li><a href="<?php echo $baseUrl; ?>/admin_services.php" class="btn btn-admin">Gestion des Services</a></li>
                <li><a href="<?php echo $baseUrl; ?>/admin_users.php" class="btn btn-admin">Gestion des Utilisateurs</a></li>
                <li><a href="<?php echo $baseUrl; ?>/logout.php" class="btn btn-logout">Déconnexion</a></li>
            <?php elseif ($isLoggedIn): ?>
                <!-- Si l'utilisateur est connecté mais pas administrateur -->
                <li><a href="<?php echo $baseUrl; ?>/user_profile.php" class="btn btn-profile">Profil</a></li>
                <li><a href="<?php echo $baseUrl; ?>/logout.php" class="btn btn-logout">Déconnexion</a></li>
            <?php else: ?>
                <!-- Si l'utilisateur n'est pas connecté -->
                <li><a href="<?php echo $baseUrl; ?>/users/login.php" class="btn btn-login">Connexion</a></li>
                <li><a href="<?php echo $baseUrl; ?>/users/signup.php" class="btn btn-register">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>