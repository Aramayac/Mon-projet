<?php
require_once 'connexionbase.php';
require_once 'header.php';
// echo $_SERVER['PHP_SELF']. "<br>";
// echo $_SERVER['REQUEST_URI']. "<br>";
// echo $_SERVER['SCRIPT_NAME']. "<br>";
// echo $_SERVER['HTTP_HOST']. "<br>";
// echo $_SERVER['SERVER_NAME']. "<br>";
// echo $_SERVER['SERVER_PORT']. "<br>";
// echo $_SERVER['DOCUMENT_ROOT']. "<br>";
// echo $_SERVER['SCRIPT_FILENAME']. "<br>";
// echo $_SERVER['PHP_SELF']. "<br>";
// echo $_SERVER['QUERY_STRING']. "<br>";
// echo $_SERVER['REQUEST_METHOD']. "<br>";

//Récupération des 5 offres les plus récentes
// $req = $bdd->query("
//     SELECT o.id_offre, o.titre, o.description, o.lieu, o.date_publication, r.nom_entreprise 
//     FROM offres_emploi o 
//     JOIN recruteurs r ON o.id_recruteur = r.id_recruteur 
//     ORDER BY o.date_publication DESC 
//     LIMIT 5
// ");
// $req = $bdd->query("
//     SELECT o.id_offre, o.titre, o.description, o.lieu, o.date_publication, r.nom_entreprise 
//     FROM offres_emploi o 
//     LEFT JOIN recruteurs r ON o.id_recruteur = r.id_recruteur 
//     ORDER BY o.date_publication DESC 
//     LIMIT 5
// ");
$req=$bdd->query("
SELECT * FROM offres_emploi
INNER JOIN recruteurs r ON offres_emploi.id_recruteur = r.id_recruteur
ORDER BY offres_emploi.date_publication DESC
LIMIT 10
");


$offres = $req->fetchAll(PDO::FETCH_ASSOC);
?>
<head>
        <!-- <link rel="stylesheet" href="https://ikabara.com/css/style.css">
		<link rel="stylesheet" href="https://ikabara.com/css/responsive.css"> -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<header>
    <style>
        .baniere{
            background-image: url('asso7.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .t {
            margin-left: 200px;
            
        }
        .tt{
            top: -50px;
        }
     
    </style>
</header>
<div class="baniere">
</div>
<div class="container mt-5 "  >
<hr>
<h3 id="offres-emploi" class="container py-5 mb-4">📢 Offres d’emploi récentes</h3>
<?php if (count($offres) > 0): ?>
    <div class="row">
        <?php foreach ($offres as $offre): ?>
            <?php
            $logo = !empty($offre['logo']) ? 'uploads/' . htmlspecialchars($offre['logo']) : 'igm/3022.jpg';
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
                            <a href="offre_emploi.php?id=<?= $offre['id_offre'] ?>" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="bi bi-eye me-1"></i> Voir l’offre
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="d-flex justify-content-end mb-3">
    <a href="offres.php" class="btn btn-primary">
        <i class="bi bi-list-ul me-1"></i> Voir toutes les offres
    </a>
  </div>
<?php else: ?>
    <div class="alert alert-info">Aucune offre disponible pour le moment.</div>
<?php endif; ?>
</div>
<?php require_once 'footer.php'; ?>

