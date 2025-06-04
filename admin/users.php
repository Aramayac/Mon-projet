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
    <title>Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-sm {
            font-weight: bold;
            border-radius: 5px;
        }
        .card{
            margin-top: 80px;
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/../includes/header_admin.php'; ?>
<div class="container py-5">

    <!-- Gestion des Candidats -->
    <div class="card mb-5">
        <div class="card-header1 bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-people-fill icon"></i>Gestion des Candidats</h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Nom</th><th>Email</th><th>Statut</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($candidats as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['nom'].' '.$c['prenom']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td>
                                <span class="badge <?= ($c['statut'] ?? '') === 'bloqué' ? 'bg-danger' : 'bg-success' ?>">
                                    <?= $c['statut'] ?? 'actif' ?>
                                </span>
                            </td>
                            <td>
                                <?php if (($c['statut'] ?? '') !== 'bloqué'): ?>
                                    <a href="bloquer_users.php?type=candidat&id=<?= $c['id_candidat'] ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-person-x"></i> Bloquer
                                    </a>
                                <?php else: ?>
                                    <a href="bloquer_users.php?type=candidat&id=<?= $c['id_candidat'] ?>&activer=1" class="btn btn-success btn-sm">
                                        <i class="bi bi-person-check"></i> Activer
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gestion des Recruteurs -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-building icon"></i>Gestion des Recruteurs</h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Entreprise</th><th>Email</th><th>Statut</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recruteurs as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['nom_entreprise']) ?></td>
                            <td><?= htmlspecialchars($r['email']) ?></td>
                            <td>
                                <span class="badge <?= ($r['statut'] ?? '') === 'bloqué' ? 'bg-danger' : 'bg-success' ?>">
                                    <?= $r['statut'] ?? 'actif' ?>
                                </span>
                            </td>
                            <td>
                                <?php if (($r['statut'] ?? '') !== 'bloqué'): ?>
                                    <a href="bloquer_users.php?type=recruteur&id=<?= $r['id_recruteur'] ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-person-x"></i> Bloquer
                                    </a>
                                <?php else: ?>
                                    <a href="bloquer_users.php?type=recruteur&id=<?= $r['id_recruteur'] ?>&activer=1" class="btn btn-success btn-sm">
                                        <i class="bi bi-person-check"></i> Activer
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="tableau_administateur.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour au Tableau de Bord
        </a>
    </div>

</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
