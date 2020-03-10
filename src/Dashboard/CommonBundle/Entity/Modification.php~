<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Modification 
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $power;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $size;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $label;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $yearFrom;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $yearTo;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $sortorder;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="modification", cascade={"persist"})
     */
    private $translations;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Generation", inversedBy="modifications")
     * @ORM\JoinColumn(name="generation_id", referencedColumnName="id")
     */
    private $generation;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\GenerationItem", mappedBy="itemModifications")
     */
    private $generationItem;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->generationItem = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set power
     *
     * @param string $power
     * @return Modification
     */
    public function setPower($power)
    {
        $this->power = $power;
    
        return $this;
    }

    /**
     * Get power
     *
     * @return string 
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return Modification
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return Modification
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set sortorder
     *
     * @param string $sortorder
     * @return Modification
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;
    
        return $this;
    }

    /**
     * Get sortorder
     *
     * @return string 
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * Add translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     * @return Modification
     */
    public function addTranslation(\Dashboard\CommonBundle\Entity\Translation $translations)
    {
        $this->translations[] = $translations;
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     */
    public function removeTranslation(\Dashboard\CommonBundle\Entity\Translation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Set generation
     *
     * @param \Dashboard\CommonBundle\Entity\Generation $generation
     * @return Modification
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
     * Add generationItem
     *
     * @param \Dashboard\CommonBundle\Entity\GenerationItem $generationItem
     * @return Modification
     */
    public function addGenerationItem(\Dashboard\CommonBundle\Entity\GenerationItem $generationItem)
    {
        $this->generationItem[] = $generationItem;
    
        return $this;
    }

    /**
     * Remove generationItem
     *
     * @param \Dashboard\CommonBundle\Entity\GenerationItem $generationItem
     */
    public function removeGenerationItem(\Dashboard\CommonBundle\Entity\GenerationItem $generationItem)
    {
        $this->generationItem->removeElement($generationItem);
    }

    /**
     * Get generationItem
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGenerationItem()
    {
        return $this->generationItem;
    }

    /**
     * Set yearFrom
     *
     * @param string $yearFrom
     * @return Modification
     */
    public function setYearFrom($yearFrom)
    {
        $this->yearFrom = $yearFrom;
    
        return $this;
    }

    /**
     * Get yearFrom
     *
     * @return string 
     */
    public function getYearFrom()
    {
        return $this->yearFrom;
    }

    /**
     * Set yearTo
     *
     * @param string $yearTo
     * @return Modification
     */
    public function setYearTo($yearTo)
    {
        $this->yearTo = $yearTo;
    
        return $this;
    }

    /**
     * Get yearTo
     *
     * @return string 
     */
    public function getYearTo()
    {
        return $this->yearTo;
    }
}
