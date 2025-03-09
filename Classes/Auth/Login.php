<?php
namespace Classes\Auth;

use Classes\Model\User;
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
        echo '<!DOCTYPE html>
              <html lang="fr">
              <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Connexion</title>
                  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                  <link rel="stylesheet" href="/Public/css/style.css">
              </head>
              <body>
                  <div class="container d-flex justify-content-center align-items-center vh-100">
                      <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
                          <h2 class="text-center text-danger">Connexion</h2>
                          <form method="POST" action="">
                              <div class="mb-3">
                                  <label for="email" class="form-label">Email:</label>
                                  <input type="email" id="email" name="email" class="form-control" required>
                              </div>
                              
                              <div class="mb-3">
                                  <label for="password" class="form-label">Mot de passe:</label>
                                  <input type="password" id="password" name="password" class="form-control" required>
                              </div>
    
                              <button type="submit" class="btn btn-danger w-100">Se connecter</button>
                          </form>
                          <p class="mt-3 text-center">
                              Pas encore de compte ? <a href="register.php" class="text-danger">S\'inscrire</a>
                          </p>
                      </div>
                  </div>
              </body>
              </html>';
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
        $user = $this->userModel->findByEmail($email);
        if ($user != null ) {
            return $this->userModel->verifyPassword($email, $password);
        }
        return null;
    }
}