<?php
namespace Controller;

use Auth\Login;

class LoginController {
    public function showLoginForm(): void {
        $login = new Login();
        $login->render();
    }

    public function processLogin(): void {
        $login = new Login();
        if ($login->handleRequest()) {
            header('Location: /index.php');
            exit;
        } else {
            echo "Erreur de connexion.";
        }
    }
}
