<?php
session_start();
require_once 'connexionbase.php';

// Vérification connexion
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: connexion.php");
    exit();
}

$id_candidat = $_SESSION['utilisateur']['id'];
$message = "";

// Charger les infos du profil
$stmt = $bdd->prepare("SELECT * FROM profils_candidats WHERE id_candidat = ?");
$stmt->execute([$id_candidat]);
$profil = $stmt->fetch();

if (!$profil) {
    // Si pas de profil, redirige vers la complétion
    header("Location: completer_profil.php");
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

    // Gestion du CV
    $cv_name = $profil['cv'];
    if (!empty($_FILES['cv']['name'])) {
        $cv_dir = 'dossier/cv/';
        if (!is_dir($cv_dir)) {
            mkdir($cv_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) === 'pdf') {
            $cv_name = uniqid() . '_' . basename($_FILES['cv']['name']);
            move_uploaded_file($_FILES['cv']['tmp_name'], $cv_dir . $cv_name);

            // Suppression de l'ancien CV si besoin
            if (!empty($profil['cv']) && file_exists($cv_dir . $profil['cv'])) {
                unlink($cv_dir . $profil['cv']);
            }
        } else {
            $message = "Seuls les fichiers PDF sont acceptés pour le CV.";
        }
    }

    if (!$message) {
        // Mise à jour
        $stmt = $bdd->prepare("UPDATE profils_candidats 
            SET telephone=?, niveau_etude=?, experience=?, competences=?, cv=? 
            WHERE id_candidat=?");
        $stmt->execute([$telephone, $niveau_etude, $experience, $competences, $cv_name, $id_candidat]);
        header("Location: tableau_candidat.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card shadow-lg p-4">
        <h3 class="mb-4 text-primary">Modifier mon profil</h3>
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($profil['telephone'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Niveau d'étude</label>
                <select name="niveau_etude" id="niveau_etude" class="form-select" required onchange="toggleAutreNiveau()">
                    <option value="" disabled>-- Sélectionnez --</option>
                    <?php
                    $niveaux = ['BEP','Bac','Licence','Master','Doctorat','Autre'];
                    foreach ($niveaux as $niv) {
                        $selected = ($profil['niveau_etude'] == $niv) ? 'selected' : '';
                        echo "<option value=\"$niv\" $selected>$niv</option>";
                    }
                    ?>
                </select>
                <input type="text" name="autre_niveau" id="autre_niveau" class="form-control mt-2"
                       placeholder="Précisez votre niveau d'étude"
                       style="display:<?= ($profil['niveau_etude'] == 'Autre') ? 'block':'none' ?>"
                       value="<?= ($profil['niveau_etude'] == 'Autre') ? htmlspecialchars($profil['niveau_etude']) : '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Expérience</label>
                <textarea name="experience" class="form-control" required><?= htmlspecialchars($profil['experience'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Compétences</label>
                <textarea name="competences" class="form-control" required><?= htmlspecialchars($profil['competences'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">CV actuel :</label>
                <?php if (!empty($profil['cv'])): ?>
                    <a href="dossier/cv/<?= htmlspecialchars($profil['cv']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm">Voir mon CV</a>
                <?php else: ?>
                    <span class="text-muted">Aucun CV ajouté</span>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Changer de CV (PDF uniquement)</label>
                <input type="file" name="cv" class="form-control" accept="application/pdf">
                <div class="form-text">Laissez vide si vous ne souhaitez pas changer de CV.</div>
            </div>
            <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
            <a href="tableau_candidat.php" class="btn btn-secondary ms-2">Annuler</a>
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