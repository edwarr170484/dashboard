<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Generation 
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category", inversedBy="generations")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="generation", cascade={"persist"})
     */
    private $translations;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $yearFrom;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $yearTo;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default":0})
     */
    private $isRightWheel;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default":0})
     */
    private $isGas;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\GenerationBoard", mappedBy="generation", cascade={"persist"})
     */
    private $boards;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\GenerationItem", mappedBy="generation", cascade={"persist"})
     */
    private $items;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Modification", mappedBy="generation", cascade={"persist"})
     */
    private $modifications;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Generation
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set yearFrom
     *
     * @param string $yearFrom
     * @return Generation
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
     * @return Generation
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

    /**
     * Set isRightWheel
     *
     * @param boolean $isRightWheel
     * @return Generation
     */
    public function setIsRightWheel($isRightWheel)
    {
        $this->isRightWheel = $isRightWheel;
    
        return $this;
    }

    /**
     * Get isRightWheel
     *
     * @return boolean 
     */
    public function getIsRightWheel()
    {
        return $this->isRightWheel;
    }

    /**
     * Set isGas
     *
     * @param boolean $isGas
     * @return Generation
     */
    public function setIsGas($isGas)
    {
        $this->isGas = $isGas;
    
        return $this;
    }

    /**
     * Get isGas
     *
     * @return boolean 
     */
    public function getIsGas()
    {
        return $this->isGas;
    }

    /**
     * Set category
     *
     * @param \Dashboard\CommonBundle\Entity\Category $category
     * @return Generation
     */
    public function setCategory(\Dashboard\CommonBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Dashboard\CommonBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     * @return Generation
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
     * Add boards
     *
     * @param \Dashboard\CommonBundle\Entity\GenerationBoard $boards
     * @return Generation
     */
    public function addBoard(\Dashboard\CommonBundle\Entity\GenerationBoard $boards)
    {
        $this->boards[] = $boards;
    
        return $this;
    }

    /**
     * Remove boards
     *
     * @param \Dashboard\CommonBundle\Entity\GenerationBoard $boards
     */
    public function removeBoard(\Dashboard\CommonBundle\Entity\GenerationBoard $boards)
    {
        $this->boards->removeElement($boards);
    }

    /**
     * Get boards
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * Add items
     *
     * @param \Dashboard\CommonBundle\Entity\GenerationItem $items
     * @return Generation
     */
    public function addItem(\Dashboard\CommonBundle\Entity\GenerationItem $items)
    {
        $this->items[] = $items;
    
        return $this;
    }

    /**
     * Remove items
     *
     * @param \Dashboard\CommonBundle\Entity\GenerationItem $items
     */
    public function removeItem(\Dashboard\CommonBundle\Entity\GenerationItem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add modifications
     *
     * @param \Dashboard\CommonBundle\Entity\Modification $modifications
     * @return Generation
     */
    public function addModification(\Dashboard\CommonBundle\Entity\Modification $modifications)
    {
        $this->modifications[] = $modifications;
    
        return $this;
    }

    /**
     * Remove modifications
     *
     * @param \Dashboard\CommonBundle\Entity\Modification $modifications
     */
    public function removeModification(\Dashboard\CommonBundle\Entity\Modification $modifications)
    {
        $this->modifications->removeElement($modifications);
    }

    /**
     * Get modifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModifications()
    {
        return $this->modifications;
    }
}
