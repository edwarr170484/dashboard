<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Service
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $title;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default": 0})
     */
    private $icon;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default": 0})
     */
    private $iconGray;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default": null})
     */
    private $description;
    
    /**
     * @ORM\Column(type="float", length=15, nullable=true, options={"default": 0})
     */
    private $price;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default": 0})
     */
    private $days;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": 0})
     */
    private $parameter;
    
     /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default": 0})
     */
    private $type;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isButton;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="service", cascade={"persist"})
     */
    private $translations;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\ServicePrice", mappedBy="service", cascade={"persist"})
     */
    private $prices;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\PackService", mappedBy="service", cascade={"persist"})
     */
    private $packServices;
    
    /**
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="services")
     */
    private $userRoles;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->packServices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userRoles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Service
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Service
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    
        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set iconGray
     *
     * @param string $iconGray
     * @return Service
     */
    public function setIconGray($iconGray)
    {
        $this->iconGray = $iconGray;
    
        return $this;
    }

    /**
     * Get iconGray
     *
     * @return string 
     */
    public function getIconGray()
    {
        return $this->iconGray;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Service
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
     * Set price
     *
     * @param float $price
     * @return Service
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
     * Set days
     *
     * @param integer $days
     * @return Service
     */
    public function setDays($days)
    {
        $this->days = $days;
    
        return $this;
    }

    /**
     * Get days
     *
     * @return integer 
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Service
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     * @return Service
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
     * Add prices
     *
     * @param \Dashboard\CommonBundle\Entity\ServicePrice $prices
     * @return Service
     */
    public function addPrice(\Dashboard\CommonBundle\Entity\ServicePrice $prices)
    {
        $this->prices[] = $prices;
    
        return $this;
    }

    /**
     * Remove prices
     *
     * @param \Dashboard\CommonBundle\Entity\ServicePrice $prices
     */
    public function removePrice(\Dashboard\CommonBundle\Entity\ServicePrice $prices)
    {
        $this->prices->removeElement($prices);
    }

    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * Add packServices
     *
     * @param \Dashboard\CommonBundle\Entity\PackService $packServices
     * @return Service
     */
    public function addPackService(\Dashboard\CommonBundle\Entity\PackService $packServices)
    {
        $this->packServices[] = $packServices;
    
        return $this;
    }

    /**
     * Remove packServices
     *
     * @param \Dashboard\CommonBundle\Entity\PackService $packServices
     */
    public function removePackService(\Dashboard\CommonBundle\Entity\PackService $packServices)
    {
        $this->packServices->removeElement($packServices);
    }

    /**
     * Get packServices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPackServices()
    {
        return $this->packServices;
    }

    /**
     * Add userRoles
     *
     * @param \Dashboard\CommonBundle\Entity\Role $userRoles
     * @return Service
     */
    public function addUserRole(\Dashboard\CommonBundle\Entity\Role $userRoles)
    {
        $this->userRoles[] = $userRoles;
    
        return $this;
    }

    /**
     * Remove userRoles
     *
     * @param \Dashboard\CommonBundle\Entity\Role $userRoles
     */
    public function removeUserRole(\Dashboard\CommonBundle\Entity\Role $userRoles)
    {
        $this->userRoles->removeElement($userRoles);
    }

    /**
     * Get userRoles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Set parameter
     *
     * @param string $parameter
     * @return Service
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    
        return $this;
    }

    /**
     * Get parameter
     *
     * @return string 
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Set isButton
     *
     * @param boolean $isButton
     * @return Service
     */
    public function setIsButton($isButton)
    {
        $this->isButton = $isButton;
    
        return $this;
    }

    /**
     * Get isButton
     *
     * @return boolean 
     */
    public function getIsButton()
    {
        return $this->isButton;
    }
}
