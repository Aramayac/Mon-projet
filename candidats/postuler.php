<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

// PHPMailer pour l'envoi de mails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$candidat = $_SESSION['utilisateur'];

// Vérifier que le profil est complet
$profil_check = $bdd->prepare("SELECT * FROM profils_candidats WHERE id_candidat = ?");
$profil_check->execute([$candidat['id']]);
$profil = $profil_check->fetch();

if (!$profil || empty($profil['cv']) || empty($profil['competences'])) {
    header("Location: /projet_Rabya/candidats/tableau_candidat.php?message=profil_incomplet");
    exit();
}

// Vérifier que l'id_offre est bien envoyé en POST
if (!isset($_POST['id_offre']) || !is_numeric($_POST['id_offre'])) {
    header("Location: /projet_Rabya/candidats/tableau_candidat.php");
    exit();
}

$id_offre = intval($_POST['id_offre']);

// Vérifier que l'offre existe
$stmt = $bdd->prepare("SELECT * FROM offres_emploi WHERE id_offre = ?");
$stmt->execute([$id_offre]);
$offre = $stmt->fetch();

if (!$offre) {
    header("Location: /projet_Rabya/candidats/tableau_candidat.php?erreur=offre");
    exit();
}

// Vérifier si le candidat a déjà postulé à cette offre
$stmt = $bdd->prepare("SELECT COUNT(*) FROM candidatures WHERE id_candidat = ? AND id_offre = ?");
$stmt->execute([$candidat['id'], $id_offre]);
$deja_postule = $stmt->fetchColumn();

if ($deja_postule > 0) {
    header("Location: /projet_Rabya/candidats/tableau_candidat.php?message=deja_postule");
    exit();
}

// Insérer la candidature
$stmt = $bdd->prepare("INSERT INTO candidatures (id_candidat, id_offre, date_candidature, statut) VALUES (?, ?, NOW(), ?)");
if ($stmt->execute([$candidat['id'], $id_offre, 'en_cours'])) {

    // Récupérer les infos du recruteur pour l'offre
    $stmt2 = $bdd->prepare("SELECT r.email, r.nom_entreprise, o.titre 
        FROM offres_emploi o 
        JOIN recruteurs r ON o.id_recruteur = r.id_recruteur 
        WHERE o.id_offre = ?");
    $stmt2->execute([$id_offre]);
    $recruteur = $stmt2->fetch();

    // 1. Notification au recruteur
    if ($recruteur) {
        $subject = "Nouvelle candidature reçue";
        $message = "Bonjour " . htmlspecialchars($recruteur['nom_entreprise']) . ",\n\nUn candidat vient de postuler à votre offre : \"" . htmlspecialchars($recruteur['titre']) . "\".\nConnectez-vous à votre espace recruteur pour consulter la candidature.";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'yacoubaarama12@gmail.com'; // à adapter
            $mail->Password   = 'tgpy prek vjjc cxpu'; // à adapter
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('yacoubaarama12@gmail.com', 'IKBara');
            $mail->addAddress($recruteur['email'], $recruteur['nom_entreprise']);

            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->send();
        } catch (Exception $e) {
            // Optionnel : log d'erreur
        }
    }

    // 2. Notification au candidat
    $candidat_email = $candidat['email'];
    $subject = "Confirmation de votre candidature";
    $message = "Bonjour " . htmlspecialchars($candidat['nom']) . ",\n\nVotre candidature à l'offre \"" . htmlspecialchars($offre['titre']) . "\" a bien été enregistrée. Nous vous tiendrons informé(e) de la suite de votre dossier.\n\nBonne chance !";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'yacoubaarama12@gmail.com'; // à adapter
        $mail->Password   = 'tgpy prek vjjc cxpu'; // à adapter
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('yacoubararama12@gmail.com', 'IKBara');
        $mail->addAddress($candidat_email, $candidat['nom']);

        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();
    } catch (Exception $e) {
        // Optionnel : log d'erreur
    }

    header("Location: /projet_Rabya/candidats/tableau_candidat.php?message=success");
    exit();
} else {
    header("Location: /projet_Rabya/candidats/tableau_candidat.php?message=erreur");
    exit();
}