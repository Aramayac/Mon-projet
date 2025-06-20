<?php
require_once __DIR__ . '/../configuration/connexionbase.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger text-center mt-5">Aucune offre sélectionnée.</div>';
    require_once 'footer.php';
    exit;
}

$id_offre = intval($_GET['id']);

$req = $bdd->prepare("
    SELECT o.*, r.nom_entreprise, r.email, r.logo
    FROM offres_emploi o
    JOIN recruteurs r ON o.id_recruteur = r.id_recruteur
    WHERE o.id_offre = ?
");
$req->execute([$id_offre]);
$offre = $req->fetch(PDO::FETCH_ASSOC);

if (!$offre) {
    echo '<div class="alert alert-warning text-center mt-5">Offre introuvable.</div>';
    require_once 'footer.php';
    exit;
}

$logo_entreprise = (isset($offre['logo']) && !empty($offre['logo']))
    ? "/projet_Rabya/recruteurs/dossier/" . htmlspecialchars($offre['logo'])
    : "https://cdn-icons-png.flaticon.com/512/3135/3135768.png";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations sur l'offre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/projet_Rabya/css/style.css">
    <style>



    </style>
</head>

<body>
    <?php require_once '../includes/header6.php'; ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Card principale -->
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <!-- Bandeau entreprise -->
                        <div class="d-flex align-items-center mb-4">
                            <img src="<?= $logo_entreprise ?>" alt="Logo entreprise" class="rounded-circle border shadow-sm me-3" style="width:80px; height:80px; object-fit:cover;">
                            <div>
                                <h2 class="mb-1 fw-bold"><?= htmlspecialchars($offre['titre']) ?></h2>
                                <div class="text-secondary">
                                    <i class="bi bi-building"></i>
                                    <?= htmlspecialchars($offre['nom_entreprise']) ?>
                                    <span class="mx-2 text-muted">|</span>
                                    <i class="bi bi-geo-alt"></i>
                                    <?= htmlspecialchars($offre['lieu']) ?>
                                </div>
                            </div>
                        </div>
                        <!-- Badges infos -->
                        <div class="mb-4">
                            <span class="badge bg-primary me-2">
                                <i class="bi bi-briefcase me-1"></i>
                                <?= htmlspecialchars($offre['type_contrat']) ?>
                            </span>
                            <span class="badge bg-success me-2">
                                <i class="bi bi-cash-coin me-1"></i>
                                <?= htmlspecialchars($offre['salaire']) ?> FCFA
                            </span>
                            <span class="badge bg-secondary">
                                <i class="bi bi-calendar-event me-1"></i>
                                Publiée le <?= date('d/m/Y', strtotime($offre['date_publication'])) ?>
                            </span>
                        </div>
                        <!-- Description -->
                        <h5 class="mb-2 text-primary"><i class="bi bi-card-text me-1"></i>Description du poste</h5>
                        <p class="fs-5"><?= nl2br(htmlspecialchars($offre['description'])) ?></p>

                        <!-- Date limite -->
                        <div class="mb-3">
                            <h6 class="mb-1 text-primary"><i class="bi bi-clock-history me-1"></i>Date limite de candidature</h6>
                            <span class="fw-bold"><?= date('d/m/Y', strtotime($offre['date_expiration'])) ?></span>
                        </div>
                        <!-- Secteur -->
                        <div class="mb-4">
                            <h6 class="mb-1 text-primary"><i class="bi bi-diagram-3 me-1"></i>Secteur d'activité</h6>
                            <span><?= htmlspecialchars($offre['secteur'] ?? '') ?></span>
                        </div>
                        <!-- Contact -->
                        <div class="mb-4">
                            <h6 class="mb-1 text-primary"><i class="bi bi-envelope-at me-1"></i>Contact RH</h6>
                            <span><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($offre['nom_entreprise']) ?> - <?= htmlspecialchars($offre['email']) ?></span>
                        </div>
                        <!-- Candidature -->
                        <div class="text-center mt-4">
                            <?php if (isset($_SESSION['id_candidat'])): ?>
                                <form action="postuler.php" method="POST">
                                    <input type="hidden" name="id_offre" value="<?= $offre['id_offre'] ?>">
                                    <button type="submit" class="btn btn-lg btn-primary px-5 fw-bold rounded-pill shadow-sm">
                                        <i class="bi bi-send-check me-1"></i>Postuler à cette offre
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning d-inline-block">
                                    <i class="bi bi-lock-fill"></i>
                                    <a href="/projet_Rabya/connexion.php" class="text-decoration-none text-primary">Connectez-vous</a> en tant que candidat pour postuler.
                                </div>
                                <div class="mt-3">
                                    <a href="/projet_Rabya/index.php" class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="bi bi-arrow-left"></i> Retour aux offres
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- Fin card -->
            </div>
        </div>
    </div>
    <?php require_once '../includes/footer.php'; ?>
</body>

</html>