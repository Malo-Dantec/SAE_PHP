<?php

echo '<header>';
    if (isset($_SESSION['idUser'])) {
        echo "<a href='/logout.php'>Déconnexion</a>";
        echo "<a href='/index.php'>Les restaurants</a>";
        echo "<a href='/Views/favoris.php'>Mes favoris</a>";
    } else {
        echo '<a href="/login.php">Connexion</a>';
        echo '<a href="/register.php">Inscription</a>';
    }
echo '</header>';

?>
