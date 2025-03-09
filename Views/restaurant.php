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

        <p><strong>Type :</strong> <?= htmlspecialchars(ucfirst($restaurant['typeRestau']) ?? 'Non spécifié') ?></p>
        <!-- Téléphone -->
        <?php
            // Récupérer le numéro de téléphone ou afficher "Non disponible" par défaut
            $telephone = $restaurant['numTel'] ?? 'Non disponible';


            if ($telephone !== 'Non disponible') {
                // Si le numéro commence par "33", on ajoute un "+" au début
                if (substr($telephone, 0, 2) === '33') {
                    // Remplacer '33' par '+33' et extraire le reste du numéro
                    $telephone = '+33 ' . substr($telephone, 2);
                    
                    // Si le numéro commence par +33 2, on laisse le 2 seul
                    if (substr($telephone, 4, 1) === '2') {
                        $telephone = substr($telephone, 0, 5) . ' ' . substr($telephone, 5);
                    }
                }
            
                // Ajouter un espace tous les 2 chiffres après le préfixe, sauf pour le premier 2
                $telephone = preg_replace('/(\d{2})(?=\d)/', '$1 ', $telephone);
            }

        ?>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($telephone) ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($restaurant['nomCommune'] ?? 'Localisation inconnue') ?></p>
        <!-- Heures d'ouverture -->
        <?php
            $heuresOuverture = $restaurant['heureOuverture'] ?? 'Non renseigné';

            if ($heuresOuverture !== 'Non renseigné') {
                // Dictionnaire des jours en français
                $joursFr = [
                    'Mo' => 'Lun',
                    'Tu' => 'Mar',
                    'We' => 'Mer',
                    'Th' => 'Jeu',
                    'Fr' => 'Ven',
                    'Sa' => 'Sam',
                    'Su' => 'Dim',
                ];
            
                // Séparer les horaires des jours
                $horaireJours = explode('; ', $heuresOuverture);
            
                // Initialiser une variable pour stocker les heures formatées
                $str_horaires = "\n";
            
                // Parcourir chaque horaire et appliquer les transformations nécessaires
                foreach ($horaireJours as $horaire) {
                    list($jours, $plageHoraire) = explode(' ', $horaire);
                    // Remplacer les abréviations des jours par les noms en français
                    foreach ($joursFr as $jourAnglais => $jourFr) {
                        $jours = str_replace($jourAnglais, $jourFr, $jours);
                        
                    }
                    // Ajouter un espace entre les jours 
                    $jours = str_replace('-', ' - ', $jours);
                    // Ajouter un espace dans la plage horaire
                    $plageHoraire = str_replace('-', 'h - ', $plageHoraire);
                    // Ajout h a la fin de la plage horaire  
                    $plageHoraire = $plageHoraire.'h';
                    // Ajouter un espace entre chaque plage horaire
                    $plageHoraire = str_replace(',', ', ', $plageHoraire);
                    
                    // Ajouter l'horaire formaté à la chaîne des heures avec un saut de ligne
                    $str_horaires .= "{$jours} : {$plageHoraire}\n";
                }
            } else {
                $str_horaires = 'Non renseigné';
            }
        ?>

        <p><strong>Heures d'ouverture :</strong> <?= nl2br(htmlspecialchars($str_horaires)) ?></p>
        




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
