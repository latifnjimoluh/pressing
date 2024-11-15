<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: connexion/login.php"); // Rediriger vers la page de login si l'utilisateur n'est pas connecté ou n'est pas admin
    exit;
}

$username = $_SESSION['username']; // Récupérer le nom d'utilisateur de la session
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pressing Pro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">Pressing Pro</div>
            <ul class="nav-links">
                <li><a href="services/service.php">Gestion des Services</a></li>
                <li><a href="admin_users.php">Gestion des Utilisateurs</a></li>
                <li><a href="logout.php" class="btn btn-logout">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="dashboard">
            <h2>Bienvenue, <?php echo htmlspecialchars($username); ?>!</h2>
            <div class="dashboard-stats">
                <div class="stat-item">
                    <h3>Services Disponibles</h3>
                    <p>12</p> <!-- Ceci peut être dynamique en récupérant le nombre de services -->
                </div>
                <div class="stat-item">
                    <h3>Utilisateurs</h3>
                    <p>45</p> <!-- Ceci peut être dynamique en récupérant le nombre d'utilisateurs -->
                </div>
                <div class="stat-item">
                    <h3>Commandes Actuelles</h3>
                    <p>30</p> <!-- Ceci peut être dynamique en récupérant le nombre de commandes -->
                </div>
            </div>
            <div class="dashboard-actions">
                <a href="services/service.php" class="btn btn-primary">Gérer les Services</a>
                <a href="admin_users.php" class="btn btn-primary">Gérer les Utilisateurs</a>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
