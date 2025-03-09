<?php
declare(strict_types=1);

namespace Classes\Model;

use PDO;
use Exception;

class User {
    private PDO $db;
    private int $avisParPage = 3; //Pour le nombre d'avis dans la page profil

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

    // Récupérer l'email de l'utilisateur
    public function getEmail(int $idUser): ?string {
        $stmt = $this->db->prepare("SELECT email FROM USER WHERE idUser = ?");
        $stmt->execute([$idUser]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user['email'] ?? null;
    }
    
    // Vérifier le mot de passe actuel
    public function checkPassword(int $idUser, string $password): bool {
        $stmt = $this->db->prepare("SELECT password FROM USER WHERE idUser = ?");
        $stmt->execute([$idUser]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return password_verify($password, $user['password']);
    }
    
    // Modifier le mot de passe
    public function updatePassword(int $idUser, string $newPassword): bool {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE USER SET password = ? WHERE idUser = ?");
        return $stmt->execute([$hashedPassword, $idUser]);
    }
    // Récupérer les avis de l'utilisateur avec pagination
    public function getAvis(int $idUser, int $page): array {
        $offset = ($page - 1) * $this->avisParPage;
        $sql = "SELECT a.idAvis, a.note, a.texteAvis, d.datePoste, r.nomRestau
                FROM AVIS a
                JOIN DONNER d ON a.idAvis = d.idAvis
                JOIN RESTAURANT r ON d.idRestau = r.idRestau
                WHERE d.idUser = ?
                ORDER BY d.datePoste DESC
                LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUser, $this->avisParPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Nombre total d'avis pour la pagination
    public function countAvis(int $idUser): int {
        $sql = "SELECT COUNT(*) FROM AVIS a
                JOIN DONNER d ON a.idAvis = d.idAvis
                WHERE d.idUser = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idUser]);
        return (int) $stmt->fetchColumn();
    }
    
    // Supprimer un avis
    public function deleteAvis(int $idUser, int $idAvis): bool {
        $stmtDelete = $this->db->prepare("DELETE FROM DONNER WHERE idAvis = ? AND idUser = ?");
        $stmtDelete->execute([$idAvis, $idUser]);
    
        // Vérifier si l'avis est encore utilisé
        $stmtCheck = $this->db->prepare("SELECT COUNT(*) FROM DONNER WHERE idAvis = ?");
        $stmtCheck->execute([$idAvis]);
        $count = $stmtCheck->fetchColumn();
    
        if ($count == 0) {
            $stmtDeleteAvis = $this->db->prepare("DELETE FROM AVIS WHERE idAvis = ?");
            return $stmtDeleteAvis->execute([$idAvis]);
        }
    
        return true;
    }
        
}
