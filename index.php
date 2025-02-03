<?php

declare(strict_types=1);
require 'Classes/Autoloader.php';

Autoloader::register();

use Provider\DataLoaderJson;

$loader = new DataLoaderJson("Data/restaurants_orleans.json");
$data = $loader->getData();

// var_dump($data);

use Auth\Login;


echo "<h1>Bienvenue sur le projet</h1>";
echo '<a href="login.php">Connexion</a>';
echo '<a href="register.php">Inscription</a>';


?>

