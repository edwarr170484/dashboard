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
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Modification", mappedBy="generation")
     * @ORM\OrderBy({"sortorder" = "ASC"})
     */
    private $modifications;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $image;
    
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
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->modifications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set image
     *
     * @param string $image
     * @return Generation
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
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
}
