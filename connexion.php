<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['role'])) {
        $role = $_POST['role'];
        if ($role === 'candidat') {
            header('Location: /../projet_Rabya/authentification/connexion_candidat.php');
            exit();
        } elseif ($role === 'recruteur') {
            header('Location: /../projet_Rabya/authentification/connexion_recruteur.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choisissez votre type de connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('/projet_Rabya/igm/3022.jpg') no-repeat center center/cover;
            


        }
    </style>
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow rounded-4 border-0 mt-5">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4 text-primary">Connexion</h2>
                    <form method="post">
                        <div class="mb-3">
                            <label for="role" class="form-label">Je suis :</label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Choisissez --</option>
                                <option value="candidat">Candidat</option>
                                <option value="recruteur">Recruteur</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Continuer</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="/projet_Rabya/index.php" class="btn btn-secondary mt-2">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>