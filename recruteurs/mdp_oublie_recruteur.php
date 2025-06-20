<?php
require_once __DIR__ . '/../configuration/connexionbase.php';
$message = '';
$typeMessage = ''; // success, danger, warning


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    
    if (!empty($email)) {
        // Vérifie si l'email existe chez les recruteurs
        $stmt = $bdd->prepare("SELECT id_recruteur FROM recruteurs WHERE email = ?");
        $stmt->execute([$email]);
        $recruteur = $stmt->fetch();

        if ($recruteur) {
            // Génère un token et une date d'expiration
            $token = bin2hex(random_bytes(16));
            $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Met à jour le recruteur avec le token
            $update = $bdd->prepare("UPDATE recruteurs SET reset_token=?, reset_token_expires=? WHERE id_recruteur=?");
            $update->execute([$token, $expire, $recruteur['id_recruteur']]);

            // Lien de réinitialisation (affiché pour test en local)
            $lien = "http://localhost/projet_Rabya/recruteurs/reinitialisation_mdp_recruteur.php?token=$token";
            $message = "Un lien de réinitialisation a été généré.";
            echo "<div class='alert alert-info text-center'>Lien de réinitialisation (test local) :<br><a href='$lien'>$lien</a></div>";
        } else {
            $message = " Aucun compte recruteur associé à cet email.";
        }
    } else {
        $message = "Veuillez entrer une adresse email.";
        $typeMessage = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié Recruteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('/projet_Rabya/igm/s3.jpg');
            background-size: cover;
            background-attachment: fixed;
        }

        .form-container {
            max-width: 420px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }

        .focus-shadow:focus {
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.7);
        }

        .btn-lg {
            font-size: 16px;
            font-weight: 500;
        }
        .alert {
            margin-top: 60px;
        }
    </style>
</head>

<body>
    <?php include '../includes/header3.php'; ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h3 class="text-center text-primary mb-4">
                        <i class="bi bi-unlock"></i> Mot de passe oublié
                    </h3>

                    <?php if ($message): ?>
                        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Adresse email</label>
                            <input type="email" name="email" class="form-control focus-shadow" >
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send-fill"></i> Envoyer le lien
                            </button>
                            <a href="/projet_Rabya/authentification/connexion_recruteur.php" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle-fill"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php 
    ?>

</body>
</html>
