<?php
session_start();
require_once __DIR__.'/../configuration/connexionbase.php';

// Sécurité : accès réservé aux candidats
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$candidat = $_SESSION['utilisateur'];

// 1. Récupération du profil du candidat
$stmt = $bdd->prepare(
    "SELECT c.nom, c.prenom, c.email, p.competences, p.cv
     FROM candidats c
     LEFT JOIN profils_candidats p ON c.id_candidat = p.id_candidat
     WHERE c.id_candidat = ?"
);
$stmt->execute([$candidat['id']]);
$profil = $stmt->fetch();

// 2. Vérification si le profil est complet
$stmt = $bdd->prepare("SELECT * FROM profils_candidats WHERE id_candidat = ?");
$stmt->execute([$candidat['id']]);
$profil_complet = $stmt->fetch();

// 3. Logique de recherche des offres (mot-clé, secteur, lieu)
$motcle = $_GET['motcle'] ?? '';
$localisation = $_GET['localisation'] ?? '';
$secteur = $_GET['secteur'] ?? '';

$where = [];
$params = [];

if ($motcle) {
    $where[] = "(o.titre LIKE :motcle OR o.description LIKE :motcle)";
    $params['motcle'] = "%$motcle%";
}
if ($localisation) {
    $where[] = "o.lieu LIKE :lieu";
    $params['lieu'] = "%$localisation%";
}
if ($secteur) {
    $where[] = "r.secteur LIKE :secteur";
    $params['secteur'] = "%$secteur%";
}

$sql = "SELECT o.*, r.nom_entreprise, r.secteur
        FROM offres_emploi o
        JOIN recruteurs r ON o.id_recruteur = r.id_recruteur";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY o.date_publication DESC";

$stmt = $bdd->prepare($sql);
$stmt->execute($params);
$offres = $stmt->fetchAll();

// 4. Récupération des offres déjà postulées (pour désactiver le bouton "Postuler")
$stmt = $bdd->prepare("SELECT id_offre FROM candidatures WHERE id_candidat = ?");
$stmt->execute([$candidat['id']]);
$mes_candidatures = $stmt->fetchAll(PDO::FETCH_COLUMN);
if (!$mes_candidatures) $mes_candidatures = [];

// 5. Récupération de toutes les candidatures du candidat (pour afficher le statut)
$stmt = $bdd->prepare(
    "SELECT c.*, o.titre
     FROM candidatures c
     JOIN offres_emploi o ON c.id_offre = o.id_offre
     WHERE c.id_candidat = ?"
);
$stmt->execute([$candidat['id']]);
$candidatures = $stmt->fetchAll();

// 6. Messages flash (succès, déjà postulé, erreur)
$message = '';
if (isset($_GET['message'])) {
   $messages = [
    'success' => "<div class='alert alert-success'>Votre candidature a bien été envoyée.</div>",
    'deja_postule' => "<div class='alert alert-warning'>Vous avez déjà postulé à cette offre.</div>",
    'erreur' => "<div class='alert alert-danger'>Une erreur est survenue lors de la candidature.</div>",
    'profil_incomplet' => "<div class='alert alert-danger'>Votre profil n'est pas complet. Veuillez compléter votre profil avant de postuler à une offre.</div>"
];
    $message = $messages[$_GET['message']] ?? '';
}
?>