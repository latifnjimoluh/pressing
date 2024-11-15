<?php
// Inclure la configuration de la base de données
require_once '../../db_config.php';
session_start();

// Vérifie si l'utilisateur est connecté et s'il est un administrateur
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; // Assurez-vous que la session de l'administrateur est correctement définie

if (!$isLoggedIn || !$isAdmin) {
    header("Location: connexion/login.php"); // Rediriger l'utilisateur non connecté ou non administrateur
    exit;
}

// Gestion des opérations CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        // Ajouter un nouveau service
        $nom_service = $_POST['nom_service'];
        $prix_service = $_POST['prix_service'];
        $description = $_POST['description'];
        
        $sql = "INSERT INTO service (nom_service, prix_service, description) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom_service, $prix_service, $description]);
    } elseif (isset($_POST['update_service'])) {
        // Mettre à jour un service existant
        $id_service = $_POST['id_service'];
        $nom_service = $_POST['nom_service'];
        $prix_service = $_POST['prix_service'];
        $description = $_POST['description'];
        
        $sql = "UPDATE service SET nom_service = ?, prix_service = ?, description = ? WHERE id_service = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom_service, $prix_service, $description, $id_service]);
    } elseif (isset($_POST['delete_service'])) {
        // Supprimer un service
        $id_service = $_POST['id_service'];
        $sql = "DELETE FROM service WHERE id_service = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_service]);
    }
}

// Récupérer les services existants
$sql = "SELECT * FROM service";
$stmt = $pdo->query($sql);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services - Pressing Pro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include '../navbar.php'; ?>
    
    <main>
        <section id="admin">
            <h2>Gestion des Services</h2>

            <!-- Formulaire d'ajout de service -->
            <div class="form-container">
                <h3>Ajouter un service</h3>
                <form action="service.php" method="POST">
                    <input type="text" name="nom_service" placeholder="Nom du service" required>
                    <input type="text" name="prix_service" placeholder="Prix du service" required>
                    <textarea name="description" placeholder="Description du service" required></textarea>
                    <button type="submit" name="add_service">Ajouter Service</button>
                </form>
            </div>

            <!-- Liste des services -->
            <div class="service-list">
                <h3>Liste des Services</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nom du Service</th>
                            <th>Prix</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?= htmlspecialchars($service['nom_service']); ?></td>
                                <td><?= htmlspecialchars($service['prix_service']); ?></td>
                                <td><?= htmlspecialchars($service['description']); ?></td>
                                <td>
                                    <!-- Formulaire de mise à jour -->
                                    <form action="service.php" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="id_service" value="<?= $service['id_service']; ?>">
                                        <button type="submit" name="update_service">Mettre à jour</button>
                                    </form>
                                    
                                    <!-- Formulaire de suppression -->
                                    <form action="service.php" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="id_service" value="<?= $service['id_service']; ?>">
                                        <button type="submit" name="delete_service" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    
    <script src="script.js"></script>
</body>
</html>
                            