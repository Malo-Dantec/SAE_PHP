<?php
namespace Classes\Controller;

use Classes\Model\Avis;
use Classes\Config\Database;

class AvisController {
    private $avis;

    public function __construct($db) {
        $this->avis = new Avis($db);
    }

    // Ajouter un avis
    public function ajouterAvis() {
        $idUser = $_SESSION['idUser'] ?? null;
        $idRestau = $_POST['idRestau'] ?? null;
        $note = $_POST['note'] ?? null;
        $texteAvis = $_POST['texteAvis'] ?? null;

        if (!$idUser || !$idRestau || !$note || !$texteAvis) {
            header("Location: /index.php");
            exit;
        }

        // Vérifier si l'utilisateur a déjà donné un avis
        if ($this->avis->has_given_avis($idRestau, $idUser)) {
            header("Location: /index.php?error=already_given");
            exit;
        }

        // Ajouter l'avis dans la base de données
        $this->avis->ajouter_avis($idRestau, $idUser, $note, $texteAvis);
        header("Location: /index.php?action=show&idRestau=$idRestau");
        exit;
    }

    // Récupérer les avis pour un restaurant
    public function getAvis($idRestau) {
        return $this->avis->get_avis_restaurant($idRestau);
    }
}

// Création du contrôleur
$db = Database::getConnection();
$controller = new AvisController($db);
