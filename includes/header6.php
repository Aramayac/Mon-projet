<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>IKBara - Recrutement en Ligne</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Montserrat', sans-serif;
            background-image: url('/projet_Rabya/igm/bg2.jpg');
            background-size: cover;
            background-position: center;
        }

        .hero-background {
            background: url('/projet_Rabya/igm/bg5.jpg') no-repeat center center/cover;
            height: 100vh;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .search-bar {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 1100px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            height: 45px;
        }

        .btn-search {
            height: 45px;
            border-radius: 8px;
        }

        .navbar .btn-job {
            background: #007bff;
            color: white;
            font-weight: bold;
            padding: 6px 14px;
            border-radius: 10px;
        }

        .navbar .btn-job:hover {
            background: #0056b3;
        }

        .social-icons a {
            color: white;
            margin-right: 10px;
        }

        .navbar-brand span {
            color: #00aaff;
            font-weight: bold;
        }

        .navbarp {
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            /* Trait fin blanc */
            padding-top: 15px;
            padding-bottom: 5px;


        }

        .link-hover:hover {
            color: #007bff !important;
            /*  bleu au survol */
        }

        .navbar-nav {
            display: flex;
            gap: 30px;
            /* Espacement personnalisé */
        }

        .nav-link {
            color: white !important;
            /* Couleur du texte */
            font-weight: bold;
            /* Gras */
            transition: color 0.3s;
            /* Transition douce */
        }

        .nav-link:hover {
            color: #007bff !important;
            /*  bleu au survol */
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <div class="hero-background">
        <div class="overlay"></div>
        <!-- Barre supérieure -->
        <div class="navbarp">
            <div class="navbar d-flex justify-content-between align-items-center px-4 py-2 bg-transparent">
                <div class="d-none d-lg-block">
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <div class="fw-bold">
                    <a href="/projet_Rabya/connexion.php" class="text-white text-decoration-none mx-2 link-hover">Connexion</a> |
                    <a href="/projet_Rabya/inscri.php" class="text-white text-decoration-none mx-2 link-hover">Inscription</a>
                </div>
            </div>
        </div>
        <!-- Navbar principale -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-transparent px-4 py-3">
            <a class="navbar-brand" href="#"><span>IK</span>Bara</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="mainNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/projet_Rabya/index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="/projet_Rabya/recruteurs/offres.php">Offres d'emplois</a></li>
                    <li class="nav-item"><a class="nav-link" href="/projet_Rabya/authentification/connexion_candidat.php">Candidats</a></li>
                    <li class="nav-item"><a class="nav-link" href="/projet_Rabya/authentification/connexion_recruteur.php">Recruteurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="/../projet_Rabya/contact.php/">Contact</a></li>
                </ul>
            </div>
            <?php if (isset($_SESSION['utilisateur']) && $_SESSION['role'] === 'recruteur'): ?>
                <a href="ajouter_offre.php" class="btn btn-job ms-2">
                    <i class="fas fa-plus me-1"></i> Créer un Job
                </a>
            <?php else: ?>
                <a href="connexion_recruteur.php?redirect=ajouter_offre.php" class="btn btn-job ms-2">
                    <i class="fas fa-plus me-1"></i> Créer un Job
                </a>
            <?php endif; ?>
        </nav>
        </nav>
        <!-- Hero Content -->
        <div class="hero-content container">
            <h1 class="fw-bold display-6 mt-4">Les informations sur l'offre</h1>


        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>