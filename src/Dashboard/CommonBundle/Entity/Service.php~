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
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $icon;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $description;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $price;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $days;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\ProductService", mappedBy="service")
     */
    private $products;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="service", cascade={"persist"})
     */
    private $translations;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param integer $price
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
     * @return integer 
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
     * Add products
     *
     * @param \Dashboard\CommonBundle\Entity\ProductService $products
     * @return Service
     */
    public function addProduct(\Dashboard\CommonBundle\Entity\ProductService $products)
    {
        $this->products[] = $products;
    
        return $this;
    }

    /**
     * Remove products
     *
     * @param \Dashboard\CommonBundle\Entity\ProductService $products
     */
    public function removeProduct(\Dashboard\CommonBundle\Entity\ProductService $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
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
}
