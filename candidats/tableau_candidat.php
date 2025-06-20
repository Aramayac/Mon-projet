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
        body {
            background: #f6f8fa;
        }

        .content-section {
            background: #fff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.07);
            margin-bottom: 38px;
            transition: box-shadow .2s;
        }

        .content-section:hover {
            box-shadow: 0 8px 32px 0 rgba(0, 51, 102, .13);
        }

        h3 i {
            color: #006699;
        }

        .avatar {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .10);
            border: 3px solid #fff;
            margin-right: 18px;
        }

        .profil-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .profil-header .btn {
            margin-left: auto;
        }

        .btn-modern {
            border-radius: 25px;
            font-weight: 500;
            box-shadow: 0 2px 6px rgba(0, 51, 102, .06);
            transition: background .15s, color .15s, box-shadow .15s;
        }

        .btn-modern:hover {
            background: #003366;
            color: #fff;
            box-shadow: 0 4px 14px rgba(0, 51, 102, .15);
        }

        .badge-status {
            font-size: 1rem;
            padding: 0.6em 1.1em;
            border-radius: 12px;
        }

        .offre-card {
            background: #f8fafc;
            border-left: 4px solid #006699;
            border-radius: 14px;
            margin-bottom: 22px;
            box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.03);
            padding: 18px 24px;
            transition: box-shadow .15s;
        }

        .offre-card:hover {
            box-shadow: 0 6px 22px 0 rgba(0, 102, 153, 0.14);
        }

        .footer {
            background-color: #003366;
            color: white;
        }

        @media (max-width: 768px) {
            .profil-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .avatar {
                margin-bottom: 12px;
            }
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
            <div class="profil-header">
                <form id="avatarForm" action="upload_avatar.php" method="post" enctype="multipart/form-data" style="display: flex; align-items: center; margin: 0;">
                    <label for="avatarInput" style="cursor:pointer; margin-bottom: 0;">
                       <img src="<?= $profil['avatar'] ?? '/projet_Rabya/candidats/avatars/m1.jpg' ?>?v=<?= time() ?>" alt="Avatar" class="avatar">
                    </label>
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none" onchange="document.getElementById('avatarForm').submit();">
                </form>
                <div>
                    <h3><i class="bi bi-person-circle me-2"></i> Mon Profil</h3>
                    <p class="mb-1"><strong><?= htmlspecialchars($profil['prenom'] ?? '') ?> <?= htmlspecialchars($profil['nom'] ?? '') ?></strong></p>
                    <p class="mb-1"><i class="bi bi-envelope"></i> <?= htmlspecialchars($profil['email'] ?? '') ?></p>
                </div>
                <a href="/projet_Rabya/candidats/modifier_profil.php" class="btn btn-primary btn-modern ms-auto">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>
                <?php if (!$profil_complet): ?>
                    <a href="/projet_Rabya/candidats/completer_profil.php" class="btn btn-warning btn-modern ms-3">
                        <i class="bi bi-exclamation-circle"></i> Compléter
                    </a>
                <?php endif; ?>
            </div>
            <div class="row mt-3">
                <div class="col-md-6 mb-2">
                    <p><strong>Compétences :</strong> <?= $profil['competences'] ? htmlspecialchars($profil['competences']) : 'Non renseigné' ?></p>
                </div>
                <div class="col-md-6 mb-2">
                    <p><strong>CV :</strong>
                        <?php if ($profil['cv']): ?>
                            <a href="/projet_Rabya/candidats/cv/<?= htmlspecialchars($profil['cv']) ?>" target="_blank" class="btn btn-outline-primary btn-sm btn-modern">Voir mon CV</a>
                        <?php else: ?>
                            <span class="text-muted">Aucun CV ajouté</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Section Offres -->
        <div id="offres" class="content-section">
            <h3><i class="bi bi-briefcase me-2"></i> Offres disponibles</h3>
            <?= $message ?>
            <form method="GET" class="row g-2 mb-4">
                <div class="col-md-4"><input type="text" name="motcle" class="form-control" placeholder="Mot-clé..."></div>
                <div class="col-md-3"><input type="text" name="localisation" class="form-control" placeholder="Localisation..."></div>
                <div class="col-md-3"><input type="text" name="secteur" class="form-control" placeholder="Secteur..."></div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-success btn-modern"><i class="bi bi-search me-1"></i> Rechercher</button>
                </div>
            </form>
            <?php foreach ($offres as $offre): ?>
                <div class="offre-card">
                    <h5><?= htmlspecialchars($offre['titre']) ?></h5>
                    <p class="mb-1"><strong>Entreprise :</strong> <?= htmlspecialchars($offre['nom_entreprise']) ?> <span class="badge bg-info"><?= htmlspecialchars($offre['secteur']) ?></span></p>
                    <p class="mb-1"><strong>Lieu :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                    <p class="mb-2"><?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                    <?php if (in_array($offre['id_offre'], $mes_candidatures)): ?>
                        <button class="btn btn-outline-secondary btn-sm btn-modern" disabled><i class="bi bi-check-circle"></i> Déjà postulé</button>
                    <?php else: ?>
                        <form method="post" action="postuler.php" style="display:inline;">
                            <input type="hidden" name="id_offre" value="<?= $offre['id_offre'] ?>">
                            <button type="submit" class="btn btn-outline-success btn-sm btn-modern">
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
                        switch (strtolower($c['statut'])) {
                            case 'acceptée':
                                $badge = 'success';
                                break;
                            case 'refusée':
                                $badge = 'danger';
                                break;
                            default:
                                $badge = 'secondary';
                                break;
                        }
                    ?>
                        <li class="list-group-item p-3">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-5 mb-2 mb-md-0 fw-semibold"><?= htmlspecialchars($c['titre']) ?></div>
                                <div class="col-6 col-md-3">
                                    <span class="badge bg-<?= $badge ?> badge-status"><?= ucfirst($c['statut']) ?></span>
                                </div>
                                <div class="col-6 col-md-4 text-md-center d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="detail_candidature.php?id_candidature=<?= $c['id_candidature'] ?>" class="btn btn-outline-primary btn-sm btn-modern">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                    <a href="boite_reception.php?id_candidature=<?= $c['id_candidature'] ?>" class="btn btn-outline-info btn-sm btn-modern">
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
            <a href="/projet_Rabya/index.php" class="btn btn-outline-secondary btn-modern">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    <!-- Pied de page -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>