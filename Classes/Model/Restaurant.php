<?php
namespace App\Model;

require_once __DIR__ . '/../../Config/Database.php';
use Config\Database;

use PDO;
use Exception;

class Restaurant {

    private PDO $db;
    private int $idRestau;
    private ?string $typeRestau;
    private string $nomRestau;
    private ?string $heureOuverture;
    private ?int $siret;
    private ?int $numTel;
    private int $codeCommune;
    private string $nomCommune;
    private int $codeRegion;
    private string $nomRegion;
    private int $codeDepartement;
    private string $nomDepartement;
    private ?string $osm_edit;

    public function __construct(
        PDO $db,
        int $idRestau,
        ?string $typeRestau = null,
        string $nomRestau,
        ?string $heureOuverture = null,
        ?int $siret = null,
        ?int $numTel = null,
        int $codeCommune,
        string $nomCommune,
        int $codeRegion,
        string $nomRegion,
        int $codeDepartement,
        string $nomDepartement,
        ?string $osm_edit = null
    ) {
        $this->db = $db;
        $this->idRestau = $idRestau;
        $this->typeRestau = $typeRestau;
        $this->nomRestau = $nomRestau;
        $this->heureOuverture = $heureOuverture;
        $this->siret = $siret;
        $this->numTel = $numTel;
        $this->codeCommune = $codeCommune;
        $this->nomCommune = $nomCommune;
        $this->codeRegion = $codeRegion;
        $this->nomRegion = $nomRegion;
        $this->codeDepartement = $codeDepartement;
        $this->nomDepartement = $nomDepartement;
        $this->osm_edit = $osm_edit;
    }


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
