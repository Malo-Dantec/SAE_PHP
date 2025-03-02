<?php
namespace Model;

require_once __DIR__ . '/../../Config/Database.php';
use Config\Database;

use PDO;
use Exception;

class Restaurant {
    public static function getAll() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM RESTAURANT");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM RESTAURANT WHERE idRestau = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
