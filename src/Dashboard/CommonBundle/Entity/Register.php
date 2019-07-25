<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="register")
 * @ORM\Entity()
 */
class Register
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $userId;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $confirmKey;
    
    /**
     * @ORM\Column(type="text",nullable=true, options={"default":"0"})
     */
    private $inviteCode;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Register
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set confirmKey
     *
     * @param string $confirmKey
     * @return Register
     */
    public function setConfirmKey($confirmKey)
    {
        $this->confirmKey = $confirmKey;

        return $this;
    }

    /**
     * Get confirmKey
     *
     * @return string 
     */
    public function getConfirmKey()
    {
        return $this->confirmKey;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Register
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
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
     * Set inviteCode
     *
     * @param string $inviteCode
     * @return Register
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
}
