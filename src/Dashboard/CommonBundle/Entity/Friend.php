<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Friend
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="friends")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $referrer;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\User")
     * @ORM\JoinColumn(name="user_friend_id", referencedColumnName="id")
     */
    private $user;
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
     * Set referrer
     *
     * @param \Dashboard\CommonBundle\Entity\User $referrer
     * @return Friend
     */
    public function setReferrer(\Dashboard\CommonBundle\Entity\User $referrer = null)
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * Get referrer
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return Friend
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
