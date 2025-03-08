<?php

use PHPUnit\Framework\TestCase;
use Classes\Model\Restaurant;
use Classes\Config\Database;
use SebastianBergmann\CodeCoverage\FileCouldNotBeWrittenException;

Database::$path = "Tests/Data/test_db.db";

class RestaurantTest extends TestCase
{
    private PDO $db;
    
    protected function setUp(): void
    {
        $this->db = Database::getConnection();
    }


    public function testGetAll()
    {
        $restaurants = Restaurant::getAll();
        
        $this->assertIsArray($restaurants);
        $this->assertEquals('Cha+', $restaurants[0]['nomRestau']);
    }

    public function testGetById()
    {
        $restaurant = Restaurant::getById(1);
        $this->assertIsArray($restaurant);
        $this->assertEquals('Cha+', $restaurant['nomRestau']);
    }

    public function testGetByIdNotFound()
    {
        $restaurant = Restaurant::getById(999); // ID inexistant
        $this->assertFalse($restaurant);
    }

    public function testAddJson()
    {
        Restaurant::addJson(__DIR__."/../Data/test.json", $this->db);
        $restaurant = Restaurant::getById(383);
        $this->assertEquals('Cha+', $restaurant["nomRestau"]);
        Restaurant::rmRestau(383);
        $this->db->exec("DELETE FROM sqlite_sequence WHERE name='RESTAURANT'"); // Efface la séquence de la table RESTAURANT
    }

    public function testRmRestau()
    {
        // Ajouter un restaurant pour qu'il y ait des données dans la base
        $restaurant = new Restaurant($this->db, 'FastFood', 'Test Restau', '12:00', '12345678901234', '0123456789', 12345, 'Commune1', 1, 'Region1', 1, 'Departement1', 'osm123');
        $restaurant->addToBd();

        // Vérifiez que le restaurant a bien été ajouté (incrémentation de l'ID)
        $restaurantsBefore = Restaurant::getAll();
        $this->assertEquals(383, count($restaurantsBefore));

        // Supprimer le restaurant récemment ajouté avec l'ID
        $restaurantIdToDelete = $restaurantsBefore[count($restaurantsBefore) - 1]['idRestau'];
        Restaurant::rmRestau($restaurantIdToDelete); // Appeler la méthode rmRestau pour supprimer
        

        // Force la récupération de la liste après la suppression
        $restaurantsAfter = Restaurant::getAll();

        // Assurer que le nombre de restaurants est bien réduit de 1
        $this->assertCount(count($restaurantsBefore) - 1, $restaurantsAfter); // Assure que le restaurant a été supprimé
        $this->db->exec("DELETE FROM sqlite_sequence WHERE name='RESTAURANT'"); // Efface la séquence de la table RESTAURANT
    }

    public function testSearchByNom()
    {
        // Tester la recherche existante
        $results = Restaurant::searchByNom('Cha+');
        $this->assertNotEmpty($results);
        $this->assertEquals('Cha+', $results[0]['nomRestau']);

        // Tester la recherche insensible à la casse
        $results = Restaurant::searchByNom('cha+');
        $this->assertNotEmpty($results);
        $this->assertEquals('Cha+', $results[0]['nomRestau']);

        // Tester une recherche inexistante
        $results = Restaurant::searchByNom('autre');
        $this->assertEmpty($results);
    }

    public function testFilterType()
    {
        // Simuler une liste de restaurants
        $restaurants = [
            ['typeRestau' => 'FastFood', 'nomRestau' => 'Cha+'],
            ['typeRestau' => 'Restaurant', 'nomRestau' => 'Freshkin'],
            ['typeRestau' => 'Cafe', 'nomRestau' => 'Starbucks']
        ];

        // Filtrer uniquement les FastFood
        $filtered = Restaurant::filterType($restaurants, ['FastFood']);
        $this->assertCount(1, $filtered);
        $this->assertEquals('Cha+', array_values($filtered)[0]['nomRestau']);

        // Filtrer FastFood et Restaurant
        $filtered = Restaurant::filterType($restaurants, ['FastFood', 'Restaurant']);
        $this->assertCount(2, $filtered);

        // Filtrer avec un type inexistant
        $filtered = Restaurant::filterType($restaurants, ['Pub']);
        $this->assertEmpty($filtered);

        // Pas de filtrage
        $filtered = Restaurant::filterType($restaurants, []);
        $this->assertCount(3, $filtered);
    }


}
