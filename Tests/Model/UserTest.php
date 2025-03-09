<?php

use PHPUnit\Framework\TestCase;
use Classes\Model\User;
use Classes\Config\Database;

Database::$path = "Tests/Data/test_db.db";

class UserTest extends TestCase
{
    private PDO $db;
    private User $userModel;
    
    protected function setUp(): void
    {
        $this->db = Database::getConnection();
        $this->userModel = new User($this->db);

        // Nettoyage avant chaque test
        $this->db->exec("DELETE FROM USER");
        $this->db->exec("DELETE FROM sqlite_sequence WHERE name='USER'");
    }

    public function testCreateUser()
    {
        $email = "test@example.com";
        $password = "password123";

        $result = $this->userModel->create($email, $password);
        $this->assertTrue($result);

        $user = $this->userModel->findByEmail($email);
        $this->assertNotNull($user);
        $this->assertEquals($email, $user['email']);
        $this->assertTrue(password_verify($password, $user['password']));
    }

    public function testFindByEmail()
    {
        $email = "findme@example.com";
        $password = "securePass";
        $this->userModel->create($email, $password);

        $user = $this->userModel->findByEmail($email);
        $this->assertNotNull($user);
        $this->assertEquals($email, $user['email']);
    }

    public function testFindByEmailNotFound()
    {
        $user = $this->userModel->findByEmail("notfound@example.com");
        $this->assertNull($user);
    }

    public function testVerifyPassword()
    {
        $email = "passwordtest@example.com";
        $password = "mypassword";
        $this->userModel->create($email, $password);

        $this->assertTrue($this->userModel->verifyPassword($email, $password));
        $this->assertFalse($this->userModel->verifyPassword($email, "wrongpassword"));
        $this->assertFalse($this->userModel->verifyPassword("pasDansLaBD@example.com", "test"));

    }

    public function testDeleteUser()
    {
        $email = "delete@example.com";
        $password = "toDelete";
        $this->userModel->create($email, $password);

        $userBefore = $this->userModel->findByEmail($email);
        $this->assertNotNull($userBefore);

        $result = $this->userModel->deleteByEmail($email);
        $this->assertTrue($result);

        $userAfter = $this->userModel->findByEmail($email);
        $this->assertNull($userAfter);
    }

    public function testFindAll()
    {
        $this->userModel->create("user1@example.com", "pass1");
        $this->userModel->create("user2@example.com", "pass2");

        $users = $this->userModel->findAll();
        $this->assertCount(2, $users);
    }

    public function testGetEmail()
    {
        $email = "testemail@example.com";
        $password = "password123";
        $this->userModel->create($email, $password);

        $user = $this->userModel->findByEmail($email);
        $this->assertNotNull($user);

        $retrievedEmail = $this->userModel->getEmail($user['idUser']);
        $this->assertEquals($email, $retrievedEmail);
    }

    public function testCheckPassword()
    {
        $email = "checkpass@example.com";
        $password = "securePass";
        $this->userModel->create($email, $password);

        $user = $this->userModel->findByEmail($email);
        $this->assertNotNull($user);

        $this->assertTrue($this->userModel->checkPassword($user['idUser'], $password));
        $this->assertFalse($this->userModel->checkPassword($user['idUser'], "wrongpass"));
    }

    public function testUpdatePassword()
    {
        $email = "updatepass@example.com";
        $password = "oldPass";
        $newPassword = "newPass123";
        $this->userModel->create($email, $password);

        $user = $this->userModel->findByEmail($email);
        $this->assertNotNull($user);

        $this->assertTrue($this->userModel->updatePassword($user['idUser'], $newPassword));
        $this->assertTrue($this->userModel->checkPassword($user['idUser'], $newPassword));
        $this->assertFalse($this->userModel->checkPassword($user['idUser'], $password));
    }

    public function testGetAvis()
    {
        $email = "avisuser@example.com";
        $password = "test123";
        $this->userModel->create($email, $password);
        $user = $this->userModel->findByEmail($email);
        var_dump($this->userModel->getAvis($user['idUser'], 1));
        // Insérer des avis manuellement pour tester
        $this->db->exec("INSERT INTO AVIS (note, texteAvis) VALUES (1, 5, 'Très bon')");
        $this->db->exec("INSERT INTO DONNER (idUser, idAvis, idRestau, datePoste) VALUES ({$user['idUser']}, 1, 1, '2024-03-09')");

        $avis = $this->userModel->getAvis($user['idUser'], 1);
        $this->assertCount(1, $avis);
        $this->assertEquals('Très bon', $avis[0]['texteAvis']);
    }

    public function testCountAvis()
    {
        $email = "countavis@example.com";
        $password = "testpass";
        $this->userModel->create($email, $password);
        $user = $this->userModel->findByEmail($email);

        $this->db->exec("INSERT INTO AVIS (idAvis, note, texteAvis) VALUES (10, 4, 'Bien')");
        $this->db->exec("INSERT INTO AVIS (idAvis, note, texteAvis) VALUES (20, 3, 'Moyen')");
        $this->db->exec("INSERT INTO DONNER (idUser, idAvis, idRestau, datePoste) VALUES ({$user['idUser']}, 10, 1, '2024-03-09')");
        $this->db->exec("INSERT INTO DONNER (idUser, idAvis, idRestau, datePoste) VALUES ({$user['idUser']}, 20, 1, '2024-03-09')");

        $avisCount = $this->userModel->countAvis($user['idUser']);
        $this->assertEquals(2, $avisCount);
    }

    public function testDeleteAvis()
    {
        $email = "deleteavis@example.com";
        $password = "deletepass";
        $this->userModel->create($email, $password);
        $user = $this->userModel->findByEmail($email);

        $this->db->exec("INSERT INTO AVIS (idAvis, note, texteAvis) VALUES (100, 5, 'Super')");
        $this->db->exec("INSERT INTO DONNER (idUser, idAvis, idRestau, datePoste) VALUES ({$user['idUser']}, 100, 1, '2024-03-09')");

        $this->assertEquals(1, $this->userModel->countAvis($user['idUser']));

        $this->assertTrue($this->userModel->deleteAvis($user['idUser'], 1));

        $this->assertEquals(0, $this->userModel->countAvis($user['idUser']));
    }

}
