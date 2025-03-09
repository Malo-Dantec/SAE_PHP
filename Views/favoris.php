<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

use Classes\Model\Favoris;
use Classes\Config\Database;

$db = Database::getConnection();
$idUser = $_SESSION['idUser'];

if (!$idUser) {
    header('Location: /login.php');
    exit;
}

$favoris = new Favoris($db);
$restaurants = $favoris->get_favoris($idUser);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="/Public/css/header.css">
    <link rel="stylesheet" href="/Public/css/main.css">
    <link rel="stylesheet" href="/Public/css/footer.css"> -->
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Ton fichier CSS personnalisé -->
<link rel="stylesheet" href="/Public/css/style.css">

    <title>Mes favoris</title>
</head>
<body>
    <?= include 'header.php'; ?>
    <main>
        <h2>Mes restaurants favoris</h2>
        <ul>
            <?php foreach ($restaurants as $restaurant) : ?>
                <li><?= htmlspecialchars($restaurant['nomRestau']) ?></li>
            <?php endforeach ?>
        </ul>
    </main>
    <?= include 'footer.php'; ?>
</body>
</html>

