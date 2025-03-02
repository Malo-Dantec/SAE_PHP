<?php
require_once './Classes/Controller/RestaurantController.php';

use Controller\RestaurantController;

$controller = new RestaurantController();

if (isset($_GET['action']) && $_GET['action'] === 'show' && isset($_GET['osm_id'])) {
    $controller->show($_GET['osm_id']);
} else {
    $controller->index();
}
?>
