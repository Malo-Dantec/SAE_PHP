<?php


Class Autoloader {

    static function register() {
        // var_dump(__CLASS__);
        spl_autoload_register(array(__CLASS__, 'autoload'));   
    }

    static function autoload($fqcn) {
        // var_dump($fqcn);
        $path = str_replace('\\', '/', $fqcn).'.php';
        require 'Classes/'.$path;
    }

    
}
