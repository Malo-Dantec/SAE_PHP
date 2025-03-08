<?php
use PHPUnit\Framework\TestCase;
use Classes\Controller\RegisterController;
use Classes\Config\Database;

class RegisterControllerTest extends TestCase
{
    private PDO $db;
    private RegisterController $registerController;

    protected function setUp(): void
    {
        // Connexion à la base de test existante
        $this->db = Database::getConnection();

        // Nettoyage avant chaque test
        $this->db->exec("DELETE FROM USER");

        // Instanciation du contrôleur
        $this->registerController = new RegisterController($this->db);
    }

    //public function testProcessRegister_Success()
    //{
    //    // Simuler une requête POST avec des données valides
    //    $_POST['email'] = "test@example.com";
    //    $_POST['password'] = "password123";
    //    $_POST['confirm_password'] = "password123";
    //    //$_SERVER['REQUEST_METHOD'] = 'POST';
//
    //    // Capturer la sortie pour intercepter la redirection
    //    ob_start();
    //    $this->registerController->processRegister();
    //    ob_end_clean();
//
    //    // Vérifier que l'utilisateur a été ajouté à la base de données
    //    $stmt = $this->db->prepare("SELECT * FROM USER WHERE email = ?");
    //    $stmt->execute(["test@example.com"]);
    //    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //    var_dump($user);
//
    //    $this->assertNotFalse($user, "L'utilisateur doit être inséré en base de données.");
    //    $this->assertEquals("test@example.com", $user["email"]);
    //}

    public function testProcessRegister_PasswordMismatch()
    {
        // Simuler une requête POST avec un mot de passe non confirmé correctement
        $_POST['email'] = "test2@example.com";
        $_POST['password'] = "password123";
        $_POST['confirm_password'] = "differentpassword";

        // Capturer la sortie
        ob_start();
        $this->registerController->processRegister();
        $output = ob_get_clean();

        // Vérifier que l'utilisateur **n'a pas** été ajouté
        $stmt = $this->db->prepare("SELECT * FROM USER WHERE email = ?");
        $stmt->execute(["test2@example.com"]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($user, "L'utilisateur ne doit pas être inséré si les mots de passe ne correspondent pas.");
        $this->assertStringContainsString("Erreur lors de l'inscription", $output);
    }

    public function testProcessRegister_ExistingEmail()
    {
        // Insérer un utilisateur existant
        $stmt = $this->db->prepare("INSERT INTO USER (email, password) VALUES (?, ?)");
        $stmt->execute(["existing@example.com", password_hash("password123", PASSWORD_BCRYPT)]);

        // Simuler une requête POST avec le même email
        $_POST['email'] = "existing@example.com";
        $_POST['password'] = "password123";
        $_POST['confirm_password'] = "password123";

        // Capturer la sortie
        ob_start();
        $this->registerController->processRegister();
        $output = ob_get_clean();

        // Vérifier que l'utilisateur **n'a pas** été ajouté en double
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM USER WHERE email = ?");
        $stmt->execute(["existing@example.com"]);
        $userCount = $stmt->fetchColumn();

        $this->assertEquals(1, $userCount, "L'utilisateur ne doit pas être ajouté en double.");
        $this->assertStringContainsString("Erreur lors de l'inscription", $output);
    }

}
