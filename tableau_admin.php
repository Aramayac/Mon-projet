<?php
session_start();
require 'config.php';

// Vérifier si l'utilisateur est bien un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT * FROM users");
$utilisateurs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Tableau de bord Administrateur</h2>

        <h3 class="mt-4">Gestion des Utilisateurs</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $user) : ?>
                    <tr>
                        <td><?= $user['id']; ?></td>
                        <td><?= htmlspecialchars($user['nom']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= $user['role']; ?></td>
                        <td>
                            <a href="bloquer_utilisateur.php?id=<?= $user['id']; ?>" class="btn btn-warning">Bloquer</a>
                            <a href="supprimer_utilisateur.php?id=<?= $user['id']; ?>" class="btn btn-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>