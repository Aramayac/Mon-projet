<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';
$errors = [];

$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($email)) $errors['email'] = "L'email est requis.";
    if (empty($mot_de_passe)) $errors['mot_de_passe'] = "Le mot de passe est requis.";

    if (empty($errors)) {
        $stmt = $bdd->prepare("SELECT * FROM candidats WHERE email = ?");
        $stmt->execute([$email]);
        $candidat = $stmt->fetch();

        if ($candidat && password_verify($mot_de_passe, $candidat['mot_de_passe'])) {
            if (($candidat['statut'] ?? 'actif') === 'bloqué') {
                $errors['general'] = "Votre compte a été bloqué par un administrateur. Contactez le support.";
            } else {
                $_SESSION['utilisateur'] = [
                    'id' => $candidat['id_candidat'],
                    'nom' => $candidat['nom'],
                    'prenom' => $candidat['prenom'],
                    'email' => $candidat['email'],
                    'statut' => $candidat['statut'] ?? 'actif'
                ];
                $_SESSION['role'] = 'candidat';
                $_SESSION['id_candidat'] = $candidat['id_candidat'];
                // Redirection intelligente
                if (!empty($redirect)) {
                    header('Location: ' . $redirect);
                } else {
                    header('Location: /projet_Rabya/candidats/tableau_candidat.php');
                }
                exit();
            }
        } else {
            $errors['general'] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion Candidat - IKBara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 80px;
        }
        .card {
            max-width: 600px;
            margin: auto;
            border-radius: 1.5rem;
        }
    </style>
</head>

<body class="bg-light">
    <?php
    include '../includes/header3.php';
    ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg rounded-4">
                    <div class="card-header bg-transparent text-white text-center fs-4 fw-bold">
                        <i class="bi bi-lock-fill me-2"></i> Connexion Candidat
                    </div>
                    <div class="card-body px-5 py-4">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="">
                            <?php if (!empty($redirect)): ?>
                                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
                            <?php endif; ?>
                            <div class="mb-3">
                                <label class="form-label">Adresse Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" name="mot_de_passe">
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="/projet_Rabya/candidats/inscription_candidat.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                                </button>
                            </div>
                            <div class="text-center mt-3">
                                <p>Vous n'avez pas de compte ? <a href="/projet_Rabya/candidats/inscription_candidat.php" class="text-decoration-none text-primary">
                                        <i class="bi bi-person-plus"></i>Inscrivez-vous
                                    </a>
                                </p>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <a href="/projet_Rabya/candidats/mot_de_passe_oublie.php" class="text-decoration-none">Mot de passe oublié ?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php
    include '../includes/footer.php';
    ?>

</body>

</html>