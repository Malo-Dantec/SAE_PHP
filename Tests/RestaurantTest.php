<?php

use PHPUnit\Framework\TestCase;
use App\Model\Restaurant;

class RestaurantTest extends TestCase
{
    private PDO $db;
    
    protected function setUp(): void
    {
        $dbFile = __DIR__ . '/Data/test_db.db';  // Fichier .db
        $this->db = new PDO("sqlite:$dbFile");
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Charger le script SQL pour initialiser la base de données
        $this->loadDatabaseSchema();
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

    public function testGetAll()
    {
        $restaurants = Restaurant::getAll();
        
        $this->assertIsArray($restaurants);
        $this->assertCount(2, $restaurants); // Il y a 2 restaurants insérés
        $this->assertEquals('Restaurant 1', $restaurants[0]['name']);
    }

    public function testGetById()
    {
        $restaurant = Restaurant::getById(1);

        $this->assertIsArray($restaurant);
        $this->assertEquals('Restaurant 1', $restaurant['name']);
    }

    public function testGetByIdNotFound()
    {
        $restaurant = Restaurant::getById(999); // ID inexistant

        $this->assertNull($restaurant); // Doit retourner null
    }
}
