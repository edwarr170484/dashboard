<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class UserPurse
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="userpurse")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\Column(type="string", length=50, options={"default":"0"})
     */
    private $balanse;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\UserPurseHistory", mappedBy="userpurse", cascade={"persist"})
     */
    private $history;

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
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return UserPurse
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
     * Set balanse
     *
     * @param string $balanse
     * @return UserPurse
     */
    public function setBalanse($balanse)
    {
        $this->balanse = $balanse;

        return $this;
    }

    /**
     * Get balanse
     *
     * @return string 
     */
    public function getBalanse()
    {
        return $this->balanse;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->history = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add history
     *
     * @param \Dashboard\CommonBundle\Entity\UserPurseHistory $history
     * @return UserPurse
     */
    public function addHistory(\Dashboard\CommonBundle\Entity\UserPurseHistory $history)
    {
        $this->history[] = $history;

        return $this;
    }

    /**
     * Remove history
     *
     * @param \Dashboard\CommonBundle\Entity\UserPurseHistory $history
     */
    public function removeHistory(\Dashboard\CommonBundle\Entity\UserPurseHistory $history)
    {
        $this->history->removeElement($history);
    }

    /**
     * Get history
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistory()
    {
        return $this->history;
    }
}
