<?php
namespace Classes\Controller;

use Classes\Auth\Register;
use PDO;

class RegisterController {
    private PDO $db;
    private Register $register;

    public function __construct(PDO $db,  Register $register = null) {
        $this->db = $db;
        if ($register === null){
            $this->register = new Register($this->db);
        }
        else{
            $this->register = $register;
        }
        
    }

    public function showRegisterForm(): void {
        $this->register->render();
    }

    public function processRegister(): void {
        
        if ($this->register->handleRequest()) {
            header('Location: /Views/login.php'); // Rediriger après l'inscription
            exit;
        } else {
            $this->register->render();
        }
    }
}
