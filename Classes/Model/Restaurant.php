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
    private ?string $siret; 
    private ?string $numTel; 
    private int $codeCommune; 
    private string $nomCommune;
    private ?int $codeRegion; 
    private string $nomRegion;
    private int $codeDepartement; 
    private string $nomDepartement; 
    private ?string $longitude;
    private ?string $latitude;
    


    private int $id_restaurant;

    public function __construct(
        PDO $db,
        ?string $typeRestau,  
        string $nomRestau, 
        ?string $heureOuverture, 
        ?string $siret, 
        ?string $numTel, 
        int $codeCommune, 
        string $nomCommune,
        ?int $codeRegion, 
        string $nomRegion,
        int $codeDepartement, 
        string $nomDepartement, 
        ?string $longitude,
        ?string $latitude
    ) {
        $this->db = $db;
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
        $this->longitude = $longitude;
        $this->latitude = $latitude;

    }





    public function addToBd(): void {
        $stmt = $this->db->prepare("
            INSERT INTO RESTAURANT (
                typeRestau, nomRestau, heureOuverture, siret, numTel, codeCommune, nomCommune,
                codeRegion, nomRegion,  codeDepartement, nomDepartement, longitude, latitude
            ) VALUES (
                :typeRestau, :nomRestau, :heureOuverture, :siret, :numTel, :codeCommune, :nomCommune,
                :codeRegion, :nomRegion,  :codeDepartement, :nomDepartement, :longitude, :latitude
            )
        ");
    
        $stmt->execute([
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
            ':longitude' => $this->longitude,
            ':latitude' => $this->latitude,
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

    public static function rmRestau($id) : void
    {
        $pdo = Database::getConnection();
        // Supprimer le restaurant avec l'ID spécifié
        $stmt = $pdo->prepare("DELETE FROM RESTAURANT WHERE idRestau = ?");
        $stmt->execute([$id]);
    }

    public static function addJson(string $path, PDO $db){
        $loader = new DataLoaderJson($path);
        $loader->jsonToData($db);
    }

    public static function searchByNom($search):array{
        $results = [];
        if ($search !== "") {
            foreach (Restaurant::getAll() as $restaurant) {
                if (strpos(strtolower($restaurant["nomRestau"]), strtolower($search)) !== false) {
                    $results[] = $restaurant;
                }
        
            }
        }
        return  $results;
    }

    public static function filterType($restau, $selectedTypes):array{
        if (!empty($selectedTypes)) {
            $restaurants = array_filter($restau, function ($restau) use ($selectedTypes) {
                return in_array($restau['typeRestau'], $selectedTypes);
            });
            return $restaurants;
            
        }
        return $restau;
    }

    

}
?>
