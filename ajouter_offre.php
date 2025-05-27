<?php
session_start();
require_once 'connexionbase.php';

// Vérifie que l'utilisateur est un recruteur
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    // Rediriger vers la page de connexion recruteur
    header("Location: connexion_recruteur.php?redirect=ajouter_offre.php");
    exit();
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $lieu = trim($_POST['lieu']);
    $type_contrat = trim($_POST['type_contrat']);
    $salaire = trim($_POST['salaire']);
    $date_expiration = $_POST['date_expiration'];
    $date_publication = date('Y-m-d');
    $id_recruteur = $_SESSION['utilisateur']['id']; // CORRECTION ICI
    

    if ($titre && $description && $lieu && $type_contrat && $salaire && $date_expiration) {
        $stmt = $bdd->prepare("INSERT INTO offres_emploi (id_recruteur, titre, description, lieu, type_contrat, salaire, date_publication, date_expiration) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$id_recruteur, $titre, $description, $lieu, $type_contrat, $salaire, $date_publication, $date_expiration])) {
            $success = "Offre ajoutée avec succès.";
        } else {
            $error = " Erreur lors de l'ajout de l'offre.";
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une offre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-primary">➕ Ajouter une offre d'emploi</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm bg-white rounded">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre de l'offre</label>
            <input type="text" class="form-control" id="titre" name="titre" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description de l'offre</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="lieu" class="form-label">Lieu</label>
            <input type="text" class="form-control" id="lieu" name="lieu" required>
        </div>

        <div class="mb-3">
            <label for="type_contrat" class="form-label">Type de contrat</label>
            <select class="form-select" id="type_contrat" name="type_contrat" required>
                <option value="">-- Sélectionner --</option>
                <option value="CDI">CDI</option>
                <option value="CDD">CDD</option>
                <option value="Stage">Stage</option>
                <option value="Freelance">Freelance</option>
                <option value="Alternance">Alternance</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="salaire" class="form-label">Salaire (€)</label>
            <input type="number" class="form-control" id="salaire" name="salaire" required>
        </div>

        <div class="mb-3">
            <label for="date_expiration" class="form-label">Date d'expiration de l'offre</label>
            <input type="date" class="form-control" id="date_expiration" name="date_expiration" required>
        </div>

        <button type="submit" class="btn btn-primary">📤 Ajouter l'offre</button>
        <a href="tableau_recruteur.php" class="btn btn-secondary ms-2">⬅ Retour</a>
    </form>
</div>
</body>
</html>