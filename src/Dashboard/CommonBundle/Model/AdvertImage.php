<?php
namespace Dashboard\CommonBundle\Model;

class AdvertImage
{
    private $name;
    
    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }
}

