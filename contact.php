<?php

// Inclure l'autoload de PHPMailer (mettez le bon chemin selon votre structure)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

// Configuration - à adapter selon votre projet

$whatsapp_phone = '22374878873';
$whatsapp_message = "Bonjour, je souhaite des informations sur le service de recrutement en ligne.";
$whatsapp_link = "https://wa.me/$whatsapp_phone?text=" . urlencode($whatsapp_message);
$whatsapp_message = "Bonjour, je souhaite obtenir des informations concernant le service de recrutement en ligne.";

// Traitement du formulaire
$success = false;
$error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $objet = htmlspecialchars(trim($_POST['objet'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));
    $societe = htmlspecialchars(trim($_POST['societe'] ?? ''));
    $role = htmlspecialchars(trim($_POST['role'] ?? ''));
    if ($nom && $prenom && $email && $objet && $message) {
        // --- ENREGISTREMENT EN BASE (à adapter selon votre logique de connexion) ---
        try {
            require_once __DIR__ . '/configuration/connexionbase.php';
            $stmt = $bdd->prepare("INSERT INTO contact (nom, prenom, email, objet, message, telephone, societe, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $objet, $message, $telephone, $societe, $role]);
        } catch (Exception $e) {
            $error = true;
        }
        // --- ENVOI MAIL AVEC PHPMailer ---
        try {
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = 2; // Décommentez pour debug
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // À personnaliser
            $mail->SMTPAuth = true;
            $mail->Username = 'yacoubaarama12@gmail.com'; // À personnaliser
            $mail->Password = 'tgpy prek vjjc cxpu'; // À personnaliser
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yacoubaarama12@gmail.com', 'Contact Recrutement Pro');
            $mail->addAddress('yacoubaarama06@gmail.com', 'Administrateur'); // À personnaliser
            $mail->addReplyTo($email, "$prenom $nom");
            // $mail->addCC('secretariat@yourdomain.com'); // Ajoutez d'autres destinataires si besoin

            $mail->isHTML(true);
            $mail->Subject = "Nouveau message de contact - $objet";
            $mail->Body = "
                <h2>Nouveau message de contact</h2>
                <ul>
                  <li><strong>Nom :</strong> " . htmlspecialchars($nom) . "</li>
                  <li><strong>Prénom :</strong> " . htmlspecialchars($prenom) . "</li>
                  <li><strong>Email :</strong> " . htmlspecialchars($email) . "</li>
                  <li><strong>Téléphone :</strong> " . htmlspecialchars($telephone) . "</li>
                  <li><strong>Société :</strong> " . htmlspecialchars($societe) . "</li>
                  <li><strong>Rôle :</strong> " . htmlspecialchars($role) . "</li>
                  <li><strong>Objet :</strong> " . htmlspecialchars($objet) . "</li>
                  <li><strong>Message :</strong><br>" . nl2br(htmlspecialchars($message)) . "</li>
                </ul>
                <hr>
                <small>Ce message a été envoyé via le formulaire de contact de l'application Recrutement Pro.</small>
            ";
            $mail->AltBody = "Nom: $nom\nPrénom: $prenom\nEmail: $email\nTéléphone: $telephone\nSociété: $societe\nRôle: $role\nObjet: $objet\nMessage: $message";
            $mail->send();
            $success = true;
        } catch (Exception $e) {
            // En production : loguer l'erreur $mail->ErrorInfo
            $error = true;
        }
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Contactez-nous | Recrutement Pro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #f5fafd 0%, #e2e6ee 100%);
            min-height: 100vh;
        }

        .contact-section {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 6px 36px 0 rgba(0, 102, 153, 0.08);
            padding: 40px 30px 30px 30px;
            margin-top: 48px;
            margin-bottom: 48px;
        }

        .contact-header {
            font-size: 2.2rem;
            font-weight: 700;
            color: #14697b;
        }

        .info-card {
            background: linear-gradient(120deg, #eaf6fa 60%, #f7fafd 100%);
            border-radius: 16px;
            padding: 22px 20px 18px 20px;
            box-shadow: 0 1px 8px #0099cc14;
            margin-bottom: 28px;
        }

        .info-card h6 {
            color: #006699;
            font-size: 1.05rem;
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
            color: #2b415d;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border-color: #d1e1ec;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0099cc;
            box-shadow: 0 0 0 3px #0099cc22;
        }

        .btn-contact {
            background: linear-gradient(90deg, #0099cc 60%, #006699 100%);
            color: #fff;
            border-radius: 21px;
            font-weight: 600;
            padding: 9px 36px;
            font-size: 1.08rem;
            transition: background 0.17s, box-shadow 0.17s;
            box-shadow: 0 3px 18px #0099cc18;
        }

        .btn-contact:hover,
        .btn-contact:focus {
            background: linear-gradient(90deg, #006699 20%, #0099cc 100%);
            color: #fff;
            box-shadow: 0 6px 28px #0099cc33;
        }

        .social-link {
            font-size: 2rem;
            margin-right: 16px;
            color: #0099cc;
            transition: color .14s;
        }

        .social-link:hover {
            color: #14697b;
        }

        .divider {
            border-top: 2px dashed #d9e8f3;
            margin: 36px 0 36px 0;
        }

        .badge-role {
            font-size: 1.08em;
            padding: 0.44em 1.2em;
            border-radius: 13px;
            background: #e3f0fa;
            color: #006699;
        }

        .btn-whatsapp {
            background: #25d366;
            background-color: #21b858;
            color: #fff;
            border-radius: 21px;
            font-weight: 600;
            font-size: 1.08rem;
            padding: 9px 34px;
            margin-top: 14px;
            box-shadow: 0 3px 18px #25d36618;
            transition: background 0.15s, box-shadow 0.14s;
        }

        .btn-whatsapp:hover {
            background: #21b858;
            color: #fff;
            box-shadow: 0 6px 28px #25d36633;
        }

        @media (max-width: 900px) {
            .contact-section {
                padding: 18px 9px 18px 9px;
            }
        }

        .btn-whatsapp {
            background: linear-gradient(90deg, #25d366 0%, #128c7e 100%);
            color: #fff !important;
            font-weight: 600;
            font-size: 1.09em;
            border-radius: 30px;
            border: none;
            box-shadow: 0 3px 12px #128c7e26;
            transition: background .18s, box-shadow .18s, transform .13s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 0;
            letter-spacing: .01em;
        }

        .btn-whatsapp .bi-whatsapp {
            font-size: 1.32em;
            margin-right: 9px;
            font-weight: bold;
            vertical-align: middle;
        }

        .btn-whatsapp:hover,
        .btn-whatsapp:focus {
            background: linear-gradient(90deg, #128c7e 0%, #25d366 100%);
            color: #fff !important;
            box-shadow: 0 6px 22px #25d36633;
            
            transform: scale(1.04);
            text-decoration: none;
        }
    </style>


    </style>
</head>
<?php include '../projet_Rabya/header_contact.php'; ?>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="contact-section animate__animated animate__fadeInUp">
                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="contact-header mb-1"><i class="bi bi-envelope-at-fill"></i> Contactez-nous</h1>
                            <div class="mb-3" style="color: #3d5365; font-size: 1.12rem;">
                                Une question, un besoin, un partenariat ? Notre équipe vous répondra dans les meilleurs délais.<br>
                                <span class="badge badge-role mt-2">Candidats, Recruteurs, Partenaires, Presse...</span>
                            </div>
                            <div class="info-card">
                                <h6><i class="bi bi-geo-alt-fill"></i> Adresse :</h6>
                                <p>123, Avenue du Progrès, Bamako, Mali</p>
                                <h6><i class="bi bi-telephone-fill"></i> Téléphone :</h6>
                                <p>+223 20 00 00 00 / +223 70 00 00 00</p>
                                <h6><i class="bi bi-envelope-fill"></i> Email :</h6>
                                <p>contact@recrutement-pro.ml</p>
                                <h6><i class="bi bi-clock-fill"></i> Horaires :</h6>
                                <p>Lun - Ven : 8h30 - 18h00</p>
                                <div class="mt-3">
                                    <a href="#" class="social-link" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                                    <a href="#" class="social-link" aria-label="X / Twitter"><i class="bi bi-twitter-x"></i></a>
                                    <a href="#" class="social-link" aria-label="Linkedin"><i class="bi bi-linkedin"></i></a>
                                    <a href="https://wa.me/<?= $whatsapp_phone ?>?text=<?= urlencode($whatsapp_message) ?>"
                                        target="_blank" class="social-link" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                </div>
                                <a href="<?= $whatsapp_link ?>"
                                    target="_blank" class="btn btn-whatsapp w-100 mt-2">
                                    <i class="bi bi-whatsapp"></i>
                                    Discussion WhatsApp directe
                                </a>
                            </div>
                            <div class="info-card">
                                <h6><i class="bi bi-info-circle-fill"></i> FAQ rapide :</h6>
                                <ul class="mb-2" style="font-size:.97em;">
                                    <li>Dépôt de CV et lettre de motivation en ligne</li>
                                    <li>Assistance technique pour l’utilisation du site</li>
                                    <li>Informations sur nos services RH et solutions entreprises</li>
                                    <li>Support candidats et chefs d’entreprise</li>
                                    <li>Gestion de la confidentialité et de vos données</li>
                                </ul>
                                <a href="faq.php" class="btn btn-outline-primary btn-sm mt-1"><i class="bi bi-question-circle"></i> Voir la FAQ</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php if ($success): ?>
                                <div class="alert alert-success animate__animated animate__fadeIn"><i class="bi bi-check-circle"></i> Votre message a bien été envoyé. Notre équipe vous répondra sous 24h.</div>
                            <?php elseif ($error): ?>
                                <div class="alert alert-danger animate__animated animate__shakeX"><i class="bi bi-x-circle"></i> Merci de remplir correctement tous les champs obligatoires ou réessayez plus tard.</div>
                            <?php endif; ?>
                            <form method="post" class="mt-2 needs-validation" novalidate autocomplete="on">
                                <div class="row g-2">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="prenom">Prénom *</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" required maxlength="50" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="nom">Nom *</label>
                                        <input type="text" class="form-control" id="nom" name="nom" required maxlength="50" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="email">E-mail *</label>
                                        <input type="email" class="form-control" id="email" name="email" required maxlength="120" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="telephone">Téléphone</label>
                                        <input type="tel" class="form-control" id="telephone" name="telephone" maxlength="30" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="role">Vous êtes *</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="" disabled selected>Choisissez...</option>
                                        <option <?= (isset($_POST['role']) && $_POST['role'] == 'Candidat') ? 'selected' : '' ?>>Candidat</option>
                                        <option <?= (isset($_POST['role']) && $_POST['role'] == 'Recruteur') ? 'selected' : '' ?>>Recruteur</option>
                                        <option <?= (isset($_POST['role']) && $_POST['role'] == 'Entreprise') ? 'selected' : '' ?>>Entreprise</option>
                                        <option <?= (isset($_POST['role']) && $_POST['role'] == 'Partenaire') ? 'selected' : '' ?>>Partenaire</option>
                                        <option <?= (isset($_POST['role']) && $_POST['role'] == 'Presse') ? 'selected' : '' ?>>Presse</option>
                                        <option <?= (isset($_POST['role']) && $_POST['role'] == 'Autre') ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="societe">Société / Organisation</label>
                                    <input type="text" class="form-control" id="societe" name="societe" maxlength="120" value="<?= htmlspecialchars($_POST['societe'] ?? '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="objet">Objet *</label>
                                    <input type="text" class="form-control" id="objet" name="objet" required maxlength="160" value="<?= htmlspecialchars($_POST['objet'] ?? '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="message">Votre message *</label>
                                    <textarea class="form-control" rows="5" id="message" name="message" required maxlength="2000"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                                </div>
                                <div class="mb-3 d-flex align-items-center">
                                    <button type="submit" class="btn btn-contact me-2">
                                        <i class="bi bi-send"></i> Envoyer
                                    </button>
                                    <span style="font-size:.95em;color:#8b9bb1;">* Champs obligatoires</span>
                                </div>
                            </form>
                            <hr class="divider">
                            <div class="text-center" style="font-size:.95em; color:#5f799a;">
                                <i class="bi bi-shield-lock"></i> Vos informations resteront strictement confidentielles et ne seront jamais transmises à des tiers.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap validation UI
        (() => {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
<?php include '../projet_Rabya/footer_contact.php'; ?>

</html>