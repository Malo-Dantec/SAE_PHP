<?php

namespace Controller;
require_once 'config/database.php';
use Config\Database;
use Model\Favoris;

class FavorisController {
    private $favoris;

    public function __construct($db) {
        $this->favoris = new Favoris($db);
    }

    public function toggleFavoris() {
        var_dump($_POST);
        $idUser = $_SESSION['idUser'] ?? null;
        $idRestau = $_POST['idRestau'] ?? null;
        $action = $_POST['action'] ?? null;
        if (!$idUser || !$idRestau || !in_array($action, ['ajouter', 'supprimer'])) {
            header("Location: /index.php");
            exit;
        }
        if ($action == 'ajouter') {
            $this->favoris->ajouter_favoris($idRestau, $idUser);
        } else {
            $this->favoris->supprimer_favoris($idRestau, $idUser);
        }
    }
}

$db = Database::getConnection();
$controller = new FavorisController($db);
$controller->toggleFavoris();


?>