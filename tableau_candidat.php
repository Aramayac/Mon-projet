<?php
session_start();
require_once 'connexionbase.php';

// Vérifier que l'utilisateur est connecté et est un candidat
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: connexion.php");
    exit();
}

$candidat = $_SESSION['utilisateur'];

// Récupérer les détails du profil complet depuis profils_candidats
$profil = $bdd->prepare(
    "SELECT c.nom, c.prenom, c.email, p.competences, p.cv
     FROM candidats c
     LEFT JOIN profils_candidats p ON c.id_candidat = p.id_candidat
     WHERE c.id_candidat = ?"
);
$profil->execute([$candidat['id']]);
$profil = $profil->fetch();

// Requête offres avec jointure
$offres = $bdd->query("SELECT o.*, r.nom_entreprise, r.secteur 
                       FROM offres_emploi o 
                       JOIN recruteurs r ON o.id_recruteur = r.id_recruteur 
                       ORDER BY date_publication DESC")->fetchAll();

// Récupérer les id_offre déjà postulés par ce candidat
$stmt = $bdd->prepare("SELECT id_offre FROM candidatures WHERE id_candidat = ?");
$stmt->execute([$candidat['id']]);
$mes_candidatures = $stmt->fetchAll(PDO::FETCH_COLUMN);
if (!$mes_candidatures) $mes_candidatures = [];

// Requête candidatures du candidat (pour affichage dans "mes candidatures")
$candidatures = $bdd->prepare("SELECT c.*, o.titre 
                               FROM candidatures c 
                               JOIN offres_emploi o ON c.id_offre = o.id_offre 
                               WHERE c.id_candidat = ?");
$candidatures->execute([$candidat['id']]);
$candidatures = $candidatures->fetchAll();

// Vérifie s'il faut afficher le bouton "Compléter mon profil"
$profil_check = $bdd->prepare("SELECT * FROM profils_candidats WHERE id_candidat = ?");
$profil_check->execute([$candidat['id']]);
$profil_complet = $profil_check->fetch();

// Gérer les messages après action postuler
$message = '';
if (isset($_GET['message'])) {
    if ($_GET['message'] === 'success') {
        $message = "<div class='alert alert-success'>Votre candidature a bien été envoyée.</div>";
    } elseif ($_GET['message'] === 'deja_postule') {
        $message = "<div class='alert alert-warning'>Vous avez déjà postulé à cette offre.</div>";
    } elseif ($_GET['message'] === 'erreur') {
        $message = "<div class='alert alert-danger'>Une erreur est survenue lors de la candidature.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Candidat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('image.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .content-section {
            background: rgba(247, 247, 247, 0.86);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 0 10px rgba(244, 137, 137, 0.68);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🎓 Recrutement</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#profil">Mon profil</a></li>
                <li class="nav-item"><a class="nav-link" href="#offres">Offres</a></li>
                <li class="nav-item"><a class="nav-link" href="#candidatures">Candidatures</a></li>
                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenu -->
<div class="container py-5">

    <!-- Profil -->
    <div id="profil" class="content-section">
        <h3 class="text-primary"><i class="bi bi-person-circle me-2"></i>Mon Profil</h3>
        <p><strong>Nom :</strong> <?= htmlspecialchars($profil['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($profil['prenom']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($profil['email']) ?></p>
        <p><strong>Compétences :</strong> <?= !empty($profil['competences']) ? htmlspecialchars($profil['competences']) : 'Non renseigné' ?></p>
        <p><strong>CV :</strong>
            <?php if (!empty($profil['cv'])): ?>
                <a href="dossier/cv/<?= htmlspecialchars($profil['cv']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Voir mon CV</a>
            <?php else: ?>
                <span class="text-muted">Aucun CV ajouté</span>
            <?php endif; ?>
        </p>
        <a href="modifier_profil.php" class="btn btn-sm btn-primary mt-3">
            <i class="bi bi-pencil-square"></i> Modifier mon profil
        </a>
        <?php if (!$profil_complet): ?>
            <a href="completer_profil.php" class="btn btn-warning mt-2 ms-2">
                <i class="bi bi-exclamation-circle"></i> Compléter mon profil
            </a>
        <?php endif; ?>
    </div>

    <!-- Offres -->
    <div id="offres" class="content-section">
        <h3 class="text-success"><i class="bi bi-briefcase me-2"></i>Offres disponibles</h3>
        <?= $message ?>
        <form method="GET" class="row mb-4">
            <div class="col-md-4 mb-2">
                <input type="text" name="motcle" class="form-control" placeholder="Mot-clé...">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="localisation" class="form-control" placeholder="Localisation...">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="secteur" class="form-control" placeholder="Secteur...">
            </div>
            <div class="col-md-2 mb-2 d-grid">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i> Rechercher</button>
            </div>
        </form>
        <?php foreach ($offres as $offre): ?>
            <div class="border-bottom mb-4 pb-3">
                <h5><?= htmlspecialchars($offre['titre']) ?></h5>
                <p><strong>Entreprise :</strong> <?= htmlspecialchars($offre['nom_entreprise']) ?> (<?= htmlspecialchars($offre['secteur']) ?>)</p>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                <p><?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                <?php if (in_array($offre['id_offre'], $mes_candidatures)): ?>
                    <button class="btn btn-secondary btn-sm" disabled><i class="bi bi-check-circle"></i> Déjà postulé</button>
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

    <!-- Candidatures -->
    <div id="candidatures" class="content-section">
        <h3 class="text-warning"><i class="bi bi-file-earmark-check me-2"></i>Mes Candidatures</h3>
        <?php if (count($candidatures) > 0): ?>
            <ul class="list-group">
                <?php foreach ($candidatures as $c): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($c['titre']) ?>
                        <span class="badge bg-secondary"><?= ucfirst($c['statut']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune candidature envoyée.</p>
        <?php endif; ?>
    </div>

</div>

<!-- retour-->
<div class="container">
    <div class="d-flex justify-content-between mt-4">
        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">© <?= date('Y') ?> Recrutement Pro. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>