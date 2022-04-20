<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class UserPurseHistory
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\UserPurse", inversedBy="history")
     * @ORM\JoinColumn(name="purse_id", referencedColumnName="id")
     */
    private $userpurse;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $actionDate;
    
    /**
     * @ORM\Column(type="integer", length = 15,nullable=true, options={"default":"0"})
     */
    private $currentBalanse;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $action;

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
     * Set actionDate
     *
     * @param \DateTime $actionDate
     * @return UserPurseHistory
     */
    public function setActionDate($actionDate)
    {
        $this->actionDate = $actionDate;

        return $this;
    }

    /**
     * Get actionDate
     *
     * @return \DateTime 
     */
    public function getActionDate()
    {
        return $this->actionDate;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return UserPurseHistory
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set currentBalanse
     *
     * @param integer $currentBalanse
     * @return UserPurseHistory
     */
    public function setCurrentBalanse($currentBalanse)
    {
        $this->currentBalanse = $currentBalanse;

        return $this;
    }

    /**
     * Get currentBalanse
     *
     * @return integer 
     */
    public function getCurrentBalanse()
    {
        return $this->currentBalanse;
    }

    /**
     * Set userpurse
     *
     * @param \Dashboard\CommonBundle\Entity\UserPurse $userpurse
     * @return UserPurseHistory
     */
    public function setUserpurse(\Dashboard\CommonBundle\Entity\UserPurse $userpurse = null)
    {
        $this->userpurse = $userpurse;

        return $this;
    }

    /**
     * Get userpurse
     *
     * @return \Dashboard\CommonBundle\Entity\UserPurse 
     */
    public function getUserpurse()
    {
        return $this->userpurse;
    }
}
