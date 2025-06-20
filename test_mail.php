<?php
// Test de l'envoi d'un e-mail avec PHPMailer

require 'mailer_config.php';

try {
    $mail = getMailer();
    $mail->addAddress('yacoubaarama06@gmail.com', 'YACOUBA ARAMA');
    $mail->Subject = 'Test PHPMailer';
    $mail->Body    = '<b>Bravo !</b> PHPMailer fonctionne sur ton projet.';

    $mail->send();
    echo "E-mail envoyé avec succès !";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
}
?>