<?php
session_start();
require_once 'connexionbase.php';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($email)) $errors['email'] = "L'email est requis.";
    if (empty($mot_de_passe)) $errors['mot_de_passe'] = "Le mot de passe est requis.";

    //ne pas laisser vide le champ email si l'utilisateur clique sur le bouton mot de passe oublié 
  
    if (empty($errors)) {
        $stmt = $bdd->prepare("SELECT * FROM candidats WHERE email = ?");
        $stmt->execute([$email]);
        $candidat = $stmt->fetch();

        if ($candidat && password_verify($mot_de_passe, $candidat['mot_de_passe'])) {
            $_SESSION['utilisateur'] = [
                'id' => $candidat['id_candidat'], // <-- LE BON ID 
                'nom' => $candidat['nom'],
                'prenom' => $candidat['prenom'],
                'email' => $candidat['email']
            ];
            $_SESSION['role'] = 'candidat';
            header('Location: tableau_candidat.php');
            exit();
        } else {
            $errors['general'] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Candidat - IKBara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php
include 'header3.php';
?>

<!-- ✅ Formulaire avec contrôle avancé -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg rounded-4">
                <div class="card-header bg-transparent text-white text-center fs-4 fw-bold">
                    <i class="bi bi-lock-fill me-2"></i> Connexion Candidat
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

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Adresse Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="mot_de_passe">
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="inscription_candidat.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Se connecter
                            </button>
                        </div>
                         <!--Vous n'avez pas de compte ? Inscrivez-vous-->
                        <div class="text-center mt-3">
                          <p>Vous n'avez pas de compte ?  <a href="inscription_candidat.php" class="text-decoration-none text-primary">
                                <i class="bi bi-person-plus"></i>Inscrivez-vous
                             </a>
                           </p>
                       </div>
                    </form>
                    <!-- mot de passe oublié -->
                    <div class="text-center mt-3">
                        <a href="mot_de_passe_oublie.php" class="text-decoration-none">Mot de passe oublié ?</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
