<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start();
use Classes\Config\Database;

$db = Database::getConnection();
$idUser = $_SESSION['idUser'] ?? null;

if (!$idUser) {
    header('Location: /login.php');
    exit;
}

// Email de l'utilisateur
$stmt = $db->prepare("SELECT email FROM USER WHERE idUser = ?");
$stmt->execute([$idUser]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur non trouvé.");
}

$email = htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['supprimerAvis'])) {
    $idAvis = $_POST['idAvis'] ?? null;

    if ($idAvis) {
        // Supprimer l'avis dans la table DONNER (car FK)
        $stmtDelete = $db->prepare("DELETE FROM DONNER WHERE idAvis = ? AND idUser = ?");
        $stmtDelete->execute([$idAvis, $idUser]);

        // Supprimer l'avis dans la table AVIS (seulement si plus utilisé)
        $stmtCheck = $db->prepare("SELECT COUNT(*) FROM DONNER WHERE idAvis = ?");
        $stmtCheck->execute([$idAvis]);
        $count = $stmtCheck->fetchColumn();

        if ($count == 0) {
            $stmtDeleteAvis = $db->prepare("DELETE FROM AVIS WHERE idAvis = ?");
            $stmtDeleteAvis->execute([$idAvis]);
        }

        // Recharger la page pour voir les changements
        header("Location: profil.php?pageAvis=$pageAvis");
        exit;
    }
}


$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ancienMDP = $_POST['ancien_mdp'] ?? '';
    $nouveauMDP = $_POST['nouveau_mdp'] ?? '';
    $memeMDP = $_POST['meme_mdp'] ?? '';

    if (empty($ancienMDP) || empty($nouveauMDP) || empty($memeMDP)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($nouveauMDP !== $memeMDP) {
        $message = "Les nouveaux mots de passe ne correspondent pas.";
    } else {
        // Vérifif de l'ancien mdp
        $stmt = $db->prepare("SELECT password FROM USER WHERE idUser = ?");
        $stmt->execute([$idUser]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($ancienMDP, $userData['password'])) {
            $message = "Ancien mot de passe incorrect.";
        } else {
            // Maj du mot de passe
            $hashedPassword = password_hash($nouveauMDP, PASSWORD_DEFAULT);
            $updateStmt = $db->prepare("UPDATE USER SET password = ? WHERE idUser = ?");
            if ($updateStmt->execute([$hashedPassword, $idUser])) {
                $message = "Mot de passe mis à jour avec succès.";
            } else {
                $message = "Erreur lors de la mise à jour.";
            }
        }
    }
}

// Nombre d'avis par page
$avisParPage = 3;
$pageAvis = isset($_GET['pageAvis']) ? max(1, intval($_GET['pageAvis'])) : 1;
$offsetAvis = ($pageAvis - 1) * $avisParPage;

$sqlAvis = "SELECT a.idAvis, a.note, a.texteAvis, d.datePoste, r.nomRestau
            FROM AVIS a
            JOIN DONNER d ON a.idAvis = d.idAvis
            JOIN RESTAURANT r ON d.idRestau = r.idRestau
            WHERE d.idUser = ?
            ORDER BY d.datePoste DESC
            LIMIT $avisParPage OFFSET $offsetAvis";

$stmtAvis = $db->prepare($sqlAvis);
$stmtAvis->execute([$idUser]);
$avisList = $stmtAvis->fetchAll(PDO::FETCH_ASSOC);

// Pagination
$sqlCountAvis = "SELECT COUNT(*) FROM AVIS a
                 JOIN DONNER d ON a.idAvis = d.idAvis
                 WHERE d.idUser = ?";

$stmtCountAvis = $db->prepare($sqlCountAvis);
$stmtCountAvis->execute([$idUser]);
$totalAvis = $stmtCountAvis->fetchColumn();
$totalPagesAvis = ceil($totalAvis / $avisParPage);
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

        <?php if ($message === "Mot de passe mis à jour avec succès.") : ?>
            <p style="color: green;"><?= htmlspecialchars($message) ?></p>
        <?php else: ?>
            <p style="color: red;"><?= htmlspecialchars($message) ?></p>
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
                            <form method="POST" action="">
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
