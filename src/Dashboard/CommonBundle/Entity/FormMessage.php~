<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class FormMessage
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authorName;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authorEmail;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $messageSubject;
    
    /**
     * @ORM\Column(type="text")
     */
    private $messageText;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     */
    private $isNew;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $answer;

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
     * Set authorName
     *
     * @param string $authorName
     * @return FormMessage
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

    /**
     * Set authorEmail
     *
     * @param string $authorEmail
     * @return FormMessage
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * Get authorEmail
     *
     * @return string 
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * Set messageSubject
     *
     * @param string $messageSubject
     * @return FormMessage
     */
    public function setMessageSubject($messageSubject)
    {
        $this->messageSubject = $messageSubject;

        return $this;
    }

    /**
     * Get messageSubject
     *
     * @return string 
     */
    public function getMessageSubject()
    {
        return $this->messageSubject;
    }

    /**
     * Set messageText
     *
     * @param string $messageText
     * @return FormMessage
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;

        return $this;
    }

    /**
     * Get messageText
     *
     * @return string 
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return FormMessage
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
     * Set isNew
     *
     * @param boolean $isNew
     * @return FormMessage
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
     * Set answer
     *
     * @param string $answer
     * @return FormMessage
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
