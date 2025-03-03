<?php 
namespace Model;

use PDO;

class Favoris {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function ajouter_favoris($idRestau, $idUser) {
        $sql = "insert into FAVORIS (idRestau, idUser) values (:idRestau, :idUser)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['idRestau' => $idRestau, 'idUser' => $idUser]);
    }

    public function supprimer_favoris($idRestau, $idUser) {
        $sql = "delete from FAVORIS where idRestau = :idRestau and idUser = :idUser";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['idRestau' => $idRestau, 'idUser' => $idUser]);
    }

    public function est_favoris($idRestau, $idUser) {
        $sql = "select count(*) from FAVORIS where idRestau = :idRestau and idUser = :idUser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idRestau' => $idRestau, 'idUser' => $idUser]);
        return $stmt->fetchColumn() > 0;
    }

    public function get_favoris($idUser) {
        $sql = "select R.idRestau, R.nom from RESTAURANT R join FAVORIS Fon R.idRestau = F.idRestau where F.idUser = :idUser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idUser' => $idUser]);
        return $stmt->fetchAll();
    }

    
}

?>
