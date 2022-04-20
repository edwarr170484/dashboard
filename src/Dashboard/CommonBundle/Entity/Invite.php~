<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Invite
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
     /**
     * @ORM\Column(type="text")
     */
    private $inviteCode;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="invite")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
     /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;

    

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
     * Set inviteCode
     *
     * @param string $inviteCode
     * @return Invite
     */
    public function setInviteCode($inviteCode)
    {
        $this->inviteCode = $inviteCode;

        return $this;
    }

    /**
     * Get inviteCode
     *
     * @return string 
     */
    public function getInviteCode()
    {
        return $this->inviteCode;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Invite
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
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return Invite
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
}
