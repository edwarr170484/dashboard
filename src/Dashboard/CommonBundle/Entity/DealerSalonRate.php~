<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class DealerSalonRate
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\DealerSalon", inversedBy="rates")
     * @ORM\JoinColumn(name="salon_id", referencedColumnName="id")
     */
    private $salon;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Rate")
     * @ORM\JoinColumn(name="rate_id", referencedColumnName="id")
     */
    private $rate;
    
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": NULL})
     */
    private $dateAdded;
    
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": NULL})
     */
    private $dateEnd;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isActive;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Bill", mappedBy="rates", cascade={"persist"})
     */
    private $bills;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bills = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return DealerSalonRate
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
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return DealerSalonRate
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    
        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return DealerSalonRate
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set salon
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalon $salon
     * @return DealerSalonRate
     */
    public function setSalon(\Dashboard\CommonBundle\Entity\DealerSalon $salon = null)
    {
        $this->salon = $salon;
    
        return $this;
    }

    /**
     * Get salon
     *
     * @return \Dashboard\CommonBundle\Entity\DealerSalon 
     */
    public function getSalon()
    {
        return $this->salon;
    }

    /**
     * Set rate
     *
     * @param \Dashboard\CommonBundle\Entity\Rate $rate
     * @return DealerSalonRate
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
     * Add bills
     *
     * @param \Dashboard\CommonBundle\Entity\Bill $bills
     * @return DealerSalonRate
     */
    public function addBill(\Dashboard\CommonBundle\Entity\Bill $bills)
    {
        $this->bills[] = $bills;
    
        return $this;
    }

    /**
     * Remove bills
     *
     * @param \Dashboard\CommonBundle\Entity\Bill $bills
     */
    public function removeBill(\Dashboard\CommonBundle\Entity\Bill $bills)
    {
        $this->bills->removeElement($bills);
    }

    /**
     * Get bills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBills()
    {
        return $this->bills;
    }
}
