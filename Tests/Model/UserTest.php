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
}
