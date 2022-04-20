<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="role")
 * @ORM\Entity()
 */
class Role implements RoleInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @see RoleInterface
     */
    public function getRole()
    {
        return $this->role;
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
     * Set name
     *
     * @param string $name
     *
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
     *
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Add user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     *
     * @return Role
     */
    public function addUser(\Dashboard\CommonBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     */
    public function removeUser(\Dashboard\CommonBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
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
}
