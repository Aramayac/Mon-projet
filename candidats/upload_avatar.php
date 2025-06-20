<?php
require_once __DIR__ . '/../configuration/connexionbase.php';
session_start();
$id_candidat = $_SESSION['id_candidat'] ?? null;
if (!$id_candidat) {
    header("Location: tableau_candidat.php");
    exit;
}

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $avatar_dir = __DIR__ . '/avatars/';
    if (!is_dir($avatar_dir)) {
        mkdir($avatar_dir, 0755, true);
    }
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed)) {
        $_SESSION['avatar_error'] = "Format d'image non autorisé.";
        header("Location: tableau_candidat.php");
        exit;
    }
    $avatar_name = 'candidat_' . $id_candidat . '_' . time() . '.' . $ext;
    $avatar_path = $avatar_dir . $avatar_name;
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_path)) {
        // On stocke juste le nom du fichier, pas tout le chemin
        $stmt = $bdd->prepare("UPDATE candidats SET avatar = ? WHERE id_candidat = ?");
        $stmt->execute([$avatar_name, $id_candidat]);
        $_SESSION['avatar_success'] = "Photo de profil mise à jour !";
    } else {
        $_SESSION['avatar_error'] = "Erreur lors de l'upload.";
    }
}
header("Location: tableau_candidat.php");
exit;