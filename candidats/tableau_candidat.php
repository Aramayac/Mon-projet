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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- CountUp.js -->
    <script src="https://cdn.jsdelivr.net/npm/countup.js@2.6.2/dist/countUp.umd.js"></script>
    <!-- Swiper.js (carousel) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e0e7ef 0%, #f8fafd 100%);
            min-height: 100vh;
            transition: background .5s, color .5s;
        }

        .dark-mode {
            background: #181e27 !important;
            color: #e4e8ef !important;
        }

        .dark-mode .content-section,
        .dark-mode .offre-card {
            background: #232c3a !important;
            color: #e4e8ef;
        }

        .dark-mode .btn-modern {
            background: #003366 !important;
            color: #fff;
        }

        .dark-mode .footer {
            background: #15191f !important;
        }

        .content-section {
            background: #fff;
            padding: 32px;
            border-radius: 22px;
            box-shadow: 0 6px 36px 0 rgba(0, 102, 153, 0.09);
            margin-bottom: 46px;
            transition: box-shadow .22s cubic-bezier(.77, 0, .18, 1), transform .18s, background .5s, color .5s;
            opacity: 0;
            transform: translateY(24px) scale(.97);
            animation: fadeUp .8s .2s forwards;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .content-section:hover {
            box-shadow: 0 12px 38px 0 rgba(0, 51, 102, .15);
            transform: scale(1.013);
        }

        h3 i,
        h5 i {
            color: #0099cc;
            animation: iconPop .6s;
        }

        @keyframes iconPop {
            0% {
                transform: scale(.7) rotate(-15deg);
            }

            70% {
                transform: scale(1.15) rotate(3deg);
            }

            100% {
                transform: scale(1) rotate(0);
            }
        }

        .avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 2px 12px rgba(0, 102, 153, 0.11);
            border: 3px solid #fff;
            margin-right: 20px;
            transition: box-shadow .18s;
        }

        .avatar:hover {
            box-shadow: 0 6px 22px #80d6ff80;
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
            border-radius: 27px;
            font-weight: 500;
            box-shadow: 0 2px 9px rgba(0, 153, 204, .09);
            transition: background .15s, color .15s, box-shadow .18s, transform .13s;
            padding: 7px 22px;
        }

        .btn-modern:hover,
        .btn-modern:focus {
            background: linear-gradient(90deg, #0099cc 20%, #006699 80%);
            color: #fff;
            transform: scale(1.07);
            box-shadow: 0 6px 22px #0099cc44;
        }

        .badge-status {
            font-size: 1rem;
            padding: 0.55em 1.3em;
            border-radius: 17px;
            letter-spacing: .03em;
            box-shadow: 0 1px 5px #0099cc18;
            animation: badgePop .8s;
        }

        @keyframes badgePop {
            0% {
                transform: scale(.7);
            }

            80% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .offre-card {
            background: linear-gradient(95deg, #f6fdff 70%, #e3f0fa 100%);
            border-left: 5px solid #0099cc;
            border-radius: 16px;
            margin-bottom: 26px;
            box-shadow: 0 2px 12px 0 rgba(0, 153, 204, 0.04);
            padding: 21px 26px;
            transition: box-shadow .16s, transform .12s, background .5s, color .5s;
            opacity: 0;
            transform: translateY(24px) scale(.98);
            animation: fadeUp .7s .35s forwards;
        }

        .offre-card:hover {
            box-shadow: 0 10px 30px 0 rgba(0, 153, 204, 0.17);
            transform: scale(1.016);
        }

        .offre-card h5 {
            font-weight: 600;
            color: #006699;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
        }

        .footer {
            background-color: #003366;
            color: white;
        }

        .section-anim {
            animation: fadeUp .6s .2s forwards;
        }

        .float-btn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 999;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #0099cc, #006699);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px #0099cc33;
            font-size: 2rem;
            transition: box-shadow .2s, background .2s, transform .18s;
            animation: bounceIn 1s .7s both;
        }

        .float-btn:hover {
            box-shadow: 0 8px 40px #0099cc55;
            transform: scale(1.1);
        }

        @keyframes bounceIn {
            0% {
                transform: scale(.6);
            }

            60% {
                transform: scale(1.15);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Swiper carousel */
        .swiper {
            width: 100%;
            padding: 12px 0 34px 0;
        }

        .swiper-slide {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 1px 8px #0099cc11;
            padding: 20px;
        }

        @media (max-width: 900px) {
            .content-section {
                padding: 16px;
            }

            .offre-card {
                padding: 14px 9px;
            }
        }

        @media (max-width: 768px) {
            .profil-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .avatar {
                margin-bottom: 14px;
            }

            .float-btn {
                bottom: 12px;
                right: 12px;
            }
        }

        /* Loader style */
        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #fff8fcf2;
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <!-- Loader animé -->
    <div id="loader">
        <div class="spinner-border text-info" style="width:4rem;height:4rem;" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>

    <!-- Barre de navigation -->
    <?php require_once __DIR__ . '/../includes/header5.php'; ?>

    <!-- Thème switcher -->
    <button id="toggle-theme" class="btn btn-dark position-fixed top-1 end-0 m-3" title="Changer de thème" style="z-index:1200; margin-top:22px;">🌙</button>

    <!-- Contenu principal -->
    <div class="container py-5">

        <!-- Animation de bienvenue SweetAlert2 -->

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let count = localStorage.getItem('salutationProfil');

                if (!count) {
                    count = 0;
                }

                if (parseInt(count) < 2) {
                    Swal.fire({
                        title: "Bienvenue <?= htmlspecialchars($profil['prenom'] ?? '') ?> !",
                        text: "Prêt à trouver le job de tes rêves ?",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2100
                    });

                    localStorage.setItem('salutationProfil', parseInt(count) + 1);
                }
            });
        </script>


        <!-- Section Profil -->
        <div id="profil" class="content-section section-anim">
            <div class="profil-header">
                <form id="avatarForm" action="upload_avatar.php" method="post" enctype="multipart/form-data">
                    <label for="avatarInput" style="cursor:pointer;">
                        <img src="/projet_Rabya/candidats/avatars/<?= htmlspecialchars($profil['avatar'] ?? 'avatar_default.png') ?>" alt="Avatar" class="avatar">
                    </label>
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none" onchange="document.getElementById('avatarForm').submit();">
                </form>
                <div>
                    <h3><i class="bi bi-person-circle me-2"></i> Mon Profil</h3>
                    <p class="mb-1 fw-semibold"><?= htmlspecialchars($profil['prenom'] ?? '') ?> <?= htmlspecialchars($profil['nom'] ?? '') ?></p>
                    <p class="mb-1"><i class="bi bi-envelope"></i> <?= htmlspecialchars($profil['email'] ?? '') ?></p>
                </div>
                <a href="/projet_Rabya/candidats/modifier_profil.php" class="btn btn-primary btn-modern ms-auto">
                    <i class="bi bi-pencil-square"></i> Modifier
                </a>
                <?php if (!$profil_complet): ?>
                    <a href="/projet_Rabya/candidats/completer_profil.php" class="btn btn-warning btn-modern ms-3 shake-anim">
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
                            <a href="/projet_Rabya/candidats/cv/<?= htmlspecialchars($profil['cv']) ?>" class="btn btn-outline-success btn-sm btn-modern" download>
                                <i class="bi bi-download"></i> Télécharger
                            </a>
                            <a href="/projet_Rabya/candidats/cv/<?= htmlspecialchars($profil['cv']) ?>" class="btn btn-outline-primary btn-sm btn-modern">Voir mon CV</a>
                        <?php else: ?>
                            <span class="text-muted">Aucun CV ajouté</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Carrousel d'offres récentes -->
        <?php if (!empty($offres_recent)): ?>
            <div class="content-section section-anim">
                <h3><i class="bi bi-lightning-fill me-2"></i> Offres récentes</h3>
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($offres_recent as $offre): ?>
                            <div class="swiper-slide">
                                <div>
                                    <h5><i class="bi bi-briefcase-fill"></i> <?= htmlspecialchars($offre['titre']) ?></h5>
                                    <p><strong>Entreprise :</strong> <?= htmlspecialchars($offre['nom_entreprise']) ?></p>
                                    <p class="mb-1"><span class="badge bg-info"><?= htmlspecialchars($offre['secteur']) ?></span></p>
                                    <p class="mb-2" style="font-size:.97em;"><?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                                    <a href="#offres" class="btn btn-outline-info btn-modern btn-sm">Voir plus</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Section Offres -->
        <div id="offres" class="content-section section-anim">
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
                    <h5><i class="bi bi-briefcase-fill"></i> <?= htmlspecialchars($offre['titre']) ?></h5>
                    <p class="mb-1"><strong>Entreprise :</strong> <?= htmlspecialchars($offre['nom_entreprise']) ?> <span class="badge bg-info"><?= htmlspecialchars($offre['secteur']) ?></span></p>
                    <p class="mb-1"><strong>Lieu :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                    <p class="mb-2"><?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                    <?php if (in_array($offre['id_offre'], $mes_candidatures)): ?>
                        <button class="btn btn-outline-secondary btn-sm btn-modern" disabled><i class="bi bi-check-circle"></i> Déjà postulé</button>
                    <?php else: ?>
                        <form method="post" action="postuler.php" style="display:inline;">
                            <input type="hidden" name="id_offre" value="<?= $offre['id_offre'] ?>">
                            <button type="submit" class="btn btn-outline-success btn-sm btn-modern scale-anim">
                                <i class="bi bi-send"></i> Postuler
                            </button>
                        </form>
                    <?php endif; ?>
                        <a href="/projet_Rabya/recruteurs/offre_emploi.php?id=<?= $offre['id_offre'] ?>" class="text-end btn btn-outline-primary btn-sm mt-2 btn-modern">
                <i class="bi bi-eye me-1"></i> Voir l’offre
            </a>
                </div>
            <?php endforeach; ?>
         <div class="d-flex justify-content-end mb-3">
            <a href="/projet_Rabya/recruteurs/offres.php" class="btn btn-primary btn-modern">
                <i class="bi bi-list-ul me-1"></i> Voir toutes les offres
            </a>
        </div>
        </div>
       

        <!-- Section Candidatures -->
        <div id="candidatures" class="content-section section-anim">
            <h3><i class="bi bi-file-earmark-check me-2"></i> Mes Candidatures</h3>
            <h5>Vous avez déjà <span id="candid-count">0</span> candidatures envoyées</h5>
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

    <!-- Bouton d'accueil flottant -->
    <a href="/projet_Rabya/index.php" class="float-btn" title="Accueil">
        <i class="bi bi-house-door-fill"></i>
    </a>

    <!-- Pied de page -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Loader animé lors de la candidature
        document.querySelectorAll('form[action="postuler.php"]').forEach(form => {
            form.addEventListener('submit', function() {
                document.getElementById('loader').style.display = 'flex';
            });
        });

        // Compteur animé CountUp.js
        document.addEventListener('DOMContentLoaded', function() {
            var candidCount = <?= count($candidatures) ?>;
            const counter = new CountUp.CountUp('candid-count', candidCount);
            if (!counter.error) counter.start();
        });

        // Thème sombre/clair toggle
        document.getElementById('toggle-theme').onclick = function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
        };
        if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');

        // Swiper.js carousel offres récentes
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.swiper')) {
                new Swiper('.mySwiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    breakpoints: {
                        700: {
                            slidesPerView: 2
                        },
                        1100: {
                            slidesPerView: 3
                        }
                    }
                });
            }
        });

        // Toastr: succès de candidature (si passé en GET)
        <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
            toastr.success('Candidature envoyée avec succès !', 'Succès', {
                timeOut: 2200,
                positionClass: 'toast-bottom-right'
            });
        <?php endif; ?>

        // Apparition animée des sections au scroll
        document.addEventListener('DOMContentLoaded', function() {
            function revealSections() {
                var sections = document.querySelectorAll('.section-anim, .offre-card');
                var trigger = window.innerHeight * 0.95;
                sections.forEach(function(sec) {
                    var rect = sec.getBoundingClientRect();
                    if (rect.top < trigger) sec.classList.add('appear');
                });
            }
            revealSections();
            window.addEventListener('scroll', revealSections);
        });
    </script>
</body>

</html>