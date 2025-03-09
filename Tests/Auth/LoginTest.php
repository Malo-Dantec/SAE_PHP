<?php

use PHPUnit\Framework\TestCase;
use Classes\Auth\Login;
use Classes\Model\User;
use Classes\Config\Database;


class LoginTest extends TestCase
{
    private PDO $db;
    private Login $login;
    private User $userModel;

    // Configuration de la base de données et de la classe Login avant chaque test
    protected function setUp(): void
    {
        // Connexion à la base de données pour les tests
        $this->db = Database::getConnection(); // Connexion à la base de données
        $this->login = new Login($this->db);
        $this->userModel = new User($this->db);
        
        // Nettoyage avant chaque test
        $this->db->exec("DELETE FROM USER");
        $this->db->exec("DELETE FROM sqlite_sequence WHERE name='USER'");

        $this->userModel->create('test@example.com', 'password123');

        $_SERVER['REQUEST_METHOD'] = 'GET';
       
    }

    protected function tearDown(): void
    {
        // Réinitialise la valeur de $_SERVER['REQUEST_METHOD'] après chaque test pour éviter l'interférence avec d'autres tests.
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }


    // Test de l'affichage du formulaire
    public function testRenderForm(): void
    {
        // Capture la sortie du formulaire
        ob_start();
        $this->login->render();
        $output = ob_get_clean();

        // Vérifie que le formulaire est bien généré
        $this->assertStringContainsString('<form method="POST"', $output);
        $this->assertStringContainsString('<input type="email"', $output);
        $this->assertStringContainsString('<input type="password"', $output);
    }

    // Test de l'authentification avec des identifiants valides
    public function testHandleRequest_Success(): void
    {
        // Simuler une requête POST avec des données valides
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password123';
        // Capture la sortie pour intercepter le message
        ob_start();
        $result = $this->login->handleRequest();
        $output = ob_get_clean();

        // Vérifie que l'authentification est réussie et l'ID utilisateur est stocké dans la session
        $this->assertNotFalse($result);
        $this->assertStringContainsString("Connexion réussie", $output);
        $this->assertEquals(1, $_SESSION['idUser']);
        
    }

    // Test de l'authentification avec des identifiants incorrects
    public function testHandleRequest_Failure(): void
    {
        // Simuler une requête POST avec un email déjà présent
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email'] = 'wrong@example.com';
        $_POST['password'] = 'password123';

        // Capture la sortie pour intercepter le message
        ob_start();
        $result = $this->login->handleRequest();
        $output = ob_get_clean();
        // Vérifie que l'authentification échoue et que le message d'erreur est affiché
        $this->assertFalse($result);
        $this->assertStringContainsString("Identifiants incorrects.", $output);

        // Simuler une requête POST avec un email vide
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email'] = '';
        $_POST['password'] = 'password123';
        ob_start();
        $result = $this->login->handleRequest();
        $output = ob_get_clean();
        $this->assertFalse($result);
        $this->assertStringContainsString("L'email ou le mot de passe est vide.", $output);

        // Simuler une requête POST avec un email déjà présent
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = '';
        ob_start();
        $result = $this->login->handleRequest();
        $output = ob_get_clean();
        $this->assertFalse($result);
        $this->assertStringContainsString("L'email ou le mot de passe est vide.", $output);


        // Simuler une requête GET avec un email déjà présent
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['email'] = 'test@example.com';
        $_GET['password'] = 'pasword';
        ob_start();
        $result = $this->login->handleRequest();
        $output = ob_get_clean();
        $this->assertFalse($result);
        
    }

    
}
