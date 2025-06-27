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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        body {
            background: linear-gradient(120deg, #e0e7ef 0%, #f8fafd 100%);
            min-height: 100vh;
            font-family: 'Poppins', 'Inter', Arial, sans-serif;
            color: #243046;
        }

        .futur-glow {
            box-shadow: 0 0 32px 6px #00cfff22, 0 2px 24px 0 #c6eaff60;
            border: 1.5px solid #00cfff33;
            background: rgba(255, 255, 255, 0.95);
        }

        .futur-glow-header {
            background: linear-gradient(90deg, #0099cc 80%, #1976d2 100%);
            color: #fff !important;
            border-radius: 21px 21px 0 0;
            box-shadow: 0 2px 15px #00cfff33 inset;
        }

        .logo-rond {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            border: 3.5px solid #00cfff;
            box-shadow: 0 2px 22px #00cfff55;
            background: #fff;
            margin-bottom: 18px;
            cursor: pointer;
            transition: box-shadow 0.2s, transform .15s;
        }

        .logo-rond:hover {
            box-shadow: 0 0 0 9px #00cfff55;
            opacity: 0.87;
            transform: scale(1.09) rotate(-4deg);
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
            color: #0099cc;
        }

        .btn-futur {
            border-radius: 30px;
            font-weight: 500;
            box-shadow: 0 2px 12px #00cfff33;
            padding: 9px 30px;
            border: none;
            background: linear-gradient(90deg, #00cfff 60%, #1976d2 100%);
            color: #fff;
            transition: background .16s, color .16s, box-shadow .16s, transform .12s;
        }

        .btn-futur:hover,
        .btn-futur:focus {
            background: linear-gradient(90deg, #1976d2 30%, #00cfff 100%);
            color: #fff;
            transform: scale(1.07);
            box-shadow: 0 8px 28px #00cfff55;
        }

        .btn-futur-outline {
            border-radius: 30px;
            font-weight: 500;
            box-shadow: 0 2px 10px #00cfff22;
            padding: 9px 30px;
            color: #00cfff;
            background: transparent;
            border: 2px solid #00cfff;
            transition: background .15s, color .2s, box-shadow .13s, transform .12s;
        }

        .btn-futur-outline:hover,
        .btn-futur-outline:focus {
            background: #00cfff;
            color: #fff;
            border: 2px solid #00cfff;
            box-shadow: 0 7px 20px #00cfff55;
            transform: scale(1.06);
        }

        .offre-card {
            background: linear-gradient(97deg, #f6fdff 67%, #e6f8fc 100%);
            border-left: 5px solid #00cfff;
            border-radius: 18px;
            margin-bottom: 28px;
            box-shadow: 0 2px 14px 0 #00cfff18, 0 1.5px 0 #00cfff22 inset;
            padding: 22px 28px;
            transition: box-shadow .17s, transform .13s, background .5s, color .5s;
            opacity: 0;
            transform: translateY(26px) scale(.98);
            animation: fadeUp .8s .29s forwards;
            color: #243046;
        }

        .offre-card h5 {
            font-weight: 600;
            color: #00cfff;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
        }

        .offre-card p,
        .offre-card strong {
            color: #273046;
        }

        .badge-status {
            font-size: 1rem;
            padding: 0.55em 1.3em;
            border-radius: 17px;
            letter-spacing: .03em;
            box-shadow: 0 1px 5px #00cfff18;
        }

        .offre-card:hover {
            box-shadow: 0 14px 34px 0 #00cfff2e, 0 1.5px 0 #00cfff inset;
            transform: scale(1.021);
        }

        .table-borderless td,
        .table-borderless th {
            background: transparent !important;
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

            .offre-card {
                padding: 14px 7px;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../includes/header_recruteurs.php'; ?>
    <div class="container py-5">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let affichages = localStorage.getItem('bienvenueAfficheeCount');

                if (!affichages) {
                    affichages = 0;
                }

                if (parseInt(affichages) < 2) {
                    Swal.fire({
                        title: '<i class="bi bi-building text-primary me-2"></i>Bienvenue <?= htmlspecialchars($recruteur['nom_entreprise']) ?> !',
                        text: "Gérez vos offres d'emploi simplement.",
                        icon: "info",
                        showConfirmButton: false,
                        timer: 2100
                    });

                    localStorage.setItem('bienvenueAfficheeCount', parseInt(affichages) + 1);
                }
            });
        </script>


        <h2 class="text-center mb-4" style="color:#00cfff; letter-spacing:1.2px; padding-top:70px; text-shadow:0 2px 18px #00cfff55;">
            <i class="bi bi-building text-info me-2"></i> Bienvenue, <?= htmlspecialchars($recruteur['nom_entreprise']) ?> <span class="fw-light"></span>
        </h2>

        <!-- Infos recruteur -->
        <div class="card shadow mb-4 border-0 rounded-4 futur-glow">
            <div class="card-header futur-glow-header rounded-top-4">
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
                                <td class="info-label"><i class="bi bi-envelope-fill text-info me-2"></i><strong>Email :</strong></td>
                                <td><?= htmlspecialchars($recruteur['email']) ?></td>
                            </tr>
                            <tr>
                                <td class="info-label"><i class="bi bi-building text-info me-2"></i><strong>Entreprise :</strong></td>
                                <td><?= htmlspecialchars($recruteur['nom_entreprise']) ?></td>
                            </tr>
                            <tr>
                                <td class="info-label"><i class="bi bi-diagram-3-fill text-info me-2"></i><strong>Secteur :</strong></td>
                                <td><?= htmlspecialchars($recruteur['secteur']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-start mt-3 ">
                        <a href="/projet_Rabya/recruteurs/modifier_recruteur.php" class="btn btn-futur-outline btn-sm">
                            <i class="bi bi-pencil-square" style="font-weight: bold ; font-size:large ;color:#0dcaf0 "> Modifier mes informations</i>
                        </a>
                    </div>
                    
                </div>
                
            </div>
        </div>
        <!-- Offres publiées -->
        <div class="card shadow border-0 rounded-4 mt-4 futur-glow">
            <div class="card-header futur-glow-header rounded-top-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Vos offres publiées</h5>
                <a href="/projet_Rabya/recruteurs/ajouter_offre.php" class="btn btn-futur-outline btn-sm " style="font-weight: bold; color:white" title="Ajouter une offre">
                    <i class="bi bi-plus-circle me-1"></i> Nouvelle offre
                </a>
            </div>
            <div class="card-body">
                <?php if (count($offres) > 0): ?>
                    <?php foreach ($offres as $offre): ?>
                        <div class="offre-card">
                            <h5><i class="bi bi-briefcase-fill me-1 text-info"></i> <?= htmlspecialchars($offre['titre']) ?></h5>
                            <p class="mb-1"><i class="bi bi-geo-alt-fill text-secondary me-1"></i><strong>Lieu :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                            <p class="mb-1"><i class="bi bi-diagram-3-fill text-secondary me-1"></i><strong>Secteur d'activité :</strong> <?= htmlspecialchars($offre['secteur'] ?? '') ?></p>
                            <p class="mb-1"><i class="bi bi-calendar-check-fill text-secondary me-1"></i><strong>Date :</strong> <?= htmlspecialchars($offre['date_publication']) ?></p>
                            <p><i class="bi bi-card-text text-secondary me-1"></i><strong>Description :</strong> <?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                            <p>
                                <i class="bi bi-info-circle text-secondary me-1"></i>
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
                            <a href="/projet_Rabya/recruteurs/voir_candidature.php?id_offre=<?= $offre['id_offre'] ?>" class="btn btn-futur-outline btn-sm">
                                <i class="bi bi-people-fill me-1"></i> Voir les candidatures
                            </a>
                            <div class="text-end mt-2">
                                <a href="/projet_Rabya/recruteurs/modifier_offre.php?id_offre=<?= $offre['id_offre'] ?>"
                                    class="btn btn-futur btn-sm me-2" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </div>
                            
                        </div>
                        
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-dark">Vous n'avez publié aucune offre pour le moment.</p>
                <?php endif; ?>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="/projet_Rabya/index.php" class="btn btn-futur-outline">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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