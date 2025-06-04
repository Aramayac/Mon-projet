<?php
session_start();
require_once __DIR__.'/../configuration/connexionbase.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$candidat = $_SESSION['utilisateur'];

$profil = $bdd->prepare(
    "SELECT c.nom, c.prenom, c.email, p.competences, p.cv
     FROM candidats c
     LEFT JOIN profils_candidats p ON c.id_candidat = p.id_candidat
     WHERE c.id_candidat = ?"
);
$profil->execute([$candidat['id']]);
$profil = $profil->fetch();

$offres = $bdd->query("SELECT o.*, r.nom_entreprise, r.secteur 
                       FROM offres_emploi o 
                       JOIN recruteurs r ON o.id_recruteur = r.id_recruteur 
                       ORDER BY date_publication DESC")->fetchAll();

$stmt = $bdd->prepare("SELECT id_offre FROM candidatures WHERE id_candidat = ?");
$stmt->execute([$candidat['id']]);
$mes_candidatures = $stmt->fetchAll(PDO::FETCH_COLUMN);
if (!$mes_candidatures) $mes_candidatures = [];

$candidatures = $bdd->prepare("SELECT c.*, o.titre 
                               FROM candidatures c 
                               JOIN offres_emploi o ON c.id_offre = o.id_offre 
                               WHERE c.id_candidat = ?");
$candidatures->execute([$candidat['id']]);
$candidatures = $candidatures->fetchAll();

$profil_check = $bdd->prepare("SELECT * FROM profils_candidats WHERE id_candidat = ?");
$profil_check->execute([$candidat['id']]);
$profil_complet = $profil_check->fetch();

$message = '';
if (isset($_GET['message'])) {
    $messages = [
        'success' => "<div class='alert alert-success'>Votre candidature a bien été envoyée.</div>",
        'deja_postule' => "<div class='alert alert-warning'>Vous avez déjà postulé à cette offre.</div>",
        'erreur' => "<div class='alert alert-danger'>Une erreur est survenue lors de la candidature.</div>"
    ];
    $message = $messages[$_GET['message']] ?? '';
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
            /* background: linear-gradient(to right, #e0ecff, #f8f9fa); */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('/projet_Rabya/igm/bg1.jpg');

            background-size: cover;

        }
        .navbar {
            background-image: url('/projet_Rabya/igm/s4.jpg');

        }
        .navbar-brand {
            color: #fff !important;
            font-weight: bold;
            margin: 0 20px;
        }
        .content-section {
            background-color:rgba(237, 230, 230, 0.82);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }
        h3 i {
            color: #003366;
        }
        .btn-outline-success:hover {
            background-color: #28a745;
            color: #fff;
        }
        .footer {
            background-color: #003366;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🎓 Recrutement</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="#profil">Mon profil</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#offres">Offres</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#candidatures">Candidatures</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">

    <!-- Profil -->
    <div id="profil" class="content-section">
        <h3><i class="bi bi-person-circle me-2"></i> Mon Profil</h3>
        <p><strong>Nom :</strong> <?= htmlspecialchars($profil['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($profil['prenom']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($profil['email']) ?></p>
        <p><strong>Compétences :</strong> <?= $profil['competences'] ? htmlspecialchars($profil['competences']) : 'Non renseigné' ?></p>
        <p><strong>CV :</strong>
            <?php if ($profil['cv']): ?>
                <a href="dossier/cv/<?= htmlspecialchars($profil['cv']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Voir mon CV</a>
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

    <!-- Offres -->
    <div id="offres" class="content-section">
        <h3><i class="bi bi-briefcase me-2"></i> Offres disponibles</h3>
        <?= $message ?>
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="motcle" class="form-control" placeholder="Mot-clé...">
            </div>
            <div class="col-md-3">
                <input type="text" name="localisation" class="form-control" placeholder="Localisation...">
            </div>
            <div class="col-md-3">
                <input type="text" name="secteur" class="form-control" placeholder="Secteur...">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-success"><i class="bi bi-search me-1"></i> Rechercher</button>
            </div>
        </form>
        <?php foreach ($offres as $offre): ?>
            <div class="border rounded p-3 mb-4">
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

    <!-- Candidatures -->
    <div id="candidatures" class="content-section">
        <h3><i class="bi bi-file-earmark-check me-2"></i> Mes Candidatures</h3>
        <?php if ($candidatures): ?>
            <ul class="list-group">
                <?php foreach ($candidatures as $c): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($c['titre']) ?>
                        <span class="badge bg-secondary"><?= ucfirst($c['statut']) ?></span>
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

<footer class="footer text-center py-3 mt-5">
    <p class="mb-0">© <?= date('Y') ?> Recrutement Pro. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
