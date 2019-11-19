<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Rate
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default": 0})
     */
    private $billId;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="rate", cascade={"persist"})
     */
    private $translations;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\RateService", mappedBy="rate", cascade={"persist"})
     */
    private $services;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Role", inversedBy="rates")
     * @ORM\JoinColumn(name="user_role_id", referencedColumnName="id")
     */
    private $userRole;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $name;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $description;
    
    /**
     * @ORM\Column(type="float", length=15, nullable=true, options={"default": 0})
     */
    private $price;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default": 0})
     */
    private $advertNumber;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set billId
     *
     * @param integer $billId
     * @return Rate
     */
    public function setBillId($billId)
    {
        $this->billId = $billId;
    
        return $this;
    }

    /**
     * Get billId
     *
     * @return integer 
     */
    public function getBillId()
    {
        return $this->billId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Rate
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
     * Set price
     *
     * @param float $price
     * @return Rate
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
     * Set advertNumber
     *
     * @param integer $advertNumber
     * @return Rate
     */
    public function setAdvertNumber($advertNumber)
    {
        $this->advertNumber = $advertNumber;
    
        return $this;
    }

    /**
     * Get advertNumber
     *
     * @return integer 
     */
    public function getAdvertNumber()
    {
        return $this->advertNumber;
    }

    /**
     * Add translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     * @return Rate
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
     * Add services
     *
     * @param \Dashboard\CommonBundle\Entity\RateService $services
     * @return Rate
     */
    public function addService(\Dashboard\CommonBundle\Entity\RateService $services)
    {
        $this->services[] = $services;
    
        return $this;
    }

    /**
     * Remove services
     *
     * @param \Dashboard\CommonBundle\Entity\RateService $services
     */
    public function removeService(\Dashboard\CommonBundle\Entity\RateService $services)
    {
        $this->services->removeElement($services);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Set userRole
     *
     * @param \Dashboard\CommonBundle\Entity\Role $userRole
     * @return Rate
     */
    public function setUserRole(\Dashboard\CommonBundle\Entity\Role $userRole = null)
    {
        $this->userRole = $userRole;
    
        return $this;
    }

    /**
     * Get userRole
     *
     * @return \Dashboard\CommonBundle\Entity\Role 
     */
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Rate
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
}
