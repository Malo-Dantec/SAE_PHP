<?php
declare(strict_types=1);
require 'Classes/Autoloader.php';

Autoloader::register();

use Provider\DataLoaderJson;

$loader = new DataLoaderJson("Data/restaurants_orleans.json");
$data = $loader->getData();

// var_dump($data);




?>


