<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>IUTables'O</title>
    <link rel="stylesheet" href="Public\css\header.css">
    <link rel="stylesheet" href="Public\css\main.css">
    <link rel="stylesheet" href="Public\css\footer.css">
</head>
<body>
    <?php 
        include 'header.php';
    ?>
    <main>
        <h1>Liste des Restaurants</h1>
        <form method="GET">
            <input type="text" name="search" placeholder="Entrez un nom..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Rechercher</button>
        </form>
        <ul>
            <?php foreach ($restaurants as $restaurant): ?>
                <li>
                    <a href="index.php?action=show&idRestau=<?= urlencode($restaurant['idRestau']) ?>">
                        <?= htmlspecialchars($restaurant['nomRestau'] ?? 'Nom inconnu') ?>
                    </a> (<?= htmlspecialchars($restaurant['typeRestau'] ?? 'Type inconnu') ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <?php 
        include 'footer.php';
    ?>
</body>
</html>
