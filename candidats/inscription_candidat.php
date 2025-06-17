<?php
require_once '../configuration/connexionbase.php';
$message = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']); // Normalisation de l'email 
    $addresse = trim($_POST['addresse']);
    $mot_de_passe = $_POST['mot_de_passe'];
    // Vérification de l'email existant
    $stmt = $bdd->prepare("SELECT * FROM candidats WHERE email = ?"); // Utilisation de LOWER pour ignorer la casse 
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $errors['email'] = "L'email est déjà utilisé.";
    }
    // Vérification de la longueur du mot de passe
    if (strlen($mot_de_passe) < 8) {
        $errors['mot_de_passe'] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    // Vérification des champs vides
    if (empty($nom)) $errors['nom'] = "Le nom est requis.";
    if (empty($prenom)) $errors['prenom'] = "Le prénom est requis.";
    if (empty($email)) $errors['email'] = "L'email est requis.";
    if (empty($addresse)) $errors['addresse'] = "L'adresse est requis.";
    if (empty($mot_de_passe)) $errors['mot_de_passe'] = "Le mot de passe est requis.";

    if (empty($errors)) {
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT); // Hachage du mot de passe
        // Insertion du candidat dans la base de données
        $req = $bdd->prepare("INSERT INTO candidats (nom, prenom, email,addresse, mot_de_passe) VALUES (?, ?, ?, ?, ?)");
        $req->execute([$nom, $prenom, $email, $addresse, $mot_de_passe_hash]); // Exécution de la requête
        $message = "Compte candidat créé avec succès."; // Message de succès
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription Candidat - IKBara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <style>
        body {
            background-image: url('../igm/p4.png');
        }

        .container {
            margin-top: -50px;
        }
    </style>
    <?php
    include '../includes/header2.php';
    ?>
    <!-- ✅ Navbar -->
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4 py-3">
    <a class="navbar-brand fw-bold text-white" href="#">IKBara</a>
</nav> -->

    <!-- ✅ Formulaire avec contrôle avancé -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg rounded-4">
                    <div class="card-header bg-primary text-white text-center fs-4 fw-bold">
                        <i class="bi bi-person-plus me-2"></i> Inscription Candidat
                    </div>
                    <div class="card-body px-5 py-4">
                        <?php if (!empty($errors)): ?> <!-- Affichage des erreurs -->
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li> <!-- Sécurisation de l'affichage -->
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if ($message): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" name="nom">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prénom</label>
                                <input type="text" class="form-control" name="prenom">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Addresse</label>
                                <input type="text" class="form-control" name="addresse">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" name="mot_de_passe">
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="../inscri.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> S’inscrire
                                </button>
                            </div>
                            <div class="text-center mt-3">
                                <p>Déjà inscrit ? <a href="/projet_Rabya/authentification/connexion_candidat.php" class="text-primary text-decoration-none">Se connecter</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>