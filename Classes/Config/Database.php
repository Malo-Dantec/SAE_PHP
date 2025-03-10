<?php
declare(strict_types=1);

namespace Classes\Config;


use PDO;
use PDOException;

class Database {
    private static ?PDO $pdo = null; // Utilisation d'une propriété statique pour stocker la connexion
    public static string $path = "../Data/database.db";
    public static function getConnection(): PDO {
        if (self::$pdo === null) { // Vérifie si la connexion existe déjà
            try {
               self::$pdo = new PDO('sqlite:' . Database::$path); 
               self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return self::$pdo; // Retourne la connexion existante
    }
}

?>
