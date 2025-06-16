<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/connexion_adminstrateur.php'); exit();
}
$offres = $bdd->query("SELECT o.*, r.nom_entreprise FROM offres_emploi o JOIN recruteurs r ON o.id_recruteur = r.id_recruteur")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Offres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-sm {
            font-weight: bold;
            border-radius: 5px;
        }
        .icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }
        h3{
            margin-top: 10%;
        }
    </style>
</head>
<body>
  <?php include __DIR__ . '/../includes/header_admin.php'; ?>
<div class="container py-5">
    <h3 class="mb-4"><i class="bi bi-briefcase-fill text-primary icon"></i> Gestion des Offres d'emploi</h3>
    <table class="table table-hover">
        <thead class="table-dark">
        <tr>
            <th>Titre</th>
            <th>Entreprise</th>
            <th>Lieu</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($offres as $o): ?>
            <tr>
                <td><?= htmlspecialchars($o['titre']) ?></td>
                <td><?= htmlspecialchars($o['nom_entreprise']) ?></td>
                <td><?= htmlspecialchars($o['lieu']) ?></td>
                <td>
                    <span class="badge <?= ($o['statut'] ?? '') === 'masquée' ? 'bg-danger' : 'bg-success' ?>">
                        <?= ($o['statut'] ?? '') === 'masquée' ? 'Masquée' : 'Publiée' ?>
                    </span>
                </td>
                <td>
                    <?php if (($o['statut'] ?? '') !== 'masquée'): ?>
                        <a href="/projet_Rabya/admin/bloquer_offres.php?id=<?= $o['id_offre'] ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-eye-slash-fill me-1"></i>Masquer
                        </a>
                    <?php else: ?>
                        <a href="/projet_Rabya/admin/bloquer_offres.php?id=<?= $o['id_offre'] ?>&activer=1" class="btn btn-success btn-sm">
                            <i class="bi bi-eye-fill me-1"></i>Publier
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="/projet_Rabya/admin/tableau_administateur.php" class="btn btn-outline-secondary mt-4">
        <i class="bi bi-arrow-left-circle me-1"></i>Retour au tableau de bord
    </a>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
