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
        <h1><?= htmlspecialchars($restaurant['name'] ?? 'Nom inconnu') ?></h1>
        <p><strong>Type :</strong> <?= htmlspecialchars($restaurant['type'] ?? 'Non spécifié') ?></p>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($restaurant['phone'] ?? 'Non disponible') ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($restaurant['nomCommune'] ?? 'Localisation inconnue') ?></p>
        <p><strong>Heures d'ouverture :</strong> <?= htmlspecialchars($restaurant['opening_hours'] ?? 'Non renseigné') ?></p>
        <p><strong>OSM ID :</strong> <?= htmlspecialchars($restaurant['osm_id'] ?? 'Non spécifié') ?></p>
        <p><a href="index.php">⬅ Retour à la liste</a></p>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
