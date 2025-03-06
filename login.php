<?php
require_once 'config/database.php';
require_once 'Classes/Controller/LoginController.php';
require_once 'Classes/Model/User.php';
require_once 'Classes/Auth/Login.php';

use Config\Database;
use Controller\LoginController;

$db = Database::getConnection();

$controller = new LoginController($db);

echo "<a href='index.php'>Accueil</a>";
echo "<a href='register.php'>S'inscire</a>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processLogin();
} else {
    $controller->showLoginForm();
}


?>