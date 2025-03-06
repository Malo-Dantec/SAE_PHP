<?php
declare(strict_types=1);
session_start();
if (!file_exists(__DIR__ . '/routes.php')) {
    die("Le fichier routes.php est introuvable.");
}


require_once __DIR__ . '/vendor/autoload.php';



require_once __DIR__ . '/routes.php';
use Auth\Login;


?>


