<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Blacklist
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
    private $userAuthor;
    
    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $userTo;
    

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
     * Set userAuthor
     *
     * @param integer $userAuthor
     * @return Blacklist
     */
    public function setUserAuthor($userAuthor)
    {
        $this->userAuthor = $userAuthor;

        return $this;
    }

    /**
     * Get userAuthor
     *
     * @return integer 
     */
    public function getUserAuthor()
    {
        return $this->userAuthor;
    }

    /**
     * Set userTo
     *
     * @param integer $userTo
     * @return Blacklist
     */
    public function setUserTo($userTo)
    {
        $this->userTo = $userTo;

        return $this;
    }

    /**
     * Get userTo
     *
     * @return integer 
     */
    public function getUserTo()
    {
        return $this->userTo;
    }
}
