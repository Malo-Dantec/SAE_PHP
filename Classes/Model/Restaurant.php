<?php
namespace App\Model;

use App\Config\Database;

use PDO;
use Exception;

class Restaurant {

    private PDO $db;
    private ?string $siret;
    private ?string $type;
    private string $name;
    private ?string $brand;
    private ?string $opening_hours;
    private ?string $phone;
    private int $code_commune;
    private string $commune;
    private int $code_region;
    private string $region;
    private int $code_departement;
    private string $departement;
    private ?string $longitude;
    private ?string $latitude;
    private ?string $osm_id;
    private ?string $wikidata;
    private ?string $brand_wikidata;
    private ?string $website;
    private ?string $facebook;
    private ?string $com_insee;
    private ?string $osm_edit;
    private ?string $operator;


    private int $id_restaurant;

    public function __construct(
        PDO $db,
        ?string $siret,
        ?string $type,
        string $name,
        ?string $brand,
        ?string $opening_hours,
        ?string $phone,
        int $code_commune,
        string $commune,
        int $code_region,
        string $region,
        int $code_departement,
        string $departement,
        ?string $longitude,
        ?string $latitude,
        ?string $osm_id,
        ?string $wikidata,
        ?string $brand_wikidata,
        ?string $website,
        ?string $facebook,
        ?string $com_insee,
        ?string $osm_edit,
        ?string $operator
    ) {
        $this->db = $db;
        $this->siret = $siret;
        $this->type = $type;
        $this->name = $name;
        $this->brand = $brand;
        $this->opening_hours = $opening_hours;
        $this->phone = $phone;
        $this->code_commune = $code_commune;
        $this->commune = $commune;
        $this->code_region = $code_region;
        $this->region = $region;
        $this->code_departement = $code_departement;
        $this->departement = $departement;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->osm_id = $osm_id;
        $this->wikidata = $wikidata;
        $this->brand_wikidata = $brand_wikidata;
        $this->website = $website;
        $this->facebook = $facebook;
        $this->com_insee = $com_insee;
        $this->osm_edit = $osm_edit;
        $this->operator = $operator;

    }



    public function addToBd(): void {
        $stmt = $this->db->prepare("
            INSERT INTO RESTAURANT (
                siret, type, name, brand, opening_hours, phone, code_commune, commune,
                code_region, region, code_departement, departement, longitude, latitude,
                osm_id, wikidata, brand_wikidata, website, facebook, com_insee, osm_edit, operator
            ) VALUES (
                :siret, :type, :name, :brand, :opening_hours, :phone, :code_commune, :commune,
                :code_region, :region, :code_departement, :departement, :longitude, :latitude,
                :osm_id, :wikidata, :brand_wikidata, :website, :facebook, :com_insee, :osm_edit, :operator
            )
        ");
    
        $stmt->execute([
            ':siret' => $this->siret,
            ':type' => $this->type,
            ':name' => $this->name,
            ':brand' => $this->brand,
            ':opening_hours' => $this->opening_hours,
            ':phone' => $this->phone,
            ':code_commune' => $this->code_commune,
            ':commune' => $this->commune,
            ':code_region' => $this->code_region,
            ':region' => $this->region,
            ':code_departement' => $this->code_departement,
            ':departement' => $this->departement,
            ':longitude' => $this->longitude,
            ':latitude' => $this->latitude,
            ':osm_id' => $this->osm_id,
            ':wikidata' => $this->wikidata,
            ':brand_wikidata' => $this->brand_wikidata,
            ':website' => $this->website,
            ':facebook' => $this->facebook,
            ':com_insee' => $this->com_insee,
            ':osm_edit' => $this->osm_edit,
            ':operator' => $this->operator
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
}
?>
