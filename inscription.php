<?php
require_once 'connexionbase.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $email = trim($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    if ($role == 'candidat') {
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $req = $bdd->prepare("INSERT INTO candidats (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
        $req->execute([$nom, $prenom, $email, $mot_de_passe]);
        $message = "Compte candidat créé avec succès.";
    } elseif ($role == 'recruteur') {
        $nom_entreprise = trim($_POST['nom_entreprise']);
        $secteur = trim($_POST['secteur']);
        $req = $bdd->prepare("INSERT INTO recruteurs (nom_entreprise, secteur, email, mot_de_passe) VALUES (?, ?, ?, ?)");
        $req->execute([$nom_entreprise, $secteur, $email, $mot_de_passe]);
        $message = "Compte recruteur créé avec succès.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - IKBara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('igm/pp.jpg') no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2 class="text-center mb-4 text-primary">📝 Inscription</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" id="formInscription">
        <div class="mb-3">
            <label for="role" class="form-label">Je suis :</label>
            <select name="role" id="role" class="form-select" required>
                <option value="">-- Choisissez --</option>
                <option value="candidat">Candidat</option>
                <option value="recruteur">Recruteur</option>
            </select>
        </div>

        <div id="bloc-candidat" style="display:none;">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" name="nom" class="form-control">
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom :</label>
                <input type="text" name="prenom" class="form-control">
            </div>
        </div>

        <div id="bloc-recruteur" style="display:none;">
            <div class="mb-3">
                <label for="nom_entreprise" class="form-label">Nom de l'entreprise :</label>
                <input type="text" name="nom_entreprise" class="form-control">
            </div>
            <div class="mb-3">
                <label for="secteur" class="form-label">Secteur :</label>
                <input type="text" name="secteur" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email :</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="mot_de_passe" class="form-label">Mot de passe :</label>
            <input type="password" name="mot_de_passe" class="form-control" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i> S’inscrire
            </button>
        </div>
    </form>

    <div class="text-center mt-3">
        <small class="text-muted">Vous avez déjà un compte ?</small>
        <a href="connexion.php" class="fw-bold text-primary">Connectez-vous</a>
    </div>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function () {
    document.getElementById('bloc-candidat').style.display = this.value === 'candidat' ? 'block' : 'none';
    document.getElementById('bloc-recruteur').style.display = this.value === 'recruteur' ? 'block' : 'none';
});
</script>

</body>
</html>
