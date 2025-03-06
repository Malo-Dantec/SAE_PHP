<?php
declare(strict_types=1);

use App\Controller\RestaurantController;
use App\Config\Database;


$db = Database::getConnection();
$controller = new RestaurantController($db);

if (isset($_GET['action']) && $_GET['action'] === 'show' && isset($_GET['idRestau'])) {
    $controller->show($_GET['idRestau']);
} else {
    $controller->index();
}
?>
