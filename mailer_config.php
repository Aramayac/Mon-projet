<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function getMailer() {
    $mail = new PHPMailer(true);
    // 📡 Paramètres SMTP pour Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'yacoubaarama12@gmail.com'; // ✅ Ton email
    // $mail->Password   = 'Arama002@'; // ⚠️ Mdp sensible → Mieux vaut utiliser un mot de passe d'application Gmail !
    $mail->Password = 'tgpy prek vjjc cxpu'; // ✅ Mot de passe d'application sécurisé
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // 📩 Paramètres de l’expéditeur
    $mail->setFrom('yacoubaarama12@gmail.com', 'IKBARA.'); // ✅ Nom de ton site
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    return $mail;
}
?>