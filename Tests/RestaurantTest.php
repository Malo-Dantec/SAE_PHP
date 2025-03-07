<?php

use PHPUnit\Framework\TestCase;
use Classes\Model\Restaurant;
use Classes\Config\Database;

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
}
