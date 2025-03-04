<?php

namespace App\Controller;

use App\Model\Restaurant;
use PDO;

class RestaurantController {
    private array $restaurants;
    private PDO $db;

    public function __construct(PDO $db) {
        try {
            //Restaurant::addJson("Data/restaurants_orleans.json", $db); // charge le json dans la bd
            $this->restaurants = Restaurant::getAll();
        } catch (Throwable $e) {
            die("Erreur dans RestaurantController : " . $e->getMessage());
        }
    }

    

    

    public function index() {
        $restaurants = $this->restaurants; // Passe les restaurants à la vue
        require __DIR__ . '/../../Views/home.php';
    }

    public function show($osm_id) {
        foreach ($this->restaurants as $restaurant) {
            if ($restaurant['osm_id'] === $osm_id) {
                require __DIR__ . '/../../Views/restaurant.php';
                return;
            }
        }
        echo "❌ Restaurant non trouvé.";
    }
}
