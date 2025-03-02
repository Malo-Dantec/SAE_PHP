<?php

use PHPUnit\Framework\TestCase;
use App\Model\User;
use PDO;

class UserTest extends TestCase
{
    public function testFindByEmail()
    {
        // Créer un mock de la connexion PDO
        $pdoMock = $this->createMock(PDO::class);
        
        // Simuler le comportement de `prepare` pour renvoyer un mock de `PDOStatement`
        $pdoStatementMock = $this->createMock(PDOStatement::class);
        $pdoMock->method('prepare')->willReturn($pdoStatementMock);

        // Simuler l'exécution de la requête et la récupération d'un résultat
        $pdoStatementMock->method('execute')->willReturn(true);
        $pdoStatementMock->method('fetch')->willReturn(['email' => 'test@gmail.com', 'password' => 'hashedPassword']);

        // Créer une instance de User avec le mock de PDO
        $user = new User($pdoMock);

        // Appeler la méthode `findByEmail`
        $result = $user->findByEmail('test@gmail.com');

        // Vérifier que l'email retourné est correct
        $this->assertNotNull($result);
        $this->assertEquals('test@gmail.com', $result['email']);
    }
}
