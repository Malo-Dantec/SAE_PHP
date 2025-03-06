<?php

namespace Controller;
require_once __DIR__ . '/../Model/Restaurant.php';
require_once __DIR__ . '/../../config/database.php';

use Model\Restaurant;
use Config\Database;
use Throwable;

class RestaurantController {
    private array $restaurants;
    private PDO $db;

    public function __construct(PDO $db) {
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
