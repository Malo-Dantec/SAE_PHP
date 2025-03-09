<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Classes\Config\Database;
use Classes\Controller\RegisterController;

// Obtenir une connexion PDO depuis config/database.php
$db = Database::getConnection();

// Instancier le contrôleur d'inscription
$controller = new RegisterController($db);

// Afficher le formulaire ou traiter les données envoyées
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processRegister();
} else {
    $controller->showRegisterForm();
}

echo "<a href='/index.php'>Accueil</a>";
echo "<a href='/Views/login.php'>Se connecter</a>";
?>
