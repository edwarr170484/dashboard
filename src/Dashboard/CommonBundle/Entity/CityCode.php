<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class CityCode
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\City", inversedBy="codes")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\UserInfo", mappedBy="cityCode")
     */
    private $users;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Product", mappedBy="cityCode")
     */
    private $products;
    

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
     * Set code
     *
     * @param string $code
     * @return CityCode
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
     * Set city
     *
     * @param \Dashboard\CommonBundle\Entity\City $city
     * @return CityCode
     */
    public function setCity(\Dashboard\CommonBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Dashboard\CommonBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param \Dashboard\CommonBundle\Entity\UserInfo $users
     * @return CityCode
     */
    public function addUser(\Dashboard\CommonBundle\Entity\UserInfo $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Dashboard\CommonBundle\Entity\UserInfo $users
     */
    public function removeUser(\Dashboard\CommonBundle\Entity\UserInfo $users)
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
     * Add products
     *
     * @param \Dashboard\CommonBundle\Entity\Product $products
     * @return CityCode
     */
    public function addProduct(\Dashboard\CommonBundle\Entity\Product $products)
    {
        $this->products[] = $products;
    
        return $this;
    }

    /**
     * Remove products
     *
     * @param \Dashboard\CommonBundle\Entity\Product $products
     */
    public function removeProduct(\Dashboard\CommonBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }
}
