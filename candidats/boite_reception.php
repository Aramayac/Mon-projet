<?php
session_start();

if (!isset($_SESSION['utilisateur']['id'])) {
    echo "Erreur : Session utilisateur introuvable.";
    exit;
}
$id_candidat = $_SESSION['utilisateur']['id'];

require_once __DIR__.'/../configuration/connexionbase.php';

// Récupérer les messages reçus
$stmt = $bdd->prepare("SELECT m.contenu, m.date_envoi, r.nom_entreprise 
                       FROM messages m
                       JOIN recruteurs r ON m.id_expediteur = r.id_recruteur
                       WHERE m.id_destinataire = ?
                       ORDER BY m.date_envoi DESC");
$stmt->execute([$id_candidat]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boîte de Réception - Candidat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
       
        .card{
            background-color:rgba(162, 174, 234, 0.59);
        }
        .card-msg {
            border: none;
            border-left: 8px solid #0d6efd;
            transition: all 0.3s ease-in-out;
        }
        .card-msg:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .msg-header {
            color: white;
            padding: 1rem;
            border-radius: .5rem .5rem 0 0;
        }
      
        .card-text{
            font-weight: bold;
            font-size: large;
        }
        .fw-bold{
            font-weight: bold;
            color:rgb(0, 175, 249);
        }
    </style>
</head>
<body>
    <?php include '../includes/header4.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="text-center mb-4">
                <h2 class="fw-bold "><i class="bi bi-envelope-fill me-2"></i>Boîte de Réception</h2>
            </div>

            <?php if (empty($messages)) : ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>Vous n'avez aucun message pour le moment.
                </div>
            <?php else : ?>
                <?php foreach ($messages as $msg) : ?>
                    <div class="card mb-4 card-msg rounded-4 ">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-building me-2"></i>
                                <?= htmlspecialchars($msg['nom_entreprise']) ?>
                            </h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($msg['contenu'])) ?></p>
                            <div class="text-end">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= date('d/m/Y à H:i', strtotime($msg['date_envoi'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</div>
</body>
</html>
