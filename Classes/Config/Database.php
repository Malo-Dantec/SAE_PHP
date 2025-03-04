<?php
declare(strict_types=1);

namespace App\Config;





use PDO;
use PDOException;

class Database {
    public static function getConnection(): PDO {
        try {
            // Connexion SQLite (vous pouvez remplacer par MySQL si besoin)
            $pdo = new PDO('sqlite:' . 'Data/database.db'); // Assurez-vous que le fichier `database.db` existe dans `/Data`.
            
            // Activer le mode d'exception pour les erreurs
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $pdo;
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}
