<?php

use PHPUnit\Framework\TestCase;
use Classes\Auth\Register;
use Classes\Model\User;
use Classes\Config\Database;

class RegisterTest extends TestCase
{
    private PDO $db;
    private Register $register;
    private User $userModel;

    // Configuration de la base de données et de la classe Register avant chaque test
    protected function setUp(): void
    {
        // Connexion à la base de données pour les tests
        $this->db = Database::getConnection(); // Connexion à la base de données
        $this->register = new Register($this->db);
        $this->userModel = new User($this->db);
        
        // Nettoyage avant chaque test
        $this->db->exec("DELETE FROM USER");
        $this->db->exec("DELETE FROM sqlite_sequence WHERE name='USER'");

        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    protected function tearDown(): void
    {
        // Réinitialise la valeur de $_SERVER['REQUEST_METHOD'] après chaque test pour éviter l'interférence avec d'autres tests.
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    // Test de l'affichage du formulaire d'inscription
    public function testRenderForm(): void
    {
        // Capture la sortie du formulaire
        ob_start();
        $this->register->render();
        $output = ob_get_clean();

        // Vérifie que le formulaire est bien généré
        $this->assertStringContainsString('<form method="POST"', $output);
        $this->assertStringContainsString('<input type="email"', $output);
        $this->assertStringContainsString('<input type="password"', $output);
    }

    // Test de l'inscription réussie avec des données valides
    public function testHandleRequest_Success(): void
    {
        // Simuler une requête POST avec des données valides
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password123';

        // Capture la sortie pour intercepter le message
        ob_start();
        $result = $this->register->handleRequest();
        $output = ob_get_clean();

        // Vérifie que l'inscription est réussie et que l'utilisateur est ajouté à la base de données
        $this->assertNotFalse($result);

        // Vérifie que l'utilisateur a bien été inséré dans la base de données
        $user = $this->userModel->findByEmail("test@example.com");
        $this->assertNotFalse($user);
        $this->assertEquals('test@example.com', $user['email']);
    }

    // Test de l'inscription échouée avec un email déjà utilisé
    public function testHandleRequest_EmailAlreadyExists(): void
    {
        // Créer un utilisateur avec un email existant
        $this->userModel->create('test@example.com', 'password123');

        // Simuler une requête POST avec un email déjà existant
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password123';

        // Capture la sortie pour intercepter le message
        ob_start();
        $result = $this->register->handleRequest();
        $output = ob_get_clean();

        // Vérifie que l'inscription échoue et que le message d'erreur est affiché
        $this->assertFalse($result);
        $this->assertStringContainsString("Cet email est déjà enregistré.", $output);
    }

    // Test de l'inscription échouée avec des champs vides
    public function testHandleRequest_EmptyFields(): void
    {
        // Simuler une requête POST avec des champs vides
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email'] = '';
        $_POST['password'] = '';

        // Capture la sortie pour intercepter le message
        ob_start();
        $result = $this->register->handleRequest();
        $output = ob_get_clean();

        // Vérifie que l'inscription échoue et que le message d'erreur est affiché
        $this->assertFalse($result);
        $this->assertStringContainsString("L'email ou le mot de passe est vide.", $output);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['email'] = '';
        $_GET['password'] = '';

        ob_start();
        $result = $this->register->handleRequest();
        $output = ob_get_clean();

        // Vérifie que l'inscription échoue et que le message d'erreur est affiché
        $this->assertFalse($result);
    }
}
