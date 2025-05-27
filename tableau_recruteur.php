<?php
session_start();
require_once 'connexionbase.php';

// Vérifier que l'utilisateur est connecté et est un recruteur
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: connexion.php");
    exit();
}

$recruteur = $_SESSION['utilisateur'];

// Gestion du changement de logo
$message_logo = "";
if (isset($_POST['changer_logo']) && isset($_FILES['nouveau_logo']) && $_FILES['nouveau_logo']['error'] === 0) {// Vérifie que le formulaire a été soumis et qu'un fichier a été uploadé
    // Vérifie que le dossier de destination existe, sinon le crée
    $dossier = 'dossier/';
    if (!is_dir($dossier)) mkdir($dossier, 0777, true);
    $extension = strtolower(pathinfo($_FILES['nouveau_logo']['name'], PATHINFO_EXTENSION));
    $types_valides = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($extension, $types_valides) && getimagesize($_FILES['nouveau_logo']['tmp_name'])) {// Vérifie que le fichier est une image valide
        // Générer un nom de fichier unique pour éviter les collisions
        $nom_fichier = uniqid('logo_', true) . '.' . $extension;
        $chemin = $dossier . $nom_fichier;

        if (move_uploaded_file($_FILES['nouveau_logo']['tmp_name'], $chemin)) {
            // Supprimer l'ancien logo si présent et différent du logo par défaut
            // if (!empty($recruteur['logo']) && file_exists($dossier . $recruteur['logo']) && $recruteur['logo'] !== 'logo_default.png') {//
            //     @unlink($dossier . $recruteur['logo']);
            // }
            // Mettre à jour la base de données
            $update = $bdd->prepare("UPDATE recruteurs SET logo = ? WHERE id_recruteur = ?");
            $update->execute([$nom_fichier, $recruteur['id']]);
            // Mettre à jour la session
            $_SESSION['utilisateur']['logo'] = $nom_fichier;
            $recruteur['logo'] = $nom_fichier;
        } else {
            $message_logo = "Erreur lors de l'envoi du fichier.";
        }
    } else {
        $message_logo = "Fichier non valide. Seules les images sont acceptées (jpg, png, gif, webp).";
    }
}

// Récupérer les offres du recruteur
$sql = $bdd->prepare("SELECT * FROM offres_emploi WHERE id_recruteur = ? ORDER BY date_publication DESC");
$sql->execute([$recruteur['id']]);
$offres = $sql->fetchAll();

$logoPath = !empty($recruteur['logo']) ? 'dossier/' . htmlspecialchars($recruteur['logo']) : 'img/logo_default.png';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Recruteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .logo-rond {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0d6efd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            background: #fff;
            margin-bottom: 16px;
            cursor: pointer;
            transition: box-shadow 0.2s;
        }
        .logo-rond:hover {
            box-shadow: 0 0 0 4px #0d6efd55;
            opacity: 0.8;
        }
        .file-input {
            display: none;
        }
        .info-label {
            width: 160px;
            color: #555;
        }
        .card-infos {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        @media (max-width: 600px) {
            .card-infos {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body class="bg-light">
<?php include 'header3.php'; ?>
<div class="container py-5">
    <!-- Souhait de bienvenue au recruteur  avec son email-->
    <h2 class="text-center mb-4 text-primary">Bienvenue, <?= htmlspecialchars($recruteur['nom_entreprise']) ?> 👋</h2>

    <!-- Infos recruteur -->
    <div class="card shadow mb-4 border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-building me-2"></i>Vos informations</h5>
        </div>
        <div class="card-body card-infos">
            <!-- Logo de l'entreprise en rond + formulaire de changement -->
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
                <div class="text-muted text-center" style="font-size: 0.9em;"></div>
            </div>
            <div>
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="info-label"><strong>Email :</strong></td>
                            <td><?= htmlspecialchars($recruteur['email']) ?></td>
                        </tr>
                        <tr>
                            <td class="info-label"><strong>Entreprise :</strong></td>
                            <td><?= htmlspecialchars($recruteur['nom_entreprise']) ?></td>
                        </tr>
                        <tr>
                            <td class="info-label"><strong>Secteur :</strong></td>
                            <td><?= htmlspecialchars($recruteur['secteur']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Offres publiées -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-success text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Vos offres publiées</h5>
            <a href="ajouter_offre.php" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Nouvelle offre
            </a>
        </div>
        <div class="card-body">
            <?php if (count($offres) > 0): ?>
                <?php foreach ($offres as $offre): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <h5 class="text-dark"><?= htmlspecialchars($offre['titre']) ?></h5>
                        <p class="mb-1"><strong>Lieu :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                        <p class="mb-1"><strong>Date :</strong> <?= htmlspecialchars($offre['date_publication']) ?></p>
                        <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                        <a href="voir_candidature.php?id_offre=<?= $offre['id_offre'] ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-people-fill me-1"></i> Voir les candidatures
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Vous n'avez publié aucune offre pour le moment.</p>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>

</body>
</html>