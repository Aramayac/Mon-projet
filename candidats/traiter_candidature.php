<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';
require_once __DIR__ . '/../mailer_config.php'; // Fichier de config PHPMailer

// Vérifier que le recruteur est connecté
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

// Vérification des paramètres POST
if (
    !isset($_POST['id_candidature']) ||
    !is_numeric($_POST['id_candidature']) ||
    !isset($_POST['action']) ||
    !in_array($_POST['action'], ['accepter', 'refuser'])
) {
    header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?message=erreur");
    exit();
}

$id_candidature = intval($_POST['id_candidature']);
$nouveau_statut = $_POST['action'] === 'accepter' ? 'acceptée' : 'refusée';

// Récupérer infos pour le message et l'e-mail
$stmt = $bdd->prepare("
    SELECT c.id_candidat, o.titre, ca.email, ca.prenom, ca.nom
    FROM candidatures c
    JOIN offres_emploi o ON c.id_offre = o.id_offre
    JOIN candidats ca ON c.id_candidat = ca.id_candidat
    WHERE c.id_candidature = ?
");
$stmt->execute([$id_candidature]);
$info = $stmt->fetch();

if (!$info) {
    header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?message=candidature_introuvable");
    exit();
}

$id_candidat = $info['id_candidat'];
$titre_offre = $info['titre'];
$email_candidat = $info['email'];
$prenom_candidat = $info['prenom'] ?? '';
$nom_candidat = $info['nom'] ?? '';
$nom_complet = trim($prenom_candidat . " " . $nom_candidat);

// Préparer le message (interne et email)
if ($nouveau_statut === 'acceptée') {
    $contenu = "Félicitations, votre candidature à l'offre " . htmlspecialchars($titre_offre) . " a été acceptée.";
    $sujet_email = "Votre candidature a été acceptée !";
    $body_email = "<p>Bonjour $nom_complet,</p>
    <p>Félicitations, votre candidature à l’offre <strong>$titre_offre</strong> a été <b>acceptée</b> !<br>
    Nous vous contacterons prochainement pour la suite du processus.</p>
    <p>Cordialement,<br>L’équipe Recrutement IKBARA</p>";
} else {
    $contenu = "Nous sommes désolés, votre candidature à l'offre " . htmlspecialchars($titre_offre) . " a été refusée.";
    $sujet_email = "Votre candidature n'a pas été retenue";
    $body_email = "<p>Bonjour $nom_complet,</p>
    <p>Nous sommes désolés, votre candidature à l’offre <strong>$titre_offre</strong> n’a pas été retenue.<br>
    Nous vous souhaitons une bonne continuation.</p>
    <p>Cordialement,<br>L’équipe Recrutement IKBARA</p>";
}

// Insérer le message dans la table messages (messagerie interne)
$stmt_msg = $bdd->prepare("
    INSERT INTO messages (id_expediteur, id_destinataire, contenu, date_envoi)
    VALUES (?, ?, ?, NOW())
");
$stmt_msg->execute([
    $_SESSION['utilisateur']['id'], // id du recruteur
    $id_candidat,
    $contenu
]);

// Envoyer l'email externe au candidat (PHPMailer)
try {
    $mail = getMailer();
    $mail->addAddress($email_candidat, $nom_complet);
    $mail->Subject = $sujet_email;
    $mail->Body    = $body_email;
    $mail->AltBody = strip_tags(str_replace("<br>", "\n", $body_email));
    $mail->send();
} catch (Exception $e) {
    // Log ou alerte admin possible ici si besoin
    // error_log("Erreur PHPMailer : " . $mail->ErrorInfo);
}

// Mettre à jour le statut de la candidature
$stmt = $bdd->prepare("UPDATE candidatures SET statut = ? WHERE id_candidature = ?");
$stmt->execute([$nouveau_statut, $id_candidature]);

header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?message=statut_maj");
exit();
?>