<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Conversation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="conversationOne")
     * @ORM\JoinColumn(name="conversation_userone_id", referencedColumnName="id")
     */
    private $userOne;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="conversationTwo")
     * @ORM\JoinColumn(name="conversation_usertwo_id", referencedColumnName="id")
     */
    private $userTwo;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $userDeleted;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Message", mappedBy="conversation")
     */
    private $messages;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add messages
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messages
     * @return Conversation
     */
    public function addMessage(\Dashboard\CommonBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messages
     */
    public function removeMessage(\Dashboard\CommonBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set userDeleted
     *
     * @param integer $userDeleted
     * @return Conversation
     */
    public function setUserDeleted($userDeleted)
    {
        $this->userDeleted = $userDeleted;

        return $this;
    }

    /**
     * Get userDeleted
     *
     * @return integer 
     */
    public function getUserDeleted()
    {
        return $this->userDeleted;
    }

    /**
     * Set userOne
     *
     * @param \Dashboard\CommonBundle\Entity\User $userOne
     * @return Conversation
     */
    public function setUserOne(\Dashboard\CommonBundle\Entity\User $userOne = null)
    {
        $this->userOne = $userOne;

        return $this;
    }

    /**
     * Get userOne
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUserOne()
    {
        return $this->userOne;
    }

    /**
     * Set userTwo
     *
     * @param \Dashboard\CommonBundle\Entity\User $userTwo
     * @return Conversation
     */
    public function setUserTwo(\Dashboard\CommonBundle\Entity\User $userTwo = null)
    {
        $this->userTwo = $userTwo;

        return $this;
    }

    /**
     * Get userTwo
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUserTwo()
    {
        return $this->userTwo;
    }
}
