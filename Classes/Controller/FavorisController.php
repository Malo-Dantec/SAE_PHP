<?php
namespace Controller;

use Model\Favoris;

class FavorisController {
    private $favoris;

    public function __construct($pdo) {
        $this->favoris = new Favoris($pdo);
    }

    public function toggleFavoris() {
        $idUser = $_SESSION['idUser'] ?? null;
        $idRestau = $_POST['idRestau'] ?? null;
        $action = $_POST['action'] ?? null;
        if (!$idUser || !$idRestau || !in_array($action, ['ajouter', supprimer])) {
            header("Location: ");
            exit;
        }
        if ($action == 'ajouter') {
            $this->favoris = ajouter_favoris($idRestau, $idUser);
        } else {
            $this->favoris = supprimer_favoris($idRestau, $idUser);
        }
    }
}

$controller = new FavorisController($pdo);
$controller->toggleFavoris();


?>