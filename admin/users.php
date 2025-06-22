<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../configuration/connexionbase.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/connexion_adminstrateur.php');
    exit();
}
$candidats = $bdd->query("SELECT * FROM candidats")->fetchAll();
$recruteurs = $bdd->query("SELECT * FROM recruteurs")->fetchAll();

// Statistiques pour le graphe
$nb_candidats = count($candidats);
$nb_recruteurs = count($recruteurs);
$nb_candidats_bloques = count(array_filter($candidats, fn($c) => ($c['statut'] ?? '') === 'bloqué'));
$nb_candidats_actifs = $nb_candidats - $nb_candidats_bloques;
$nb_recruteurs_bloques = count(array_filter($recruteurs, fn($r) => ($r['statut'] ?? '') === 'bloqué'));
$nb_recruteurs_actifs = $nb_recruteurs - $nb_recruteurs_bloques;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-image: url('/projet_Rabya/igm/bg1.jpg');
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border: none;
            border-radius: 22px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.12);
            margin-top: 60px;
            transition: transform .5s cubic-bezier(.23,1.05,.32,1), box-shadow .4s;
        }
        .card:hover {
            transform: scale(1.025) translateY(-7px) rotateZ(-0.5deg);
            box-shadow: 0 12px 36px rgba(33, 80, 150, 0.13);
        }
        .icon {
            font-size: 1.6rem;
            margin-right: 10px;
        }
        .table th, .table td {
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
        .section-graph {
            background: rgba(255,255,255,0.89);
            border-radius: 22px;
            margin: 48px auto 28px auto;
            box-shadow: 0 4px 18px rgba(33, 80, 150, 0.11);
            padding: 36px 10px 32px 10px;
            max-width: 720px;
            animation: fadeInUp 1.4s cubic-bezier(.25, 1, .5, 1.1);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px);}
            to   { opacity: 1; transform: translateY(0);}
        }
        .section-graph h4 {
            font-weight: 700;
            letter-spacing: 1px;
            color: #1463c3;
            margin-bottom: 18px;
        }
        .chart-container {
            margin: 0 auto;
            width: 350px;
            max-width: 99vw;
        }
        /* Animation apparition lignes */
        [data-aos] {
            opacity: 0;
            transition-property: opacity, transform;
            &.aos-animate { opacity: 1; }
        }
        .section-graph .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
        .section-graph{
            margin-top: 80px;
        }
        .card{
            margin: 80px auto;
            max-width: 850px;
        }
        .card-header {
            font-size: 1.2rem;
            font-weight: 600;
            background: linear-gradient(135deg, #007bff, #00c6ff);
            color: white;
            border-radius: 22px 22px 0 0;
        }
    
    </style>
</head>

<body>
    <?php include __DIR__ . '/../includes/header_admin.php'; ?>
    <div class="container py-5">
        <!-- Gestion des Candidats -->
        <div class="card mb-5" data-aos="fade-right">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people-fill icon"></i>Gestion des Candidats</h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidats as $c): ?>
                                <tr data-aos="fade-up" data-aos-delay="<?= 50 + 7 * $c['id_candidat'] ?>">
                                    <td><?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?></td>
                                    <td><?= htmlspecialchars($c['email']) ?></td>
                                    <td>
                                        <span class="badge <?= ($c['statut'] ?? '') === 'bloqué' ? 'bg-danger' : 'bg-success' ?>">
                                            <?= $c['statut'] ?? 'actif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (($c['statut'] ?? '') !== 'bloqué'): ?>
                                            <a href="bloquer_users.php?type=candidat&id=<?= $c['id_candidat'] ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-person-x"></i> Bloquer
                                            </a>
                                        <?php else: ?>
                                            <a href="bloquer_users.php?type=candidat&id=<?= $c['id_candidat'] ?>&activer=1" class="btn btn-success btn-sm">
                                                <i class="bi bi-person-check"></i> Activer
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Gestion des Recruteurs -->
        <div class="card" data-aos="fade-left">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-building icon"></i>Gestion des Recruteurs</h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Entreprise</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recruteurs as $r): ?>
                                <tr data-aos="fade-up" data-aos-delay="<?= 50 + 7 * $r['id_recruteur'] ?>">
                                    <td><?= htmlspecialchars($r['nom_entreprise']) ?></td>
                                    <td><?= htmlspecialchars($r['email']) ?></td>
                                    <td>
                                        <span class="badge <?= ($r['statut'] ?? '') === 'bloqué' ? 'bg-danger' : 'bg-success' ?>">
                                            <?= $r['statut'] ?? 'actif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (($r['statut'] ?? '') !== 'bloqué'): ?>
                                            <a href="bloquer_users.php?type=recruteur&id=<?= $r['id_recruteur'] ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-person-x"></i> Bloquer
                                            </a>
                                        <?php else: ?>
                                            <a href="bloquer_users.php?type=recruteur&id=<?= $r['id_recruteur'] ?>&activer=1" class="btn btn-success btn-sm">
                                                <i class="bi bi-person-check"></i> Activer
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
                <!-- Section Graphique -->
        <div class="section-graph" data-aos="zoom-in">
            <h4 class="text-center mb-3"><i class="bi bi-bar-chart-fill"></i> Statistiques des Utilisateurs</h4>
            <div class="chart-container">
                <canvas id="usersChart" width="320" height="320"></canvas>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="tableau_administateur.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour au Tableau de Bord
            </a>
        </div>
    </div>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Animation d'apparition
        AOS.init({
            duration: 900,
            once: true
        });

        // Chart.js
        const usersChart = new Chart(document.getElementById('usersChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: [
                    'Candidats actifs', 'Candidats bloqués',
                    'Recruteurs actifs', 'Recruteurs bloqués'
                ],
                datasets: [{
                    data: [
                        <?= $nb_candidats_actifs ?>, <?= $nb_candidats_bloques ?>,
                        <?= $nb_recruteurs_actifs ?>, <?= $nb_recruteurs_bloques ?>
                    ],
                    backgroundColor: [
                        '#29b6f6', '#ef5350', // bleu, rouge
                        '#66bb6a', '#ffca28'  // vert, jaune
                    ],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 16 } } },
                    tooltip: { enabled: true }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1800,
                    easing: 'easeOutElastic'
                }
            }
        });
    </script>
</body>
</html>