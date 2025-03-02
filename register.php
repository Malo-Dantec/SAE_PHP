<?php
require_once 'config/database.php';
require_once 'Classes/Controller/RegisterController.php';
require_once 'Classes/Model/User.php';
require_once 'Classes/Auth/Register.php';

use Config\Database;
use Controller\RegisterController;

// Obtenir une connexion PDO depuis config/database.php
$pdo = Database::getConnection();

// Instancier le contrôleur d'inscription
$controller = new RegisterController($pdo);

// Afficher le formulaire ou traiter les données envoyées
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processRegister();
} else {
    $controller->showRegisterForm();
}

echo "<a href='index.php'>Accueil</a>";
echo "<a href='login.php'>Se connecter</a>";
