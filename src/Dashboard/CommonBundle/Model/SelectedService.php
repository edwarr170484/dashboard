<?php
namespace Dashboard\CommonBundle\Model;

class SelectedService
{
    private $product;
    private $service;
    private $price;
    private $bill;
    
    public function getBill(){
        return $this->bill;
    }

    public function setBill($bill){
        $this->bill = $bill;
    }
    
    public function getProduct(){
        return $this->product;
    }

    public function setProduct($product){
        $this->product = $product;
    }
    
    public function getService(){
        return $this->service;
    }

    public function setService($service){
        $this->service = $service;
    }
    
    public function getPrice(){
        return $this->price;
    }

    public function setPrice($price){
        $this->price = $price;
    }
}

