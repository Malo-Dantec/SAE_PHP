<?php

use Classes\Config\Database;
use Classes\Model\Favoris;
use Classes\Controller\RestaurantController;
use Classes\Controller\AvisController;

$db = Database::getConnection();
$idUser = $_SESSION['idUser'] ?? null;
$idRestau = $_GET['idRestau'] ?? null;

$favoris = new Favoris($db);
$est_favoris = $idUser ? $favoris->est_favoris($idRestau, $idUser) : false;

// Gérer l'action d'affichage des restaurants
$controller = new RestaurantController();

if (isset($_GET['action']) && $_GET['action'] === 'show' && isset($_GET['idRestau'])) {
    // Affiche un restaurant spécifique avec ses avis
    $controller->show($_GET['idRestau']);
} else {
    // Affiche la liste de tous les restaurants
    $controller->index();
}

// Gérer l'ajout d'un avis
if (isset($_POST['action']) && $_POST['action'] === 'ajouter_avis') {
    // Récupérer les données du formulaire
    $idUser = $_POST['idUser'] ?? null;
    $idRestau = $_POST['idRestau'] ?? null;
    $note = $_POST['note'] ?? null;
    $texteAvis = $_POST['texteAvis'] ?? null;

    // Vérifier si les données nécessaires sont présentes
    if ($idUser && $idRestau && $note && $texteAvis) {
        // Ajouter l'avis à la base de données
        $avisController = new AvisController();
        $avisController->ajouterAvis($idUser, $idRestau, $note, $texteAvis);

        // Rediriger vers la page du restaurant après l'ajout de l'avis
        header("Location: restaurant.php?idRestau=$idRestau");
        exit();
    }
}

?>
