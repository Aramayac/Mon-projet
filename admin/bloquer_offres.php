<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

// Vérifier la session admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/tableau_administateur.php');
    exit();
}

// Vérifier l'ID de l'offre
$id = $_GET['id'] ?? '';
$activer = isset($_GET['activer']); // si activer=1, on publie, sinon on masque

if (!is_numeric($id)) {
    header('Location: offres.php?msg=parametre_invalide');
    exit();
}

// Déterminer le nouveau statut
$nouveau_statut = $activer ? 'publiée' : 'masquée';

// Mettre à jour le statut de l'offre
$stmt = $bdd->prepare("UPDATE offres_emploi SET statut = ? WHERE id_offre = ?");
$stmt->execute([$nouveau_statut, $id]);

// Redirection avec message de succès
header('Location: offres.php?msg=' . ($activer ? 'publiee' : 'masquee'));
exit();
