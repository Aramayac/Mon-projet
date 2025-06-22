<?php
session_start();
require_once __DIR__ . '/../configuration/connexionbase.php';

if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'recruteur') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}


$id_candidat = $_GET['id_candidat'] ?? null;//
if (!$id_candidat || !is_numeric($id_candidat)) {
    header("Location: tableau_recruteur.php?message=erreur");
    exit();
}

// Récupérer toutes les infos du candidat (profil + table candidat)
$stmt = $bdd->prepare("
    SELECT c.nom, c.prenom, c.email, c.avatar, p.competences,p.experience, p.cv
    FROM candidats c
    LEFT JOIN profils_candidats p ON c.id_candidat = p.id_candidat
    WHERE c.id_candidat = ?
");
$stmt->execute([$id_candidat]);
$profil = $stmt->fetch();

if (!$profil) {
    header("Location: tableau_recruteur.php?message=erreur");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil du candidat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .profile-container {
            max-width: 520px;
            margin: 60px auto;
        }
        .profile-card {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 8px 32px 0 rgba(0,51,102,.13);
            padding: 2.5rem 2.2rem;
            position: relative;
            text-align: center;
        }
        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.2rem auto;
            box-shadow: 0 2px 12px rgba(0,0,0,.13);
            border: 4px solid #f5f7fa;
            background: #f2f4f8;
        }
        .candidate-name {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a4173;
            margin-bottom: .25rem;
        }
        .candidate-email {
            color: #3a81c3;
            font-size: 1.05rem;
            margin-bottom: 1.1rem;
        }
        .profile-section {
            text-align: left;
            margin: 1.5rem 0 .8rem 0;
        }
        .profile-section h5 {
            font-size: 1.11rem;
            color: #003366;
            font-weight: 600;
            margin-bottom: .4rem;
        }
        .competence-badge {
            background: #e9f3fd;
            color: #1853a3;
            padding: .4em .85em;
            border-radius: 15px;
            margin-right: .42em;
            font-size: 1.02rem;
            margin-bottom: .5em;
            display: inline-block;
        }
        .profile-cv-link {
            margin-top: 1.1rem;
            display: inline-block;
        }
        .profile-cv-link .btn {
            border-radius: 22px;
            font-weight: 500;
        }
        .profile-cv-link .btn:hover {
            background: #003366;
            color: #fff;
        }
        .back-link {
            margin-top: 2.2rem;
            display: inline-block;
        }
        .back-link .btn {
            border-radius: 22px;
        }
        .profile-card::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
            background: transparent url('/projet_Rabya/igm/bg.jpg') no-repeat center center;
            background-image: url('/projet_Rabya/igm/image.png');
            background-size: cover;


            border-radius: 50%;
            box-shadow: 0 4px 16px rgba(0,0,0,.1);
        }
        .profile-container{
            margin-top: 60px;
        }
        .profile-card{
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header3.php'; ?>
    <div class="profile-container">
        <div class="profile-card">
            <img src="/projet_Rabya/candidats/avatars/<?= htmlspecialchars($profil['avatar'] ?? 'avatar_default.png') ?>"
                 alt="Avatar" class="profile-avatar">

            <div class="candidate-name">
                <?= htmlspecialchars($profil['prenom'] . ' ' . $profil['nom']) ?>
            </div>
            <div class="candidate-email">
                <a href="mailto:<?= htmlspecialchars($profil['email']) ?>" class="text-decoration-none">
                    <?= htmlspecialchars($profil['email']) ?>
                </a>
            </div>

            <div class="profile-section">
                <h5><i class="bi bi-award"></i> Compétences</h5>
                <?php if ($profil['competences']) :
                    foreach (explode(',', $profil['competences']) as $comp) :
                        $comp = trim($comp);
                        if ($comp) echo "<span class='competence-badge'>" . htmlspecialchars($comp) . "</span>";
                    endforeach;
                else: ?>
                    <span class="text-muted">Non renseigné</span>
                <?php endif; ?>
            </div>
            <div class="profile-section text-muted text-center">
                <h5><i class="bi bi-briefcase"></i> Expérience</h5>
                <?php if ($profil['experience']): ?>
                    <p><?= htmlspecialchars($profil['experience']) ?></p>
                <?php else: ?>
                    <span class="text-muted">Non renseigné</span>
                <?php endif; ?>
            </div>

            <div class="profile-section ">
                <h5><i class="bi bi-file-earmark-person"></i> CV</h5>
                <?php if ($profil['cv']): ?>
                    <div class="profile-cv-link">
                        <a href="/projet_Rabya/candidats/cv/<?= htmlspecialchars($profil['cv']) ?>"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-file-earmark-arrow-down"></i> Voir le CV
                        </a>
                    </div>
                <?php else: ?>
                    <span class="text-muted">Aucun CV</span>
                <?php endif; ?>
            </div>

            <div class="back-link">
                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>