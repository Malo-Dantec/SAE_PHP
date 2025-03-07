<?php

namespace Classes\Controller;


use Classes\Model\Restaurant;
use Classes\Config\Database;
use Throwable;

class RestaurantController {
    private array $restaurants;

    public function __construct() {
        try {
            // Récupérer tous les restaurants depuis la base de données
            $this->restaurants = Restaurant::getAll();
        } catch (Throwable $e) {
            die("❌ Erreur dans RestaurantController : " . $e->getMessage());
        }
    }

    public function index() {
        $restaurants = $this->restaurants;
        require __DIR__ . '/../../Views/home.php';
    }

    public function show($idRestau) {
        $restaurant = Restaurant::getById($idRestau);

        if ($restaurant) {
            require __DIR__ . '/../../Views/restaurant.php';
        } else {
            echo "❌ Restaurant non trouvé.";
        }
    }
}
