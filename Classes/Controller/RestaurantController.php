<?php

namespace Controller;
require_once __DIR__ . '/../Provider/DataLoaderJson.php';

use Provider\DataLoaderJson;

class RestaurantController {
    private array $restaurants;

    public function __construct() {
        try {
            $loader = new DataLoaderJson(__DIR__ . "/../../Data/restaurants_orleans.json");
            $this->restaurants = $loader->getData();
            echo "Données chargées avec succès !";
            die();
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
