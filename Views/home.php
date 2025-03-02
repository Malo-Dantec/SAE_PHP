<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Restaurants</title>
</head>
<body>
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
</body>
</html>
