<?php
require_once 'connexionbase.php';
$message = '';
$valid = false; // Par défaut, le formulaire n'est pas valide 
// Vérifier si le token est passé en GET
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    // Vérifier le token
    $stmt = $bdd->prepare("SELECT id_recruteur FROM recruteurs WHERE reset_token=? AND reset_token_expires > NOW()");
    $stmt->execute([$token]);
    $recruteur = $stmt->fetch();
    if ($recruteur) {
        $valid = true;// Le token est valide, on peut afficher le formulaire de réinitialisation
        // Si le formulaire est soumis
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $mdp = trim($_POST['mot_de_passe']);

            if (strlen($mdp) < 6) {
                $message = "⚠️ Le mot de passe doit faire **au moins 6 caractères**.";
            } else {
                // Mettre à jour le mot de passe
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
                $update = $bdd->prepare("UPDATE recruteurs SET mot_de_passe=?, reset_token=NULL, reset_token_expires=NULL WHERE id_recruteur=?");
                $update->execute([$mdp_hash, $recruteur['id_recruteur']]);

                $message = "✅ Mot de passe mis à jour avec succès !";
                $valid = false;// On désactive le formulaire après la mise à jour
            }
        }
    } else {
        $message = "⚠️ Lien invalide ou expiré.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<style>
    body {
        background-image: url('igm/s3.jpg');
        background-size: cover;
        background-attachment: fixed;
    }
    .navbar {
        background-image: url('igm/s4.jpg');
        background-size: cover;
    }
    p {
        color: white;
    }
    </style>
    <!-- ✅ Navbar moderne -->
<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
    <a class="navbar-brand fw-bold text-white" href="#">IKBara</a>
</nav>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-4 border-0">
                <div class="card-header bg-primary text-dark text-center fs-4 fw-bold">
                    <i class="bi bi-lock"></i> Réinitialiser mon mot de passe
                </div>
                <div class="card-body px-4 py-4">
                    <?php if ($message): ?>
                        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    <?php if ($valid): ?><!-- Si le token est valide, afficher le formulaire de réinitialisation -->
                        <form method="post">
                            <div class="mb-3">
                                <label>Nouveau mot de passe :</label>
                                <input type="password" name="mot_de_passe" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-key"></i> Changer le mot de passe
                            </button>
                            <!--annuler le changement de mot de passe-->
                            <a href="mdp_oublie_recruteur.php" class="btn btn-secondary w-100 mt-2">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </form>
                    <?php else: ?><!--on affiche directement un bouton "Se connecter" après succès.-->
                        <div class="text-center mt-3">
                            <a href="connexion.php" class="btn btn-success w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Se connecter
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
