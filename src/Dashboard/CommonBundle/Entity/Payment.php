<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Payment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": 0})
     */
    private $title;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default": 0})
     */
    private $icon;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default": 0})
     */
    private $tieser;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default": 0})
     */
    private $info;
    
    /**
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="payments")
     */
    private $userRoles;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userRoles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Payment
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Payment
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    
        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set tieser
     *
     * @param string $tieser
     * @return Payment
     */
    public function setTieser($tieser)
    {
        $this->tieser = $tieser;
    
        return $this;
    }

    /**
     * Get tieser
     *
     * @return string 
     */
    public function getTieser()
    {
        return $this->tieser;
    }

    /**
     * Set info
     *
     * @param string $info
     * @return Payment
     */
    public function setInfo($info)
    {
        $this->info = $info;
    
        return $this;
    }

    /**
     * Get info
     *
     * @return string 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Add userRoles
     *
     * @param \Dashboard\CommonBundle\Entity\Role $userRoles
     * @return Payment
     */
    public function addUserRole(\Dashboard\CommonBundle\Entity\Role $userRoles)
    {
        $this->userRoles[] = $userRoles;
    
        return $this;
    }

    /**
     * Remove userRoles
     *
     * @param \Dashboard\CommonBundle\Entity\Role $userRoles
     */
    public function removeUserRole(\Dashboard\CommonBundle\Entity\Role $userRoles)
    {
        $this->userRoles->removeElement($userRoles);
    }

    /**
     * Get userRoles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }
}
