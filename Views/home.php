<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Config\Database;

$db = Database::getConnection();

$restaurantsParPage = 13;

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
    <!-- <link rel="stylesheet" href="/Public\css\header.css">
    <link rel="stylesheet" href="/Public\css\main.css">
    <link rel="stylesheet" href="/Public\css\footer.css"> -->
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Ton fichier CSS personnalisé -->
<link rel="stylesheet" href="/Public/css/style.css">

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

        <ul class="list-group">
    <?php foreach ($restaurants as $restaurant): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="index.php?action=show&idRestau=<?= urlencode($restaurant['idRestau']) ?>" class="fw-bold">
                <?= htmlspecialchars($restaurant['nomRestau'] ?? 'Nom inconnu') ?>
            </a>
            <span class="badge bg-primary"><?= htmlspecialchars($restaurant['typeRestau'] ?? 'Type inconnu') ?></span>
        </li>
    <?php endforeach; ?>
</ul>

<nav>
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1 ?>">← Précédent</a>
            </li>
        <?php endif; ?>

        <li class="page-item active">
            <span class="page-link">Page <?= $page ?> sur <?= $totalPages ?></span>
        </li>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1 ?>">Suivant →</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

    </main>
    <?php 
        include 'footer.php';
    ?>
</body>
</html>
