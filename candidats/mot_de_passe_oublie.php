<?php
require_once __DIR__ . '/../configuration/connexionbase.php';

$message = '';
$typeMessage = ''; // success, danger, warning

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = strtolower(trim($_POST['email']));

    if (empty($email)) {
        $message = "Veuillez entrer votre adresse email.";
        $typeMessage = "warning";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "L'adresse email n'est pas valide.";
        $typeMessage = "danger";
    } else {
        // Rechercher le candidat
        $stmt = $bdd->prepare("SELECT id_candidat FROM candidats WHERE LOWER(email) = LOWER(?)");
        $stmt->execute([$email]);
        $candidat = $stmt->fetch();

        if ($candidat) {
            // Générer un token
            $token = bin2hex(random_bytes(16));
            $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Enregistrer le token
            $update = $bdd->prepare("UPDATE candidats SET reset_token=?, reset_token_expires=? WHERE id_candidat=?");
            $update->execute([$token, $expire, $candidat['id_candidat']]);

            // Lien (à remplacer par un envoi email en prod)
            $lien = "http://localhost/projet_Rabya/candidats/reinitialise_mdp.php?token=$token";
            $message = "Un lien de réinitialisation a été généré (affiché pour test).";
            $typeMessage = "success";

            // Affichage pour test
            echo "<div class='alert alert-info text-center'>Lien de réinitialisation (test) : <a href='$lien'>$lien</a></div>";
        } else {
            $message = "Aucun compte n'est associé à cet email.";
            $typeMessage = "danger";
        }
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
            background-image: url('/projet_Rabya/igm/s3.jpg');
            background-size: cover;
            background-attachment: fixed;
        }

        .navbar {
            background-image: url('/projet_Rabya/igm/s4.jpg');
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
    <?php
    include '../includes/header3.php';
    ?>

    <!-- ✅ Formulaire de récupération -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h3 class="text-center mb-4 text-primary bg-s">
                        <i class="bi bi-lock"></i> Mot de passe oublié
                    </h3>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= htmlspecialchars($typeMessage) ?> text-center"><?= htmlspecialchars($message) ?></div>
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
                            <a href="/projet_Rabya/authentification/connexion_candidat.php" class="btn btn-secondary btn-lg w-100">
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