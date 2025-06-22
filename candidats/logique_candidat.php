<?php
session_start();// Démarrage de la session pour accéder aux variables de session
require_once __DIR__ . '/../configuration/connexionbase.php';

// Sécurité : accès réservé aux candidats
// Si l'utilisateur n'est pas connecté ou n'est pas un candidat, on le redirige vers la page de connexion
if (!isset($_SESSION['utilisateur']) || $_SESSION['role'] !== 'candidat') {
    header("Location: /projet_Rabya/connexion.php");
    exit();
}

$candidat = $_SESSION['utilisateur'];// Récupération des informations du candidat depuis la session 

// 1. Récupération du profil du candidat
$stmt = $bdd->prepare(
    "SELECT c.nom, c.prenom, c.email, c.avatar, p.competences, p.cv
     FROM candidats c
     LEFT JOIN profils_candidats p ON c.id_candidat = p.id_candidat
     WHERE c.id_candidat = ?"
);
$stmt->execute([$candidat['id']]);// Exécution de la requête avec l'ID du candidat
$profil = $stmt->fetch();

// 2. Vérification si le profil est complet
$stmt = $bdd->prepare("SELECT * FROM profils_candidats WHERE id_candidat = ?");
$stmt->execute([$candidat['id']]);// Exécution de la requête pour vérifier si le profil du candidat est complet
$profil_complet = $stmt->fetch();

// 3. Logique de recherche des offres (mot-clé, secteur, lieu)
$motcle = $_GET['motcle'] ?? '';
$localisation = $_GET['localisation'] ?? '';// Récupération du mot-clé de recherche depuis les paramètres GET
$secteur = $_GET['secteur'] ?? '';

$where = [];// Tableau pour stocker les conditions de recherche
$params = [];// Tableau pour stocker les paramètres de la requête

if ($motcle) {// Si un mot-clé est fourni, on ajoute une condition de recherche
    $where[] = "(o.titre LIKE :motcle OR o.description LIKE :motcle)";// On recherche dans le titre et la description de l'offre
    $params['motcle'] = "%$motcle%";// On utilise un paramètre pour éviter les injections SQL
}
if ($localisation) {// Si une localisation est fournie, on ajoute une condition de recherche
    $where[] = "o.lieu LIKE :lieu";// On recherche dans le lieu de l'offre 
    $params['lieu'] = "%$localisation%";//
}
if ($secteur) {
    $where[] = "r.secteur LIKE :secteur";
    $params['secteur'] = "%$secteur%";
}

// Ajoute TOUJOURS cette condition :
$where[] = "o.statut = 'publiée'";

$sql = "SELECT o.*, r.nom_entreprise, r.secteur
        FROM offres_emploi o
        JOIN recruteurs r ON o.id_recruteur = r.id_recruteur";

if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY o.date_publication DESC LIMIT 3";

$stmt = $bdd->prepare($sql);// Préparation de la requête SQL
$stmt->execute($params);// Exécution de la requête avec les paramètres
$offres = $stmt->fetchAll();// Exécution de la requête et récupération des résultats

// 4. Récupération des offres déjà postulées (pour désactiver le bouton "Postuler")
$stmt = $bdd->prepare("SELECT id_offre FROM candidatures WHERE id_candidat = ?");
$stmt->execute([$candidat['id']]);//
$mes_candidatures = $stmt->fetchAll(PDO::FETCH_COLUMN);
if (!$mes_candidatures) $mes_candidatures = [];// Si aucune candidature n'est trouvée, on initialise le tableau à vide

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
