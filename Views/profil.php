<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Classes\Config\Database;
use Classes\Model\User;
use Classes\Model\Avis;

session_start();

// Connexion à la base de données
$db = Database::getConnection();
$idUser = $_SESSION['idUser'] ?? null;

if (!$idUser) {
    header('Location: /login.php');
    exit;
}

// Instanciation des modèles
$userModel = new User($db);
$avisModel = new Avis($db);

// Récupérer l'email de l'utilisateur
$email = $userModel->getEmail($idUser);
if (!$email) {
    die("Utilisateur non trouvé.");
}

// Supprimer un avis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['supprimerAvis'])) {
    $idAvis = $_POST['idAvis'] ?? null;
    if ($idAvis) {
        $avisModel->deleteAvis($idUser, $idAvis);
        header("Location: profil.php?pageAvis=" . ($_GET['pageAvis'] ?? 1));
        exit;
    }
}

// Modifier le mot de passe
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nouveau_mdp'])) {
    $ancienMDP = $_POST['ancien_mdp'] ?? '';
    $nouveauMDP = $_POST['nouveau_mdp'] ?? '';
    $memeMDP = $_POST['meme_mdp'] ?? '';

    if (empty($ancienMDP) || empty($nouveauMDP) || empty($memeMDP)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($nouveauMDP !== $memeMDP) {
        $message = "Les nouveaux mots de passe ne correspondent pas.";
    } elseif (!$userModel->checkPassword($idUser, $ancienMDP)) {
        $message = "Ancien mot de passe incorrect.";
    } elseif ($userModel->updatePassword($idUser, $nouveauMDP)) {
        $message = "Mot de passe mis à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour.";
    }
}

// Récupérer les avis avec pagination
$pageAvis = isset($_GET['pageAvis']) ? max(1, intval($_GET['pageAvis'])) : 1;
$avisList = $userModel->getAvis($idUser, $pageAvis);
$totalAvis = $userModel->countAvis($idUser);
$totalPagesAvis = ceil($totalAvis / 3);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="/Public/css/header.css">
    <link rel="stylesheet" href="/Public/css/main.css">
    <link rel="stylesheet" href="/Public/css/footer.css">
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Ton fichier CSS personnalisé -->
<link rel="stylesheet" href="/Public/css/style.css">

</head>
<body>
    <?= include 'header.php'; ?>
    <main>
        <h2>Mon profil</h2>

        <?php if (!empty($message)): ?>
            <p style="color: <?= $message === "Mot de passe mis à jour avec succès." ? 'green' : 'red' ?>;">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <form method="POST" class="mb-4">
    <div class="mb-3">
        <label for="email" class="form-label">Email :</label>
        <input type="email" id="email" name="email" class="form-control" value="<?= $email ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="ancien_mdp" class="form-label">Ancien mot de passe :</label>
        <input type="password" id="ancien_mdp" name="ancien_mdp" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="nouveau_mdp" class="form-label">Nouveau mot de passe :</label>
        <input type="password" id="nouveau_mdp" name="nouveau_mdp" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-danger w-100">Modifier mon mot de passe</button>
        </form>
  
        <aside class="avis-sidebar">
            <h3>Mes Avis</h3>
            <?php if (empty($avisList)): ?>
                <p>Aucun avis publié.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($avisList as $avis): ?>
                        <li>
                            <strong>Restaurant :</strong> <?= htmlspecialchars($avis['nomRestau']) ?> <br>
                            <strong>Note :</strong> <?= htmlspecialchars($avis['note']) ?>/5 <br>
                            <em><?= nl2br(htmlspecialchars($avis['texteAvis'])) ?></em> <br>
                            <small>Posté le : <?= date('d/m/Y', $avis['datePoste']) ?></small> <br>

                            <!-- Formulaire pour supprimer l'avis -->
                            <form method="POST">
                                <input type="hidden" name="idAvis" value="<?= $avis['idAvis'] ?>">
                                <button type="submit" name="supprimerAvis" class="delete-btn">🗑️ Supprimer</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Pagination -->
                <div class="pagination">
                    <?php if ($pageAvis > 1): ?>
                        <a href="?pageAvis=<?= $pageAvis - 1 ?>">← Précédent</a>
                    <?php endif; ?>

                    <span>Page <?= $pageAvis ?> sur <?= $totalPagesAvis ?></span>

                    <?php if ($pageAvis < $totalPagesAvis): ?>
                        <a href="?pageAvis=<?= $pageAvis + 1 ?>">Suivant →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </aside>
    </main>
    <?= include 'footer.php'; ?>
</body>
</html>
