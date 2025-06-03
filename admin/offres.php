<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';
if (!isset($_SESSION['admin_id'])) { header('Location: /projet_Rabya/admin/connexion_adminstrateur.php'); exit(); }
$offres = $bdd->query("SELECT o.*, r.nom_entreprise FROM offres_emploi o JOIN recruteurs r ON o.id_recruteur = r.id_recruteur")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Offres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h3>Offres d'emploi</h3>
    <table class="table table-striped">
        <thead><tr><th>Titre</th><th>Entreprise</th><th>Lieu</th><th>Statut</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($offres as $o): ?>
            <tr>
                <td><?= htmlspecialchars($o['titre']) ?></td>
                <td><?= htmlspecialchars($o['nom_entreprise']) ?></td>
                <td><?= htmlspecialchars($o['lieu']) ?></td>
                <td><?= $o['statut'] ?? 'publiée' ?></td>
                <td>
                  <?php if (($o['statut'] ?? '') !== 'masquée'): ?>
                    <a href="/projet_Rabya/admin/bloquer_offres.php?id=<?= $o['id_offre'] ?>" class="btn btn-warning btn-sm">Masquer</a>
                  <?php else: ?>
                    <a href="/projet_Rabya/admin/bloquer_offres.php?id=<?= $o['id_offre'] ?>&activer=1" class="btn btn-success btn-sm">Publier</a>
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