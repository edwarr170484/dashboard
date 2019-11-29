<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class ProductInfo
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Product", inversedBy="info")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\Column(type="float", length=15, nullable=true, options={"default": 0})
     */
    private $price;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":0})
     */
    private $year;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":0})
     */
    private $probeg;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Generation")
     * @ORM\JoinColumn(name="generation_id", referencedColumnName="id")
     */
    private $generation;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\GenerationBoard")
     * @ORM\JoinColumn(name="board_id", referencedColumnName="id")
     */
    private $board;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\FilterValue")
     * @ORM\JoinColumn(name="gas_type_id", referencedColumnName="id")
     */
    private $gasType;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\FilterValue")
     * @ORM\JoinColumn(name="gas_transmission_id", referencedColumnName="id")
     */
    private $transmissionType;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\FilterValue")
     * @ORM\JoinColumn(name="gear_type_id", referencedColumnName="id")
     */
    private $gearType;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Modification")
     * @ORM\JoinColumn(name="modification_id", referencedColumnName="id")
     */
    private $modification;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\FilterValue")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     */
    private $color;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $wheel;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $isGasBaloon;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Shape")
     * @ORM\JoinColumn(name="shape_id", referencedColumnName="id")
     */
    private $shape;
    
     /**
     * @ORM\Column(type="integer", length=5, nullable=true, options={"default":0})
     */
    private $owners;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":0})
     */
    private $vin;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":0})
     */
    private $description;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $nds;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $torg;
    
     /**
     * @ORM\Column(type="float", length=5, nullable=true, options={"default": 0 })
     */
    private $garant;
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return ProductInfo
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return ProductInfo
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return string 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set probeg
     *
     * @param string $probeg
     * @return ProductInfo
     */
    public function setProbeg($probeg)
    {
        $this->probeg = $probeg;
    
        return $this;
    }

    /**
     * Get probeg
     *
     * @return string 
     */
    public function getProbeg()
    {
        return $this->probeg;
    }

    /**
     * Set wheel
     *
     * @param boolean $wheel
     * @return ProductInfo
     */
    public function setWheel($wheel)
    {
        $this->wheel = $wheel;
    
        return $this;
    }

    /**
     * Get wheel
     *
     * @return boolean 
     */
    public function getWheel()
    {
        return $this->wheel;
    }

    /**
     * Set isGasBaloon
     *
     * @param boolean $isGasBaloon
     * @return ProductInfo
     */
    public function setIsGasBaloon($isGasBaloon)
    {
        $this->isGasBaloon = $isGasBaloon;
    
        return $this;
    }

    /**
     * Get isGasBaloon
     *
     * @return boolean 
     */
    public function getIsGasBaloon()
    {
        return $this->isGasBaloon;
    }

    /**
     * Set owners
     *
     * @param integer $owners
     * @return ProductInfo
     */
    public function setOwners($owners)
    {
        $this->owners = $owners;
    
        return $this;
    }

    /**
     * Get owners
     *
     * @return integer 
     */
    public function getOwners()
    {
        return $this->owners;
    }

    /**
     * Set vin
     *
     * @param string $vin
     * @return ProductInfo
     */
    public function setVin($vin)
    {
        $this->vin = $vin;
    
        return $this;
    }

    /**
     * Get vin
     *
     * @return string 
     */
    public function getVin()
    {
        return $this->vin;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ProductInfo
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set nds
     *
     * @param boolean $nds
     * @return ProductInfo
     */
    public function setNds($nds)
    {
        $this->nds = $nds;
    
        return $this;
    }

    /**
     * Get nds
     *
     * @return boolean 
     */
    public function getNds()
    {
        return $this->nds;
    }

    /**
     * Set torg
     *
     * @param boolean $torg
     * @return ProductInfo
     */
    public function setTorg($torg)
    {
        $this->torg = $torg;
    
        return $this;
    }

    /**
     * Get torg
     *
     * @return boolean 
     */
    public function getTorg()
    {
        return $this->torg;
    }

    /**
     * Set garant
     *
     * @param float $garant
     * @return ProductInfo
     */
    public function setGarant($garant)
    {
        $this->garant = $garant;
    
        return $this;
    }

    /**
     * Get garant
     *
     * @return float 
     */
    public function getGarant()
    {
        return $this->garant;
    }

    /**
     * Set product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     * @return ProductInfo
     */
    public function setProduct(\Dashboard\CommonBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \Dashboard\CommonBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set generation
     *
     * @param \Dashboard\CommonBundle\Entity\Generation $generation
     * @return ProductInfo
     */
    public function setGeneration(\Dashboard\CommonBundle\Entity\Generation $generation = null)
    {
        $this->generation = $generation;
    
        return $this;
    }

    /**
     * Get generation
     *
     * @return \Dashboard\CommonBundle\Entity\Generation 
     */
    public function getGeneration()
    {
        return $this->generation;
    }

    /**
     * Set board
     *
     * @param \Dashboard\CommonBundle\Entity\GenerationBoard $board
     * @return ProductInfo
     */
    public function setBoard(\Dashboard\CommonBundle\Entity\GenerationBoard $board = null)
    {
        $this->board = $board;
    
        return $this;
    }

    /**
     * Get board
     *
     * @return \Dashboard\CommonBundle\Entity\GenerationBoard 
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set gasType
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $gasType
     * @return ProductInfo
     */
    public function setGasType(\Dashboard\CommonBundle\Entity\FilterValue $gasType = null)
    {
        $this->gasType = $gasType;
    
        return $this;
    }

    /**
     * Get gasType
     *
     * @return \Dashboard\CommonBundle\Entity\FilterValue 
     */
    public function getGasType()
    {
        return $this->gasType;
    }

    /**
     * Set transmissionType
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $transmissionType
     * @return ProductInfo
     */
    public function setTransmissionType(\Dashboard\CommonBundle\Entity\FilterValue $transmissionType = null)
    {
        $this->transmissionType = $transmissionType;
    
        return $this;
    }

    /**
     * Get transmissionType
     *
     * @return \Dashboard\CommonBundle\Entity\FilterValue 
     */
    public function getTransmissionType()
    {
        return $this->transmissionType;
    }

    /**
     * Set gearType
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $gearType
     * @return ProductInfo
     */
    public function setGearType(\Dashboard\CommonBundle\Entity\FilterValue $gearType = null)
    {
        $this->gearType = $gearType;
    
        return $this;
    }

    /**
     * Get gearType
     *
     * @return \Dashboard\CommonBundle\Entity\FilterValue 
     */
    public function getGearType()
    {
        return $this->gearType;
    }

    /**
     * Set modification
     *
     * @param \Dashboard\CommonBundle\Entity\Modification $modification
     * @return ProductInfo
     */
    public function setModification(\Dashboard\CommonBundle\Entity\Modification $modification = null)
    {
        $this->modification = $modification;
    
        return $this;
    }

    /**
     * Get modification
     *
     * @return \Dashboard\CommonBundle\Entity\Modification 
     */
    public function getModification()
    {
        return $this->modification;
    }

    /**
     * Set color
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $color
     * @return ProductInfo
     */
    public function setColor(\Dashboard\CommonBundle\Entity\FilterValue $color = null)
    {
        $this->color = $color;
    
        return $this;
    }

    /**
     * Get color
     *
     * @return \Dashboard\CommonBundle\Entity\FilterValue 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set shape
     *
     * @param \Dashboard\CommonBundle\Entity\Shape $shape
     * @return ProductInfo
     */
    public function setShape(\Dashboard\CommonBundle\Entity\Shape $shape = null)
    {
        $this->shape = $shape;
    
        return $this;
    }

    /**
     * Get shape
     *
     * @return \Dashboard\CommonBundle\Entity\Shape 
     */
    public function getShape()
    {
        return $this->shape;
    }
}
