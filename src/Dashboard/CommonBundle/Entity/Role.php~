<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Role implements RoleInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true, options={"default":null})
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $filterTitle;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=255, unique=true)
     */
    private $role;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"null"})
     */
    private $advertNumber;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"null"})
     */
    private $advertFotoNumber;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    private $users;
    
    /**
     * @ORM\ManyToMany(targetEntity="Service", inversedBy="userRoles")
     * 
     */
    private $services;
    
    /**
     * @ORM\ManyToMany(targetEntity="Payment", inversedBy="userRoles")
     * 
     */
    private $payments;
    
    /**
     * @ORM\ManyToMany(targetEntity="Pack", inversedBy="userRoles")
     * 
     */
    private $servicePacks;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Rate", mappedBy="userRole", cascade={"persist"})
     */
    private $rates;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":0})
     */
    private $invoiceText;
    
    private $newUsers;
    
    public function getNewUsers(){
        $count = 0;
        $this->getUsers()->map(function($user) use(&$count){
            if($user->getIsActive()){
                if(!$user->getIsConfirm()){
                    $count++;
                }
            }
        });
        
        return $count;
    }
    
    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->servicePacks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rates = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Role
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
     * Set filterTitle
     *
     * @param string $filterTitle
     * @return Role
     */
    public function setFilterTitle($filterTitle)
    {
        $this->filterTitle = $filterTitle;
    
        return $this;
    }

    /**
     * Get filterTitle
     *
     * @return string 
     */
    public function getFilterTitle()
    {
        return $this->filterTitle;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Set advertNumber
     *
     * @param integer $advertNumber
     * @return Role
     */
    public function setAdvertNumber($advertNumber)
    {
        $this->advertNumber = $advertNumber;
    
        return $this;
    }

    /**
     * Get advertNumber
     *
     * @return integer 
     */
    public function getAdvertNumber()
    {
        return $this->advertNumber;
    }

    /**
     * Set advertFotoNumber
     *
     * @param integer $advertFotoNumber
     * @return Role
     */
    public function setAdvertFotoNumber($advertFotoNumber)
    {
        $this->advertFotoNumber = $advertFotoNumber;
    
        return $this;
    }

    /**
     * Get advertFotoNumber
     *
     * @return integer 
     */
    public function getAdvertFotoNumber()
    {
        return $this->advertFotoNumber;
    }

    /**
     * Set invoiceText
     *
     * @param string $invoiceText
     * @return Role
     */
    public function setInvoiceText($invoiceText)
    {
        $this->invoiceText = $invoiceText;
    
        return $this;
    }

    /**
     * Get invoiceText
     *
     * @return string 
     */
    public function getInvoiceText()
    {
        return $this->invoiceText;
    }

    /**
     * Add users
     *
     * @param \Dashboard\CommonBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\Dashboard\CommonBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Dashboard\CommonBundle\Entity\User $users
     */
    public function removeUser(\Dashboard\CommonBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add services
     *
     * @param \Dashboard\CommonBundle\Entity\Service $services
     * @return Role
     */
    public function addService(\Dashboard\CommonBundle\Entity\Service $services)
    {
        $this->services[] = $services;
    
        return $this;
    }

    /**
     * Remove services
     *
     * @param \Dashboard\CommonBundle\Entity\Service $services
     */
    public function removeService(\Dashboard\CommonBundle\Entity\Service $services)
    {
        $this->services->removeElement($services);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Add payments
     *
     * @param \Dashboard\CommonBundle\Entity\Payment $payments
     * @return Role
     */
    public function addPayment(\Dashboard\CommonBundle\Entity\Payment $payments)
    {
        $this->payments[] = $payments;
    
        return $this;
    }

    /**
     * Remove payments
     *
     * @param \Dashboard\CommonBundle\Entity\Payment $payments
     */
    public function removePayment(\Dashboard\CommonBundle\Entity\Payment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Add servicePacks
     *
     * @param \Dashboard\CommonBundle\Entity\Pack $servicePacks
     * @return Role
     */
    public function addServicePack(\Dashboard\CommonBundle\Entity\Pack $servicePacks)
    {
        $this->servicePacks[] = $servicePacks;
    
        return $this;
    }

    /**
     * Remove servicePacks
     *
     * @param \Dashboard\CommonBundle\Entity\Pack $servicePacks
     */
    public function removeServicePack(\Dashboard\CommonBundle\Entity\Pack $servicePacks)
    {
        $this->servicePacks->removeElement($servicePacks);
    }

    /**
     * Get servicePacks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServicePacks()
    {
        return $this->servicePacks;
    }

    /**
     * Add rates
     *
     * @param \Dashboard\CommonBundle\Entity\Rate $rates
     * @return Role
     */
    public function addRate(\Dashboard\CommonBundle\Entity\Rate $rates)
    {
        $this->rates[] = $rates;
    
        return $this;
    }

    /**
     * Remove rates
     *
     * @param \Dashboard\CommonBundle\Entity\Rate $rates
     */
    public function removeRate(\Dashboard\CommonBundle\Entity\Rate $rates)
    {
        $this->rates->removeElement($rates);
    }

    /**
     * Get rates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRates()
    {
        return $this->rates;
    }
}
