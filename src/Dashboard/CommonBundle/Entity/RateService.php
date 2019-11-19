<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class RateService
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Service")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $value;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Rate", inversedBy="services")
     * @ORM\JoinColumn(name="rate_id", referencedColumnName="id")
     */
    private $rate;
    

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
     * Set value
     *
     * @param integer $value
     * @return RateService
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set service
     *
     * @param \Dashboard\CommonBundle\Entity\Service $service
     * @return RateService
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

    /**
     * Set rate
     *
     * @param \Dashboard\CommonBundle\Entity\Rate $rate
     * @return RateService
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
}
