<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

// Vérification que l'utilisateur est connecté et est recruteur
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

// Récupération des filtres avancés GET
$nom  = $_GET['nom'] ?? '';
$prenom = $_GET['prenom'] ?? '';
$email = $_GET['email'] ?? '';
$telephone = $_GET['telephone'] ?? '';
$niveau_etude = $_GET['niveau_etude'] ?? '';
$experience_min = $_GET['experience_min'] ?? '';
$experience_max = $_GET['experience_max'] ?? '';
$competence = $_GET['competence'] ?? '';
$date_completer_min = $_GET['date_completer_min'] ?? '';
$date_completer_max = $_GET['date_completer_max'] ?? '';
$has_cv = isset($_GET['has_cv']) ? true : false;

// Construction dynamique de la requête
$where = [];
$params = [];

if ($nom )              { $where[] = "c.nom LIKE ?"; $params[] = "%$nom%"; }
if ($prenom )            { $where[] = "c.prenom LIKE ?"; $params[] = "%$prenom%"; }
if ($email)            { $where[] = "c.email LIKE ?"; $params[] = "%$email%"; }
if ($telephone)        { $where[] = "p.telephone LIKE ?"; $params[] = "%$telephone%"; }
if ($niveau_etude)     { $where[] = "p.niveau_etude = ?"; $params[] = $niveau_etude; }
if ($competence)       { $where[] = "p.competences LIKE ?"; $params[] = "%$competence%"; }
if ($experience_min !== '') { $where[] = "CAST(p.experience AS UNSIGNED) >= ?"; $params[] = $experience_min; }
if ($experience_max !== '') { $where[] = "CAST(p.experience AS UNSIGNED) <= ?"; $params[] = $experience_max; }
if ($date_completer_min) { $where[] = "p.date_completer >= ?"; $params[] = $date_completer_min . " 00:00:00"; }
if ($date_completer_max) { $where[] = "p.date_completer <= ?"; $params[] = $date_completer_max . " 23:59:59"; }
if ($has_cv)           { $where[] = "p.cv IS NOT NULL AND p.cv <> ''"; }

$sql = "SELECT c.id_candidat, c.nom,c.prenom, c.email, p.telephone, p.niveau_etude, p.experience, p.competences, p.cv, p.date_completer
        FROM candidats c
        LEFT JOIN profils_candidats p ON c.id_candidat = p.id_candidat";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY p.date_completer DESC, c.nom ASC";

$stmt = $bdd->prepare($sql);
$stmt->execute($params);
$candidats = $stmt->fetchAll();

// Pour la liste des niveaux d'étude distincts (pour le select)
$niveauList = $bdd->query("SELECT DISTINCT niveau_etude FROM profils_candidats WHERE niveau_etude IS NOT NULL AND niveau_etude <> '' ORDER BY niveau_etude ASC")->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche avancée de candidats</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & icônes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Flatpickr pour le calendrier -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #e0e7ef 0%, #f8fafd 100%);
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .search-card {
            background: #fff;
            box-shadow: 0 4px 32px 4px #00cfff22, 0 2px 24px 0 #c6eaff60;
            border-radius: 18px;
            border: none;
            padding: 32px 28px 28px 28px;
        }
        .table thead th {
            background: #e7f4fb;
        }
        .btn-cv {
            font-size: 1em;
            border-radius: 24px;
        }
        .filter-label {
            font-weight: 600;
            color: #0099cc;
        }
        .advanced-toggle {
            cursor: pointer;
            color: #1976d2;
            font-weight: 500;
            text-decoration: underline;
            margin-left: 6px;
        }
        .flatpickr-input {
            background: #f5faff;
        }
        @media (max-width: 991px) {
            .search-card { padding: 18px 7px; }
       
    }
     .card{
            max-width: 1500px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/header_recruteurs.php'; ?>
    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card search-card mb-4">
                    <h2 class="mb-3 text-center" style="color:#00cfff;letter-spacing:1.1px;">
                        <i class="bi bi-search me-2"></i>Recherche avancée de candidats
                    </h2>
                    <form method="get" class="row g-3 align-items-end mb-4">
                        <div class="col-md-2">
                            <label for="nom" class="form-label filter-label">Nom</label>
                            <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>">
                        </div>
                         <div class="col-md-2">
                            <label for="prenom" class="form-label filter-label">Prenom</label>
                            <input type="text" name="prenom" id="nom" class="form-control" value="<?= htmlspecialchars($prenom) ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="email" class="form-label filter-label">Email</label>
                            <input type="text" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="telephone" class="form-label filter-label">Téléphone</label>
                            <input type="text" name="telephone" id="telephone" class="form-control" value="<?= htmlspecialchars($telephone) ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="niveau_etude" class="form-label filter-label">Niveau d'étude</label>
                            <select name="niveau_etude" id="niveau_etude" class="form-select">
                                <option value="">Tous</option>
                                <?php foreach ($niveauList as $niv): ?>
                                    <option value="<?= htmlspecialchars($niv) ?>" <?= $niveau_etude == $niv ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($niv) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="competence" class="form-label filter-label">Compétence</label>
                            <input type="text" name="competence" id="competence" class="form-control" value="<?= htmlspecialchars($competence) ?>" placeholder="Ex: PHP, Design...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <span class="advanced-toggle" onclick="document.getElementById('advanced-filters').classList.toggle('d-none')">
                                <i class="bi bi-funnel"></i> Options avancées
                            </span>
                        </div>
                        <div class="col-12 d-none" id="advanced-filters">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label for="experience_min" class="form-label filter-label">Exp. min (années)</label>
                                    <input type="number" min="0" name="experience_min" id="experience_min" class="form-control" value="<?= htmlspecialchars($experience_min) ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="experience_max" class="form-label filter-label">Exp. max (années)</label>
                                    <input type="number" min="0" name="experience_max" id="experience_max" class="form-control" value="<?= htmlspecialchars($experience_max) ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="date_completer_min" class="form-label filter-label">Profil complété après</label>
                                    <input type="text" name="date_completer_min" id="date_completer_min" class="form-control flatpickr" value="<?= htmlspecialchars($date_completer_min) ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="date_completer_max" class="form-label filter-label">Profil complété avant</label>
                                    <input type="text" name="date_completer_max" id="date_completer_max" class="form-control flatpickr" value="<?= htmlspecialchars($date_completer_max) ?>">
                                </div>
                                <div class="col-md-2 d-flex align-items-center">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="has_cv" id="has_cv" class="form-check-input" <?= $has_cv ? 'checked' : '' ?>>
                                        <label for="has_cv" class="form-check-label filter-label">Avec CV</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-info btn-lg px-4" style="border-radius:30px;">
                                <i class="bi bi-search"></i> Rechercher
                            </button>
                            <a href="rechercher_candidat.php" class="btn btn-outline-secondary btn-lg px-4 ms-2" style="border-radius:30px;">
                                <i class="bi bi-x-circle"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                    <?php if ($candidats && count($candidats) > 0): ?>
                        <div class="table-responsive mt-4">
                            <table class="table align-middle table-hover table-bordered shadow-sm">
                                <thead class="table-primary">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prenom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Niveau d'étude</th>
                                    <th>Expérience</th>
                                    <th>Compétences</th>
                                    <th>CV</th>
                                    <th>Complété le</th>
                                    <th>Profil</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($candidats as $cand): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cand['nom']) ?></td>
                                        <td><?= htmlspecialchars($cand['prenom'] )?></td>
                                        <td><?= htmlspecialchars($cand['email']) ?></td>
                                        <td><?= htmlspecialchars($cand['telephone'] ?? '—') ?></td>
                                        <td><?= htmlspecialchars($cand['niveau_etude'] ?? '—') ?></td>
                                        <td><?= htmlspecialchars($cand['experience'] ?? '—') ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= nl2br(htmlspecialchars($cand['competences'] ?? '—')) ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($cand['cv'])): ?>
                                                <a href="/../projet_Rabya/candidats/cv/<?= htmlspecialchars($cand['cv']) ?>" target="_blank" class="btn btn-outline-primary btn-cv btn-sm">
                                                    <i class="bi bi-file-earmark-arrow-down"></i> CV
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Non fourni</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $cand['date_completer'] ? date('d/m/Y', strtotime($cand['date_completer'])) : '–' ?>
                                        </td>
                                        <td>
                                            <a href="profil_candidat.php?id_candidat=<?= $cand['id_candidat'] ?>" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-person"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning text-center mb-0 mt-4">Aucun candidat trouvé avec ces critères.</div>
                    <?php endif; ?>
                    <div class="text-end mt-4">
                        <a href="/projet_Rabya/recruteurs/tableau_recruteur.php" class="btn btn-secondary btn-lg" style="border-radius:30px;">
                            <i class="bi bi-arrow-left"></i> Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Flatpickr calendrier -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            allowInput: true
        });
    </script>
</body>
</html>