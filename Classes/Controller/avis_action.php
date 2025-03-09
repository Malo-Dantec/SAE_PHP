<?php
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';

use Classes\Config\Database;
use Classes\Controller\AvisController;

Database::$path = __DIR__."/../../Data/database.db";

$db = Database::getConnection();
$controller = new AvisController($db);

if (isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $controller->ajouterAvis();
} else {
    header("Location: /index.php");
    exit;
}
?>
