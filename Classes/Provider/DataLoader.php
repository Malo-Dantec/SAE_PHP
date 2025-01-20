<?php
declare(strict_types=1);

namespace Provider;

abstract class DataLoader implements DataLoaderInterface {
    protected $data = [];

    public function getData():array {
        return $this->data;
    }
}


?>