<?php



namespace Classes\Config;

require_once __DIR__ . '/../../vendor/autoload.php';
use Classes\Config\Database;

if ($argc < 2) {
    die("Le chemin de la base de données (path) doit être spécifié en argument.\n");
}

// Récupérez l'argument passé (le chemin)
Database::$path = $argv[1]; // $argv[1] contient le premier argument passé


// Connexion à la base de données
$db = Database::getConnection();
// Charger le fichier JSON
$jsonFile = 'Data/restaurants_orleans.json';
$jsonData = file_get_contents($jsonFile);
$restaurants = json_decode($jsonData, true);

if (!$restaurants) {
    die("Erreur de chargement du fichier JSON.");
}

// Préparer la requête SQL
$query = "INSERT INTO RESTAURANT (idRestau, typeRestau, nomRestau, heureOuverture, siret, numTel, codeCommune, nomCommune, codeRegion, nomRegion, codeDepartement, nomDepartement, latitude, longitude) 
          VALUES (:idRestau, :typeRestau, :nomRestau, :heureOuverture, :siret, :numTel, :codeCommune, :nomCommune, :codeRegion, :nomRegion, :codeDepartement, :nomDepartement, :latitude, :longitude)";

$stmt = $db->prepare($query);

foreach ($restaurants as $resto) {
    // Nettoyage des données
    $idRestau = isset($resto['idRestau']) ? preg_replace('/\D/', '', $resto['idRestau']) : null; // Retirer les lettres de osm_id
    $siret = isset($resto['siret']) ? (is_numeric($resto['siret']) ? $resto['siret'] : null) : null;
    $numTel = isset($resto['phone']) ? preg_replace('/\D/', '', $resto['phone']) : null; // Retirer les caractères non numériques

    try {
        $stmt->execute([
            ':idRestau'       => $idRestau,
            ':typeRestau'     => $resto['type'] ?? null,
            ':nomRestau'      => $resto['name'] ?? 'Nom inconnu',
            ':heureOuverture' => $resto['opening_hours'] ?? null,
            ':siret'          => $siret,
            ':numTel'         => $numTel,
            ':codeCommune'    => $resto['code_commune'] ?? null,
            ':nomCommune'     => $resto['com_nom'] ?? null,
            ':codeRegion'     => $resto['code_region'] ?? null,
            ':nomRegion'      => $resto['region'] ?? null,
            ':codeDepartement'=> $resto['code_departement'] ?? null,
            ':nomDepartement' => $resto['departement'] ?? null,
            ':latitude'       => $resto['geo_point_2d']["lat"] ?? null,
            ':longitude'       => $resto['geo_point_2d']["lon"] ?? null
        ]);
    } catch (\PDOException $e) {
        echo "Erreur lors de l'insertion de '{$resto['name']}' : " . $e->getMessage() . "\n";
    }
}

echo "Importation réussie !";
?>
