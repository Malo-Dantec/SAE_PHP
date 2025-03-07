<?php

use Classes\Config\Database;
use Classes\Model\Favoris;

$db = Database::getConnection();
$idUser = $_SESSION['idUser'] ?? null;
$idRestau = $restaurant['idRestau'] ?? null;

$favoris = new Favoris($db);
$est_favoris = $favoris->est_favoris($idRestau, $idUser);
$db = Database::getConnection();


$est_favoris = $idUser ? $favoris->est_favoris($idRestau, $idUser) : false;
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Restaurant</title>
    <link rel="stylesheet" href="Public\css\header.css">
    <link rel="stylesheet" href="Public\css\main.css">
    <link rel="stylesheet" href="Public\css\footer.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h1><?= htmlspecialchars($restaurant['nomRestau'] ?? 'Nom inconnu') ?></h1>
        <form method="POST" action="Classes/Controller/favoris_action.php">
            <input type="hidden" name="idRestau" value="<?= htmlspecialchars($restaurant['idRestau'] ?? '') ?>">
            <button type="submit" name="action" value="<?= $est_favoris ? 'supprimer' : 'ajouter' ?>">
                <?= $est_favoris ? "Retirer des favoris" : "Ajouter aux favoris" ?>
            </button>
        </form>

        <p><strong>Type :</strong> <?= htmlspecialchars($restaurant['typeRestau'] ?? 'Non spécifié') ?></p>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($restaurant['numTel'] ?? 'Non disponible') ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($restaurant['nomCommune'] ?? 'Localisation inconnue') ?></p>
        <p><strong>Heures d'ouverture :</strong> <?= htmlspecialchars($restaurant['heureOuverture'] ?? 'Non renseigné') ?></p>
        <p><strong>OSM ID :</strong> <?= htmlspecialchars($restaurant['idRestau'] ?? 'Non spécifié') ?></p>
        <p><a href="index.php">⬅ Retour à la liste</a></p>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
