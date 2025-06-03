<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/connexion_adminstrateur.php'); exit();
}
$candidats = $bdd->query("SELECT * FROM candidats")->fetchAll();
$recruteurs = $bdd->query("SELECT * FROM recruteurs")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h3>Candidats</h3>
    <table class="table table-striped">
        <thead><tr><th>Nom</th><th>Email</th><th>Statut</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($candidats as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['nom'].' '.$c['prenom']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td><?= $c['statut'] ?? 'actif' ?></td>
                <td>
                  <?php if (($c['statut'] ?? '') !== 'bloqué'): ?>
                    <a href="/projet_Rabya/admin/bloquer_users.php?type=candidat&id=<?= $c['id_candidat'] ?>" class="btn btn-warning btn-sm">Bloquer</a>
                  <?php else: ?>
                    <a href="/projet_Rabya/admin/bloquer_users.php?type=candidat&id=<?= $c['id_candidat'] ?>&activer=1" class="btn btn-success btn-sm">Activer</a>
                  <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Recruteurs</h3>
    <table class="table table-striped">
        <thead><tr><th>Entreprise</th><th>Email</th><th>Statut</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($recruteurs as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['nom_entreprise']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= $r['statut'] ?? 'actif' ?></td>
                <td>
                  <?php if (($r['statut'] ?? '') !== 'bloqué'): ?>
                    <a href="/projet_Rabya/admin/bloquer_users.php?type=recruteur&id=<?= $r['id_recruteur'] ?>" class="btn btn-warning btn-sm">Bloquer</a>
                  <?php else: ?>
                    <a href="/projet_Rabya/admin/bloquer_users.php?type=recruteur&id=<?= $r['id_recruteur'] ?>&activer=1" class="btn btn-success btn-sm">Activer</a>
                  <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/../projet_Rabya/admin/tableau_administateur.php" class="btn btn-secondary">Retour au dashboard</a>
</div>
</body>
</html>