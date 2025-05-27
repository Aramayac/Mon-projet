<?php
require_once 'connexionbase.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
   
    $stmt = $bdd->prepare("SELECT id_candidat FROM candidats WHERE email = ?");
    $stmt->execute([$email]);
    $candidat = $stmt->fetch();// Récupère le candidat correspondant à l'email
    if ($candidat) {
        // Générer un token
        $token = bin2hex (random_bytes(16));// Génère un token aléatoire de 32 caractères
        // Définir l'expiration du token (1 heure)
        $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Enregistrer le token en base
        $update = $bdd->prepare("UPDATE candidats SET reset_token=?, reset_token_expires=? WHERE id_candidat=?");
        $update->execute([$token, $expire, $candidat['id_candidat']]);

        // Lien de réinitialisation (test local)
        $lien = "http://localhost/projet_Rabya/reinitialise_mdp.php?token=$token";// Lien de réinitialisation avec le token

        $message = "Un lien de réinitialisation a été envoyé à ton email.";
        // Afficher le lien de réinitialisation (pour test local)
        echo "<div class='alert alert-info'>Lien de réinitialisation (test local) : <a href='$lien'>$lien</a></div>";// Affiche le lien de réinitialisation pour test
    } else {
        $message = " Cet email n'existe pas.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
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

        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .focus-shadow:focus {
            box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
        }

        .btn-lg {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- ✅ Navbar moderne -->
<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
    <a class="navbar-brand fw-bold text-white" href="#">IKBara</a>
</nav>

<!-- ✅ Formulaire de récupération -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-container">
                <h3 class="text-center mb-4 text-primary bg-s">
                    <i class="bi bi-lock"></i> Mot de passe oublié
                </h3>

                <?php if ($message): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label>Email :</label>
                        <input type="email" name="email" class="form-control focus-shadow">
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-send"></i> Envoyer le lien
                        </button>
                        <a href="connexion_candidat.php" class="btn btn-secondary btn-lg w-100">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
