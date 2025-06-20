<?php
require_once __DIR__ . '/configuration/connexionbase.php';
require_once __DIR__ . '/includes/header.php';

// Construction dynamique du WHERE
$where = []; // Tableau pour stocker les conditions WHERE
$params = []; //  Tableau pour stocker les paramètres de la requête

if (!empty($_GET['motcle'])) { // Vérification si le mot-clé est fourni
    $where[] = "(offres_emploi.titre LIKE :motcle OR offres_emploi.description LIKE :motcle)"; // On recherche dans le titre et la description de l'offre
    $params[':motcle'] = '%' . $_GET['motcle'] . '%'; // On utilise un paramètre pour éviter les injections SQL
}
// Vérification si la localisation est fournie
if (!empty($_GET['localisation'])) { // On ajoute une condition de recherche pour la localisation
    $where[] = "offres_emploi.lieu LIKE :localisation"; // On recherche dans le lieu de l'offre
    $params[':localisation'] = '%' . $_GET['localisation'] . '%'; // On utilise un paramètre pour éviter les injections SQL
}

if (!empty($_GET['secteur'])) { // Vérification si le secteur est fourni
    $where[] = "offres_emploi.secteur LIKE :secteur"; // On ajoute une condition de recherche pour le secteur
    $params[':secteur'] = '%' . $_GET['secteur'] . '%';
}
//
$sql = "
SELECT * FROM offres_emploi
INNER JOIN recruteurs r ON offres_emploi.id_recruteur = r.id_recruteur
WHERE offres_emploi.statut = 'publiée'"; //  Un seul WHERE

if ($where) {
    $sql .= " AND " . implode(" AND ", $where); //  On ajoute les conditions WHERE dynamiquement
}

$sql .= " ORDER BY offres_emploi.date_publication DESC LIMIT 10";

$req = $bdd->prepare($sql);
$req->execute($params);

$offres = $req->fetchAll(PDO::FETCH_ASSOC);
?>

<head>
    <!-- <link rel="stylesheet" href="https://ikabara.com/css/style.css">
		<link rel="stylesheet" href="https://ikabara.com/css/responsive.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<header>
    <style>
        .baniere {
            background-image: url('asso7.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .t {
            margin-left: 200px;

        }

        .tt {
            top: -50px;
        }
    </style>
</header>
<div class="baniere">
</div>
<div class="container mt-5 ">
    <hr>
    <h3 id="offres-emploi" class="container py-5 mb-4">📢 Offres d’emploi récentes</h3>
    <?php if (count($offres) > 0): ?>
        <div class="row">
            <?php foreach ($offres as $offre): ?> <!-- Pour chaque offre, on crée une carte -->
                <?php
                $logo = !empty($offre['logo']) ? '/projet_Rabya/recruteurs/dossier/' . htmlspecialchars($offre['logo']) : 'igm/3022.jpg';
                ?>
                <div class="col-lg-6 mb-5 ">
                    <div class="card position-relative shadow-sm border-0 h-100 pt-4 bg-primary bg-opacity-10">
                        <!-- Logo en haut à gauche, en surplomb -->
                        <div style="position:absolute; top:-30px; left:30px;">
                            <img src="<?= $logo ?>" alt="Logo entreprise"
                                style="width:60px; height:60px; object-fit:cover; border-radius:50%; border:3px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,0.10); background:#fff;">
                        </div>
                        <div class="card-body pt-3 ps-5">
                            <h5 class="card-title mb-2"><?= htmlspecialchars($offre['titre']) ?></h5>
                            <div class="mb-1 text-secondary fw-semibold" style="font-size:1.07em;">
                                <i class="bi bi-building me-1"></i>
                                <?= htmlspecialchars($offre['nom_entreprise'] ?? 'Recruteur inconnu') ?>
                            </div>
                            <div class="mb-1 text-muted" style="font-size:0.98em;">
                                <i class="bi bi-geo-alt-fill text-primary me-1"></i>
                                <?= htmlspecialchars($offre['lieu'] ?? 'Lieu non précisé') ?>
                                <br>
                                <i class="bi bi-clock me-1"></i>
                                publié le : <?= date('d-m-Y', strtotime($offre['date_publication'])) ?>
                                <?php if (!empty($offre['type_contrat'])): ?>
                                    <br>
                                    <i class="bi bi-briefcase me-1"></i>
                                    <?= htmlspecialchars($offre['type_contrat']) ?>
                                <?php endif; ?>
                                <div class="t">
                                    <?php if (!empty($offre['date_expiration'])): ?>
                                        <br>
                                        <i class="bi bi-calendar-x text-danger me-1"></i>
                                        Expire le : <?= date('d-m-Y', strtotime($offre['date_expiration'])) ?>
                                    <?php endif; ?>
                                </div>

                            </div>
                            <div>
                                <a href="/projet_Rabya/recruteurs/offre_emploi.php?id=<?= $offre['id_offre'] ?>" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bi bi-eye me-1"></i> Voir l’offre
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a href="/projet_Rabya/recruteurs/offres.php" class="btn btn-primary">
                <i class="bi bi-list-ul me-1"></i> Voir toutes les offres
            </a>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Aucune offre disponible pour le moment.</div>
    <?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>