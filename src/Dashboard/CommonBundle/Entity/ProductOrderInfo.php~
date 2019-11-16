<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class ProductOrderInfo
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\ProductOrder", inversedBy="info")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;
    
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return ProductOrderInfo
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
     * @return ProductOrderInfo
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
     * Set order
     *
     * @param \Dashboard\CommonBundle\Entity\ProductOrder $order
     * @return ProductOrderInfo
     */
    public function setOrder(\Dashboard\CommonBundle\Entity\ProductOrder $order = null)
    {
        $this->order = $order;
    
        return $this;
    }

    /**
     * Get order
     *
     * @return \Dashboard\CommonBundle\Entity\ProductOrder 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set generation
     *
     * @param \Dashboard\CommonBundle\Entity\Generation $generation
     * @return ProductOrderInfo
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
     * @return ProductOrderInfo
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
     * @return ProductOrderInfo
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
     * @return ProductOrderInfo
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
     * @return ProductOrderInfo
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
     * @return ProductOrderInfo
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
}
