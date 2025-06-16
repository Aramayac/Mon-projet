<?php
session_start();
require_once __DIR__.'/../configuration/connexionbase.php';

// Vérifier que le recruteur est connecté
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

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
$nouveau_statut = $_POST['action'] === 'accepter' ? 'acceptée' : 'refusée'; // operation ternaire pour déterminer le nouveau statut de la candidature

// Recuperer les info pour le message
$stmt = $bdd->prepare("SELECT c.id_candidat, o.titre 
                       FROM candidatures c 
                       JOIN offres_emploi o ON c.id_offre = o.id_offre 
                       WHERE c.id_candidature = ?");
$stmt->execute([$id_candidature]);
$info = $stmt->fetch();
$id_candidat = $info['id_candidat'];
$titre_offre = $info['titre'];


// Préparer le message automatique
if ($nouveau_statut === 'acceptée') {
    $contenu = "Félicitations, votre candidature à l'offre " . htmlspecialchars($titre_offre) . " a été acceptée.";
} else {
    $contenu = "Nous sommes désolés, votre candidature à l'offre " . htmlspecialchars($titre_offre) . " a été refusée.";
}


// Insérer le message dans la table messages
$stmt_msg = $bdd->prepare("INSERT INTO messages (id_expediteur, id_destinataire, contenu,date_envoi) VALUES (?, ?, ?, ?)");
$stmt_msg->execute([
    $_SESSION['utilisateur']['id'], // id du recruteur (ou 0 pour systeme pur)
    $id_candidat,
    $contenu,
    $id_candidature
]);

// Optionnel : vérifier que la candidature appartient bien à une offre du recruteur
// ...

$stmt = $bdd->prepare("UPDATE candidatures SET statut = ? WHERE id_candidature = ?");
$stmt->execute([$nouveau_statut, $id_candidature]);

header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?message=statut_maj");
exit();
?>