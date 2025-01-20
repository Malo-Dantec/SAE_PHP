<?php 
declare(strict_types=1);

namespace Provider;

final class DataLoaderJson extends DataLoader {
    public function __construct(string $source) {
        $content = file_get_contents($source);
        $this->data = json_decode($content, true);
        if(empty($this->data)) {
            throw new \Exception(sprintf("No data in \s"));
        }
    }
}



?>