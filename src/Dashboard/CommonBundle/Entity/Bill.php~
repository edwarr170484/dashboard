<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Bill 
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": null})
     */
    private $dateAdded;
    
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": null})
     */
    private $datePayed;
    
    /**
     * @ORM\Column(type="float", length=15, nullable=true, options={"default": 0})
     */
    private $price;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="bills")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default" : 0})
     */
    private $file;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Rate")
     * @ORM\JoinColumn(name="rate_id", referencedColumnName="id")
     */
    private $rate;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Pack")
     * @ORM\JoinColumn(name="service_pack_id", referencedColumnName="id")
     */
    private $servicePack;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\ProductService", inversedBy="bills")
     */
    private $services;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    private $isPayed;
    
    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Bill
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    
        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set datePayed
     *
     * @param \DateTime $datePayed
     * @return Bill
     */
    public function setDatePayed($datePayed)
    {
        $this->datePayed = $datePayed;
    
        return $this;
    }

    /**
     * Get datePayed
     *
     * @return \DateTime 
     */
    public function getDatePayed()
    {
        return $this->datePayed;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Bill
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
     * Set file
     *
     * @param string $file
     * @return Bill
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set isPayed
     *
     * @param boolean $isPayed
     * @return Bill
     */
    public function setIsPayed($isPayed)
    {
        $this->isPayed = $isPayed;
    
        return $this;
    }

    /**
     * Get isPayed
     *
     * @return boolean 
     */
    public function getIsPayed()
    {
        return $this->isPayed;
    }

    /**
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return Bill
     */
    public function setUser(\Dashboard\CommonBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     * @return Bill
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
     * Set rate
     *
     * @param \Dashboard\CommonBundle\Entity\Rate $rate
     * @return Bill
     */
    public function setRate(\Dashboard\CommonBundle\Entity\Rate $rate = null)
    {
        $this->rate = $rate;
    
        return $this;
    }

    /**
     * Get rate
     *
     * @return \Dashboard\CommonBundle\Entity\Rate 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set servicePack
     *
     * @param \Dashboard\CommonBundle\Entity\Pack $servicePack
     * @return Bill
     */
    public function setServicePack(\Dashboard\CommonBundle\Entity\Pack $servicePack = null)
    {
        $this->servicePack = $servicePack;
    
        return $this;
    }

    /**
     * Get servicePack
     *
     * @return \Dashboard\CommonBundle\Entity\Pack 
     */
    public function getServicePack()
    {
        return $this->servicePack;
    }

    /**
     * Add services
     *
     * @param \Dashboard\CommonBundle\Entity\ProductService $services
     * @return Bill
     */
    public function addService(\Dashboard\CommonBundle\Entity\ProductService $services)
    {
        $this->services[] = $services;
    
        return $this;
    }

    /**
     * Remove services
     *
     * @param \Dashboard\CommonBundle\Entity\ProductService $services
     */
    public function removeService(\Dashboard\CommonBundle\Entity\ProductService $services)
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
}
