
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
         body {
            background-image: url('/projet_Rabya/igm/bg1.jpg');
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 80px; /* Pour laisser de l'espace sous la navbar fixe */
        }
        .navbar {
            background-image: url('/projet_Rabya/igm/s4.jpg');
        }
        .navbar-brand {
            color: #fff !important;
            font-weight: bold;
        }
        .content-section {
            background-color: rgba(237, 230, 230, 0.92);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }
        h3 i {
            color: #003366;
        }
        .footer {
            background-color: #003366;
            color: white;
        }
        
    </style>
</head>
<body>

   <nav class="navbar navbar-expand-lg shadow fixed-top">

    <div class="container-fluid">
        <a class="navbar-brand" href="#">🎓 Recrutement</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="/projet_Rabya/index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#profil">Mon profil</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#offres">Offres</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#candidatures">Candidatures</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>
</body>
</html>