<?php
namespace Controller;

use Auth\Register;
use PDO;

class RegisterController {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function showRegisterForm(): void {
        $register = new Register($this->db); // Passe la connexion PDO
        $register->render();
    }

    public function processRegister(): void {
        $register = new Register($this->db); // Passe la connexion PDO
        if ($register->handleRequest()) {
            header('Location: /index.php'); // Rediriger après l'inscription
            exit;
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
}
