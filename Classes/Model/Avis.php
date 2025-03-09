<?php

namespace Classes\Model;

use PDO;

class Avis {
    private PDO $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ajouter un avis
    public function ajouter_avis($idRestau, $idUser, $note, $texteAvis) {
        $sql = "INSERT INTO AVIS (note, texteAvis) VALUES (:note, :texteAvis)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['note' => $note, 'texteAvis' => $texteAvis]);
        $idAvis = $this->db->lastInsertId(); // Récupère l'id de l'avis récemment inséré

        // Lier l'avis au restaurant et à l'utilisateur dans la table DONNER
        $sql = "INSERT INTO DONNER (idAvis, idUser, idRestau, datePoste) VALUES (:idAvis, :idUser, :idRestau, :datePoste)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'idAvis' => $idAvis,
            'idUser' => $idUser,
            'idRestau' => $idRestau,
            'datePoste' => time() // Date actuelle
        ]);
    }

    // Récupérer les avis pour un restaurant
    public function get_avis_restaurant($idRestau) {
        $sql = "SELECT A.idAvis, A.note, A.texteAvis, U.email
                FROM AVIS A
                JOIN DONNER D ON A.idAvis = D.idAvis
                JOIN USER U ON D.idUser = U.idUser
                WHERE D.idRestau = :idRestau";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['idRestau' => $idRestau]);
        return $stmt->fetchAll();
    }

    // Vérifier si un utilisateur a déjà donné un avis pour un restaurant
    public function has_given_avis($idRestau, $idUser) {
        $sql = "SELECT COUNT(*) FROM DONNER WHERE idRestau = :idRestau AND idUser = :idUser";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['idRestau' => $idRestau, 'idUser' => $idUser]);
        return $stmt->fetchColumn() > 0;
    }

   // Supprimer un avis
   public function deleteAvis(int $idAvis): bool {

    $stmtDelete = $this->db->prepare("DELETE FROM DONNER WHERE idAvis = ?");
    $stmtDelete->execute([$idAvis]);
    
    $stmtDeleteAvis = $this->db->prepare("DELETE FROM AVIS WHERE idAvis = ?");
    return $stmtDeleteAvis->execute([$idAvis]);

    }
}
 