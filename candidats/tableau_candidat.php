<?php
require_once __DIR__ . '/../configuration/connexionbase.php';
require_once __DIR__ . '/../candidats/logique_candidat.php';


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Candidat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .content-section {
            background-color: rgba(237, 230, 230, 0.92);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        h3 i {
            color: #003366;
        }

        .footer {
            background-color: #003366;
            color: white;
        }
    </style>
</head>

<body>

    <!-- Barre de navigation -->
    <?php require_once __DIR__ . '/../includes/header5.php'; ?>
    <!-- Contenu principal -->
    <div class="container py-5">

        <!-- Section Profil -->
        <div id="profil" class="content-section">
            <h3><i class="bi bi-person-circle me-2"></i> Mon Profil</h3>
            <p><strong>Nom :</strong> <?= htmlspecialchars($profil['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($profil['prenom']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($profil['email']) ?></p>
            <p><strong>Compétences :</strong> <?= $profil['competences'] ? htmlspecialchars($profil['competences']) : 'Non renseigné' ?></p>
            <p><strong>CV :</strong>
                <?php if ($profil['cv']): ?>
                    <a href="/projet_Rabya/candidats/cv/<?= htmlspecialchars($profil['cv']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Voir mon CV</a>
                <?php else: ?>
                    <span class="text-muted">Aucun CV ajouté</span>
                <?php endif; ?>
            </p>
            <a href="/projet_Rabya/candidats/modifier_profil.php" class="btn btn-primary mt-3">
                <i class="bi bi-pencil-square"></i> Modifier mon profil
            </a>
            <?php if (!$profil_complet): ?>
                <a href="/projet_Rabya/candidats/completer_profil.php" class="btn btn-warning mt-3 ms-2">
                    <i class="bi bi-exclamation-circle"></i> Compléter mon profil
                </a>
            <?php endif; ?>
        </div>

        <!-- Section Offres -->
        <div id="offres" class="content-section">
            <h3><i class="bi bi-briefcase me-2"></i> Offres disponibles</h3>
            <?= $message ?>
            <form method="GET" class="row g-2 mb-4">
                <div class="col-md-4"><input type="text" name="motcle" class="form-control" placeholder="Mot-clé..."></div>
                <div class="col-md-3"><input type="text" name="localisation" class="form-control" placeholder="Localisation..."></div>
                <div class="col-md-3"><input type="text" name="secteur" class="form-control" placeholder="Secteur...">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-success"><i class="bi bi-search me-1"></i> Rechercher</button>
                </div>
            </form>
            <?php foreach ($offres as $offre): ?>
                <div class="border rounded p-3 mb-4 bg-white">
                    <h5><?= htmlspecialchars($offre['titre']) ?></h5>
                    <p><strong>Entreprise :</strong> <?= htmlspecialchars($offre['nom_entreprise']) ?> (<?= htmlspecialchars($offre['secteur']) ?>)</p>
                    <p><strong>Lieu :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                    <p><?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                    <?php if (in_array($offre['id_offre'], $mes_candidatures)): ?>
                        <button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-check-circle"></i> Déjà postulé</button>
                    <?php else: ?>
                        <form method="post" action="postuler.php" style="display:inline;">
                            <input type="hidden" name="id_offre" value="<?= $offre['id_offre'] ?>">
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-send"></i> Postuler
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Section Candidatures -->
        <div id="candidatures" class="content-section">
            <h3><i class="bi bi-file-earmark-check me-2"></i> Mes Candidatures</h3>
            <?php if ($candidatures): ?>
                <div class="d-none d-md-flex fw-bold border-bottom pb-2 mb-2" style="font-size:1.08rem;">
                    <div class="col-12 col-md-5">Offre</div>
                    <div class="col-6 col-md-3">Statut</div>
                    <div class="col-6 col-md-4 text-md-center">Action</div>
                </div>
                <ul class="list-group list-group-flush shadow-sm">
                    <?php foreach ($candidatures as $c):
                        $badge = match (strtolower($c['statut'])) {
                            'acceptée' => 'success',
                            'refusée' => 'danger',
                            default => 'secondary',
                        };
                    ?>
                        <li class="list-group-item p-3">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-5 mb-2 mb-md-0 fw-semibold"><?= htmlspecialchars($c['titre']) ?></div>
                                <div class="col-6 col-md-3">
                                    <span class="badge bg-<?= $badge ?>"><?= ucfirst($c['statut']) ?></span>
                                </div>
                                <div class="col-6 col-md-4 text-md-center d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="detail_candidature.php?id_candidature=<?= $c['id_candidature'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                    <a href="boite_reception.php?id_candidature=<?= $c['id_candidature'] ?>" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-chat-dots"></i> Messages
                                    </a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Aucune candidature envoyée.</p>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-start mt-4">
            <a href="/projet_Rabya/index.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>
        </div>

    </div>

    <!-- Pied de page -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>