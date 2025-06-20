<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
  header("Location: /projet_Rabya/connexion.php");
  exit();
}

$recruteur = $_SESSION['utilisateur'];
$id = $recruteur['id'];
$msg = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $nom_entreprise = $_POST['nom_entreprise'] ?? '';
  $secteur = $_POST['secteur'] ?? '';

  if ($email && $nom_entreprise && $secteur) {
    $sql = $bdd->prepare("UPDATE recruteurs SET email=?, nom_entreprise=?, secteur=? WHERE id_recruteur=?");
    $ok = $sql->execute([$email, $nom_entreprise, $secteur, $id]);
    if ($ok) {
      $_SESSION['utilisateur']['email'] = $email;
      $_SESSION['utilisateur']['nom_entreprise'] = $nom_entreprise;
      $_SESSION['utilisateur']['secteur'] = $secteur;
      header("Location: tableau_recruteur.php?msg=Informations modifiées avec succès !");
      exit();
    } else {
      $msg = "Erreur lors de la mise à jour.";
    }
  } else {
    $msg = "Tous les champs sont obligatoires.";
  }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Modifier mes informations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }

    .card {
      border: none;
      border-radius: 1rem;
      margin-top: 60px;
    }

    .card-header {
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
    }

    .form-label i {
      margin-right: 8px;
      color: #0d6efd;
    }
    .mb-3{
      font-weight: bold;
    }
  </style>
</head>
<?php include __DIR__ . '/../includes/header3.php'; ?>

<body>
  <div class="container py-5">
    <div class="col-md-8 col-lg-6 mx-auto">
      <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center fs-5 fw-bold">
          <i class="bi bi-pencil-square me-2"></i>Modifier mes informations
        </div>
        <div class="card-body p-4">
          <?php if ($msg): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              <?= htmlspecialchars($msg) ?>
            </div>
          <?php endif; ?>

          <form method="post">
            <div class="mb-3">
              <label for="email" class="form-label"><i class="bi bi-envelope-fill"></i>Email</label>
              <input type="email" class="form-control" name="email" id="email" required value="<?= htmlspecialchars($recruteur['email']) ?>">
            </div>
            <div class="mb-3">
              <label for="nom_entreprise" class="form-label"><i class="bi bi-building-fill"></i>Nom de l'entreprise</label>
              <input type="text" class="form-control" name="nom_entreprise" id="nom_entreprise" required value="<?= htmlspecialchars($recruteur['nom_entreprise']) ?>">
            </div>
            <div class="mb-3">
              <label for="secteur" class="form-label"><i class="bi bi-diagram-3-fill"></i>Secteur</label>
              <select class="form-select" name="secteur" id="secteur" required>
                <option value="" disabled>Sélectionnez un secteur</option>
                <?php include '../includes/secteurs.php'; ?>
                <?php foreach ($secteurs as $sect): ?>
                  <option value="<?= htmlspecialchars($sect) ?>" <?= ($recruteur['secteur'] === $sect) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sect) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="d-flex justify-content-between mt-4">
              <a href="/projet_Rabya/recruteurs/tableau_recruteur.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i>Annuler
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>Enregistrer
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>


</html>