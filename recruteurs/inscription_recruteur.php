<?php
require_once __DIR__.'/../configuration/connexionbase.php';
$message = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_entreprise = trim($_POST['nom_entreprise']);
    $secteur = trim($_POST['secteur']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);
    $telephone = trim($_POST['telephone']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Gestion du logo (optionnel)
    $logo = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $dossier = 'uploads/';
        if (!is_dir($dossier)) mkdir($dossier, 0777, true); // Crée le dossier s'il n'existe pas

        $extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $nom_fichier = uniqid('logo_', true) . '.' . $extension;
        $chemin = $dossier . $nom_fichier;

        // On vérifie que c'est bien une image
        $types_valides = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($extension, $types_valides) && getimagesize($_FILES['logo']['tmp_name'])) {
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $chemin)) {
                $logo = $nom_fichier;
            } else {
                $errors['logo'] = "Erreur lors de l'envoi du logo.";
            }
        } elseif ($_FILES['logo']['name'] != '') {
            $errors['logo'] = "Le fichier doit être une image (jpg, png, gif, webp).";
        }
    }

    // Vérification de l'unicité de l'email
    $stmt = $bdd->prepare("SELECT * FROM recruteurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $errors['email'] = "L'email est déjà utilisé.";
    }

    // Vérification des champs requis
    if (empty($nom_entreprise)) $errors['nom_entreprise'] = "Le nom de l'entreprise est requis.";
    if (empty($secteur)) $errors['secteur'] = "Le secteur d'activité est requis.";
    if (empty($email)) $errors['email'] = "L'email est requis.";
    if (empty($adresse)) $errors['adresse'] = "L'adresse est requise.";
    if (empty($telephone)) $errors['telephone'] = "Le téléphone est requis.";
    if (empty($mot_de_passe)) $errors['mot_de_passe'] = "Le mot de passe est requis.";
    if (strlen($mot_de_passe) < 8) $errors['mot_de_passe'] = "Le mot de passe doit contenir au moins 8 caractères.";

    if (empty($errors)) {
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $req = $bdd->prepare("INSERT INTO recruteurs (nom_entreprise, secteur, email, adresse, telephone, mot_de_passe, logo)
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $req->execute([$nom_entreprise, $secteur, $email, $adresse, $telephone, $mot_de_passe_hash, $logo]);
        $message = "Compte recruteur créé avec succès.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Recruteur - IKBara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<style>
        body {
            background-image: url('/projet_Rabya/igm/p5.png');
        }
         .container{
            margin-top: -50px;
        }
    </style>
<!--  Navbar -->
<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4 py-3">
    <a class="navbar-brand fw-bold text-white" href="#">IKBara</a>
</nav> -->
<?php
include '../includes/header2.php';
?>

<!--  Formulaire avec contrôle avancé -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg rounded-4">
                <div class="card-header bg-primary text-white text-center fs-4 fw-bold">
                    <i class="bi bi-building me-2"></i> Inscription Recruteur
                </div>
                <div class="card-body px-5 py-4">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if ($message): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nom de l'entreprise</label>
                            <input type="text" class="form-control" name="nom_entreprise">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Secteur d'activité</label>
                            <input type="text" class="form-control" name="secteur">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <input type="text" class="form-control" name="adresse">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="number" class="form-control" name="telephone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="mot_de_passe">
                        </div>
                        <!--Logo d'entreprise-->
                        <div class="mb-3">
                            <label class="form-label">Logo d'entreprise (optionnel)</label>
                            <input type="file" class="form-control" name="logo" accept="image/*">   
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="/projet_Rabya/inscri.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> S’inscrire
                            </button>
                        </div>
                        <div>
                            <p class="mt-3 text-center">Déjà inscrit ? <a href="/projet_Rabya/authentification/connexion_recruteur.php" class="text-decoration-none text-primary">Connectez-vous</a></p>
                        </div>        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
