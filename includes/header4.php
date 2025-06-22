<head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .navbar {
            background-image: url('../igm/s4.jpg');
            background-size: cover;
        }

        body {

            background-image: url('/projet_Rabya/igm/ms1.jpg');
            background-size: contain;
            background-repeat: repeat;
            /* Répétition sur toute la page */
            background-position: center;

        }

        .card-header {
            background-image: url('../igm/s4.jpg');
            background-size: cover;
        }

        .navbar-nav {
            font-weight: bold;
            color: white;
        }

        @media (max-width: 991px) {
            .navbar-nav {
                flex-direction: column;
                gap: 15px;
                /* Meilleur espacement en mobile */
                text-align: center;
            }
        }
    </style>
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-transparent px-4 py-3 ">
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
            <li class="nav-item"><a class="nav-link text-white" href="/../projet_Rabya/contact.php/">Contact</a></li>
        </ul>
    </div>
</nav>