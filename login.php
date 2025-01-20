<?php
require_once 'classes/Controller/LoginController.php';

use Controller\LoginController;

$controller = new LoginController();
$controller->showLoginForm();
$controller->processLogin();
?>
