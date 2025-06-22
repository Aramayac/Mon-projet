<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../configuration/connexionbase.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/connexion_adminstrateur.php');
    exit();
}
$offres = $bdd->query("SELECT o.*, r.nom_entreprise FROM offres_emploi o JOIN recruteurs r ON o.id_recruteur = r.id_recruteur")->fetchAll();

$total = count($offres);
$nb_publiees = count(array_filter($offres, fn($o) => ($o['statut'] ?? '') === 'publiée'));
$nb_non_pub = $total - $nb_publiees;

$offres_par_entreprise = [];
foreach ($offres as $o) {
    $offres_par_entreprise[$o['nom_entreprise']] = ($offres_par_entreprise[$o['nom_entreprise']] ?? 0) + 1;
}
arsort($offres_par_entreprise);
$entreprises_labels = json_encode(array_keys($offres_par_entreprise));
$entreprises_data = json_encode(array_values($offres_par_entreprise));
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Offres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-image: url('/projet_Rabya/igm/bg1.jpg');
            background-size: cover;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .section-graph {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 22px;
            margin: 45px auto 35px auto;
            box-shadow: 0 4px 18px rgba(33, 80, 150, 0.13);
            padding: 16px 6px 14px 6px;
            max-width: 450px;
            animation: fadeInDown 1.2s cubic-bezier(.25, 1, .5, 1.1);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-35px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-graph h4 {
            font-weight: 700;
            letter-spacing: 1px;
            color: #1463c3;
            margin-bottom: 18px;
        }

        .chart-container {
            margin: 0 auto;
            width: 220px;
            max-width: 100vw;
        }

        .card-offres {
            border: none;
            border-radius: 22px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.13);
            margin-top: 50px;
            animation: fadeInUp 1.2s cubic-bezier(.25, 1, .5, 1.1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon {
            font-size: 1.7rem;
            margin-right: 10px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-sm {
            font-weight: bold;
            border-radius: 6px;
            transition: background .25s, color .25s, transform .18s;
        }

        .btn-sm:active {
            transform: scale(.96);
        }

        .mb-4 {
            margin-top: 60px;
        }

        .table thead {
            animation: fadeIn .9s cubic-bezier(.22, 1, .36, 1.11);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .table tbody tr {
            transition: background .2s;
        }

        .table tbody tr:hover {
            background: #f3f7fa !important;
        }

        .btn-outline-primary,
        .btn-outline-primary:focus {
            font-weight: 600;
            letter-spacing: 1px;
            transition: background .16s, color .16s;
        }

        .btn-outline-primary:hover {
            background: #3a86ff !important;
            color: #fff !important;
            border-color: #3a86ff !important;
        }

        @media (max-width: 700px) {
            .chart-container {
                width: 98vw;
            }

            .section-graph {
                padding: 20px 3px 20px 3px;
            }
        }
        .card-offres{
            margin-top: 70px;
        }
        .card-offres h3 {
            font-weight: 900;
            color: #0d6efd;
            margin-bottom: 20px;
        }
      
    </style>
</head>

<body>
    <?php include __DIR__ . '/../includes/header_admin.php'; ?>
    <div class="container py-5">
        <div class="card-offres mb-5" data-aos="fade-up">
            <h3 class="mb-4 text-center"><i class="bi bi-briefcase-fill text-primary icon"></i> Gestion des Offres d'emploi</h3>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Titre</th>
                            <th>Entreprise</th>
                            <th>Lieu</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($offres as $o): ?>
                            <tr>
                                <td><?= htmlspecialchars($o['titre']) ?></td>
                                <td><?= htmlspecialchars($o['nom_entreprise']) ?></td>
                                <td><?= htmlspecialchars($o['lieu']) ?></td>
                                <td>
                                    <?php
                                    if ($o['statut'] === 'en_attente') {
                                        echo '<span class="badge bg-warning text-dark">En attente</span>';
                                    } elseif ($o['statut'] === 'masquée') {
                                        echo '<span class="badge bg-danger">Masquée</span>';
                                    } elseif ($o['statut'] === 'publiée') {
                                        echo '<span class="badge bg-success">Publiée</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">Inconnu</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if (($o['statut'] ?? '') === 'en_attente' || ($o['statut'] ?? '') === 'masquée'): ?>
                                        <a href="/projet_Rabya/admin/bloquer_offres.php?id=<?= $o['id_offre'] ?>&action=publier" class="btn btn-success btn-sm">
                                            <i class="bi bi-eye-fill me-1"></i>Publier
                                        </a>
                                    <?php endif; ?>
                                    <?php if (($o['statut'] ?? '') === 'publiée'): ?>
                                        <a href="/projet_Rabya/admin/bloquer_offres.php?id=<?= $o['id_offre'] ?>&action=masquer" class="btn btn-warning btn-sm">
                                            <i class="bi bi-eye-slash-fill me-1"></i>Masquer
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Graphe Statistique Offres -->
        <div class="section-graph" data-aos="fade-down">
            <h4 class="text-center mb-3">Statistiques des Offres</h4>
            <div class="chart-container">
                <canvas id="offresChart" width="270" height="140"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="entreprisesChart" width="390" height="250"></canvas>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="/projet_Rabya/admin/tableau_administateur.php" class="btn btn-outline-primary text-decoration-bold">
                <i class="bi bi-arrow-left-circle me-1"></i>Retour au tableau de bord
            </a>
        </div>
    </div>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Animation d'apparition
        AOS.init({
            duration: 1000,
            once: true
        });

        // Graphe 1 : Offres publiées vs non publiées (en attente + masquées)
        const ctx1 = document.getElementById('offresChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Offres Publiées', 'Offres non Publiées'],
                datasets: [{
                    data: [<?= $nb_publiees ?>, <?= $nb_non_pub ?>],
                    backgroundColor: ['#42a5f5', '#ef5350'],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 15
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1800,
                    easing: 'easeOutElastic'
                }
            }
        });

        // Graphe 2 : Offres par entreprise (Barres horizontales)
        const ctx2 = document.getElementById('entreprisesChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?= $entreprises_labels ?>,
                datasets: [{
                    label: 'Nb Offres',
                    data: <?= $entreprises_data ?>,
                    backgroundColor: '#66bb6a',
                    borderRadius: 7,
                    maxBarThickness: 32
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>