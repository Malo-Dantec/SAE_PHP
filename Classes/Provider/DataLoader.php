<?php
declare(strict_types=1);

namespace Classes\Provider;


use Classes\Provider\DataLoaderInterface;

abstract class DataLoader implements DataLoaderInterface {
    protected array $data = [];

    public function getData(): array {
        return $this->data;
    }
}
?>
