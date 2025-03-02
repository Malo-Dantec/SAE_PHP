<?php

use PHPUnit\Framework\TestCase;
use App\Model\User;

class UserTest extends TestCase
{
    private PDO $db;

    protected function setUp(): void
    {
        // Connexion à la base de données SQLite avec une extension .db dans le répertoire Data
        $dbFile = __DIR__ . '/Data/test_db.db';  // Fichier .db
        $this->db = new PDO("sqlite:$dbFile");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Charger le script SQL pour initialiser la base de données
        $this->loadDatabaseSchema();
    }

    protected function tearDown(): void
    {
        // Pour éviter la persistance de la base de données de test entre les tests
        $dbFile = __DIR__ . '/Data/test_db.db';  // Utilise le même nom du fichier
        //if (file_exists($dbFile)) {
        //    unlink($dbFile); // Supprimer le fichier de la base de données
        //}
    }

    /**
     * Charger et exécuter le script SQL pour initialiser la base de données.
     */
    private function loadDatabaseSchema(): void
    {
        // Lire le script SQL pour créer la structure de la base de données
        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '../Data/bd.sql');
        $this->db->exec($sql);  // Exécuter le script SQL pour initialiser la base de données
    }

    public function testCreateUser()
    {
        $user = new User($this->db);

        // Créer un nouvel utilisateur
        $result = $user->create('new_user@example.com', 'newpassword123');

        // Vérifier que l'utilisateur a bien été créé
        $this->assertTrue($result);

        // Vérifier que l'utilisateur peut être trouvé par email
        $foundUser = $user->findByEmail('new_user@example.com');
        $this->assertNotNull($foundUser);
        $this->assertEquals('new_user@example.com', $foundUser['email']);
    }

    
}
