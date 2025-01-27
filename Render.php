<?php
declare(strict_types=1);
require 'Classes/Autoloader.php';

Autoloader::register();

use Provider\DataLoaderJson;
use Model\Restaurant;

$loader = new DataLoaderJson("Data/restaurants_orleans.json");
$data = $loader->getData();

$restaurants = new Restaurant($data);


var_dump($restaurants->getRestaurnantsByType("bar"));




?>


