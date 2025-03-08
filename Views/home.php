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
            <!-- Barre de recherche -->
            <input type="text" name="search" placeholder="Entrez un nom..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Rechercher</button>

            <!-- Bouton Reset (lien qui recharge la page sans paramètres GET) -->
            <a href="index.php">
                Reset
            </a>

            <!-- Filtres de types -->
            <div>
                <?php
                $types = ['fast_food', 'bar', 'cafe', 'pub', 'ice_cream', 'restaurant'];
                foreach ($types as $type):
                    $checked = (isset($_GET['types']) && in_array($type, $_GET['types'])) ? 'checked' : '';?>
                    <label>
                        <input type="checkbox" name="types[]" value="<?= $type ?>" <?= $checked ?>>
                        <?= ucfirst($type) ?> <!-- ucfirst met en majuscule la première lettre --> 
                    </label>
                <?php endforeach; ?>
            </div>
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
