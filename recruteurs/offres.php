<?php
require_once __DIR__ . '/../configuration/connexionbase.php';
require_once __DIR__ . '/../includes/header_offres.php';

// Récupération des offres d'emploi publiées uniquement
$req = $bdd->query("
    SELECT o.id_offre, o.titre, o.description, o.lieu, o.date_publication, o.date_expiration, o.type_contrat, r.nom_entreprise, r.logo 
    FROM offres_emploi o 
    INNER JOIN recruteurs r ON o.id_recruteur = r.id_recruteur 
    WHERE o.statut = 'publiée'
    ORDER BY o.date_publication DESC
");

$offres = $req->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Toutes les offres d'emploi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <style>
        body {
            background-color: rgb(5, 119, 232);
        }
    </style>
    <div class="container py-5">
        <h2 class="text-center mb-4 text-primary">📢 Toutes les offres d'emploi</h2>

        <?php if (count($offres) > 0): ?>
            <div class="row">
                <?php foreach ($offres as $offre): ?>
                    <?php
                    $logo = !empty($offre['logo']) ? 'dossier/' . htmlspecialchars($offre['logo']) : '/projet_Rabya/igm/3022.jpg';
                    ?>
                    <div class="col-lg-6 col-md-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-body">
                                <!--  Logo entreprise et titre -->
                                <div class="d-flex align-items-center  mb-3">
                                    <img src="<?= $logo ?>" alt="Logo entreprise"
                                        width="50" height="50" class="rounded-circle me-3 ">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <a href="offre_emploi.php?id=<?= $offre['id_offre'] ?>" class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($offre['titre']) ?>
                                            </a>
                                        </h5>
                                        <h6 class="card-subtitle text-muted">
                                            <?= htmlspecialchars($offre['nom_entreprise'] ?? 'Recruteur inconnu') ?>
                                        </h6>
                                    </div>
                                </div>

                                <!--  Informations principales -->
                                <ul class="list-unstyled">
                                    <li><i class="bi bi-geo-alt-fill text-primary"></i> <?= htmlspecialchars($offre['lieu'] ?? 'Lieu non précisé') ?></li>
                                    <li><i class="bi bi-clock text-warning"></i> Publié le : <?= date('d/m/Y', strtotime($offre['date_publication'])) ?></li>
                                    <?php if (!empty($offre['type_contrat'])): ?>
                                        <li><i class="bi bi-briefcase-fill text-success"></i> Type : <?= htmlspecialchars($offre['type_contrat']) ?></li>
                                    <?php endif; ?>
                                    <?php if (!empty($offre['date_expiration'])): ?>
                                        <li><i class="bi bi-calendar-x text-danger"></i> Expire le : <?= date('d/m/Y', strtotime($offre['date_expiration'])) ?></li>
                                    <?php endif; ?>
                                </ul>

                                <!--  Boutons -->
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <a href="/projet_Rabya/recruteurs/offre_emploi.php?id=<?= $offre['id_offre'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> Voir l’offre
                                    </a>
                                    <a href="/projet_Rabya/candidats/postuler.php?id=<?= $offre['id_offre'] ?>" class="btn btn-success btn-sm">
                                        <i class="bi bi-send"></i> Postuler
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="alert alert-info text-center">Aucune offre disponible pour le moment.</div>
        <?php endif; ?>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>

</html>