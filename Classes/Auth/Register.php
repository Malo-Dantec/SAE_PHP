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
        echo '<!DOCTYPE html>
              <html lang="fr">
              <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Inscription</title>
                  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                  <link rel="stylesheet" href="/Public/css/style.css">
              </head>
              <body>
                  <div class="container d-flex justify-content-center align-items-center vh-100">
                      <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
                          <h2 class="text-center text-danger">Inscription</h2>
                          <form method="POST" action="">
                              <div class="mb-3">
                                  <label for="email" class="form-label">Email:</label>
                                  <input type="email" id="email" name="email" class="form-control" required>
                              </div>
                              
                              <div class="mb-3">
                                  <label for="password" class="form-label">Mot de passe:</label>
                                  <input type="password" id="password" name="password" class="form-control" required>
                              </div>
    
                              <button type="submit" class="btn btn-danger w-100">S\'inscrire</button>
                          </form>
                          <p class="mt-3 text-center">
                              Déjà un compte ? <a href="login.php" class="text-danger">Se connecter</a>
                          </p>
                      </div>
                  </div>
              </body>
              </html>';
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
