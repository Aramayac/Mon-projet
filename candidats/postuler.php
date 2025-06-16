<?php
session_start();
require_once __DIR__.'/../configuration/connexionbase.php';

// Vérifier que le candidat est connecté
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$candidat = $_SESSION['utilisateur'];

// Vérifier que le profil est complet
$profil_check = $bdd->prepare("SELECT * FROM profils_candidats WHERE id_candidat = ?");
$profil_check->execute([$candidat['id']]);
$profil = $profil_check->fetch();

// Ici, on considère qu'il faut avoir un CV et des compétences renseignées
if (!$profil || empty($profil['cv']) || empty($profil['competences'])) {
    // Redirige avec un message d'erreur (à gérer dans la vue)
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
    header("Location: /projet_Rabya/candidats/tableau_candidat.php?message=success");
    exit();
} else {
    header("Location: /projet_Rabya/candidats/tableau_candidat.php?message=erreur");
    exit();
}
?>