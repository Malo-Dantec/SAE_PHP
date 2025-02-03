<?php

declare(strict_types=1);
require 'Tests/Tests.php';



use \PHPUnit\Framework\TestCase;
use Model\User;


class UserTest extends TestCase {
    private PDO $pdo;
    private User $user;
    private PDOStatement $stmt;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->user = new User($this->pdo);
    }

    public function testCreateUser(): void {
        $this->stmt->method('execute')->willReturn(true);
        
        $resultTrue = $this->user->create('test@example.com', 'password123');
        $this->assertTrue($resultTrue);
    }

    public function testFindByEmailReturnsUser(): void {
        $this->stmt->method('execute');
        //$this->stmt->method('fetch')->willReturn(['email' => 'test@example.com', 'password' => 'hashedpassword']);
        $this->user->create('test@example.com', 'password123');
        $result = $this->user->findByEmail('test@example.com');
        
        $this->assertIsArray($result);
        $this->assertEquals('test@example.com', $result['email']);
    }

    public function testFindByEmailReturnsNullWhenUserNotFound(): void {
        $this->stmt->expects($this->once())->method('execute');
        $this->stmt->method('fetch')->willReturn(false);
        
        $result = $this->user->findByEmail('nonexistent@example.com');
        
        $this->assertNull($result);
    }

    public function testVerifyPasswordReturnsTrue(): void {
        $hashedPassword = password_hash('password123', PASSWORD_BCRYPT);
        
        $this->stmt->expects($this->once())->method('execute');
        $this->stmt->method('fetch')->willReturn(['email' => 'test@example.com', 'password' => $hashedPassword]);
        
        $result = $this->user->verifyPassword('test@example.com', 'password123');
        
        $this->assertTrue($result);
    }

    public function testVerifyPasswordReturnsFalseWhenIncorrect(): void {
        
        $this->stmt->expects($this->once())->method('execute');
        $this->stmt->method('fetch')->willReturn(['email' => 'test@example.com', 'password' => "password123"]);
        
        $result = $this->user->verifyPassword('test@example.com', 'wrongpassword');
        
        $this->assertFalse($result);
    }

    public function testDeleteByEmail(): void {
        $this->stmt->expects($this->once())->method('execute')->willReturn(true);
        
        $result = $this->user->deleteByEmail('test@example.com');
        
        $this->assertTrue($result);
    }

    public function testFindAllReturnsUsers(): void {
        $this->pdo->method('query')->willReturn($this->stmt);
        $this->stmt->method('fetchAll')->willReturn([
            ['email' => 'user1@example.com', 'password' => 'pass1'],
            ['email' => 'user2@example.com', 'password' => 'pass2']
        ]);
        
        $result = $this->user->findAll();
        
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('user1@example.com', $result[0]['email']);
    }
}
