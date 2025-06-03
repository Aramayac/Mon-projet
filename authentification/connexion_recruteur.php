<?php
session_start();
require_once __DIR__.'/../configuration/connexionbase.php';
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($email)) $errors['email'] = "L'email est requis.";
    if (empty($mot_de_passe)) $errors['mot_de_passe'] = "Le mot de passe est requis.";

    if (empty($errors)) {
        $stmt = $bdd->prepare("SELECT * FROM recruteurs WHERE email = ?");
        $stmt->execute([$email]);
        $recruteur = $stmt->fetch();

        if ($recruteur && password_verify($mot_de_passe, $recruteur['mot_de_passe'])) {
            $_SESSION['utilisateur'] = [
                'id' => $recruteur['id_recruteur'],
                'nom_entreprise' => $recruteur['nom_entreprise'],
                'secteur' => $recruteur['secteur'],
                'email' => $recruteur['email'],
                'logo' => $recruteur['logo'] // On ajoute le logo !
            ];
            // $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/projet_Rabya/recruteurs/tableau_recruteur.php';
            // header("Location: $redirect");
            // exit();
            $_SESSION['role'] = 'recruteur';
            // Redirection vers le tableau de bord recruteur
            header('Location: /projet_Rabya/recruteurs/tableau_recruteur.php');
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
    <title>Connexion Recruteur - IKBara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include __DIR__.'/../includes/header3.php';?>
<!-- ✅ Formulaire avec contrôle avancé -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg rounded-4">
                <div class="card-header bg-primary text-white text-center fs-4 fw-bold">
                    <i class="bi bi-building-lock me-2"></i> Connexion Recruteur
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
                            <a href="/projet_Rabya/recruteurs/inscription_recruteur.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Se connecter
                            </button>
                        </div>
                        <!--Vous n'avez pas de compte ? Inscrivez-vous-->
                        <div class="text-center mt-3">
                          <p>Vous n'avez pas de compte ?  <a href="/projet_Rabya/recruteurs/inscription_recruteur.php" class="text-decoration-none text-primary">
                                <i class="bi bi-person-plus"></i>Inscrivez-vous
                             </a>
                           </p>
                        </div>
                        <!-- Lien vers la page de réinitialisation du mot de passe -->
                        <div class="text-center mt-3">
                            <a href="/projet_Rabya/recruteurs/mdp_oublie_recruteur.php" class="text-decoration-none text-primary">
                                <i class="bi bi-key"></i> Mot de passe oublié ?
                            </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
