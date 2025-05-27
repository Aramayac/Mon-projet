<?php
session_start();
require_once 'connexionbase.php';

// Vérifie que l'utilisateur est connecté et est un candidat
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: connexion.php");
    exit();
}

$id_candidat = $_SESSION['utilisateur']['id'];

$message = "";

// Vérifie si le profil existe déjà (empêche les doublons)
$stmt = $bdd->prepare("SELECT COUNT(*) FROM profils_candidats WHERE id_candidat = ?");
$stmt->execute([$id_candidat]);
if ($stmt->fetchColumn() > 0) {
    header("Location: tableau_candidat.php");
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telephone = trim($_POST['telephone'] ?? '');
    $niveau_etude = trim($_POST['niveau_etude'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $competences = trim($_POST['competences'] ?? '');

    if ($niveau_etude === 'Autre' && !empty($_POST['autre_niveau'])) {
        $niveau_etude = trim($_POST['autre_niveau']);
    }

    // Gestion CV
    $cv_name = null;
    if (!empty($_FILES['cv']['name'])) {
        $cv_dir = 'dossier/cv/';
        if (!is_dir($cv_dir)) {
            mkdir($cv_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) === 'pdf') {
            $cv_name = uniqid() . '_' . basename($_FILES['cv']['name']);
            move_uploaded_file($_FILES['cv']['tmp_name'], $cv_dir . $cv_name);
        } else {
            $message = "Seuls les fichiers PDF sont acceptés pour le CV.";
        }
    } else {
        $message = "Le CV est requis.";
    }

    // Insertion si tout est OK
    if (!$message) {
        $stmt = $bdd->prepare("INSERT INTO profils_candidats (id_candidat, telephone, niveau_etude, experience, competences, cv)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_candidat, $telephone, $niveau_etude, $experience, $competences, $cv_name]);
        header("Location: tableau_candidat.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compléter mon profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card shadow-lg p-4">
        <h3 class="mb-4 text-primary">Complétez votre profil</h3>
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Niveau d'étude</label>
                <select name="niveau_etude" id="niveau_etude" class="form-select" required onchange="toggleAutreNiveau()">
                    <option value="" disabled selected>-- Sélectionnez --</option>
                    <option value="BEP">BEP</option>
                    <option value="Bac">Bac</option>
                    <option value="Licence">Licence</option>
                    <option value="Master">Master</option>
                    <option value="Doctorat">Doctorat</option>
                    <option value="Autre">Autre</option>
                </select>
                <input type="text" name="autre_niveau" id="autre_niveau" class="form-control mt-2" placeholder="Précisez votre niveau d'étude" style="display:none;">
            </div>
            <div class="mb-3">
                <label class="form-label">Expérience</label>
                <textarea name="experience" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Compétences</label>
                <textarea name="competences" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléverser votre CV (PDF uniquement)</label>
                <input type="file" name="cv" class="form-control" accept="application/pdf" required>
            </div>
            <button type="submit" class="btn btn-success">Enregistrer</button>
        </form>
    </div>
</div>
<script>
function toggleAutreNiveau() {
    const select = document.getElementById('niveau_etude');
    const autre = document.getElementById('autre_niveau');
    autre.style.display = select.value === 'Autre' ? 'block' : 'none';
}
</script>
</body>
</html>