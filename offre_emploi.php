<?php
require_once 'connexionbase.php';
require_once 'header.php';

// Vérifie qu’un ID d’offre a été fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger text-center mt-5">Aucune offre sélectionnée.</div>';
    require_once 'footer.php';
    exit;
}

$id_offre = intval($_GET['id']);

// Récupérer les détails de l’offre
$req = $bdd->prepare("
    SELECT o.*, r.nom_entreprise, r.email
    FROM offres_emploi o
    JOIN recruteurs r ON o.id_recruteur = r.id_recruteur
    WHERE o.id_offre = ?
");
$req->execute([$id_offre]);
$offre = $req->fetch(PDO::FETCH_ASSOC);

if (!$offre) {
    echo '<div class="alert alert-warning text-center mt-5">Offre introuvable.</div>';
    require_once 'footer.php';
    exit;
}
?>
<div class="container mt-5 mb-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h2 class="card-title mb-3"><?= htmlspecialchars($offre['titre']) ?></h2>
            <h5 class="text-muted mb-4"><?= htmlspecialchars($offre['nom_entreprise']) ?> • <?= htmlspecialchars($offre['lieu']) ?></h5>

            <div class="mb-3">
                <span class="badge bg-primary"><?= htmlspecialchars($offre['type_contrat']) ?></span>
                <span class="badge bg-success"><?= htmlspecialchars($offre['salaire']) ?> FCFA</span>
                <span class="badge bg-secondary">📅 Publiée le <?= date('d/m/Y', strtotime($offre['date_publication'])) ?></span>
            </div>

            <h5>Description de l'offre</h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($offre['description'])) ?></p>

            <h6 class="mt-4">Date limite de candidature :</h6>
            <p><?= date('d/m/Y', strtotime($offre['date_expiration'])) ?></p>

            <div class="mt-4">
                <?php if (isset($_SESSION['id_candidat'])): ?>
                    <form action="postuler.php" method="POST">
                        <input type="hidden" name="id_offre" value="<?= $offre['id_offre'] ?>">
                        <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send-check"></i> Postuler à cette offre 
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-lock-fill"></i> <a href="connexion.php">Connectez-vous</a> en tant que candidat pour postuler.
                    </div>
                    <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
              </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
