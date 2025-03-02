<?php

echo '<header>';
    if (isset($_SESSION['email'])) {
        echo "<a href='logout.php'>Déconnexion</a>";
    } else {
        echo '<a href="login.php">Connexion</a>';
        echo '<a href="register.php">Inscription</a>';
    }
echo '</header>';

?>
