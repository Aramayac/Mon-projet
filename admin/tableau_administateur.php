<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/connexion_adminstrateur.php'); exit();
}
$nb_candidats = $bdd->query("SELECT COUNT(*) FROM candidats")->fetchColumn();
$nb_recruteurs = $bdd->query("SELECT COUNT(*) FROM recruteurs")->fetchColumn();
$nb_offres = $bdd->query("SELECT COUNT(*) FROM offres_emploi")->fetchColumn();
$nb_candidatures = $bdd->query("SELECT COUNT(*) FROM candidatures")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>Dashboard Administrateur</h2>
    <div class="row g-4 mb-4">
        <div class="col"><div class="card text-bg-success"><div class="card-body"><h5>Candidats</h5><p class="display-6"><?= $nb_candidats ?></p></div></div></div>
        <div class="col"><div class="card text-bg-info"><div class="card-body"><h5>Recruteurs</h5><p class="display-6"><?= $nb_recruteurs ?></p></div></div></div>
        <div class="col"><div class="card text-bg-warning"><div class="card-body"><h5>Offres</h5><p class="display-6"><?= $nb_offres ?></p></div></div></div>
        <div class="col"><div class="card text-bg-secondary"><div class="card-body"><h5>Candidatures</h5><p class="display-6"><?= $nb_candidatures ?></p></div></div></div>
    </div>
    <a href="/../projet_Rabya/admin/users.php" class="btn btn-outline-primary me-2">Gérer les utilisateurs</a>
    <a href="offres.php" class="btn btn-outline-success me-2">Gérer les offres</a>
    <a href="deconnexion.php" class="btn btn-outline-dark">Déconnexion</a>
</div>
</body>
</html>