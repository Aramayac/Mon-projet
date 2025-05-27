<?php
session_start();
require_once 'connexionbase.php';

// Vérifier que l'utilisateur est un recruteur
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: connexion.php");
    exit();
}

// Vérifier que l'id_offre est passé en GET
if (!isset($_GET['id_offre']) || !is_numeric($_GET['id_offre'])) {
    header("Location: tableau_recruteur.php");
    exit();
}

$id_offre = intval($_GET['id_offre']);
$id_recruteur = $_SESSION['utilisateur']['id'];

// Vérification que l'offre appartient bien à ce recruteur
$stmt = $bdd->prepare("SELECT * FROM offres_emploi WHERE id_offre = ? AND id_recruteur = ?");
$stmt->execute([$id_offre, $id_recruteur]);
$offre = $stmt->fetch();

if (!$offre) {
    // L'offre n'existe pas ou n'appartient pas à ce recruteur
    header("Location: tableau_recruteur.php");
    exit();
}

// Récupérer toutes les candidatures à cette offre
$stmt = $bdd->prepare("SELECT c.*, cand.nom, cand.prenom, cand.email, pc.cv 
                       FROM candidatures c
                       JOIN candidats cand ON c.id_candidat = cand.id_candidat
                       LEFT JOIN profils_candidats pc ON c.id_candidat = pc.id_candidat
                       WHERE c.id_offre = ?
                       ORDER BY c.date_candidature DESC
");
$stmt->execute([$id_offre]);
$candidatures = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Candidatures pour <?= htmlspecialchars($offre['titre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-primary"><i class="bi bi-people-fill me-2"></i>Candidatures pour : <?= htmlspecialchars($offre['titre']) ?></h2>
    <a href="tableau_recruteur.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Retour au tableau de bord</a>

    <?php if (count($candidatures) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date de candidature</th>
                    <th>CV</th>
                    <th>Statut</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($candidatures as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nom']) ?></td>
                        <td><?= htmlspecialchars($c['prenom']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['date_candidature']) ?></td>
                        <td>
                            <?php if (!empty($c['cv'])): ?>
                                <a href="dossier/cv/<?= htmlspecialchars($c['cv']) ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> Voir CV
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Non ajouté</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= ucfirst(htmlspecialchars($c['statut'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle"></i> Il n'y a aucune candidature pour cette offre pour le moment.
        </div>
    <?php endif; ?>
</div>
</body>
</html>