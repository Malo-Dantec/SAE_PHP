<?php

use PHPUnit\Framework\TestCase;
use Classes\Model\Favoris;
use Classes\Config\Database;

Database::$path = "Tests/Data/test_db.db";

class FavorisTest extends TestCase
{
    private PDO $db;
    private Favoris $favorisModel;

    protected function setUp(): void
    {
        $this->db = Database::getConnection();
        $this->favorisModel = new Favoris($this->db);

        // Nettoyage des favoris et des séquences auto-incrémentées
        $this->db->exec("DELETE FROM FAVORIS");
        $this->db->exec("DELETE FROM sqlite_sequence WHERE name='FAVORIS'");

    }

    public function testAjouterFavoris()
    {
        $result = $this->favorisModel->ajouter_favoris(1, 1);
        $this->assertTrue($result);

        // Vérification que le favori a bien été ajouté
        $stmt = $this->db->query("SELECT COUNT(*) FROM FAVORIS WHERE idUser = 1");
        $this->assertEquals(1, $stmt->fetchColumn());
    }

    public function testSupprimerFavoris()
    {
        // Ajout d'un favori à supprimer
        $this->favorisModel->ajouter_favoris(1, 1);
        
        $result = $this->favorisModel->supprimer_favoris(1, 1);
        $this->assertTrue($result);

        // Vérification que le favori a bien été supprimé
        $stmt = $this->db->query("SELECT COUNT(*) FROM FAVORIS WHERE idUser = 1");
        $this->assertEquals(0, $stmt->fetchColumn());
    }

    public function testEstFavoris()
    {
        // Vérification avant d'ajouter
        $this->assertFalse($this->favorisModel->est_favoris(1, 1));

        // Ajout d'un favori
        $this->favorisModel->ajouter_favoris(1, 1);
        $this->assertTrue($this->favorisModel->est_favoris(1, 1));
    }

    public function testGetFavoris()
    {
        // Ajout de plusieurs favoris
        $this->favorisModel->ajouter_favoris(1, 1);
        $this->favorisModel->ajouter_favoris(2, 1);

        $favoris = $this->favorisModel->get_favoris(1);
        $this->assertCount(2, $favoris);
        $this->assertEquals('Cha+', $favoris[0]['nomRestau']);
        $this->assertEquals('Freshkin', $favoris[1]['nomRestau']);
    }
}
