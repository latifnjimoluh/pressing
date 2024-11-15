<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['user_id']); // Si l'utilisateur est connecté, la session existe
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Pressing Pro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <div class="logo">Pressing Pro</div>
            <ul class="nav-links">
                <li><a href="#about">À propos</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
                
                <?php if ($isLoggedIn): ?>
                    <!-- Si l'utilisateur est connecté, afficher le bouton Déconnexion -->
                    <li><a href="logout.php" class="btn btn-logout">Déconnexion</a></li>
                <?php else: ?>
                    <!-- Si l'utilisateur n'est pas connecté, afficher les boutons Connexion et Inscription -->
                    <li><a href="users/login.php" class="btn btn-login">Connexion</a></li>
                    <li><a href="users/signup.php" class="btn btn-register">Inscription</a></li>
                <?php endif; ?>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Main content -->
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <h1>Bienvenue chez Pressing Pro</h1>
            <p>Un service de nettoyage professionnel pour vos vêtements et textiles.</p>
        </section>

        <!-- About Section -->
        <section id="about" class="about">
            <h2>À propos de nous</h2>
            <p>Pressing Pro est votre partenaire de confiance pour tous vos besoins de nettoyage et d'entretien de vêtements. Nous offrons des services de qualité pour particuliers et professionnels.</p>
        </section>

        <!-- Services Section -->
        <section id="services" class="services">
            <h2>Nos Services</h2>
            <div class="services-grid">
                <div class="service-item">
                    <h3>Nettoyage à sec</h3>
                    <p>Un traitement professionnel pour vos vêtements délicats.</p>
                </div>
                <div class="service-item">
                    <h3>Blanchisserie</h3>
                    <p>Nettoyage et repassage pour garder vos textiles frais et impeccables.</p>
                </div>
                <div class="service-item">
                    <h3>Réparation et Retouche</h3>
                    <p>Réparez et ajustez vos vêtements pour un ajustement parfait.</p>
                </div>
            </div>
        </section>
    </main>

    <!-- JavaScript for mobile navbar toggle -->
    <script src="script.js"></script>
</body>
</html>
