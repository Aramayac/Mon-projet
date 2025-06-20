<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

// Vérifier que l'utilisateur est connecté et est un recruteur
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

// Vérifier l'id_offre en GET
if (!isset($_GET['id_offre']) || !is_numeric($_GET['id_offre'])) {
    header("Location: recruteur.php?msg=offre_invalide");
    exit();
}

$id_offre = intval($_GET['id_offre']);

// Vérifier que l'offre appartient au recruteur connecté
$stmt = $bdd->prepare("SELECT * FROM offres_emploi WHERE id_offre = ? AND id_recruteur = ?");
$stmt->execute([$id_offre, $_SESSION['utilisateur']['id']]);
$offre = $stmt->fetch();

if (!$offre) {
    header("Location: recruteur.php?msg=offre_introuvable");
    exit();
}

// 1. (optionnel) Supprimer les candidatures associées à cette offre
$bdd->prepare("DELETE FROM candidatures WHERE id_offre = ?")->execute([$id_offre]);

// 2. Supprimer l'offre
$bdd->prepare("DELETE FROM offres_emploi WHERE id_offre = ?")->execute([$id_offre]);

header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?msg=offre_supprimee");
exit();
