<?php
// $host = 'localhost';
// $dbname = 'arama_job';
// $username = 'root';
// $password = 'Arama001@';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=recrutement_en_ligne;charset=utf8mb4', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

?>

