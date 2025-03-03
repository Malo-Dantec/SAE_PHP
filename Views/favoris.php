<?php 
use Model\Favoris;

$idUser = $_SESSION['idUser'];

if (!$idUser) {
    header('Location: /login.php');
    exit;
}

$favoris = new Favoris($pdo);
$restaurants = $favoris->get_favoris($idUser);





?>

<h2>Mes restaurants favoris</h2>
<ul>
    <?php foreach ($restaurants as $restaurant) : ?>
        <li><?= htmlspecialchars($restaurant['nom']) ?></li>
    <?php endforeach ?>
</ul>