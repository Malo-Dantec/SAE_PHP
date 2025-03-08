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

// Récupérer l'email de l'utilisateur
$stmt = $db->prepare("SELECT email FROM USER WHERE idUser = ?");
$stmt->execute([$idUser]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur non trouvé.");
}

$email = htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Les nouveaux mots de passe ne correspondent pas.";
    } else {
        // Vérifier l'ancien mot de passe
        $stmt = $db->prepare("SELECT password FROM USER WHERE idUser = ?");
        $stmt->execute([$idUser]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($oldPassword, $userData['password'])) {
            $message = "Ancien mot de passe incorrect.";
        } else {
            // Mettre à jour le mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $db->prepare("UPDATE USER SET password = ? WHERE idUser = ?");
            if ($updateStmt->execute([$hashedPassword, $idUser])) {
                $message = "Mot de passe mis à jour avec succès.";
            } else {
                $message = "Erreur lors de la mise à jour.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="Public/css/header.css">
    <link rel="stylesheet" href="Public/css/main.css">
    <link rel="stylesheet" href="Public/css/footer.css">
</head>
<body>
    <?= include 'header.php'; ?>
    <main>
        <h2>Mon profil</h2>

        <?php if ($message): ?>
            <p style="color: red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= $email ?>" readonly>

            <label for="old_password">Ancien mot de passe :</label>
            <input type="password" id="old_password" name="old_password" required>

            <label for="new_password">Nouveau mot de passe :</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Modifier mon mot de passe</button>
        </form>
    </main>
    <?= include 'footer.php'; ?>
</body>
</html>
