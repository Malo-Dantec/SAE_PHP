<?php

class Autoloader {
    static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);   
    }

    static function autoload($fqcn) {
        $path = str_replace('\\', '/', $fqcn) . '.php';
        $file = __DIR__ . '/' . $path;
    }
}
