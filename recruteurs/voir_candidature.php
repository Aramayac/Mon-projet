<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$id_offre = $_GET['id_offre'] ?? null;
if (!$id_offre || !is_numeric($id_offre)) {
    header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?message=erreur");
    exit();
}

$stmt = $bdd->prepare("SELECT * FROM offres_emploi WHERE id_offre = ? AND id_recruteur = ?");
$stmt->execute([$id_offre, $_SESSION['utilisateur']['id']]);
$offre = $stmt->fetch();
if (!$offre) {
    header("Location: /projet_Rabya/recruteurs/tableau_recruteur.php?message=erreur");
    exit();
}

$stmt = $bdd->prepare("SELECT c.id_candidature, c.statut, can.nom, can.prenom, can.email
                       FROM candidatures c
                       JOIN candidats can ON c.id_candidat = can.id_candidat
                       WHERE c.id_offre = ?");
$stmt->execute([$id_offre]);
$candidatures = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Candidatures - <?= htmlspecialchars($offre['titre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f7f9fc;
            font-family: Arial, sans-serif;
            
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 80px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn-sm {
            padding: 0.25rem 0.6rem;
        }

        h3.title {
            font-weight: 600;
            color: #2c3e50;
        }
       
    </style>
</head>

<body>
    <?php include '../includes/header3.php'; ?>

    <div class="container py-5">
        <div class="card shadow-lg border-0">
            <div class="card-body">
                <h3 class="title mb-4">
                    <i class="bi bi-file-person-fill text-primary"></i>
                    Candidatures pour : <span class="text-dark"><?= htmlspecialchars($offre['titre']) ?></span>
                </h3>

                <?php if ($candidatures): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">👤 Candidat</th>
                                    <th scope="col">📧 Email</th>
                                    <th scope="col">📌 Statut</th>
                                    <th scope="col">⚙️ Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidatures as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></td>
                                        <td><?= htmlspecialchars($c['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= 
                                                $c['statut'] === 'acceptée' ? 'success' :
                                                ($c['statut'] === 'refusée' ? 'danger' : 'secondary')
                                            ?>">
                                                <?= ucfirst(str_replace('_', ' ', $c['statut'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($c['statut'] === 'en_cours'): ?>
                                                <form method="post" action="/projet_Rabya/candidats/traiter_candidature.php" class="d-inline">
                                                    <input type="hidden" name="id_candidature" value="<?= $c['id_candidature'] ?>">
                                                    <input type="hidden" name="id_offre" value="<?= $id_offre ?>">
                                                    <button type="submit" name="action" value="accepter" class="btn btn-success btn-sm">
                                                        <i class="bi bi-check-circle"></i> Accepter
                                                    </button>
                                                </form>
                                                <form method="post" action="/projet_Rabya/candidats/traiter_candidature.php" class="d-inline">
                                                    <input type="hidden" name="id_candidature" value="<?= $c['id_candidature'] ?>">
                                                    <input type="hidden" name="id_offre" value="<?= $id_offre ?>">
                                                    <button type="submit" name="action" value="refuser" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-x-circle"></i> Refuser
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted fst-italic">Action terminée</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucune candidature pour cette offre pour le moment.
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="tableau_recruteur.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
