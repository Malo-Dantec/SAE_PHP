<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Classes\Config\Database;
use Classes\Controller\LoginController;

$db = Database::getConnection();

$controller = new LoginController($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processLogin();
} else {
    $controller->showLoginForm();
}

echo "<a href='/index.php'>Accueil</a>";
echo "<a href='register.php'>S'inscire</a>";
?>