<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/connexion_adminstrateur.php');
    exit();
}

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';
$activer = isset($_GET['activer']); // si activer=1, on active, sinon on bloque

// Vérifications de sécurité
if (!in_array($type, ['candidat', 'recruteur']) || !is_numeric($id)) {
    header('Location: /projet_Rabya/admin/users.php?msg=parametre_invalide');
    exit();
}

$table = $type === 'candidat' ? 'candidats' : 'recruteurs';
$champ_id = $type === 'candidat' ? 'id_candidat' : 'id_recruteur';
$nouveau_statut = $activer ? 'actif' : 'bloqué';

// Mise à jour du statut
$stmt = $bdd->prepare("UPDATE $table SET statut = ? WHERE $champ_id = ?");
$stmt->execute([$nouveau_statut, $id]);

// Optionnel : Récupérer l'email pour notifier l'utilisateur
$user = $bdd->prepare("SELECT email FROM $table WHERE $champ_id = ?");
$user->execute([$id]);
$email = $user->fetchColumn();

if ($email) {
    $sujet = $activer ? "Compte réactivé" : "Compte bloqué";
    $message = $activer
        ? "Bonjour, votre compte a été réactivé. Vous pouvez à nouveau accéder à l’application."
        : "Bonjour, votre compte a été bloqué par un administrateur. Vous ne pouvez plus accéder à l’application jusqu’à nouvel ordre.";
    // mail($email, $sujet, $message); // Décommente pour activer l'envoi réel
}

// Redirection avec message de retour
header('Location: /projet_Rabya/admin/users.php?msg=' . ($activer ? 'active' : 'bloque'));
exit();
