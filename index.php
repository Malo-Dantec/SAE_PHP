<?php
declare(strict_types=1);
session_start();




require_once __DIR__ . '/vendor/autoload.php';

use Classes\Config\Database;

Database::$path = "Data/database.db";

require_once __DIR__ . '/routes.php';

if (!file_exists(__DIR__ . '/routes.php')) {
    die("Le fichier routes.php est introuvable.");
}



use Auth\Login;


?>

