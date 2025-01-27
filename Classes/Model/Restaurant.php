<?php

namespace Model;

class Restaurant{
    private array $restaurants;

    public function __construct(array $restaurants){
        $this->restaurants = $restaurants;
        
    }

    public function getRestaurnantsByName(string $nom){
        $restaurantsNom = [];
        
        foreach ($this->restaurants as $restaurant){
            
            if (strpos($restaurant["name"], $nom)){
                $restaurantsNom[] = $restaurant;
            }
        }
        return $restaurantsNom;
    }


    public function getRestaurnantsByType(string $type){
        $restaurantsType = [];
        
        foreach ($this->restaurants as $restaurant){
           
            if (strpos($restaurant["type"], $type)){
                $restaurantsType[] = $restaurant["name"];
            }
        }
        return $restaurantsType;
    }
}
