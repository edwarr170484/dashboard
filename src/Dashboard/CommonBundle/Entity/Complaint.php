<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Complaint
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="complaint")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Product", inversedBy="complaint")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
     /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\DealerSalon", inversedBy="complaints")
     * @ORM\JoinColumn(name="salon_id", referencedColumnName="id")
     */
    private $salon;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authorName;
    
    /**
     * @ORM\Column(type="text")
     */
    private $reason;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default": 0})
     */
    private $status;


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
     * Set reason
     *
     * @param string $reason
     * @return Complaint
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Complaint
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
     * Set status
     *
     * @param boolean $status
     * @return Complaint
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return Complaint
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
     * @return Complaint
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
     * Set salon
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalon $salon
     * @return Complaint
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
     * Set authorName
     *
     * @param string $authorName
     * @return Complaint
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    
        return $this;
    }

    /**
     * Get authorName
     *
     * @return string 
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }
}
