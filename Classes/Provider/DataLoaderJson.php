<?php

namespace Classes\Provider;

use Classes\Model\Restaurant;
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
                $restau['type'],
                $restau['name'],
                $restau['opening_hours'],
                $restau['siret'],
                $restau['phone'],
                $restau['code_commune'],
                $restau['commune'],
                $restau[''],
                $restau['code_region'],
                $restau['code_departement'],
                $restau['departement'],
                $restau['longitude'],
                $restau['latitude']
            );
            $newRestau->addToBd();
        }
        //var_dump($json[0]["type"]);
        return true;
    }
}
