<?php
// footer_contact.php - à inclure en bas de la page contact
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <title></title>

</head>

<footer class="text-center py-4 mt-5" style="background:rgb(201, 226, 239); border-top:2px solid #e0e9f0;">
  <div class="container">
    <div class="mb-2">
      <a href="https://facebook.com" target="_blank" class="me-2" aria-label="Facebook"><i class="bi bi-facebook fs-3 text-primary"></i></a>
      <a href="https://twitter.com" target="_blank" class="me-2" aria-label="X / Twitter"><i class="bi bi-twitter-x fs-3 text-info"></i></a>
      <a href="https://linkedin.com" target="_blank" class="me-2" aria-label="Linkedin"><i class="bi bi-linkedin fs-3 text-primary"></i></a>
      <a href="<?= $whatsapp_link ?>"><i class="bi bi-whatsapp fs-3 text-success"></i></a>
    </div>
    <div class="mb-1" style="color:#5f799a;font-size:.97em;">
      <i class="bi bi-geo-alt-fill"></i> 123 Avenue du Progrès, Bamako &nbsp; | &nbsp;
      <i class="bi bi-envelope-fill"></i> Services@ikabara &nbsp; | &nbsp;
      <i class="bi bi-telephone-fill"></i> +223 20 00 00 00 
                        <i class="fas fa-flag" style="color: green;"></i>
                        <i class="fas fa-flag" style="color: yellow;"></i>
                        <i class="fas fa-flag" style="color: red;"></i>
    </div>
    <div style="color:#8b9bb1;font-size:.96em;">
      &copy; <?= date('Y') ?>IKBARA – Tous droits réservés.
      <span class="mx-2">|</span>
      <a href="confidentialite.php" class="text-decoration-none" style="color:#5f799a;">Politique de confidentialité</a>
    </div>
  </div>
   <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</footer>