<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

// Vérifier que l'utilisateur est connecté et est un recruteur
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$recruteur = $_SESSION['utilisateur'];

// Gestion du changement de logo
$message_logo = "";
if (isset($_POST['changer_logo']) && isset($_FILES['nouveau_logo']) && $_FILES['nouveau_logo']['error'] === 0) {
    $dossier =  'dossier/';
    if (!is_dir($dossier)) mkdir($dossier, 0777, true);
    $extension = strtolower(pathinfo($_FILES['nouveau_logo']['name'], PATHINFO_EXTENSION));
    $types_valides = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($extension, $types_valides) && getimagesize($_FILES['nouveau_logo']['tmp_name'])) {
        $nom_fichier = uniqid('logo_', true) . '.' . $extension;
        $chemin = $dossier . $nom_fichier;

        if (move_uploaded_file($_FILES['nouveau_logo']['tmp_name'], $chemin)) {
            $update = $bdd->prepare("UPDATE recruteurs SET logo = ? WHERE id_recruteur = ?");
            $update->execute([$nom_fichier, $recruteur['id']]);
            $_SESSION['utilisateur']['logo'] = $nom_fichier;
            $recruteur['logo'] = $nom_fichier;
        } else {
            $message_logo = "Erreur lors de l'envoi du fichier.";
        }
    } else {
        $message_logo = "Fichier non valide. Seules les images sont acceptées (jpg, png, gif, webp).";
    }
}

$sql = $bdd->prepare("SELECT * FROM offres_emploi WHERE id_recruteur = ? ORDER BY date_publication DESC");
$sql->execute([$recruteur['id']]);
$offres = $sql->fetchAll();

$logoPath = !empty($recruteur['logo']) ? 'dossier/' . htmlspecialchars($recruteur['logo']) : 'img/3022.png';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Recruteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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

        .dark-mode .card,
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

        .logo-rond {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #0d6efd;
            box-shadow: 0 2px 18px rgba(0, 102, 204, 0.12);
            background: #fff;
            margin-bottom: 18px;
            cursor: pointer;
            transition: box-shadow 0.2s, transform .15s;
        }

        .logo-rond:hover {
            box-shadow: 0 0 0 8px #0d6efd44;
            opacity: 0.85;
            transform: scale(1.07) rotate(-2deg);
        }

        .file-input {
            display: none;
        }

        .card-infos {
            display: flex;
            align-items: center;
            gap: 36px;
        }

        .info-label {
            width: 170px;
            color: #555;
        }

        .btn-modern {
            border-radius: 29px;
            font-weight: 500;
            box-shadow: 0 2px 9px rgba(0, 153, 204, .09);
            transition: background .16s, color .15s, box-shadow .18s, transform .13s;
            padding: 8px 28px;
        }

        .btn-modern:hover,
        .btn-modern:focus {
            background: linear-gradient(90deg, #0099cc 20%, #006699 80%);
            color: #fff;
            transform: scale(1.08);
            box-shadow: 0 6px 22px #0099cc44;
        }

        .offre-card {
            background: linear-gradient(97deg, #f6fdff 70%, #e3f0fa 100%);
            border-left: 5px solid #0099cc;
            border-radius: 16px;
            margin-bottom: 26px;
            box-shadow: 0 2px 12px 0 rgba(0, 153, 204, 0.07);
            padding: 21px 26px;
            transition: box-shadow .16s, transform .12s, background .5s, color .5s;
            opacity: 0;
            transform: translateY(24px) scale(.98);
            animation: fadeUp .7s .28s forwards;
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

        .badge-status {
            font-size: 1rem;
            padding: 0.55em 1.3em;
            border-radius: 17px;
            letter-spacing: .03em;
            box-shadow: 0 1px 5px #0099cc18;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 900px) {
            .card-infos {
                flex-direction: column;
                align-items: flex-start;
            }

            .logo-rond {
                margin-bottom: 14px;
            }
        }

        @media (max-width: 600px) {
            .card-infos {
                flex-direction: column;
                align-items: flex-start;
            }

            .logo-rond {
                margin-bottom: 14px;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../includes/header_recruteurs.php'; ?>
    <button id="toggle-theme" class="btn btn-dark position-fixed top-0 end-0 m-3" title="Changer de thème" style="z-index:1200;">🌙</button>
    <div class="container py-5">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "Bienvenue <?= htmlspecialchars($recruteur['nom_entreprise']) ?> !",
                    text: "Gérez vos offres d'emploi simplement.",
                    icon: "info",
                    showConfirmButton: false,
                    timer: 2100
                });
            });
        </script>
        <h2 class="text-center mb-4 text-primary" style="padding-top:70px;">
            <i class="bi bi-building"></i> Bienvenue, <?= htmlspecialchars($recruteur['nom_entreprise']) ?> 👋
        </h2>
        <!-- Infos recruteur -->
        <div class="card shadow mb-4 border-0 rounded-4">
            <div class="card-header bg-primary text-white rounded-top-4">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Vos informations</h5>
            </div>
            <div class="card-body card-infos">
                <!-- Logo rond -->
                <div>
                    <form method="post" enctype="multipart/form-data" id="formLogo">
                        <label for="nouveau_logo">
                            <img src="<?= $logoPath ?>" alt="Logo entreprise" class="logo-rond" title="Changer le logo">
                        </label>
                        <input type="file" name="nouveau_logo" id="nouveau_logo" class="file-input" accept="image/*" onchange="document.getElementById('formLogo').submit();">
                        <input type="hidden" name="changer_logo" value="1">
                    </form>
                    <?php if (!empty($message_logo)): ?>
                        <div class="alert alert-info mt-2 py-1 px-2"><?= htmlspecialchars($message_logo) ?></div>
                    <?php endif; ?>
                </div>
                <div>
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="info-label"><i class="bi bi-envelope-fill text-secondary me-2"></i><strong>Email :</strong></td>
                                <td><?= htmlspecialchars($recruteur['email']) ?></td>
                            </tr>
                            <tr>
                                <td class="info-label"><i class="bi bi-building text-secondary me-2"></i><strong>Entreprise :</strong></td>
                                <td><?= htmlspecialchars($recruteur['nom_entreprise']) ?></td>
                            </tr>
                            <tr>
                                <td class="info-label"><i class="bi bi-diagram-3-fill text-secondary me-2"></i><strong>Secteur :</strong></td>
                                <td><?= htmlspecialchars($recruteur['secteur']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-start mt-3">
                        <a href="/projet_Rabya/recruteurs/modifier_recruteur.php" class="btn btn-outline-primary btn-modern btn-sm">
                            <i class="bi bi-pencil-square"></i> Modifier mes informations
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Offres publiées -->
        <div class="card shadow border-0 rounded-4 mt-4">
            <div class="card-header bg-success text-white rounded-top-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Vos offres publiées</h5>
                <a href="/projet_Rabya/recruteurs/ajouter_offre.php" class="btn btn-light btn-modern btn-sm" title="Ajouter une offres">
                    <i class="bi bi-plus-circle me-1"></i> Nouvelle offre
                </a>
            </div>
            <div class="card-body">
                <?php if (count($offres) > 0): ?>
                    <?php foreach ($offres as $offre): ?>
                        <div class="offre-card">
                            <h5><i class="bi bi-briefcase-fill me-1 text-primary"></i> <?= htmlspecialchars($offre['titre']) ?></h5>
                            <p class="mb-1"><i class="bi bi-geo-alt-fill text-muted me-1"></i><strong>Lieu :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                            <p class="mb-1"><i class="bi bi-diagram-3-fill text-muted me-1"></i><strong>Secteur d'activité :</strong> <?= htmlspecialchars($offre['secteur'] ?? '') ?></p>
                            <p class="mb-1"><i class="bi bi-calendar-check-fill text-muted me-1"></i><strong>Date :</strong> <?= htmlspecialchars($offre['date_publication']) ?></p>
                            <p><i class="bi bi-card-text text-muted me-1"></i><strong>Description :</strong> <?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                            <p>
                                <i class="bi bi-info-circle text-muted me-1"></i>
                                <strong>Statut :</strong>
                                <?php
                                if ($offre['statut'] === 'en_attente') {
                                    echo '<span class="badge bg-warning text-dark badge-status">En attente de validation</span>';
                                } elseif ($offre['statut'] === 'masquée') {
                                    echo '<span class="badge bg-danger badge-status">Masquée par l\'administrateur</span>';
                                } elseif ($offre['statut'] === 'publiée') {
                                    echo '<span class="badge bg-success badge-status">Publiée</span>';
                                } else {
                                    echo '<span class="badge bg-secondary badge-status">Inconnu</span>';
                                }
                                ?>
                            </p>
                            <a href="/projet_Rabya/recruteurs/voir_candidature.php?id_offre=<?= $offre['id_offre'] ?>" class="btn btn-outline-primary btn-modern btn-sm">
                                <i class="bi bi-people-fill me-1"></i> Voir les candidatures
                            </a>
                            <div class="text-end mt-2">
                                <a href="/projet_Rabya/recruteurs/modifier_offre.php?id_offre=<?= $offre['id_offre'] ?>"
                                    class="btn btn-primary btn-modern btn-sm me-2" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="supprimer_offre.php?id_offre=<?= $offre['id_offre'] ?>"
                                    class="btn btn-danger btn-modern btn-sm"
                                    title="Supprimer"
                                    onclick="return confirm('Voulez-vous vraiment supprimer cette offre ?');">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Vous n'avez publié aucune offre pour le moment.</p>
                <?php endif; ?>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="/projet_Rabya/index.php" class="btn btn-secondary btn-modern">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Thème sombre/clair toggle
        document.getElementById('toggle-theme').onclick = function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
        };
        if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');

        // Apparition animée des cartes offres au scroll
        document.addEventListener('DOMContentLoaded', function() {
            function revealSections() {
                var sections = document.querySelectorAll('.offre-card');
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
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>