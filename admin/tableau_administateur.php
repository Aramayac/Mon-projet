<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/connexion_adminstrateur.php');
    exit();
}

$nb_candidats = $bdd->query("SELECT COUNT(*) FROM candidats")->fetchColumn();
$nb_recruteurs = $bdd->query("SELECT COUNT(*) FROM recruteurs")->fetchColumn();
$nb_offres = $bdd->query("SELECT COUNT(*) FROM offres_emploi")->fetchColumn();
$nb_candidatures = $bdd->query("SELECT COUNT(*) FROM candidatures")->fetchColumn();
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
      background: url('/projet_Rabya/igm/bg1.jpg') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .dashboard-header {
      font-size: 3rem;
      font-weight: bold;
      text-align: center;
      margin-top: 80px;
      color: #0d6efd;
    }
    /* --- Carousel Circular --- */
    .carousel-circle-container {
      perspective: 1200px;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 400px;
      margin-top: 60px;
      margin-bottom: 30px;
      position: relative;
      user-select: none;
    }
    .carousel-circle {
      width: 340px;
      height: 340px;
      position: relative;
      transform-style: preserve-3d;
      transition: transform 1s cubic-bezier(.75,0,.25,1);
    }
    .carousel-card {
      background: rgba(255,255,255,0.92);
      border-radius: 1.2rem;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
      width: 180px;
      height: 220px;
      position: absolute;
      left: 50%; top: 50%;
      transform-style: preserve-3d;
      margin: -110px 0 0 -90px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: box-shadow 0.3s, background 0.3s;
      border: none;
      text-align: center;
    }
    .carousel-card.active {
      background: linear-gradient(135deg, #e3f2fd 55%, #d1eaff 100%);
      box-shadow: 0 16px 40px 0 #0d6efd44;
      z-index: 2;
      scale: 1.09;
    }
    .carousel-card .card-icon {
      font-size: 3.2rem;
      margin-bottom: 1rem;
    }
    .carousel-card .fw-bold {
      font-size: 1.3rem;
      margin-bottom: 0.5rem;
    }
    .carousel-card .fs-3 {
      font-size: 2.2rem;
      font-weight: bold;
      color: #0d6efd;
    }
    .carousel-nav-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      font-size: 2.4rem;
      color: #0d6efd;
      background: rgba(255,255,255,0.85);
      border: none;
      border-radius: 50%;
      width: 48px; height: 48px;
      box-shadow: 0 4px 16px #0d6efd33;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10;
      transition: background 0.2s;
    }
    .carousel-nav-btn:hover {
      background: #0d6efd;
      color: #fff;
    }
    .carousel-nav-btn.left { left: 35px; }
    .carousel-nav-btn.right { right: 35px; }
    @media (max-width: 800px) {
      .carousel-circle-container { height: 350px;}
      .carousel-circle { width: 250px; height: 250px;}
      .carousel-card { width: 130px; height: 160px; margin: -80px 0 0 -65px;}
    }
    @media (max-width: 500px) {
      .carousel-circle-container { height: 270px;}
      .carousel-circle { width: 170px; height: 170px;}
      .carousel-card { width: 80px; height: 100px; margin: -50px 0 0 -40px;}
      .carousel-card .card-icon { font-size: 1.5rem;}
      .carousel-card .fw-bold { font-size: 1rem;}
      .carousel-card .fs-3 { font-size: 1.15rem;}
      .carousel-nav-btn { font-size: 1.5rem; width: 32px; height: 32px;}
    }
    /* Fin carousel */
    .btn-group-custom a {
      min-width: 200px;
    }
    #chart-container {
      width: 320px;
      margin: 0 auto;
    }
    .section-bg {
      background: rgba(255, 255, 255, 0.85);
      border-radius: 15px;
      padding: 30px;
      margin-top: 30px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>
  <?php include __DIR__ . '/../includes/header_admin.php'; ?>

  <div class="container py-5">
    <h1 class="dashboard-header">Tableau de bord Administrateur</h1>

    <!-- Carousel circulaire -->
    <div class="carousel-circle-container">
      <button class="carousel-nav-btn left" onclick="rotateCarousel(-1)" aria-label="Précédent"><i class="bi bi-chevron-left"></i></button>
      <div class="carousel-circle" id="carouselCircle">
        <!-- Les cartes seront injectées ici -->
      </div>
      <button class="carousel-nav-btn right" onclick="rotateCarousel(1)" aria-label="Suivant"><i class="bi bi-chevron-right"></i></button>
    </div>

    <div class="d-flex flex-wrap gap-3 justify-content-center btn-group-custom mt-5">
      <a href="/projet_Rabya/admin/users.php" class="btn btn-outline-primary btn-lg">
        <i class="bi bi-people-fill me-2"></i> Gérer les utilisateurs
      </a>
      <a href="offres.php" class="btn btn-outline-success btn-lg">
        <i class="bi bi-file-earmark-text me-2"></i> Gérer les offres
      </a>
      <a href="deconnexion.php" class="btn btn-outline-danger btn-lg">
        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
      </a>
    </div>

    <div class="section-bg mt-5 text-center">
      <h3 class="mb-4">Répartition Générale</h3>
      <div id="chart-container">
        <canvas id="dashboardChart" width="300" height="300"></canvas>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../includes/footer.php'; ?>

  <script>
    // --------- Carousel Circulaire ---------
    const cardsData = [
      {
        icon: 'bi-person-fill text-primary',
        title: 'Candidats',
        value: <?= $nb_candidats ?>,
      },
      {
        icon: 'bi-building-fill text-success',
        title: 'Recruteurs',
        value: <?= $nb_recruteurs ?>,
      },
      {
        icon: 'bi-briefcase-fill text-warning',
        title: 'Offres',
        value: <?= $nb_offres ?>,
      },
      {
        icon: 'bi-check-circle-fill text-danger',
        title: 'Candidatures',
        value: <?= $nb_candidatures ?>,
      }
    ];

    const carousel = document.getElementById('carouselCircle');
    let activeIndex = 0;
    const cardCount = cardsData.length;
    const angle = 360 / cardCount;

    // Génère les cartes
    function renderCards() {
      carousel.innerHTML = '';
      for(let i = 0; i < cardsData.length; i++) {
        const card = document.createElement('div');
        card.className = 'carousel-card' + (i === activeIndex ? ' active' : '');
        card.style.transform = `rotateY(${i * angle}deg) translateZ(150px)`;
        card.innerHTML = `
          <i class="bi ${cardsData[i].icon} card-icon"></i>
          <h5 class="fw-bold">${cardsData[i].title}</h5>
          <p class="fs-3 text-dark">${cardsData[i].value}</p>
        `;
        carousel.appendChild(card);
      }
      carousel.style.transform = `rotateY(${-activeIndex * angle}deg)`;
    }
    renderCards();

    // Navigation
    function rotateCarousel(direction) {
      activeIndex = (activeIndex + direction + cardCount) % cardCount;
      renderCards();
    }

    // Autoplay (optionnel)
    setInterval(() => rotateCarousel(1), 4000);

    // --------- Chart.js ---------
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    const dashboardChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Candidats', 'Recruteurs', 'Offres', 'Candidatures'],
        datasets: [{
          data: [<?= $nb_candidats ?>, <?= $nb_recruteurs ?>, <?= $nb_offres ?>, <?= $nb_candidatures ?>],
          backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545'],
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' }
        }
      }
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>