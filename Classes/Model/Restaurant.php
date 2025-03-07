<?php
namespace Classes\Model;

use Classes\Config\Database;
use Classes\Provider\DataLoaderJson;

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
    private string $osm_edit;
    


    private int $id_restaurant;

    public function __construct(
        PDO $db,
        int $idRestau,
        ?string $typeRestau,  
        string $nomRestau, 
        ?string $heureOuverture, 
        ?int $siret, 
        ?int $numTel, 
        int $codeCommune, 
        string $nomCommune,
        int $codeRegion, 
        string $nomRegion,
        int $codeDepartement, 
        string $nomDepartement, 
        string $osm_edit
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





    public function addToBd(): void {
        $stmt = $this->db->prepare("
            INSERT INTO RESTAURANT (
                idRestau, typeRestau, nomRestau, heureOuverture, siret, numTel, codeCommune, nomCommune,
                codeRegion, nomRegion,  codeDepartement, nomDepartement, osm_edit
            ) VALUES (
                :idRestau, :typeRestau, :nomRestau, :heureOuverture, :siret, :numTel, :codeCommune, :nomCommune,
                :codeRegion, :nomRegion,  :codeDepartement, :nomDepartement, :osm_edit
            )
        ");
    
        $stmt->execute([
            ':idRestau' => $this->idRestau,
            ':typeRestau' => $this->typeRestau, 
            ':nomRestau' => $this->nomRestau, 
            ':heureOuverture' => $this->heureOuverture, 
            ':siret' => $this->siret, 
            ':numTel' => $this->numTel, 
            ':codeCommune' => $this->codeCommune, 
            ':nomCommune' => $this->nomCommune,
            ':codeRegion' => $this->codeRegion, 
            ':nomRegion' => $this->nomRegion,
            ':codeDepartement' => $this->codeDepartement, 
            ':nomDepartement' => $this->nomDepartement, 
            ':osm_edit' => $this->osm_edit
        ]);
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

    public static function addJson(string $path, PDO $db){
        $loader = new DataLoaderJson($path);
        $loader->jsonToData($db);
    }
}
?>
