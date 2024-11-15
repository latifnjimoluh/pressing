<?php
// Inclure la configuration de la base de données
require_once 'db_config.php';

session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['user_id']); // Si l'utilisateur est connecté, la session existe

// Récupérer les services depuis la base de données (incluant la description)
$sql = "SELECT * FROM service";
$stmt = $pdo->query($sql);

// Fermer la connexion à la base de données
// L'objet PDO sera automatiquement détruit à la fin du script
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
    <?php include 'navbar.php'; ?>

    <!-- Main content -->
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <h1>Bienvenue chez Pressing Pro</h1>
            <p>Un service de nettoyage professionnel pour vos vêtements et textiles.</p>
        </section>

        <!-- Services Section -->
        <section id="services" class="services">
            <h2>Nos Services</h2>
            <div class="services-grid">
                <?php
                // Afficher les services récupérés depuis la base de données
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='service-item'>";
                        echo "<h3>" . $row['nom_service'] . "</h3>";
                        echo "<p>Prix : " . $row['prix_service'] . "</p>";
                        echo "<p>Description : " . $row['description'] . "</p>"; // Affichage de la description
                        echo "</div>";
                    }
                } else {
                    echo "<p>Aucun service disponible pour le moment.</p>";
                }
                ?>
            </div>
        </section>

        <!-- Service Request Form (Visible only when logged in) -->
        <?php if ($isLoggedIn): ?>
        <section id="request" class="request">
            <h2>Demander un service</h2>
            <form action="process_request.php" method="POST">
                <label for="service">Choisissez un service</label>
                <select id="service" name="service" required>
                    <?php
                    // Remise à zéro de la connexion pour récupérer les services
                    $stmt = $pdo->query("SELECT * FROM service");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['id_service'] . "'>" . $row['nom_service'] . "</option>";
                    }
                    ?>
                </select>
                <button type="submit">Envoyer la demande</button>
            </form>
        </section>
        <?php endif; ?>
    </main>

    <!-- JavaScript for mobile navbar toggle -->
    <script src="script.js"></script>
</body>
</html>
