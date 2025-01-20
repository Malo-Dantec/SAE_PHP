<?php
declare(strict_types=1);

namespace Auth;

class Login {
    private string $email = '';
    private string $password = '';

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
            // Récupération des données postées
            $this->email = $_POST['email'] ?? '';
            $this->password = $_POST['password'] ?? '';

            // Validation des données (par exemple, vérifier si non vide)
            if (empty($this->email) || empty($this->password)) {
                echo "L'email ou le mot de passe est vide.";
                return false;
            }

            // Vérification des identifiants (simulateur pour l'exemple)
            if ($this->authenticate($this->email, $this->password)) {
                echo "Connexion réussie.";
                return true;
            } else {
                echo "Identifiants incorrects.";
                return false;
            }
        }
        return false;
    }

    // Méthode pour authentifier l'utilisateur (exemple simple, à adapter avec une base de données)
    private function authenticate(string $email, string $password): bool {
        // Simuler des identifiants valides
        $validEmail = 'test@example.com';
        $validPassword = 'password123';

        return $email === $validEmail && $password === $validPassword;
    }
}

?>
