<?php
namespace Dashboard\CommonBundle\Model;

class AdvertInfo
{
    private $category;
    private $baseCategory;
    private $year;
    private $board;
    private $generation;
    private $gasType;
    private $gearType;
    private $transmissionType;
    private $modification;
    private $color;
    private $probeg;
    private $condition;
    private $owners;
    private $vin;
    private $description;
    private $price;
    private $nds;
    private $torg;
    private $garant;
    private $contactName;
    private $contactPhone;
    private $contactEmail;
    private $contactCity;
    private $contactCityCode;
    private $servicePack;
    private $services;
    private $rightWheel;
    private $isGas;
    private $step;
    
    public function getStep()
    {
        return $this->step;
    }

    public function setStep($step)
    {
        $this->step = $step;
    }
    
    public function getIsGas()
    {
        return $this->isGas;
    }

    public function setIsGas($isGas)
    {
        $this->isGas = $isGas;
    }
    
    public function getRightWheel()
    {
        return $this->rightWheel;
    }

    public function setRightWheel($rightWheel)
    {
        $this->rightWheel = $rightWheel;
    }
    
    public function getServices()
    {
        return $this->services;
    }

    public function setServices($services)
    {
        $this->services = $services;
    }
    
    public function getServicePack()
    {
        return $this->servicePack;
    }

    public function setServicePack($servicePack)
    {
        $this->servicePack = $servicePack;
    }
    
    public function getBaseCategory()
    {
        return $this->baseCategory;
    }

    public function setBaseCategory($baseCategory)
    {
        $this->baseCategory = $baseCategory;
    }
    
    public function getContactCityCode()
    {
        return $this->contactCityCode;
    }

    public function setContactCityCode($contactCityCode)
    {
        $this->contactCityCode = $contactCityCode;
    }
    
    public function getContactCity()
    {
        return $this->contactCity;
    }

    public function setContactCity($contactCity)
    {
        $this->contactCity = $contactCity;
    }
    
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
    }
    
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
    }
    
    public function getContactName()
    {
        return $this->contactName;
    }

    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
    }
    
    public function getGarant()
    {
        return $this->garant;
    }

    public function setGarant($garant)
    {
        $this->garant = $garant;
    }
    
    public function getTorg()
    {
        return $this->torg;
    }

    public function setTorg($torg)
    {
        $this->torg = $torg;
    }
    
    public function getNds()
    {
        return $this->nds;
    }

    public function setNds($nds)
    {
        $this->nds = $nds;
    }
    
    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    public function getVin()
    {
        return $this->vin;
    }

    public function setVin($vin)
    {
        $this->vin = $vin;
    }
    
    public function getOwners()
    {
        return $this->owners;
    }

    public function setOwners($owners)
    {
        $this->owners = $owners;
    }
    
    public function getCondition()
    {
        return $this->condition;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
    }
    
    public function getProbeg()
    {
        return $this->probeg;
    }

    public function setProbeg($probeg)
    {
        $this->probeg = $probeg;
    }
    
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }
    
    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }
    
    public function getBoard()
    {
        return $this->board;
    }

    public function setBoard($board)
    {
        $this->board = $board;
    }
    
    public function getGeneration()
    {
        return $this->generation;
    }

    public function setGeneration($generation)
    {
        $this->generation = $generation;
    }
    
    public function getGasType()
    {
        return $this->gasType;
    }

    public function setGasType($gasType)
    {
        $this->gasType = $gasType;
    }
    
    public function getGearType()
    {
        return $this->gearType;
    }

    public function setGearType($gearType)
    {
        $this->gearType = $gearType;
    }
    
    public function getTransmissionType()
    {
        return $this->transmissionType;
    }

    public function setTransmissionType($transmissionType)
    {
        $this->transmissionType = $transmissionType;
    }
    
    public function getModification()
    {
        return $this->modification;
    }

    public function setModification($modification)
    {
        $this->modification = $modification;
    }
    
    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }
}

