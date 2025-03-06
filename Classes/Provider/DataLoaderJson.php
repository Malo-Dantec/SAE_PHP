<?php

namespace App\Provider;

use App\Model\Restaurant;
use PDO;

class DataLoaderJson {
    private string $filePath;

    public function __construct(string $filePath) {
        $fullPath = realpath($filePath);
        $this->filePath = $fullPath;
        if (!file_exists($fullPath)) {
            var_dump($fullPath);
            throw new \Exception(":warning: Fichier JSON introuvable : " . $fullPath);
        }        
    }

    public function getData(): array {
        $json = file_get_contents($this->filePath);
        return json_decode($json, true) ?? [];
    }

    public function jsonToData(PDO $db): bool {
        $json = $this->getData();
        foreach ($json as $restau) {
            $newRestau = new Restaurant(
                $db,
                $restau["siret"],
                $restau["type"] ?? null,
                $restau["name"],
                $restau["brand"] ?? null,
                $restau["opening_hours"] ?? null,
                $restau["phone"] ?? null,
                $restau["code_commune"],
                $restau["commune"],
                $restau["code_region"],
                $restau["region"],
                $restau["code_departement"],
                $restau["departement"],
                $restau["longitude"] ?? null,
                $restau["latitude"] ?? null,
                $restau["osm_id"] ?? null,
                $restau["wikidata"] ?? null,
                $restau["brand_wikidata"] ?? null,
                $restau["website"] ?? null,
                $restau["facebook"] ?? null,
                $restau["com_insee"] ?? null,
                $restau["osm_edit"] ?? null,
                $restau["operator"] ?? null
            );
            $newRestau->addToBd();
        }
        //var_dump($json[0]["type"]);
        return true;
    }
}
