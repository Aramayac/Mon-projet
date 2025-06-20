<?php
session_start();
// Détruit toutes les variables de session
$_SESSION = [];
// Détruit la session côté serveur
session_destroy();
// Redirige vers la page d'accueil ou de connexion
// echo "Vous avez été déconnecté avec succès.";
// Redirection vers la page de connexion
header('Location: /projet_Rabya/connexion.php');
exit();
?>