<?php
session_start();
require_once '../config/database.php';
require_once '../Classes/Model/Favoris.php';

use Model\Favoris;
use Config\Database;

// Vérifier si l'utilisateur est connecté
$idUser = $_SESSION['idUser'] ?? null;
$idRestau = $_POST['idRestau'] ?? null;
$action = $_POST['action'] ?? null;

if (!$idUser || !$idRestau || !in_array($action, ['ajouter', 'supprimer'])) {
    header("Location: /index.php");
    exit;
}

// Connexion à la BD
$db = Database::getConnection();
$favoris = new Favoris($db);

// Ajouter ou supprimer des favoris
if ($action == 'ajouter') {
    $favoris->ajouter_favoris($idRestau, $idUser);
} else {
    $favoris->supprimer_favoris($idRestau, $idUser);
}

// Rediriger vers la page précédente
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;
