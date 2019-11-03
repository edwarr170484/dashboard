<?php
namespace Dashboard\CommonBundle\Model;

class AdvertInfo
{
    private $category;
    private $year;
    private $board;
    private $generation;
    private $gasType;
    private $gearType;
    private $transmissionType;
    private $modification;
    private $color;
    
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

