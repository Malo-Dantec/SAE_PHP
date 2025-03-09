<?php

use PHPUnit\Framework\TestCase;
use Classes\Config\Database;

class DatabaseTest extends TestCase
{
    public function testGetConnectionReturnsPDOInstance()
    {
        $pdo = Database::getConnection();
        $this->assertInstanceOf(PDO::class, $pdo);
    }

    public function testGetConnectionIsSingleton()
    {
        $pdo1 = Database::getConnection();
        $pdo2 = Database::getConnection();

        $this->assertSame($pdo1, $pdo2, "Database::getConnection() doit toujours retourner la même instance.");
    }

    public function testConnectionError()
    {
        // Sauvegarde le chemin original
        $originalPath = Database::$path;
        
        // Définit un chemin invalide
        Database::$path = "/chemin/invalide/db.db";
    
        try {
            $pdo = Database::getConnection();
            $this->fail("Erreur de connexion");
        } catch (Exception $e) {
            $this->assertStringContainsString("Erreur de connexion", $e->getMessage());
        } finally {
            // Restaure le chemin original après le test
            Database::$path = $originalPath;
        }
    }
    
}
