<?php
require_once __DIR__ . '/../configuration/connexionbase.php';

// Ajout PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php'; // Adapte ce chemin si besoin

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
            $token = bin2hex(random_bytes(16)); // Génération d'un token aléatoire de 32 caractères
            $expire = date('Y-m-d H:i:s', strtotime('+ 10 minutes')); // Expiration du token dans 10 minutes

            // Enregistrer le token 
            $update = $bdd->prepare("UPDATE candidats SET reset_token=?, reset_token_expires=? WHERE id_candidat=?");
            $update->execute([$token, $expire, $candidat['id_candidat']]);
            // Préparer le lien de réinitialisation en local 


            // Lien de réinitialisation (adapte le domaine en production)
            $lien = "http://localhost/projet_Rabya/candidats/reinitialise_mdp.php?token=$token";

            // Envoi du mail via PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuration SMTP (exemple Gmail)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Ton serveur SMTP, adapte si autre fournisseur
                $mail->SMTPAuth = true;
                $mail->Username = 'yacoubaarama12@gmail.com'; // Ton email d'envoi
                $mail->Password = 'tgpy prek vjjc cxpu'; // Mot de passe d'application ou ton mot de passe email
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Destinataire
                $mail->setFrom('yacoubaarama12@gmail.com', 'IKBARA.'); // Nom de ton site
                $mail->addAddress($email);

                // Contenu du mail
                $mail->isHTML(true);
                $mail->Subject = 'Réinitialisation de votre mot de passe';
                $mail->Body    = "Bonjour,<br><br>
                    Vous avez demandé la réinitialisation de votre mot de passe.<br>
                    Cliquez sur ce lien pour créer un nouveau mot de passe :<br>
                    <a href='$lien'>$lien</a><br><br>
                    Ce lien est valable 1 heure.<br><br>
                    Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.";

                $mail->send();
                $message = "Un email de réinitialisation vient de vous être envoyé.";
                $typeMessage = "success";
            } catch (Exception $e) {
                $message = "L'envoi de l'email a échoué. Veuillez réessayer. Erreur : " . $mail->ErrorInfo;
                $typeMessage = "danger";
            }
        } else {
            $message = "Aucun compte n'est associé à cet email.";
            $typeMessage = "danger";
        }
        // if ($_SERVER['SERVER_NAME'] === 'localhost') {
        //     echo "<div class='alert alert-info text-center'>Lien de réinitialisation (test local) : <a href='$lien'>$lien</a></div>";
        // }
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
            margin-top: 50px;
        }

        .focus-shadow:focus {
            box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
        }

        .btn-lg {
            font-size: 18px;
            font-weight: bold;
        }

        .alert {
            margin-top: 0;
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
                            <input type="email" name="email" class="form-control focus-shadow" required>
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