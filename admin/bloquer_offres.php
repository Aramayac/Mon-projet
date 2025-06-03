<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';
if (!isset($_SESSION['admin_id'])) { header('Location: /projet_Rabya/admin/tableau_administateur.php'); exit(); }
$id = $_GET['id'] ?? '';
$activer = isset($_GET['activer']); // si activer=1, on publie, sinon on masque
$stmt = $bdd->prepare("UPDATE offres_emploi SET statut=? WHERE id_offre=?");
$stmt->execute([$activer ? 'publiée' : 'masquée', $id]);
header('Location: offres.php'); exit();