<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class UserRateItem 
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\RateService")
     * @ORM\JoinColumn(name="rate_service_id", referencedColumnName="id")
     */
    private $service;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default": 0})
     */
    private $count;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\UserRate", inversedBy="items")
     * @ORM\JoinColumn(name="user_rate_id", referencedColumnName="id")
     */
    private $userrate;
    

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
     * Set count
     *
     * @param integer $count
     * @return UserRateItem
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set service
     *
     * @param \Dashboard\CommonBundle\Entity\RateService $service
     * @return UserRateItem
     */
    public function setService(\Dashboard\CommonBundle\Entity\RateService $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Dashboard\CommonBundle\Entity\RateService 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set userrate
     *
     * @param \Dashboard\CommonBundle\Entity\UserRate $userrate
     * @return UserRateItem
     */
    public function setUserrate(\Dashboard\CommonBundle\Entity\UserRate $userrate = null)
    {
        $this->userrate = $userrate;

        return $this;
    }

    /**
     * Get userrate
     *
     * @return \Dashboard\CommonBundle\Entity\UserRate 
     */
    public function getUserrate()
    {
        return $this->userrate;
    }
}
