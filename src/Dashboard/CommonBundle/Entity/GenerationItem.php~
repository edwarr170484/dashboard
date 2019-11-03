<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class GenerationItem
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Generation", inversedBy="items")
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
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Modification", inversedBy="generationItem")
     */
    private $itemModifications;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itemModifications = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set generation
     *
     * @param \Dashboard\CommonBundle\Entity\Generation $generation
     * @return GenerationItem
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
     * @return GenerationItem
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
     * @return GenerationItem
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
     * @return GenerationItem
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
     * @return GenerationItem
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
     * Add itemModifications
     *
     * @param \Dashboard\CommonBundle\Entity\Modification $itemModifications
     * @return GenerationItem
     */
    public function addItemModification(\Dashboard\CommonBundle\Entity\Modification $itemModifications)
    {
        $this->itemModifications[] = $itemModifications;
    
        return $this;
    }

    /**
     * Remove itemModifications
     *
     * @param \Dashboard\CommonBundle\Entity\Modification $itemModifications
     */
    public function removeItemModification(\Dashboard\CommonBundle\Entity\Modification $itemModifications)
    {
        $this->itemModifications->removeElement($itemModifications);
    }

    /**
     * Get itemModifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemModifications()
    {
        return $this->itemModifications;
    }
}
