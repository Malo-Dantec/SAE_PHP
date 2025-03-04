<?php

namespace App\Controller;

use App\Provider\DataLoaderJson;
use PDO;

class RestaurantController {
    private array $restaurants;
    private PDO $db;

    public function __construct(PDO $db) {
        try {
            $loader = new DataLoaderJson(__DIR__ . "/../../Data/restaurants_orleans.json");
            $this->restaurants = $loader->getData();
            $loader->jsonToData($db);
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
