
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <body>
    <style>
        .navbar{
            background-image: url('/projet_Rabya/igm/s2.jpg');
            background-size: cover;
        }
        .container{
            padding: 20px;
        }
        .nav-item a {
            color: white !important;
            font-weight: bold;
            font-size: large;
        }
      .navbar-fixed {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1030;
    transition: transform 0.3s ease, background-color 0.3s ease;
}
.navbar-scrolled {
    background-color: rgba(0, 0, 0, 0.9) !important;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}


    </style>
</body>
<nav class="navbar navbar-expand-lg navbar-dark bg-transparent shadow navbar-fixed">
    <div class="container">
        <a class="navbar-brand fw-bold" href="tableau_administateur.php">
            <i class="bi bi-speedometer2"></i> Tableau de bord Administrateur
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="users.php"><i class="bi bi-people-fill"></i> Utilisateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="offres.php"><i class="bi bi-file-earmark-text"></i> Offres</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> Profil
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Paramètres</a></li>
                        <li><a class="dropdown-item text-danger" href="deconnexion.php">Déconnexion</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

</body>
<script>
    let lastScrollTop = 0;
    const navbar = document.querySelector(".navbar");

    window.addEventListener("scroll", function() {
        let scrollTop = window.scrollY;
        
        if (scrollTop > lastScrollTop) {
            // Quand on descend -> Fixe en haut
            navbar.classList.add("navbar-scrolled");
        } else if (scrollTop < 50) {
            // Quand on remonte -> Retour à sa place
            navbar.classList.remove("navbar-scrolled");
        }
        
        lastScrollTop = scrollTop;
    });
</script>
</html>