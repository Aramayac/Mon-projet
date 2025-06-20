<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$id_candidat = $_SESSION['utilisateur']['id'];
$message = "";

$stmt = $bdd->prepare("SELECT COUNT(*) FROM profils_candidats WHERE id_candidat = ?");
$stmt->execute([$id_candidat]);
if ($stmt->fetchColumn() > 0) {
    header("Location: tableau_candidat.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telephone = trim($_POST['telephone'] ?? '');
    $niveau_etude = trim($_POST['niveau_etude'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $competences = trim($_POST['competences'] ?? '');

    if ($niveau_etude === 'Autre' && !empty($_POST['autre_niveau'])) {
        $niveau_etude = trim($_POST['autre_niveau']);
    }

    $cv_name = null;
    if (!empty($_FILES['cv']['name'])) {
        $cv_dir = __DIR__ . '/cv/';
        if (!is_dir($cv_dir)) {
            mkdir($cv_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) === 'pdf') {
            $cv_name = uniqid() . '_' . basename($_FILES['cv']['name']);
            move_uploaded_file($_FILES['cv']['tmp_name'], $cv_dir . $cv_name);
        } else {
            $message = "Seuls les fichiers PDF sont acceptés.";
        }
    } else {
        $message = "Le CV est requis.";
    }

    if (!$message) {
        $stmt = $bdd->prepare("INSERT INTO profils_candidats (id_candidat, telephone, niveau_etude, experience, competences, cv)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_candidat, $telephone, $niveau_etude, $experience, $competences, $cv_name]);
        header("Location: /projet_Rabya/candidats/tableau_candidat.php");
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #005AA7, #FFFDE4);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 800px;
            background-image: url('/projet_Rabya/igm/s4.jpg');
            background-size: cover;
        }

        .card {
            padding: 30px;
        }

        h3 {
            background-image: url('/projet_Rabya/igm/m1.jpg');
            background-size: cover;
            border-radius: 10px;
            padding: 10px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .text-center {
            color: white;
        }

        .bi-person-plus {
            color: #005AA7;

            font-size: 1.7rem;
        }

        h3:hover {
            color: rgb(102, 0, 255);
        }
    </style>
</head>

<body>
    <?php include '../includes/header5.php'; ?>

    <div class="container">
        <div class="card shadow-lg">
            <h3 class="text-center text-transparent"><i class="bi bi-person-plus me-2"></i> Compléter mon profil</h3>

            <?php if (!empty($message)): ?>
                <div class="alert alert-warning"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <div class="row g-4 align-items-center">

                    <!-- Téléphone -->
                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" name="telephone" id="telephone" class="form-control" required>
                    </div>

                    <!-- Niveau d'étude -->
                    <div class="col-md-6">
                        <label for="niveau_etude" class="form-label">Niveau d'étude</label>
                        <select name="niveau_etude" id="niveau_etude" class="form-select" onchange="toggleAutreNiveau()" required>
                            <option value="" disabled selected>-- Choisir --</option>
                            <?php
                            $niveaux = ['BEP', 'Bac', 'Licence', 'Master', 'Doctorat', 'Autre'];
                            foreach ($niveaux as $niv) {
                                echo "<option value=\"$niv\">$niv</option>";
                            }
                            ?>
                        </select>
                        <input type="text" name="autre_niveau" id="autre_niveau" class="form-control mt-2" placeholder="Précisez" style="display: none;">
                    </div>

                    <!-- Expérience -->
                    <div class="col-md-6">
                        <label for="experience" class="form-label">Expérience</label>
                        <textarea name="experience" id="experience" class="form-control" rows="2" required></textarea>
                    </div>

                    <!-- Compétences -->
                    <div class="col-md-6">
                        <label for="competences" class="form-label">Compétences</label>
                        <input type="text" name="competences" id="competences" class="form-control" required>
                    </div>

                </div>

                <div class="mb-3 mt-4">
                    <label for="cv" class="form-label">Téléverser votre CV (PDF uniquement)</label>
                    <input type="file" name="cv" id="cv" class="form-control" accept="application/pdf" required>
                </div>

                <div class="d-flex justify-content-end mt-4 gap-3">
                    <a href="/projet_Rabya/candidats/tableau_candidat.php" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Enregistrer
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function toggleAutreNiveau() {
            document.getElementById('autre_niveau').style.display = document.getElementById('niveau_etude').value === 'Autre' ? 'block' : 'none';
        }
    </script>

</body>

</html>