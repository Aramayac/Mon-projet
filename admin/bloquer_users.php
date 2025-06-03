<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';
if (!isset($_SESSION['admin_id'])) { header('Location: /projet_Rabya/admin/connexion_adminstrateur.php'); exit(); }
$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';
$activer = isset($_GET['activer']); // si activer=1, on active, sinon on bloque
if ($type === 'candidat') {
    $stmt = $bdd->prepare("UPDATE candidats SET statut=? WHERE id_candidat=?");
    $stmt->execute([$activer ? 'actif' : 'bloqué', $id]);
} elseif ($type === 'recruteur') {
    $stmt = $bdd->prepare("UPDATE recruteurs SET statut=? WHERE id_recruteur=?");
    $stmt->execute([$activer ? 'actif' : 'bloqué', $id]);
}
header('Location: /projet_Rabya/admin/users.php'); exit();