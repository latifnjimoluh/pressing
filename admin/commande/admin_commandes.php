<?php
session_start();
require_once '../../db_config.php'; // Connexion à la base de données

// Vérification de l'utilisateur (administrateur)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Récupérer toutes les commandes avec les noms des clients et employés
$sql = "
    SELECT c.idcommande, c.date_commande, c.date_livraison, c.status_commande, c.prix_total, 
           cl.nom AS client_nom, cl.prenom AS client_prenom, 
           em.nom AS employe_nom, em.prenom AS employe_prenom
    FROM commande c
    JOIN client cl ON c.id_client = cl.id_client
    JOIN employe em ON c.id_employe = em.id_employe
";
$stmt = $pdo->query($sql);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajouter une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $date_commande = $_POST['date_commande'];
    $date_livraison = $_POST['date_livraison'];
    $status_commande = $_POST['status_commande'];
    $prix_total = $_POST['prix_total'];
    $id_client = $_POST['id_client'];
    $id_employe = $_POST['id_employe'];
    $idpaiement = $_POST['idpaiement'];

    $sql = "INSERT INTO commande (date_commande, date_livraison, status_commande, prix_total, id_client, id_employe, idpaiement) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$date_commande, $date_livraison, $status_commande, $prix_total, $id_client, $id_employe, $idpaiement]);
    header("Location: admin_commandes.php");
    exit;
}

// Mettre à jour une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $idcommande = $_POST['idcommande'];
    $date_commande = $_POST['date_commande'];
    $date_livraison = $_POST['date_livraison'];
    $status_commande = $_POST['status_commande'];
    $prix_total = $_POST['prix_total'];
    $id_client = $_POST['id_client'];
    $id_employe = $_POST['id_employe'];
    $idpaiement = $_POST['idpaiement'];

    $sql = "UPDATE commande SET date_commande = ?, date_livraison = ?, status_commande = ?, prix_total = ?, id_client = ?, id_employe = ?, idpaiement = ? 
            WHERE idcommande = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$date_commande, $date_livraison, $status_commande, $prix_total, $id_client, $id_employe, $idpaiement, $idcommande]);
    header("Location: admin_commandes.php");
    exit;
}

// Supprimer une commande
if (isset($_GET['delete'])) {
    $idcommande = $_GET['delete'];
    $sql = "DELETE FROM commande WHERE idcommande = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idcommande]);
    header("Location: admin_commandes.php");
    exit;
}

// Récupérer les clients et employés pour les listes déroulantes
$sqlClients = "SELECT id_client, nom, prenom FROM client";
$stmtClients = $pdo->query($sqlClients);
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

$sqlEmployes = "SELECT id_employe, nom, prenom FROM employe";
$stmtEmployes = $pdo->query($sqlEmployes);
$employes = $stmtEmployes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <header>

    <?php include '../navbar.php'; ?>
    </header>

    <main>
        <h1>Gestion des Commandes</h1>

        <h2>Ajouter une Commande</h2>
        <form action="admin_commandes.php" method="POST">
            <input type="datetime-local" name="date_commande" required>
            <input type="datetime-local" name="date_livraison" required>
            <input type="text" name="status_commande" placeholder="Statut" required>
            <input type="text" name="prix_total" placeholder="Prix Total" required>

            <select name="id_client" required>
                <option value="">Sélectionner un Client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?php echo $client['id_client']; ?>"><?php echo $client['nom'] . ' ' . $client['prenom']; ?></option>
                <?php endforeach; ?>
            </select>

            <select name="id_employe" required>
                <option value="">Sélectionner un Employé</option>
                <?php foreach ($employes as $employe): ?>
                    <option value="<?php echo $employe['id_employe']; ?>"><?php echo $employe['nom'] . ' ' . $employe['prenom']; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="number" name="idpaiement" placeholder="ID Paiement" required>

            <button type="submit" name="add">Ajouter</button>
        </form>

        <h2>Liste des Commandes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>Date Commande</th>
                    <th>Date Livraison</th>
                    <th>Statut</th>
                    <th>Prix Total</th>
                    <th>Client</th>
                    <th>Employé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?php echo $commande['idcommande']; ?></td>
                        <td><?php echo $commande['date_commande']; ?></td>
                        <td><?php echo $commande['date_livraison']; ?></td>
                        <td><?php echo $commande['status_commande']; ?></td>
                        <td><?php echo $commande['prix_total']; ?></td>
                        <td><?php echo $commande['client_nom'] . ' ' . $commande['client_prenom']; ?></td>
                        <td><?php echo $commande['employe_nom'] . ' ' . $commande['employe_prenom']; ?></td>
                        <td>
                            <a href="admin_commandes.php?edit=<?php echo $commande['idcommande']; ?>">Modifier</a> |
                            <a href="admin_commandes.php?delete=<?php echo $commande['idcommande']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
