<?php

namespace Provider;

class DataLoaderJson {
    private string $filePath;

    public function __construct(string $filePath) {
        $fullPath = realpath(__DIR__ . "/.." . $filePath);
        var_dump(file_exists($filePath));
        if (!file_exists($fullPath)) {
            var_dump($fullPath);
            throw new \Exception("⚠️ Fichier JSON introuvable : " . $fullPath);
        }        
    }

    public function getData(): array {
        $json = file_get_contents($this->filePath);
        return json_decode($json, true) ?? [];
    }
}
