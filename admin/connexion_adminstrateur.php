<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';


if (isset($_SESSION['admin_id'])) {
    header('Location: /projet_Rabya/admin/tableau_administateur.php');
    exit();
}
// champs laisses vides
$sms="";
if (empty($_POST['username']) || empty($_POST['password'])) {
    $sms = " Veuillez remplir tous les champs.";
    // Afficher le message d'erreur
  
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $bdd->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: /projet_Rabya/admin/tableau_administateur.php');
        exit();
    } else {
        $error = " Identifiants invalides. Veuillez réessayer.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('/projet_Rabya/igm/bg1.jpg');
            background-size: cover;
            font-family: 'Montserrat', sans-serif;
        }

        .login-card {
            max-width: 450px;
            margin: auto;
            padding: 30px;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
        }

        .btn-primary {
            font-weight: 600;
        }

        .page-title {
            font-weight: 600;
            color: #2c3e50;
        }
    </style>
</head>


<body>
    <div class="container py-5">
        <div class="login-card">
            <h3 class="text-center mb-4 page-title">
                <i class="bi bi-person-lock"></i> Connexion Admin
            </h3>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($sms)) : ?>
                <div class="alert alert-warning text-center"><?= htmlspecialchars($sms) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="username" class="form-control" >
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" >
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-box-arrow-in-right"></i> Se connecter
                    </button>
                    <a href="connexion_adminstrateur.php" class="btn btn-outline-secondary">
                        <i class="bi bi-person-plus"></i> Créer un compte admin
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>