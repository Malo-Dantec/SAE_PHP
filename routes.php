<?php
require_once './Classes/Controller/RestaurantController.php';

use Classes\Controller\RestaurantController;

$controller = new RestaurantController();

if (isset($_GET['action']) && $_GET['action'] === 'show' && isset($_GET['idRestau'])) {
    $controller->show($_GET['idRestau']);
} else {
    $controller->index();
}
?>
