<?php

namespace Controller;

use Auth\Login;
use PDO;

class LoginController {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;// Passer la connexion PDO
    }

    public function showLoginForm(): void {
        $login = new Login($this->db); // Passe la connexion PDO
        $login->render();
    }

    public function processLogin(): void {
        $login = new Login($this->db); // Passer la connexion PDO
        if ($login->handleRequest()) {
            header('Location: /index.php');
            exit;
        } else {
            echo "Erreur de connexion.";
        }
    }
}