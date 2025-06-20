<?php

require_once __DIR__ . '/admin_auth.php';

require_once __DIR__ . '/../configuration/connexionbase.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/connexion_adminstrateur.php');
    exit();
} // Fatima arama001
$nb_candidats = $bdd->query("SELECT COUNT(*) FROM candidats")->fetchColumn(); // nombre de candidats 
$nb_recruteurs = $bdd->query("SELECT COUNT(*) FROM recruteurs")->fetchColumn(); // nombre de recruteurs
$nb_offres = $bdd->query("SELECT COUNT(*) FROM offres_emploi")->fetchColumn(); // nombre d'offres d'emploi
$nb_candidatures = $bdd->query("SELECT COUNT(*) FROM candidatures")->fetchColumn(); // nombre de candidatures
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            /* background: linear-gradient(to right, #f0f4f8, #d9e4f5); */
            background-image: url('/projet_Rabya/igm/bg1.jpg');
            background-size: cover;
            min-height: 100vh;
        }

        .dashboard-header {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            color:rgb(25, 0, 255) ;
            margin-top: 9%;
        }

        .card {
            border: none;
            border-radius: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .btn-group-custom a {
            min-width: 180px;
        }
        .mt-5{
             background: linear-gradient(to right, #f0f4f8, #d9e4f5);
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../includes/header_admin.php'; ?>
    <div class="container py-5">
        <h1 class="dashboard-header">Tableau de bord Administrateur</h1>
        <div class="row g-4 mb-5">
            <div class="col-md-3 col-sm-6">
                <div class="card bg-white text-center p-4">
                    <i class="bi bi-person-fill text-primary card-icon"></i>
                    <h5 class="fw-bold">Candidats</h5>
                    <p class="fs-3 text-dark"><?= $nb_candidats ?></p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-white text-center p-4">
                    <i class="bi bi-building-fill text-success card-icon"></i>
                    <h5 class="fw-bold">Recruteurs</h5>
                    <p class="fs-3 text-dark"><?= $nb_recruteurs ?></p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-white text-center p-4">
                    <i class="bi bi-briefcase-fill text-warning card-icon"></i>
                    <h5 class="fw-bold">Offres</h5>
                    <p class="fs-3 text-dark"><?= $nb_offres ?></p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card bg-white text-center p-4">
                    <i class="bi bi-check-circle-fill text-danger card-icon"></i>
                    <h5 class="fw-bold">Candidatures</h5>
                    <p class="fs-3 text-dark"><?= $nb_candidatures ?></p>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3 justify-content-center btn-group-custom">
            <a href="/../projet_Rabya/admin/users.php" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-people-fill me-2"></i> Gérer les utilisateurs
            </a>
            <a href="offres.php" class="btn btn-outline-success btn-lg">
                <i class="bi bi-file-earmark-text me-2"></i> Gérer les offres
            </a>
            <a href="deconnexion.php" class="btn btn-outline-danger btn-lg">
                <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
            </a>
        </div>
    </div>
    <div class="mt-5">
        <div class="mt-5 text-center">
            <h3 class="text-center mb-4">Répartition générale</h3>
            <div style="width: 320px; margin: 0 auto;">
                <canvas id="dashboardChart" width="300" height="300"></canvas>
            </div>
        </div>
        <script>
            const ctx = document.getElementById('dashboardChart').getContext('2d');
            const dashboardChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Candidats', 'Recruteurs', 'Offres', 'Candidatures'], // les étiquettes 
                    datasets: [{
                        data: [<?= $nb_candidats ?>, <?= $nb_recruteurs ?>, <?= $nb_offres ?>, <?= $nb_candidatures ?>],
                        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
        <?php include __DIR__ . '/../includes/footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>