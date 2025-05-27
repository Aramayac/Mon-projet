<?php
session_start();
require_once 'connexionbase.php';

// Vérifier que le candidat est connecté
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: connexion.php");
    exit();
}

$candidat = $_SESSION['utilisateur'];

// Vérifier que l'id_offre est bien envoyé en POST
if (!isset($_POST['id_offre']) || !is_numeric($_POST['id_offre'])) {
    header("Location: tableau_candidat.php");
    exit();
}

$id_offre = intval($_POST['id_offre']);

// Vérifier que l'offre existe
$stmt = $bdd->prepare("SELECT * FROM offres_emploi WHERE id_offre = ?");
$stmt->execute([$id_offre]);
$offre = $stmt->fetch();

if (!$offre) {
    header("Location: tableau_candidat.php?erreur=offre");
    exit();
}

// Vérifier si le candidat a déjà postulé à cette offre
$stmt = $bdd->prepare("SELECT COUNT(*) FROM candidatures WHERE id_candidat = ? AND id_offre = ?");
$stmt->execute([$candidat['id'], $id_offre]);
$deja_postule = $stmt->fetchColumn();

if ($deja_postule > 0) {
    // Déjà postulé, on revient à la liste avec un message
    header("Location: tableau_candidat.php?message=deja_postule");
    exit();
}

// Insérer la candidature
$stmt = $bdd->prepare("INSERT INTO candidatures (id_candidat, id_offre, date_candidature, statut) VALUES (?, ?, NOW(), ?)");
if ($stmt->execute([$candidat['id'], $id_offre, 'en attente'])) {
    header("Location: tableau_candidat.php?message=success");
    exit();
} else {
    header("Location: tableau_candidat.php?message=erreur");
    exit();
}
?>