<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class ServicePrice
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $billId;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\Column(type="float", length=15, nullable=true, options={"default":"0"})
     */
    private $price;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Service", inversedBy="prices")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;
   

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
     * @return ServicePrice
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
     * Set price
     *
     * @param float $price
     * @return ServicePrice
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
     * Set category
     *
     * @param \Dashboard\CommonBundle\Entity\Category $category
     * @return ServicePrice
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
     * Set service
     *
     * @param \Dashboard\CommonBundle\Entity\Service $service
     * @return ServicePrice
     */
    public function setService(\Dashboard\CommonBundle\Entity\Service $service = null)
    {
        $this->service = $service;
    
        return $this;
    }

    /**
     * Get service
     *
     * @return \Dashboard\CommonBundle\Entity\Service 
     */
    public function getService()
    {
        return $this->service;
    }
}
