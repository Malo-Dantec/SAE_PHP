<?php
declare(strict_types=1);

namespace App\Config;





use PDO;
use PDOException;

class Database {
    private static ?PDO $pdo = null; // Utilisation d'une propriété statique pour stocker la connexion

    public static function getConnection(): PDO {
<<<<<<< HEAD:config/database.php
        if (self::$pdo === null) { // Vérifie si la connexion existe déjà
            try {
                self::$pdo = new PDO('sqlite:' . __DIR__ . '/database.db'); 
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
=======
        try {
            // Connexion SQLite (vous pouvez remplacer par MySQL si besoin)
            $pdo = new PDO('sqlite:' . 'Data/database.db'); // Assurez-vous que le fichier `database.db` existe dans `/Data`.
            
            // Activer le mode d'exception pour les erreurs
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $pdo;
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
>>>>>>> develop:Classes/Config/Database.php
        }
        return self::$pdo; // Retourne la connexion existante
    }
}

?>
