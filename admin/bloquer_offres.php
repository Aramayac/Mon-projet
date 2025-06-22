<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

// PHPMailer pour l'envoi des mails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php'; // adapte le chemin si besoin

if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/tableau_administateur.php');
    exit();
}

$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';

if (!is_numeric($id) || !in_array($action, ['publier','masquer'])) {
    header('Location: offres.php?msg=parametre_invalide');
    exit();
}

$nouveau_statut = $action === 'publier' ? 'publiée' : 'masquée';

$stmt = $bdd->prepare("UPDATE offres_emploi SET statut = ? WHERE id_offre = ?");
$stmt->execute([$nouveau_statut, $id]);

// Notification si l'offre est publiée
if ($nouveau_statut === 'publiée') {
    $stmt2 = $bdd->prepare("SELECT r.email, r.nom_entreprise, o.titre 
        FROM offres_emploi o 
        JOIN recruteurs r ON o.id_recruteur = r.id_recruteur 
        WHERE o.id_offre = ?");
    $stmt2->execute([$id]);
    $recruteur = $stmt2->fetch();
    if ($recruteur) {
        $subject = "Votre offre est publiée";
        $message = "Bonjour " . htmlspecialchars($recruteur['nom_entreprise']) . ",\n\nVotre offre \"" . htmlspecialchars($recruteur['titre']) . "\" vient d'être validée par l'administrateur et est maintenant visible aux candidats.\n\nBonne chance dans vos recrutements !";

        // --- PHPMailer ---
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'yacoubaarama12@gmail.com'; // Remplace par ton email expéditeur
            $mail->Password   = 'tgpy prek vjjc cxpu'; // Remplace par ton mot de passe ou code application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('yacoubaarama12@gmail.com', 'IKBara');
            $mail->addAddress($recruteur['email'], $recruteur['nom_entreprise']);

            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            // Optionnel : log ou message de succès
        } catch (Exception $e) {
            // Optionnel : log de l'erreur, exemple : error_log("Erreur mail: " . $mail->ErrorInfo);
        }
        // --- fin PHPMailer ---
    }
}

header('Location: offres.php?msg='.$action);
exit();