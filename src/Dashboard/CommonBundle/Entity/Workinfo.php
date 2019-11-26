<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Workinfo
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\DealerInfo", inversedBy="workinfo")
     * @ORM\JoinColumn(name="dealer_id", referencedColumnName="id")
     */
    private $dealer;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $monday;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $tuesday;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $wednesday;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $thursday;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $friday;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $saturday;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $sunday;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $fullDay;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isWokdays;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isHolidays;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isAlldays;
    
    
    /**
     * @ORM\Column(type="time", nullable=true, options={"default": NULL})
     */
    private $workStart;
    
    /**
     * @ORM\Column(type="time", nullable=true, options={"default": NULL})
     */
    private $workStop;
    
    /**
     * @ORM\Column(type="time", nullable=true, options={"default": NULL})
     */
    private $breakStart;
    
    /**
     * @ORM\Column(type="time", nullable=true, options={"default": NULL})
     */
    private $breakStop;
    

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
     * Set monday
     *
     * @param boolean $monday
     * @return Workinfo
     */
    public function setMonday($monday)
    {
        $this->monday = $monday;
    
        return $this;
    }

    /**
     * Get monday
     *
     * @return boolean 
     */
    public function getMonday()
    {
        return $this->monday;
    }

    /**
     * Set tuesday
     *
     * @param boolean $tuesday
     * @return Workinfo
     */
    public function setTuesday($tuesday)
    {
        $this->tuesday = $tuesday;
    
        return $this;
    }

    /**
     * Get tuesday
     *
     * @return boolean 
     */
    public function getTuesday()
    {
        return $this->tuesday;
    }

    /**
     * Set wednesday
     *
     * @param boolean $wednesday
     * @return Workinfo
     */
    public function setWednesday($wednesday)
    {
        $this->wednesday = $wednesday;
    
        return $this;
    }

    /**
     * Get wednesday
     *
     * @return boolean 
     */
    public function getWednesday()
    {
        return $this->wednesday;
    }

    /**
     * Set thursday
     *
     * @param boolean $thursday
     * @return Workinfo
     */
    public function setThursday($thursday)
    {
        $this->thursday = $thursday;
    
        return $this;
    }

    /**
     * Get thursday
     *
     * @return boolean 
     */
    public function getThursday()
    {
        return $this->thursday;
    }

    /**
     * Set friday
     *
     * @param boolean $friday
     * @return Workinfo
     */
    public function setFriday($friday)
    {
        $this->friday = $friday;
    
        return $this;
    }

    /**
     * Get friday
     *
     * @return boolean 
     */
    public function getFriday()
    {
        return $this->friday;
    }

    /**
     * Set saturday
     *
     * @param boolean $saturday
     * @return Workinfo
     */
    public function setSaturday($saturday)
    {
        $this->saturday = $saturday;
    
        return $this;
    }

    /**
     * Get saturday
     *
     * @return boolean 
     */
    public function getSaturday()
    {
        return $this->saturday;
    }

    /**
     * Set sunday
     *
     * @param boolean $sunday
     * @return Workinfo
     */
    public function setSunday($sunday)
    {
        $this->sunday = $sunday;
    
        return $this;
    }

    /**
     * Get sunday
     *
     * @return boolean 
     */
    public function getSunday()
    {
        return $this->sunday;
    }

    /**
     * Set fullDay
     *
     * @param boolean $fullDay
     * @return Workinfo
     */
    public function setFullDay($fullDay)
    {
        $this->fullDay = $fullDay;
    
        return $this;
    }

    /**
     * Get fullDay
     *
     * @return boolean 
     */
    public function getFullDay()
    {
        return $this->fullDay;
    }

    /**
     * Set isWokdays
     *
     * @param boolean $isWokdays
     * @return Workinfo
     */
    public function setIsWokdays($isWokdays)
    {
        $this->isWokdays = $isWokdays;
    
        return $this;
    }

    /**
     * Get isWokdays
     *
     * @return boolean 
     */
    public function getIsWokdays()
    {
        return $this->isWokdays;
    }

    /**
     * Set isHolidays
     *
     * @param boolean $isHolidays
     * @return Workinfo
     */
    public function setIsHolidays($isHolidays)
    {
        $this->isHolidays = $isHolidays;
    
        return $this;
    }

    /**
     * Get isHolidays
     *
     * @return boolean 
     */
    public function getIsHolidays()
    {
        return $this->isHolidays;
    }

    /**
     * Set isAlldays
     *
     * @param boolean $isAlldays
     * @return Workinfo
     */
    public function setIsAlldays($isAlldays)
    {
        $this->isAlldays = $isAlldays;
    
        return $this;
    }

    /**
     * Get isAlldays
     *
     * @return boolean 
     */
    public function getIsAlldays()
    {
        return $this->isAlldays;
    }

    /**
     * Set workStart
     *
     * @param \DateTime $workStart
     * @return Workinfo
     */
    public function setWorkStart($workStart)
    {
        $this->workStart = $workStart;
    
        return $this;
    }

    /**
     * Get workStart
     *
     * @return \DateTime 
     */
    public function getWorkStart()
    {
        return $this->workStart;
    }

    /**
     * Set workStop
     *
     * @param \DateTime $workStop
     * @return Workinfo
     */
    public function setWorkStop($workStop)
    {
        $this->workStop = $workStop;
    
        return $this;
    }

    /**
     * Get workStop
     *
     * @return \DateTime 
     */
    public function getWorkStop()
    {
        return $this->workStop;
    }

    /**
     * Set breakStart
     *
     * @param \DateTime $breakStart
     * @return Workinfo
     */
    public function setBreakStart($breakStart)
    {
        $this->breakStart = $breakStart;
    
        return $this;
    }

    /**
     * Get breakStart
     *
     * @return \DateTime 
     */
    public function getBreakStart()
    {
        return $this->breakStart;
    }

    /**
     * Set breakStop
     *
     * @param \DateTime $breakStop
     * @return Workinfo
     */
    public function setBreakStop($breakStop)
    {
        $this->breakStop = $breakStop;
    
        return $this;
    }

    /**
     * Get breakStop
     *
     * @return \DateTime 
     */
    public function getBreakStop()
    {
        return $this->breakStop;
    }

    /**
     * Set dealer
     *
     * @param \Dashboard\CommonBundle\Entity\DealerInfo $dealer
     * @return Workinfo
     */
    public function setDealer(\Dashboard\CommonBundle\Entity\DealerInfo $dealer = null)
    {
        $this->dealer = $dealer;
    
        return $this;
    }

    /**
     * Get dealer
     *
     * @return \Dashboard\CommonBundle\Entity\DealerInfo 
     */
    public function getDealer()
    {
        return $this->dealer;
    }
}
