<?php
namespace Classes\Auth;

use Classes\Model\User;
use PDO;

class Register {
    private string $email = '';
    private string $password = '';
    private User $userModel;

    public function __construct(PDO $db) {
        $this->userModel = new User($db); // Initialiser la classe User
    }

    public function render(): void {
        echo '<form method="POST" action="">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br>
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <button type="submit">S\'inscrire</button>
              </form>';
    }

    public function handleRequest(): bool {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données
            $this->email = $_POST['email'] ?? '';
            $this->password = $_POST['password'] ?? '';

            // Valider les données
            if (empty($this->email) || empty($this->password)) {
                echo "L'email ou le mot de passe est vide.";
                return false;
            }

            // Vérifier si l'utilisateur existe déjà
            if ($this->userModel->findByEmail($this->email)) {
                echo "Cet email est déjà enregistré.";
                return false;
            }

            // Créer un nouvel utilisateur
            return $this->userModel->create($this->email, $this->password);
        }
        return false;
    }
}
