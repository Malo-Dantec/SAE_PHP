<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Config\Database;

$db = Database::getConnection();

$restaurantsParPage = 8;

// Page actuelle (par défaut 1)
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $restaurantsParPage;


$search = $_GET['search'] ?? '';
$types = $_GET['types'] ?? [];

// Construction de la requête SQL avec filtres
$sql = "SELECT * FROM RESTAURANT WHERE 1";
$params = [];

if (!empty($search)) {
    $sql .= " AND nomRestau LIKE ?";
    $params[] = "%$search%";
}

if (!empty($types)) {
    $placeholders = implode(',', array_fill(0, count($types), '?'));
    $sql .= " AND typeRestau IN ($placeholders)";
    $params = array_merge($params, $types);
}

$sql .= " LIMIT $restaurantsParPage OFFSET $offset";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le nombre total de restaurants pour la pagination
$sqlCount = "SELECT COUNT(*) FROM RESTAURANT WHERE 1";
if (!empty($search)) {
    $sqlCount .= " AND nomRestau LIKE ?";
}
if (!empty($types)) {
    $sqlCount .= " AND typeRestau IN ($placeholders)";
}

$stmtCount = $db->prepare($sqlCount);
$stmtCount->execute($params);
$totalRestaurants = $stmtCount->fetchColumn();
$totalPages = ceil($totalRestaurants / $restaurantsParPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>IUTables'O</title>
    <link rel="stylesheet" href="/Public\css\header.css">
    <link rel="stylesheet" href="/Public\css\main.css">
    <link rel="stylesheet" href="/Public\css\footer.css">
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
                $typesDispo = ['fast_food', 'bar', 'cafe', 'pub', 'ice_cream', 'restaurant'];
                $typesSelectionnes = $_GET['types'] ?? [];

                foreach ($typesDispo as $type):
                    $checked = (!empty($typesSelectionnes) && in_array($type, $typesSelectionnes)) ? 'checked' : '';
                ?>
                    <label>
                        <input type="checkbox" name="types[]" value="<?= htmlspecialchars($type) ?>" <?= $checked ?>>
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

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&<?= http_build_query(['types' => $types]) ?>">← Précédent</a>
            <?php endif; ?>

            <span>Page <?= $page ?> sur <?= $totalPages ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&<?= http_build_query(['types' => $types]) ?>">Suivant →</a>
            <?php endif; ?>
        </div>

    </main>
    <?php 
        include 'footer.php';
    ?>
</body>
</html>
