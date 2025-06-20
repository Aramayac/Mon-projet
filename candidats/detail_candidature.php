<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$candidat_id = $_SESSION['utilisateur']['id'];
$id_candidature = $_GET['id_candidature'] ?? null;

if (!$id_candidature) {
    header("Location: tableau_candidat.php?message=erreur");
    exit();
}

$stmt = $bdd->prepare(
    "SELECT c.*, o.titre, o.description, o.lieu, o.date_publication, r.nom_entreprise
     FROM candidatures c
     JOIN offres_emploi o ON c.id_offre = o.id_offre
     JOIN recruteurs r ON o.id_recruteur = r.id_recruteur
     WHERE c.id_candidature = ? AND c.id_candidat = ?"
);
$stmt->execute([$id_candidature, $candidat_id]);
$candidature = $stmt->fetch();

if (!$candidature) {
    header("Location: tableau_candidat.php?message=erreur");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détail de ma candidature</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .card{
            margin-top: 80px;
        }
    </style>
</head>

<body class="bg-light">

    <?php require_once __DIR__ . '/../includes/header3.php'; ?>

    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="bi bi-clipboard-data-fill me-2 fs-4"></i>
                <span class="fs-5 fw-semibold">Détail de votre candidature</span>
            </div>
            <div class="card-body">
                <h5 class="fw-bold mb-3"><?= htmlspecialchars($candidature['titre']) ?></h5>

                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-building me-1 text-primary"></i> <strong>Entreprise :</strong> <?= htmlspecialchars($candidature['nom_entreprise']) ?></li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-1 text-primary"></i> <strong>Lieu :</strong> <?= htmlspecialchars($candidature['lieu']) ?></li>
                    <li class="mb-2"><i class="bi bi-calendar-event me-1 text-primary"></i> <strong>Date de publication :</strong> <?= htmlspecialchars($candidature['date_publication']) ?></li>
                </ul>

                <div class="mb-4">
                    <strong><i class="bi bi-file-text me-1 text-primary"></i> Description de l'offre :</strong>
                    <p class="mt-2"><?= nl2br(htmlspecialchars($candidature['description'])) ?></p>
                </div>

                <div class="mb-4">
                    <strong><i class="bi bi-info-circle me-1 text-primary"></i> Statut de votre candidature :</strong>
                    <span class="badge bg-<?=
                                            strtolower($candidature['statut']) === 'acceptée' ? 'success' : (strtolower($candidature['statut']) === 'refusée' ? 'danger' : 'secondary')
                                            ?> ms-2">
                        <i class="bi <?=
                                        strtolower($candidature['statut']) === 'acceptée' ? 'bi-check-circle-fill' : (strtolower($candidature['statut']) === 'refusée' ? 'bi-x-circle-fill' : 'bi-hourglass-split')
                                        ?>"></i> <?= ucfirst($candidature['statut']) ?>
                    </span>
                </div>

                <div class="text-end">
                    <a href="tableau_candidat.php#candidatures" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Retour aux candidatures
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>