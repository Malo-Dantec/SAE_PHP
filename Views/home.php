<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>IUTables'O</title>
</head>
<body>
    <?php 
        include 'header.php';
    ?>
    <main>
        <h1>Liste des Restaurants</h1>
        <ul>
            <?php foreach ($restaurants as $restaurant): ?>
                <li>
                    <a href="index.php?action=show&osm_id=<?= urlencode($restaurant['osm_id']) ?>">
                        <?= htmlspecialchars($restaurant['name'] ?? 'Nom inconnu') ?>
                    </a> (<?= htmlspecialchars($restaurant['type'] ?? 'Type inconnu') ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <?php 
        include 'footer.php';
    ?>
</body>
</html>
