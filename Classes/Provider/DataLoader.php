<?php
declare(strict_types=1);

namespace App\Provider;

require_once '/DataLoaderInterface.php';
use Provider\DataLoaderInterface;

abstract class DataLoader implements DataLoaderInterface {
    protected array $data = [];

    public function getData(): array {
        return $this->data;
    }
}
?>
