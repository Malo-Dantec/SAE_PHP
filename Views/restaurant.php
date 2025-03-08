<?php
use Classes\Config\Database;
use Classes\Controller\AvisController;
use Classes\Model\Favoris;


$db = Database::getConnection();

$idRestau = $_GET['idRestau'] ?? null;
$idUser = $_SESSION['idUser'] ?? null;

$restaurant = $db->query("SELECT * FROM RESTAURANT WHERE idRestau = $idRestau")->fetch(PDO::FETCH_ASSOC);

$favoris = new Favoris($db);
$est_favoris = $idUser ? $favoris->est_favoris($idRestau, $idUser) : false;

if (!$restaurant) {
    echo "<p>Restaurant non trouvé.</p>";
    exit;
}

$avisController = new AvisController($db);
$avisList = $avisController->getAvis($idRestau);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Restaurant</title>
    <link rel="stylesheet" href="/Public/css/header.css">
    <link rel="stylesheet" href="/Public/css/main.css">
    <link rel="stylesheet" href="/Public/css/footer.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <h1><?= htmlspecialchars($restaurant['nomRestau'] ?? 'Nom inconnu') ?></h1>

        <!-- Formulaire d'ajout aux favoris -->
        <form method="POST" action="Classes/Controller/favoris_action.php">
            <input type="hidden" name="idRestau" value="<?= htmlspecialchars($restaurant['idRestau'] ?? '') ?>">
            <button type="submit" name="action" value="<?= $est_favoris ? 'supprimer' : 'ajouter' ?>">
                <?= $est_favoris ? "⭐" : "☆" ?>
            </button>
        </form>

        <p><strong>Type :</strong> <?= htmlspecialchars($restaurant['typeRestau'] ?? 'Non spécifié') ?></p>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($restaurant['numTel'] ?? 'Non disponible') ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($restaurant['nomCommune'] ?? 'Localisation inconnue') ?></p>
        <p><strong>Heures d'ouverture :</strong> <?= htmlspecialchars($restaurant['heureOuverture'] ?? 'Non renseigné') ?></p>
        <p><strong>OSM ID :</strong> <?= htmlspecialchars($restaurant['idRestau'] ?? 'Non spécifié') ?></p>

        <!-- Affichage des avis existants -->
        <h2>Avis des clients</h2>
        <div id="avisContainer">
            <?php if ($avisList): ?>
                <?php foreach ($avisList as $avis): ?>
                    <div class="avis">
                        <strong><?= htmlspecialchars($avis['email'] ?? 'Utilisateur inconnu') ?></strong> (Note: <?= htmlspecialchars($avis['note']) ?>/5)
                        <p><?= nl2br(htmlspecialchars($avis['texteAvis'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun avis pour ce restaurant pour le moment.</p>
            <?php endif; ?>
        </div>

        <!-- Formulaire pour ajouter un avis -->
        <?php
        if ($idUser) {
            echo "<form method='POST' action='Classes/Controller/avis_action.php'>
                <input type='hidden' name='idRestau' value='" . htmlspecialchars($restaurant['idRestau']) . "'>
                <label for='note'>Note (1 à 5):</label>
                <input type='number' name='note' min='1' max='5' required>
                <br>
                <label for='texteAvis'>Votre avis:</label>
                <textarea name='texteAvis' required></textarea>
                <br>
                <button type='submit' name='action' value='ajouter'>Ajouter l'avis</button>
            </form>";
        } else {
            echo "<p>Veuillez vous connecter pour laisser un avis.</p>";
        }
        ?>

        <p><a href="index.php">⬅ Retour à la liste</a></p>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
