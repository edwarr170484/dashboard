<?php
namespace Dashboard\CommonBundle\Model;

class AdvertImage
{
    private $name;
    private $sortorder;
    
    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }
    
    public function getSortorder(){
        return $this->sortorder;
    }

    public function setSortorder($sortorder){
        $this->sortorder = $sortorder;
    }
}

