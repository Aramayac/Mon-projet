<?php
session_start();
require_once 'connexionbase.php';

// Vérifier que l'utilisateur est connecté et est un candidat
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: connexion.php");
    exit();
}

$candidat = $_SESSION['utilisateur'];

// Récupérer les offres d’emploi
$req = $bdd->query("SELECT o.*, r.nom_entreprise, r.secteur 
                    FROM offres o 
                    JOIN recruteurs r ON o.id_recruteur = r.id 
                    ORDER BY date_publication DESC");
$offres = $req->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Candidat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="text-center mb-4 text-primary">Bienvenue, <?= htmlspecialchars($candidat['prenom']) ?> 👋</h2>

    <!-- Infos personnelles -->
    <div class="card shadow mb-4 border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Vos informations</h5>
        </div>
        <div class="card-body">
            <p><strong>Nom :</strong> <?= htmlspecialchars($candidat['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($candidat['prenom']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($candidat['email']) ?></p>
            <!-- Vous pourrez ajouter CV, compétences, etc. plus tard -->
        </div>
    </div>

    <!-- Liste des offres -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-success text-white rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>Offres d'emploi disponibles</h5>
        </div>
        <div class="card-body">
            <?php if (count($offres) > 0): ?>
                <?php foreach ($offres as $offre): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <h5 class="text-dark"><?= htmlspecialchars($offre['titre']) ?></h5>
                        <p class="mb-1"><strong>Entreprise :</strong> <?= htmlspecialchars($offre['nom_entreprise']) ?> (<?= $offre['secteur'] ?>)</p>
                        <p class="mb-1"><strong>Localisation :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                        <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                        <a href="postuler.php?id_offre=<?= $offre['id'] ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-send-check me-1"></i> Postuler
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune offre d’emploi n’est disponible pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
