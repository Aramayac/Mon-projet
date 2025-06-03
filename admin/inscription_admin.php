<?php
require_once __DIR__ . '/../configuration/connexionbase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $bdd->prepare("INSERT INTO admins (username, password) VALUES (?,?)");
        if ($stmt->execute([$username, $hash])) {
            echo "Admin créé avec succès !";
        } else {
            echo "Erreur lors de la création.";
        }
    } else {
        echo "Champs manquants.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Inscrire un admin</title></head>
<body>
<form method="post">
    <label>Nom d'utilisateur : <input name="username" required></label><br>
    <label>Mot de passe : <input name="password" type="password" required></label><br>
    <button type="submit">Créer admin</button>
</form>
</body>
</html>