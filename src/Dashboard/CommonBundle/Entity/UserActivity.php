<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class UserActivity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="activity")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\Column(type="integer", length=5, options={"default":"0"})
     */
    private $enterCount;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $lastActivity;

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
     * Set enterCount
     *
     * @param integer $enterCount
     * @return UserActivity
     */
    public function setEnterCount($enterCount)
    {
        $this->enterCount = $enterCount;

        return $this;
    }

    /**
     * Get enterCount
     *
     * @return integer 
     */
    public function getEnterCount()
    {
        return $this->enterCount;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     * @return UserActivity
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime 
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return UserActivity
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
