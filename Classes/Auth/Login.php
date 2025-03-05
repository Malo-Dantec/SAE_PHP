<?php
namespace Auth;

use Model\User;
use PDO;

class Login {
    private string $email = '';
    private string $password = '';
    private User $userModel;

    public function __construct(PDO $db) {
        $this->userModel = new User($db); // Initialiser la classe User
        session_start(); // Démarrer la session pour gérer l'authentification
    }

    // Méthode pour afficher le formulaire
    public function render(): void {
        echo '<form method="POST" action="">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br>
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <button type="submit">Se connecter</button>
              </form>';
    }

    // Méthode pour traiter les données envoyées par le formulaire
    public function handleRequest(): bool {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->email = $_POST['email'] ?? '';
            $this->password = $_POST['password'] ?? '';

            if (empty($this->email) || empty($this->password)) {
                echo "L'email ou le mot de passe est vide.";
                return false;
            }

            $userId = $this->authenticate($this->email, $this->password);
            if ($userId !== null) {
                $_SESSION['idUser'] = $userId; // Stocke l'ID utilisateur en session
                echo "Connexion réussie.";
                return true;
            } else {
                echo "Identifiants incorrects.";
                return false;
            }
        }
        return false;
    }

    // Méthode pour authentifier l'utilisateur avec la base de données
    private function authenticate(string $email, string $password): ?int {
        return $this->userModel->verifyPassword($email, $password);
    }
}