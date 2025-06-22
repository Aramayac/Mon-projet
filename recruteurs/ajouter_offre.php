<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';
include_once __DIR__ . '/../includes/header7.php';

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php'; // adapte le chemin si besoin

if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/authentification/connexion_recruteur.php?redirect=ajouter_offre.php");
    exit();
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $secteur = trim($_POST['secteur']);
    $description = trim($_POST['description']);
    $lieu = trim($_POST['lieu']);
    $type_contrat = trim($_POST['type_contrat']);
    $salaire = trim($_POST['salaire']);
    $date_expiration = $_POST['date_expiration'];
    $date_publication = date('Y-m-d');
    $id_recruteur = $_SESSION['utilisateur']['id'];

    // Récupérer les infos du recruteur pour la notification (nom_entreprise)
    $stmt_recruteur = $bdd->prepare("SELECT nom_entreprise FROM recruteurs WHERE id_recruteur = ?");
    $stmt_recruteur->execute([$id_recruteur]);
    $recruteur = $stmt_recruteur->fetch();

    if ($titre && $secteur &&  $description && $lieu && $type_contrat && $salaire && $date_expiration) {
        // Ajout du statut 'en_attente' à l'insertion
        $stmt = $bdd->prepare("INSERT INTO offres_emploi (id_recruteur, titre, secteur, description, lieu, type_contrat, salaire, date_publication, date_expiration, statut) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'en_attente')");
        if ($stmt->execute([$id_recruteur, $titre, $secteur, $description, $lieu, $type_contrat, $salaire, $date_publication, $date_expiration])) {
            $success = "Offre ajoutée avec succès. Elle sera publiée après validation par l'administration.";

            // --- PHPMailer - Notification à l'admin ---
            $admin_email = "yacoubaarama06@gmail.com"; // Mets ici l'email réel de l'admin

            $mail = new PHPMailer(true);
            try {
                // Configurer SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // serveur SMTP (Gmail ici)
                $mail->SMTPAuth   = true;
                $mail->Username   = 'yacoubaarama12@gmail.com'; // ton adresse email expéditrice
                $mail->Password   = 'tgpy prek vjjc cxpu'; // mot de passe ou code application
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Destinataires
                $mail->setFrom('yacoubaarama12@gmail.com', 'Malitour');
                $mail->addAddress($admin_email, 'Admin');

                // Contenu
                $mail->isHTML(false);
                $mail->Subject = "Nouvelle offre à valider";
                $mail->Body    = "Bonjour Admin,\n\nUne nouvelle offre vient d'être publiée par " . htmlspecialchars($recruteur['nom_entreprise']) . ".\nTitre de l'offre : " . htmlspecialchars($titre) . "\n\nConnectez-vous pour la valider.";

                $mail->send();
                // Optionnel : $success .= "<br>Notification envoyée à l'admin.";
            } catch (Exception $e) {
                // Optionnel : $error .= "Erreur lors de l'envoi de la notification : {$mail->ErrorInfo}";
            }
            // --- fin PHPMailer ---
        } else {
            $error = " Une erreur est survenue lors de l'ajout de l'offre.";
        }
    } else {
        $error = " Tous les champs sont obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter une offre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            font-family: 'Segoe UI', sans-serif;
            background-image: url('/projet_Rabya/igm/bg.jpg');
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .form-label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .btn-secondary:hover {
            background-color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4 text-center text-primary">
                    <i class="fas fa-briefcase me-2"></i>Ajouter une offre d'emploi
                </h2>
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php include '../includes/secteurs.php'; ?>
                <form method="POST" class="card p-4 bg-white">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre de l'offre</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>
                    <div class="mb-3">
                        <label for="secteur" class="form-label">Secteur d'activité</label>
                        <select class="form-select" name="secteur" required>
                            <option value="" disabled selected>Sélectionnez un secteur</option>
                            <?php foreach ($secteurs as $secteur): ?>
                                <option value="<?= htmlspecialchars($secteur) ?>"><?= htmlspecialchars($secteur) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description de l'offre</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="lieu" class="form-label">Lieu</label>
                        <input type="text" class="form-control" id="lieu" name="lieu" required>
                    </div>
                    <div class="mb-3">
                        <label for="type_contrat" class="form-label">Type de contrat</label>
                        <select class="form-select" id="type_contrat" name="type_contrat" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="CDI">CDI</option>
                            <option value="CDD">CDD</option>
                            <option value="Stage">Stage</option>
                            <option value="Freelance">Freelance</option>
                            <option value="Alternance">Alternance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="salaire" class="form-label">Salaire (€)</label>
                        <input type="number" class="form-control" id="salaire" name="salaire" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_expiration" class="form-label">Date d'expiration</label>
                        <input type="date" class="form-control" id="date_expiration" name="date_expiration" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i>Ajouter l'offre
                        </button>
                        <a href="/projet_Rabya/recruteurs/tableau_recruteur.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>