<?php
namespace Dashboard\CommonBundle\Model;

class SelectedSalon
{
    private $salon;
    private $rate;
    private $price;
    private $bill;
    
    public function getSalon(){
        return $this->salon;
    }
    
    public function setSalon($salon){
        $this->salon = $salon;
    }
    
    public function getRate(){
        return $this->rate;
    }
    
    public function setRate($rate){
        $this->rate = $rate;
    }
    
    public function getPrice(){
        return $this->price;
    }
    
    public function setPrice($price){
        $this->price = $price;
    }
    
    public function getBill(){
        return $this->bill;
    }

    public function setBill($bill){
        $this->bill = $bill;
    }
}

