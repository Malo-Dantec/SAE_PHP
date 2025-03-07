<?php

namespace Classes\Controller;


use Classes\Model\Restaurant;
use Classes\Config\Database;
use Throwable;



class RestaurantController {
    private array $restaurants;

    public function __construct() {
        try {

            // Récupérer la recherche et les types sélectionnés
            $search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : "";
            $selectedTypes = isset($_GET['types']) ? $_GET['types'] : [];

            // Récupérer les restaurants selon la recherche
            $this->restaurants = ($search !== "") ? Restaurant::searchByNom($search) : Restaurant::getAll();

            // Appliquer le filtre par type
            $this->restaurants = Restaurant::filterType($this->restaurants, $selectedTypes);

            
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
