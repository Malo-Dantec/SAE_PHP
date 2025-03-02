<?php
declare(strict_types=1);
session_start();
if (!file_exists(__DIR__ . '/routes.php')) {
    die("Le fichier routes.php est introuvable.");
}
require_once __DIR__ . '/routes.php';

require_once 'Classes/Autoloader.php';
Autoloader::register();

use Auth\Login;

if (isset($_SESSION['email'])) {
    echo "<a href='logout.php'>Déconnexion</a>";
} else {
    echo '<a href="login.php">Connexion</a>';
    echo '<a href="register.php">Inscription</a>';
}


?>


