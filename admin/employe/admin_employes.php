<?php
session_start();
require_once '../../db_config.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion/login.php"); // Rediriger vers la page de connexion si non administrateur
    exit;
}

// Lire tous les employés
$sql = "SELECT * FROM employe";
$stmt = $pdo->query($sql);
$employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Charger les données d'un employé à modifier
$employeToEdit = null;
if (isset($_GET['edit'])) {
    $id_employe = $_GET['edit'];
    $sql = "SELECT * FROM employe WHERE id_employe = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_employe]);
    $employeToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ajouter un employé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO employe (nom, prenom, telephone, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom, $telephone, $email, $password]);
    header("Location: admin_employes.php");
    exit;
}

// Mettre à jour un employé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id_employe = $_POST['id_employe'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];

    $sql = "UPDATE employe SET nom = ?, prenom = ?, telephone = ?, email = ? WHERE id_employe = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom, $telephone, $email, $id_employe]);
    header("Location: admin_employes.php");
    exit;
}

// Supprimer un employé
if (isset($_GET['delete'])) {
    $id_employe = $_GET['delete'];
    $sql = "DELETE FROM employe WHERE id_employe = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_employe]);
    header("Location: admin_employes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Employés - Pressing Pro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <main>
        <h1>Gestion des Employés</h1>

        <h2><?php echo $employeToEdit ? 'Modifier un employé' : 'Ajouter un employé'; ?></h2>

        <form action="admin_employes.php" method="POST">
            <input type="hidden" name="id_employe" value="<?php echo $employeToEdit ? $employeToEdit['id_employe'] : ''; ?>">
            <input type="text" name="nom" placeholder="Nom" value="<?php echo $employeToEdit ? $employeToEdit['nom'] : ''; ?>" required>
            <input type="text" name="prenom" placeholder="Prénom" value="<?php echo $employeToEdit ? $employeToEdit['prenom'] : ''; ?>" required>
            <input type="text" name="telephone" placeholder="Téléphone" value="<?php echo $employeToEdit ? $employeToEdit['telephone'] : ''; ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo $employeToEdit ? $employeToEdit['email'] : ''; ?>" required>
            <?php if ($employeToEdit): ?>
                <button type="submit" name="update">Modifier</button>
            <?php else: ?>
                <button type="submit" name="add">Ajouter</button>
            <?php endif; ?>
        </form>

        <h2>Liste des Employés</h2>
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
                <?php foreach ($employes as $employe): ?>
                    <tr>
                        <td><?php echo $employe['id_employe']; ?></td>
                        <td><?php echo $employe['nom']; ?></td>
                        <td><?php echo $employe['prenom']; ?></td>
                        <td><?php echo $employe['email']; ?></td>
                        <td>
                            <a href="admin_employes.php?edit=<?php echo $employe['id_employe']; ?>">Modifier</a> |
                            <a href="admin_employes.php?delete=<?php echo $employe['id_employe']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
