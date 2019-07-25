<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="region")
 * @ORM\Entity()
 */
class Region
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\City", mappedBy="region")
     * @ORM\OrderBy({"sortorder" = "ASC"})
     **/
    private $city;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Product", mappedBy="region")
     */
    private $product;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\UserInfo", mappedBy="region")
     */
    private $user;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="region", cascade={"persist"})
     */
    private $translations;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $sortorder;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->city = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Region
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
     * Add city
     *
     * @param \Dashboard\CommonBundle\Entity\City $city
     * @return Region
     */
    public function addCity(\Dashboard\CommonBundle\Entity\City $city)
    {
        $this->city[] = $city;
    
        return $this;
    }

    /**
     * Remove city
     *
     * @param \Dashboard\CommonBundle\Entity\City $city
     */
    public function removeCity(\Dashboard\CommonBundle\Entity\City $city)
    {
        $this->city->removeElement($city);
    }

    /**
     * Get city
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     * @return Region
     */
    public function addProduct(\Dashboard\CommonBundle\Entity\Product $product)
    {
        $this->product[] = $product;
    
        return $this;
    }

    /**
     * Remove product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     */
    public function removeProduct(\Dashboard\CommonBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add user
     *
     * @param \Dashboard\CommonBundle\Entity\UserInfo $user
     * @return Region
     */
    public function addUser(\Dashboard\CommonBundle\Entity\UserInfo $user)
    {
        $this->user[] = $user;
    
        return $this;
    }

    /**
     * Remove user
     *
     * @param \Dashboard\CommonBundle\Entity\UserInfo $user
     */
    public function removeUser(\Dashboard\CommonBundle\Entity\UserInfo $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     * @return Region
     */
    public function addTranslation(\Dashboard\CommonBundle\Entity\Translation $translations)
    {
        $this->translations[] = $translations;
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     */
    public function removeTranslation(\Dashboard\CommonBundle\Entity\Translation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return Region
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;
    
        return $this;
    }

    /**
     * Get sortorder
     *
     * @return integer 
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }
}
