<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class DealerSalon
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\DealerInfo", inversedBy="salons")
     * @ORM\JoinColumn(name="dealer_info_id", referencedColumnName="id")
     */
    private $dealerInfo;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $website;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $logotype;
    
     /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    
     /**
     * @ORM\Column(type="datetime")
     */
    private $dateStopped;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isActive;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\DealerSalonPhone", mappedBy="dealerSalon")
     */
    private $phones;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Workinfo", mappedBy="dealerSalon", cascade={"persist"})
     */
    private $workinfo;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Job", inversedBy="salons")
     */
    private $jobs;
    
    /**
     * @ORM\Column(type="integer", length=3, nullable=true, options={"default":"0"})
     */
    private $rating;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Review", mappedBy="salons")
     */
    private $reviews;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->jobs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return DealerSalon
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
     * Set address
     *
     * @param string $address
     * @return DealerSalon
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return DealerSalon
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set logotype
     *
     * @param string $logotype
     * @return DealerSalon
     */
    public function setLogotype($logotype)
    {
        $this->logotype = $logotype;
    
        return $this;
    }

    /**
     * Get logotype
     *
     * @return string 
     */
    public function getLogotype()
    {
        return $this->logotype;
    }

    /**
     * Set dealerInfo
     *
     * @param \Dashboard\CommonBundle\Entity\DealerInfo $dealerInfo
     * @return DealerSalon
     */
    public function setDealerInfo(\Dashboard\CommonBundle\Entity\DealerInfo $dealerInfo = null)
    {
        $this->dealerInfo = $dealerInfo;
    
        return $this;
    }

    /**
     * Get dealerInfo
     *
     * @return \Dashboard\CommonBundle\Entity\DealerInfo 
     */
    public function getDealerInfo()
    {
        return $this->dealerInfo;
    }

    /**
     * Add phones
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalonPhone $phones
     * @return DealerSalon
     */
    public function addPhone(\Dashboard\CommonBundle\Entity\DealerSalonPhone $phones)
    {
        $this->phones[] = $phones;
    
        return $this;
    }

    /**
     * Remove phones
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalonPhone $phones
     */
    public function removePhone(\Dashboard\CommonBundle\Entity\DealerSalonPhone $phones)
    {
        $this->phones->removeElement($phones);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Set workinfo
     *
     * @param \Dashboard\CommonBundle\Entity\Workinfo $workinfo
     * @return DealerSalon
     */
    public function setWorkinfo(\Dashboard\CommonBundle\Entity\Workinfo $workinfo = null)
    {
        $this->workinfo = $workinfo;
    
        return $this;
    }

    /**
     * Get workinfo
     *
     * @return \Dashboard\CommonBundle\Entity\Workinfo 
     */
    public function getWorkinfo()
    {
        return $this->workinfo;
    }

    /**
     * Add jobs
     *
     * @param \Dashboard\CommonBundle\Entity\Job $jobs
     * @return DealerSalon
     */
    public function addJob(\Dashboard\CommonBundle\Entity\Job $jobs)
    {
        $this->jobs[] = $jobs;
    
        return $this;
    }

    /**
     * Remove jobs
     *
     * @param \Dashboard\CommonBundle\Entity\Job $jobs
     */
    public function removeJob(\Dashboard\CommonBundle\Entity\Job $jobs)
    {
        $this->jobs->removeElement($jobs);
    }

    /**
     * Get jobs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return DealerSalon
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
     * Set dateStopped
     *
     * @param \DateTime $dateStopped
     * @return DealerSalon
     */
    public function setDateStopped($dateStopped)
    {
        $this->dateStopped = $dateStopped;

        return $this;
    }

    /**
     * Get dateStopped
     *
     * @return \DateTime 
     */
    public function getDateStopped()
    {
        return $this->dateStopped;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return DealerSalon
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
     * Set rating
     *
     * @param integer $rating
     * @return DealerSalon
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Add reviews
     *
     * @param \Dashboard\CommonBundle\Entity\Review $reviews
     * @return DealerSalon
     */
    public function addReview(\Dashboard\CommonBundle\Entity\Review $reviews)
    {
        $this->reviews[] = $reviews;
    
        return $this;
    }

    /**
     * Remove reviews
     *
     * @param \Dashboard\CommonBundle\Entity\Review $reviews
     */
    public function removeReview(\Dashboard\CommonBundle\Entity\Review $reviews)
    {
        $this->reviews->removeElement($reviews);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReviews()
    {
        return $this->reviews;
    }
}
