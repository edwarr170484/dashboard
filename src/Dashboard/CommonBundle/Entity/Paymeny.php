<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paymeny
 */
class Paymeny
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title = 0;

    /**
     * @var string
     */
    private $icon = 0;

    /**
     * @var string
     */
    private $tieser = 0;

    /**
     * @var string
     */
    private $info = 0;

    /**
     * @var \Doctrine\Common\Collections\Collection
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
     * @return Paymeny
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
     * @return Paymeny
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
     * @return Paymeny
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
     * @return Paymeny
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
     * @return Paymeny
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
