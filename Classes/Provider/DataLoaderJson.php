<?php

namespace Provider;

use App\Model\Restaurant;

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

    public function jsonToData(): bool {
        $json = $this->getData();
        foreach ($json as $restau) {
            new Restaurant($restau, );
        }
        //var_dump($json[0]["type"]);
        return true;
    }
}
