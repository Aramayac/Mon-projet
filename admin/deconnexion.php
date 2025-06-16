<?php
session_start();
session_destroy();
header('Location: /projet_Rabya/admin/connexion_adminstrateur.php');
exit();
