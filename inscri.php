<?php
include_once __DIR__ . '/configuration/connexionbase.php';
include_once __DIR__ . '/includes/header2.php';
// echo $_SERVER['REQUEST_METHOD']. "<br>";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription Express - IKBara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .page-title {
            background: rgba(120, 139, 160, 0.73);
            color: white;
            padding: 25px;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin-top: -134px;
        }

        .registration-container {
            display: flex;
            justify-content: center;
            gap: 150px;
            flex-wrap: wrap;
            margin-top: 60px;
        }

        .registration-box {
            padding: 25px;
            text-align: center;
            width: 320px;
            transition: transform 0.2s;
        }

        .registration-box:hover {
            transform: scale(1.03);
        }

        .registration-box img {
            width: 120%;
            height: auto;
            margin-bottom: 20px;

        }

        .btn-choice {
            width: 100%;
            font-size: 18px;
        }

        h3 a {
            text-decoration: none;
        }

        picture {
            padding-top: 20px;

        }
    </style>
</head>

<body>
    <!-- Titre principal -->
    <div class="page-title">
        Inscription Express
    </div>
    <!-- Conteneur principal -->
    <div class="container registration-container">
        <!-- Candidat -->
        <div class="registration-box">
            <h3><a href="../projet_Rabya/candidats/inscription_candidat.php">Candidat</a></h3>
            <img src="igm/r1.jpg" alt="Candidate">
            <div class="block-links">
                <a href="../projet_Rabya/candidats/inscription_candidat.php" class="btn btn-primary">Cliquez ici</a>
            </div>
        </div>
        <!-- Recruteur -->
        <div class="registration-box">
            <h3><a href="/projet_Rabya/recruteurs/inscription_recruteur.php">Recruteur</a></h3>

            <picture> <img src="igm/r3.jpg" alt="Recruiter"> </picture>
            <div class="block-links">
                <a href="/projet_Rabya/recruteurs/inscription_recruteur.php" class="btn btn-primary">Cliquez ici</a>
            </div>
        </div>
    </div>
    <!-- Retour -->
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php
include_once 'includes/footer.php';
?>

</html>