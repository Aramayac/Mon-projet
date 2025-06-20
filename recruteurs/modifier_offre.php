<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$recruteur = $_SESSION['utilisateur'];
$id_offre = $_GET['id_offre'] ?? null;

// On récupère l'offre à modifier
if ($id_offre) {
    $sql = $bdd->prepare("SELECT * FROM offres_emploi WHERE id_offre = ? AND id_recruteur = ?");
    $sql->execute([$id_offre, $recruteur['id']]);
    $offre = $sql->fetch();
    if (!$offre) {
        header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?msg=Offre introuvable !");
        exit();
    }
} else {
    header("Location:  /projet_Rabya/recruteurs/tableau_recruteur.php?msg=Paramètre manquant !");
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $secteur = $_POST['secteur'] ?? '';
    $lieu = $_POST['lieu'] ?? '';
    $description = $_POST['description'] ?? '';

    $update = $bdd->prepare(
        "UPDATE offres_emploi SET titre = ?, secteur= ?, lieu = ?, description = ? WHERE id_offre = ? AND id_recruteur = ?"
    );
    $ok = $update->execute([$titre, $secteur, $lieu, $description, $id_offre, $recruteur['id']]);
    if ($ok) {
        header("Location:  /projet_Rabya/recruteurs/tableau_recruteur.php?msg=Offre modifiée avec succès !");
        exit();
    } else {
        $msg = "Erreur lors de la modification.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier une offre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card{
            margin-top: 80px;
        }
    </style>

</head>

<body class="bg-light">
    <?php include '../includes/header3.php'; ?>
    <div class="container py-5">
        <div class="card shadow rounded-4">
            <div class="card-header bg-transparent text-white text-center fs-4 fw-bold">
                Modifier l'offre
            </div>
            <div class="card-body">
                <?php if (!empty($msg)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" class="form-control" name="titre" id="titre" required value="<?= htmlspecialchars($offre['titre']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="secteur" class="form-label">Secteur</label>
                        <select class="form-select" name="secteur" id="secteur" required>
                            <option value="" disabled>Sélectionnez un secteur</option>
                            <?php include '../includes/secteurs.php'; ?>
                            <?php foreach ($secteurs as $secteur): ?>
                                <option value="<?= htmlspecialchars($secteur) ?>"
                                    <?= ($offre['secteur'] === $secteur) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($secteur) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="lieu" class="form-label">Lieu</label>
                        <input type="text" class="form-control" name="lieu" id="lieu" required value="<?= htmlspecialchars($offre['lieu']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="5" required><?= htmlspecialchars($offre['description']) ?></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="tableau_recruteur.php" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>