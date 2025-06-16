<?php
session_start();
require_once __DIR__.'/../configuration/connexionbase.php';

// Vérifier que le recruteur est connecté
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$id_offre = $_GET['id_offre'] ?? null;
if (!$id_offre || !is_numeric($id_offre)) {
    header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?message=erreur");
    exit();
}
// Vérifier que l'offre appartient bien au recruteur connecté
$stmt = $bdd->prepare("SELECT * FROM offres_emploi WHERE id_offre = ? AND id_recruteur = ?");
$stmt->execute([$id_offre, $_SESSION['utilisateur']['id']]);
$offre = $stmt->fetch();
if (!$offre) {
    header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?message=erreur");
    exit();
}

// Récupérer toutes les candidatures pour cette offre
$stmt = $bdd->prepare("SELECT c.id_candidature, c.statut, can.nom, can.prenom, can.email
                       FROM candidatures c
                       JOIN candidats can ON c.id_candidat = can.id_candidat
                       WHERE c.id_offre = ?");
$stmt->execute([$id_offre]);
$candidatures = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Candidatures pour l'offre</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-5">
    <h3>Candidatures pour l'offre : <?= htmlspecialchars($offre['titre']) ?></h3>
    <?php if ($candidatures): ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($candidatures as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td>
                        <span class="badge bg-<?= $c['statut']=='acceptée'?'success':($c['statut']=='refusée'?'danger':'secondary') ?>">
                            <?= ucfirst(str_replace('_',' ',$c['statut'])) ?>
                        </span>
                    </td>
                    <td>
                        <?php if($c['statut']=='en_cours'): ?>
                        <form method="post" action="/projet_Rabya/candidats/traiter_candidature.php" class="d-inline">
                            <input type="hidden" name="id_candidature" value="<?= $c['id_candidature'] ?>">
                            <input type="hidden" name="id_offre" value="<?= $id_offre ?>">
                            <button type="submit" name="action" value="accepter" class="btn btn-success btn-sm">Accepter</button>
                        </form>
                        <form method="post" action="/projet_Rabya/candidats/traiter_candidature.php" class="d-inline">
                            <input type="hidden" name="id_candidature" value="<?= $c['id_candidature'] ?>">
                            <input type="hidden" name="id_offre" value="<?= $id_offre ?>">
                            <button type="submit" name="action" value="refuser" class="btn btn-danger btn-sm">Refuser</button>
                        </form>
                        <?php else: ?>
                            <em>Aucune action possible</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune candidature pour cette offre.</p>
    <?php endif; ?>
    <a href="tableau_recruteur.php" class="btn btn-secondary">Retour</a>
</div>
</body>
</html>