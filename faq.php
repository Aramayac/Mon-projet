<?php
// faq.php — FAQ professionnelle et moderne pour application de recrutement en ligne
$whatsapp_phone = '22374878873';
$whatsapp_message = "Bonjour, je souhaite des informations sur le service de recrutement en ligne.";
$whatsapp_link = "https://wa.me/$whatsapp_phone?text=" . urlencode($whatsapp_message);
$whatsapp_message = "Bonjour, je souhaite obtenir des informations concernant le service de recrutement en ligne.";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FAQ | Recrutement Pro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 + Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f7fafc; }
        .faq-section { background: #fff; border-radius: 18px; box-shadow: 0 6px 36px 0 rgba(0,102,153,0.06); padding: 35px 25px; margin-top: 48px; margin-bottom: 48px; }
        .faq-title { font-size: 2.1rem; font-weight: 700; color: #14697b; margin-bottom: 18px; }
        .faq-intro { color: #43566c; margin-bottom: 35px; }
        .accordion-button { font-weight: 600; color: #14697b; background: #f4fafd; }
        .accordion-button:not(.collapsed) { background: linear-gradient(90deg,#eaf6fa 60%,#f6fafd 100%); color: #006699; }
        .accordion-item { border-radius: 12px; margin-bottom: 12px; overflow: hidden; }
        .faq-contact-link { color: #0099cc; text-decoration: none; font-weight: bold; }
        .faq-contact-link:hover { text-decoration: underline; }
        @media (max-width:900px) { .faq-section { padding: 19px 5px; } }
    </style>
</head>
<body>
<?php include '../projet_Rabya/header_contact.php'; ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-8 col-lg-9">
      <div class="faq-section">
        <div class="faq-title"><i class="bi bi-question-circle-fill"></i> Foire Aux Questions</div>
        <div class="faq-intro">
          Retrouvez ici les réponses aux questions les plus fréquentes concernant notre application de recrutement en ligne.<br>
          Pour toute demande spécifique, <a href="../contact.php/" class="faq-contact-link">contactez notre équipe</a>.
        </div>
        <div class="accordion" id="faqAccordion">

          <!-- 1. Créer un compte candidat -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                <i class="bi bi-person-plus me-2"></i>Comment créer un compte candidat ?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Cliquez sur <strong>“S’inscrire”</strong> en haut à droite de la page d’accueil, remplissez les champs requis puis validez.<br>
                A partir de là, vous pouvez vous connecter avec vos informations de compte. 
              </div>
            </div>
          </div>

          <!-- 2. Déposer un CV et postuler -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                <i class="bi bi-upload me-2"></i>Comment déposer mon CV et postuler à une offre ?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Après connexion, accédez à votre espace personnel puis cliquez sur <strong>“Completer mon profil”</strong>.<br>
                Les candidatures s’effectuent directement depuis chaque offre d’emploi en cliquant sur <strong>“Postuler”</strong>.
              </div>
            </div>
          </div>

          <!-- 3. Statut des candidatures -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                <i class="bi bi-clipboard-check me-2"></i>Comment suivre l’avancement de ma candidature ?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Rendez-vous dans l’onglet <strong>“Mes candidatures”</strong> de votre espace personnel pour consulter le statut de vos dossiers (en cours, retenu, non retenu...).
              </div>
            </div>
          </div>

          <!-- 4. Sécurité & confidentialité -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                <i class="bi bi-shield-lock me-2"></i>Ma vie privée et mes données sont-elles protégées ?
              </button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Oui, toutes les données sont traitées de façon strictement confidentielle et protégées selon la réglementation en vigueur.<br>
                Vous pouvez à tout moment exercer vos droits en <a href="../contact.php/" class="faq-contact-link">nous contactant</a>.
              </div>
            </div>
          </div>

          <!-- 5. Recruteurs : publier une offre -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                <i class="bi bi-briefcase me-2"></i>Comment publier une offre d’emploi en tant que recruteur ?
              </button>
            </h2>
            <div id="faq5" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Connectez-vous à votre espace recruteur puis cliquez sur <strong>“Publier une offre”</strong>.<br>
                Remplissez le formulaire et validez : l’équipe de modération vérifie chaque offre avant publication.
              </div>
            </div>
          </div>

          <!-- 6. Assistance technique -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
                <i class="bi bi-tools me-2"></i>Que faire en cas de problème technique ou de bug ?
              </button>
            </h2>
            <div id="faq6" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Contactez notre support via le <a href="../contact.php/" class="faq-contact-link">formulaire de contact</a> ou par WhatsApp.<br>
                Merci de décrire précisément votre problème pour que nous puissions le résoudre rapidement.
              </div>
            </div>
          </div>

          <!-- 7. Partenariats, presse, formations -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingSeven">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7">
                <i class="bi bi-people me-2"></i>Je suis une entreprise, un partenaire ou un média, comment vous joindre ?
              </button>
            </h2>
            <div id="faq7" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Notre équipe est à votre écoute pour toute demande spécifique (partenariats, communication, presse, formations).<br>
                Merci d’utiliser la page <a href="../contact.php/" class="faq-contact-link">Contact</a> : nous vous répondrons sous 24h.
              </div>
            </div>
          </div>

          <!-- 8. WhatsApp direct -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingEight">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
                <i class="bi bi-whatsapp me-2"></i>Puis-je discuter directement avec un conseiller via WhatsApp ?
              </button>
            </h2>
            <div id="faq8" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Oui ! Cliquez simplement sur le bouton WhatsApp présent sur la page Contact pour ouvrir une discussion instantanée avec notre équipe.
              </div>
            </div>
          </div>

        </div>
        <div class="mt-4 text-center text-secondary" style="font-size:.97em;">
          <i class="bi bi-envelope"></i> Une question non couverte ?  
          <a href="../contact.php/" class="faq-contact-link">Contactez-nous directement</a>.
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../projet_Rabya/footer_contact.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>