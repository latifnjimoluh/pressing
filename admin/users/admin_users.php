<?php
session_start();
require_once '../../db_config.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion/login.php"); // Rediriger vers la page de connexion si non administrateur
    exit;
}

// Lire tous les utilisateurs
$sql = "SELECT * FROM client";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Charger les données d'un utilisateur à modifier
$userToEdit = null;
if (isset($_GET['edit'])) {
    $id_client = $_GET['edit'];
    $sql = "SELECT * FROM client WHERE id_client = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_client]);
    $userToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ajouter un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $No_cni = $_POST['No_cni'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO client (nom, prenom, adresse, telephone, No_cni, email, password, historique_commande) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom, $adresse, $telephone, $No_cni, $email, $password, '']);
    header("Location: admin_users.php");
    exit;
}

// Mettre à jour un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id_client = $_POST['id_client'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $No_cni = $_POST['No_cni'];
    $email = $_POST['email'];

    $sql = "UPDATE client SET nom = ?, prenom = ?, adresse = ?, telephone = ?, No_cni = ?, email = ? WHERE id_client = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom, $adresse, $telephone, $No_cni, $email, $id_client]);
    header("Location: admin_users.php");
    exit;
}

// Supprimer un utilisateur
if (isset($_GET['delete'])) {
    $id_client = $_GET['delete'];
    $sql = "DELETE FROM client WHERE id_client = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_client]);
    header("Location: admin_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs - Pressing Pro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <main>
        <h1>Gestion des Utilisateurs</h1>

        <h2><?php echo $userToEdit ? 'Modifier un utilisateur' : 'Ajouter un utilisateur'; ?></h2>

        <form action="admin_users.php" method="POST">
            <input type="hidden" name="id_client" value="<?php echo $userToEdit ? $userToEdit['id_client'] : ''; ?>">
            <input type="text" name="nom" placeholder="Nom" value="<?php echo $userToEdit ? $userToEdit['nom'] : ''; ?>" required>
            <input type="text" name="prenom" placeholder="Prénom" value="<?php echo $userToEdit ? $userToEdit['prenom'] : ''; ?>" required>
            <input type="text" name="adresse" placeholder="Adresse" value="<?php echo $userToEdit ? $userToEdit['adresse'] : ''; ?>" required>
            <input type="text" name="telephone" placeholder="Téléphone" value="<?php echo $userToEdit ? $userToEdit['telephone'] : ''; ?>" required>
            <input type="text" name="No_cni" placeholder="Numéro CNI" value="<?php echo $userToEdit ? $userToEdit['No_cni'] : ''; ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo $userToEdit ? $userToEdit['email'] : ''; ?>" required>
            <?php if ($userToEdit): ?>
                <button type="submit" name="update">Modifier</button>
            <?php else: ?>
                <button type="submit" name="add">Ajouter</button>
            <?php endif; ?>
        </form>

        <h2>Liste des utilisateurs</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id_client']; ?></td>
                        <td><?php echo $user['nom']; ?></td>
                        <td><?php echo $user['prenom']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <a href="admin_users.php?edit=<?php echo $user['id_client']; ?>">Modifier</a> |
                            <a href="admin_users.php?delete=<?php echo $user['id_client']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
