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
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": 0})
     */
    private $controller;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": 0})
     */
    private $code;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Bill", mappedBy="payment")
     */
    private $bills;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\RateBill", mappedBy="payment")
     */
    private $rateBills;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": 0})
     */
    private $clientId;
    
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

    /**
     * Set controller
     *
     * @param string $controller
     * @return Payment
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    
        return $this;
    }

    /**
     * Get controller
     *
     * @return string 
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Payment
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add bills
     *
     * @param \Dashboard\CommonBundle\Entity\Bill $bills
     * @return Payment
     */
    public function addBill(\Dashboard\CommonBundle\Entity\Bill $bills)
    {
        $this->bills[] = $bills;
    
        return $this;
    }

    /**
     * Remove bills
     *
     * @param \Dashboard\CommonBundle\Entity\Bill $bills
     */
    public function removeBill(\Dashboard\CommonBundle\Entity\Bill $bills)
    {
        $this->bills->removeElement($bills);
    }

    /**
     * Get bills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * Add rateBills
     *
     * @param \Dashboard\CommonBundle\Entity\RateBill $rateBills
     * @return Payment
     */
    public function addRateBill(\Dashboard\CommonBundle\Entity\RateBill $rateBills)
    {
        $this->rateBills[] = $rateBills;
    
        return $this;
    }

    /**
     * Remove rateBills
     *
     * @param \Dashboard\CommonBundle\Entity\RateBill $rateBills
     */
    public function removeRateBill(\Dashboard\CommonBundle\Entity\RateBill $rateBills)
    {
        $this->rateBills->removeElement($rateBills);
    }

    /**
     * Get rateBills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRateBills()
    {
        return $this->rateBills;
    }

    /**
     * Set clientId
     *
     * @param string $clientId
     * @return Payment
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    
        return $this;
    }

    /**
     * Get clientId
     *
     * @return string 
     */
    public function getClientId()
    {
        return $this->clientId;
    }
}
