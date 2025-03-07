<?php
declare(strict_types=1);

namespace Provider;


use Provider\DataLoaderInterface;

abstract class DataLoader implements DataLoaderInterface {
    protected array $data = [];

    public function getData(): array {
        return $this->data;
    }
}
?>
