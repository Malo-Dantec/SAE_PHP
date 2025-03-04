<?php
declare(strict_types=1);

namespace App\Model;

use PDO;
use Exception;

class User {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Créer un nouvel utilisateur dans la base de données.
     */
    public function create(string $email, string $password): bool {
        // Hachage du mot de passe pour la sécurité
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insérer l'utilisateur dans la base de données
        $stmt = $this->db->prepare('INSERT INTO USER (email, password) VALUES (:email, :password)');
        return $stmt->execute(['email' => $email, 'password' => $hashedPassword]);
    }

    /**
     * Trouver un utilisateur par email.
     */
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare('SELECT * FROM USER WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Vérifier si un mot de passe est correct pour un utilisateur donné.
     */
    public function verifyPassword(string $email, string $password): bool {
        $user = $this->findByEmail($email);

        if (!$user) {
            return false; // Utilisateur non trouvé
        }

        return password_verify($password, $user['password']);
    }

    /**
     * Supprimer un utilisateur par email.
     */
    public function deleteByEmail(string $email): bool {
        $stmt = $this->db->prepare('DELETE FROM USER WHERE email = :email');
        return $stmt->execute(['email' => $email]);
    }

    /**
     * Récupérer tous les utilisateurs.
     */
    public function findAll(): array {
        $stmt = $this->db->query('SELECT * FROM USER');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
