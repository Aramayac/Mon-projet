<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

// Vérification connexion
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$id_candidat = $_SESSION['utilisateur']['id']; // Récupérer l'ID du candidat depuis la session
$message = "";

// Charger les infos du profil
$stmt = $bdd->prepare("SELECT * FROM profils_candidats WHERE id_candidat = ?"); // Préparer la requête pour récupérer le profil du candidat
$stmt->execute([$id_candidat]);
$profil = $stmt->fetch(); // Exécuter la requête et récupérer les données du profil

if (!$profil) { // Si le profil n'existe pas, rediriger vers la page de complétion
    header("Location: /projet_Rabya/candidats/completer_profil.php");
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telephone = trim($_POST['telephone'] ?? '');
    $niveau_etude = trim($_POST['niveau_etude'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $competences = trim($_POST['competences'] ?? ''); //

    if ($niveau_etude === 'Autre' && !empty($_POST['autre_niveau'])) { // Si l'utilisateur a sélectionné "Autre", on récupère la valeur du champ texte
        $niveau_etude = trim($_POST['autre_niveau']);
    }

    // Gestion du CV
    $cv_name = $profil['cv']; // Initialiser le nom du CV avec l'ancien nom
    if (!empty($_FILES['cv']['name'])) { // Si un nouveau CV est téléchargé
        $cv_dir = __DIR__ . '/cv/'; //
        if (!is_dir($cv_dir)) { // Vérifier si le dossier existe, sinon le créer
            mkdir($cv_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION); // Récupérer l'extension du fichier
        if (strtolower($ext) === 'pdf') { // Vérifier que le fichier est un PDF
            $cv_name = uniqid() . '_' . basename($_FILES['cv']['name']); // Générer un nom unique pour le CV
            move_uploaded_file($_FILES['cv']['tmp_name'], $cv_dir . $cv_name); // Déplacer le fichier téléchargé dans le dossier CV

            // Suppression de l'ancien CV si besoin
            if (!empty($profil['cv']) && file_exists($cv_dir . $profil['cv'])) { // Si un ancien CV existe, on le supprime
                unlink($cv_dir . $profil['cv']);
            }
        } else {
            $message = "Seuls les fichiers PDF sont acceptés pour le CV.";
        }
    }

    if (!$message) { // Si aucun message d'erreur, on procède à la mise à jour du profil
        // Mise à jour
        $stmt = $bdd->prepare("UPDATE profils_candidats 
            SET telephone=?, niveau_etude=?, experience=?, competences=?, cv=? 
            WHERE id_candidat=?");
        $stmt->execute([$telephone, $niveau_etude, $experience, $competences, $cv_name, $id_candidat]);
        header("Location: /projet_Rabya/candidats/tableau_candidat.php");
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

        .bi-pencil-square {
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
            <h3 class="text-center text-transparent"><i class="bi bi-pencil-square me-2"></i> Modifier mon profil</h3>

            <?php if ($message): ?>
                <div class="alert alert-warning"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <div class="row g-4 align-items-center">

                    <!-- Téléphone -->
                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" name="telephone" id="telephone" class="form-control" value="<?= htmlspecialchars($profil['telephone'] ?? '') ?>" required>
                    </div>

                    <!-- Niveau d'étude -->
                    <div class="col-md-6">
                        <label for="niveau_etude" class="form-label">Niveau d'étude</label>
                        <select name="niveau_etude" id="niveau_etude" class="form-select" onchange="toggleAutreNiveau()" required>
                            <option value="" disabled selected>-- Choisir --</option>
                            <?php
                            $niveaux = ['BEP', 'DEF', 'Bac', 'Licence', 'Master', 'Doctorat', 'Autre'];
                            foreach ($niveaux as $niv) {
                                $selected = ($profil['niveau_etude'] == $niv) ? 'selected' : '';
                                echo "<option value=\"$niv\" $selected>$niv</option>";
                            }
                            ?>
                        </select>
                        <input type="text" name="autre_niveau" id="autre_niveau" class="form-control mt-2" placeholder="Précisez" style="display: none;">
                    </div>

                    <!-- Expérience -->
                    <div class="col-md-6">
                        <label for="experience" class="form-label">Expérience</label>
                        <textarea name="experience" id="experience" class="form-control" rows="2" required><?= htmlspecialchars($profil['experience'] ?? '') ?></textarea>
                    </div>

                    <!-- Compétences -->
                    <div class="col-md-6">
                        <label for="competences" class="form-label">Compétences</label>
                        <input type="text" name="competences" id="competences" class="form-control" value="<?= htmlspecialchars($profil['competences'] ?? '') ?>" required>
                    </div>

                </div>

                <div class="row mt-4 align-items-center">
                    <!-- CV Actuel -->
                    <div class="col-md-6">
                        <label class="form-label">CV actuel</label><br>
                        <?php if (!empty($profil['cv'])): ?>
                            <a href="/projet_Rabya/candidats/cv/<?= htmlspecialchars($profil['cv']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-file-earmark-pdf"></i> Voir mon CV
                            </a>
                            <a href="/projet_Rabya/candidats/cv/<?= htmlspecialchars($profil['cv']) ?>"
                                class="btn btn-outline-success btn-sm btn-modern"
                                download>
                                <i class="bi bi-download"></i> Télécharger
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Aucun CV ajouté</span>
                        <?php endif; ?>
                    </div>

                    <!-- Ajouter un nouveau CV -->
                    <div class="col-md-6">
                        <label for="cv" class="form-label">Changer de CV (PDF)</label>
                        <input type="file" name="cv" id="cv" class="form-control" accept="application/pdf">
                        <small class="form-text text-muted">Facultatif</small>
                    </div>
                </div>

                <!-- Boutons -->
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