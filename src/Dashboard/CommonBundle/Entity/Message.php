<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Message
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="messageOutbox")
     * @ORM\JoinColumn(name="user_from_id", referencedColumnName="id")
     */
    private $userFrom;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="messageInbox")
     * @ORM\JoinColumn(name="user_to_id", referencedColumnName="id")
     */
    private $userTo;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="messageOwner")
     * @ORM\JoinColumn(name="user_owner_id", referencedColumnName="id")
     */
    private $userOwner;
    
    /**
     * @ORM\Column(type="string", length = 255, nullable=true, options={"default":"null"})
     */
    private $subject;
    
    /**
     * @ORM\Column(type="text")
     */
    private $message;
    
    /**
     * @ORM\Column(type="string", length = 255, nullable=true, options={"default":"null"})
     */
    private $image;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isNew;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $sentDate;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $readedDate;
    
     /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Product", inversedBy="messages")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Conversation", inversedBy="messages")
     * @ORM\JoinColumn(name="conversation_id", referencedColumnName="id")
     */
    private $conversation;

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
     * Set subject
     *
     * @param string $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Message
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set isNew
     *
     * @param boolean $isNew
     * @return Message
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew
     *
     * @return boolean 
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Message
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set sentDate
     *
     * @param \DateTime $sentDate
     * @return Message
     */
    public function setSentDate($sentDate)
    {
        $this->sentDate = $sentDate;

        return $this;
    }

    /**
     * Get sentDate
     *
     * @return \DateTime 
     */
    public function getSentDate()
    {
        return $this->sentDate;
    }

    /**
     * Set readedDate
     *
     * @param \DateTime $readedDate
     * @return Message
     */
    public function setReadedDate($readedDate)
    {
        $this->readedDate = $readedDate;

        return $this;
    }

    /**
     * Get readedDate
     *
     * @return \DateTime 
     */
    public function getReadedDate()
    {
        return $this->readedDate;
    }

    /**
     * Set userFrom
     *
     * @param \Dashboard\CommonBundle\Entity\User $userFrom
     * @return Message
     */
    public function setUserFrom(\Dashboard\CommonBundle\Entity\User $userFrom = null)
    {
        $this->userFrom = $userFrom;

        return $this;
    }

    /**
     * Get userFrom
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUserFrom()
    {
        return $this->userFrom;
    }

    /**
     * Set userTo
     *
     * @param \Dashboard\CommonBundle\Entity\User $userTo
     * @return Message
     */
    public function setUserTo(\Dashboard\CommonBundle\Entity\User $userTo = null)
    {
        $this->userTo = $userTo;

        return $this;
    }

    /**
     * Get userTo
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUserTo()
    {
        return $this->userTo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     * @return Message
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
     * Set userOwner
     *
     * @param \Dashboard\CommonBundle\Entity\User $userOwner
     * @return Message
     */
    public function setUserOwner(\Dashboard\CommonBundle\Entity\User $userOwner = null)
    {
        $this->userOwner = $userOwner;

        return $this;
    }

    /**
     * Get userOwner
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUserOwner()
    {
        return $this->userOwner;
    }

    /**
     * Set conversation
     *
     * @param \Dashboard\CommonBundle\Entity\Conversation $conversation
     * @return Message
     */
    public function setConversation(\Dashboard\CommonBundle\Entity\Conversation $conversation = null)
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Get conversation
     *
     * @return \Dashboard\CommonBundle\Entity\Conversation 
     */
    public function getConversation()
    {
        return $this->conversation;
    }
}
