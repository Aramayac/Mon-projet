<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>IKBara - Recrutement en Ligne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .background {
            background-image: url('image.jpg'); /* Remplace par une vraie image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.6); /* Voile sombre */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="overlay">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top">
                <div class="container">
                    <a class="navbar-brand" href="index.php">IKBara</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link" href="#">Accueil</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Offres d'emplois</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Formations</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Entreprises</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Nos talents</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                            <li class="nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
                            <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Section Recherche -->
            <div class="container py-5">
                <h1 class="display-4">Trouvez l'emploi de vos rêves en un seul endroit</h1>
                <p class="lead">Recherchez parmi des milliers d'offres.</p>

                <div class="row g-2 justify-content-center">
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Quoi ?">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Où ?">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option>Secteur d'activité</option>
                            <option>Informatique</option>
                            <option>Finance</option>
                            <option>Marketing</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">Je me lance</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
