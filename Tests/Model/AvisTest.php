<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Classes\Model\Avis;
use Classes\Model\User;
use Classes\Config\Database;

Database::$path = "Tests/Data/test_db.db"; 

class AvisTest extends TestCase {
    private PDO $db;
    private Avis $avis;
    private User $userModel;

    protected function setUp(): void {
        $this->db = Database::getConnection();
        $this->avis = new Avis($this->db);
        $this->userModel = new User($this->db);

        // Nettoyage des tables avant chaque test
        $this->db->exec("DELETE FROM AVIS");
        $this->db->exec("DELETE FROM DONNER");
        $this->db->exec("DELETE FROM sqlite_sequence WHERE name='AVIS'");
        $this->db->exec("DELETE FROM sqlite_sequence WHERE name='DONNER'");
    }

    public function testAjouterAvis(): void {
        // Ajouter un avis
        $result = $this->avis->ajouter_avis(1, 1, 5, "Super restaurant !");
        
        // Vérifier que l'ajout a réussi
        $this->assertTrue($result);

        // Vérifier que l'avis a bien été inséré dans la base
        $stmt = $this->db->query("SELECT * FROM AVIS");
        $avis = $stmt->fetchAll();

        $this->assertCount(1, $avis);
        $this->assertEquals(5, $avis[0]['note']);
        $this->assertEquals("Super restaurant !", $avis[0]['texteAvis']);
    }

    public function testGetAvisRestaurant(): void {
        $this->userModel->create("email@gmail.com", "pass123");
        // Ajouter un avis avant de tester la récupération
        $this->avis->ajouter_avis(1, 1, 4, "Très bon !");

        // Récupérer les avis du restaurant 1
        $avis = $this->avis->get_avis_restaurant(1);
        // Vérifier qu'il y a bien un avis et que les données sont correctes
        $this->assertCount(1, $avis);
        $this->assertEquals(4, $avis[0]['note']);
        $this->assertEquals("Très bon !", $avis[0]['texteAvis']);
    }

    public function testHasGivenAvis(): void {
        // Vérifier que l'utilisateur n'a pas encore donné d'avis
        $this->assertFalse($this->avis->has_given_avis(1, 1));

        // Ajouter un avis
        $this->avis->ajouter_avis(1, 1, 5, "Parfait !");

        // Vérifier que l'utilisateur a bien donné un avis
        $this->assertTrue($this->avis->has_given_avis(1, 1));
    }
}
?>
