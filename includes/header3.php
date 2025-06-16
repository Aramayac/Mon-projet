<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .navbar {
            background-image: url('../igm/bg15.jpg');
            background-size: cover;
            font-size: 17px;
        }

        body {
            background-image: url('../igm/bg15.jpg');
            background-size: cover;
            min-height: 100vh;
        }

        .card-header {
            background-image: url('../igm/s4.jpg');
            background-size: cover;
        }

        .navbar-nav {
            font-weight: bold;

        }

        @media (max-width: 991px) {
            .navbar-nav {
                flex-direction: column;
                gap: 15px;
                /* Meilleur espacement en mobile */
                text-align: center;
            }
        }

        .collapse {
            font-weight: bold;
            font-size: 17px;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 24px;
            color:rgb(13, 0, 255) !important;
        }
        .navbar-brand:hover {
             color: #00aaff !important;
            /* Couleur dorée au survol */
        }

         .navbar-brand span {
            color: #00aaff;
            font-weight: bold;
        }

        .nav-link {
            font-weight: bold;
            font-size: 17.5px;
        }

        .nav-link:hover {
            color: rgb(0, 102, 255) !important;
            /* Couleur dorée au survol */
        }
    </style>
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-transparent px-4 py-3 fixed-top">
    <a class="navbar-brand" href="#"><span>IK</span>Bara</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="mainNav">
        <ul class="navbar-nav d-flex gap-4">
            <li class="nav-item"><a class="nav-link text-white" href="../index.php">Accueil</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/projet_Rabya/recruteurs/offres.php">Offres d'emplois</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/projet_Rabya/authentification/connexion_candidat.php">Candidats</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/projet_Rabya/authentification/connexion_recruteur.php">Recruteurs</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Contact</a></li>
        </ul>
    </div>
</nav>
</body>
</html>