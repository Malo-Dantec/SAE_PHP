<?php
session_start(); // Démarrer la session si elle n'est pas active

// Supprimer toutes les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Rediriger vers la page d'accueil ou de connexion
header("Location: index.php");
exit;
