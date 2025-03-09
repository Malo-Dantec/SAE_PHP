<?php

echo '<header>';
    if (isset($_SESSION['idUser'])) {
        echo "<a href='/Views/logout.php'>Déconnexion</a>";
        echo "<a href='/index.php'>Les restaurants</a>";
        echo "<a href='/Views/favoris.php'>Mes favoris</a>";
        echo "<a href='/Views/profil.php'>Profil</a>";
    } else {
        echo '<a href="/Views/login.php">Connexion</a>';
        echo '<a href="/Views/register.php">Inscription</a>';
    }
echo '</header>';

?>
