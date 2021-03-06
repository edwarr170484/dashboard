<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class DealerInfo
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="dealerinfo")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $company;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $firma;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $nifNumber;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $website;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\CityCode")
     * @ORM\JoinColumn(name="city_code_id", referencedColumnName="id")
     */
    private $cityCode;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $email;  
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\DealerPhone", mappedBy="dealerInfo")
     */
    private $phones;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $logotype; 
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default": null})
     */
    private $description; 
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Category", inversedBy="dealers")
     * @ORM\JoinTable(name="dealer_autos")
     */
    private $autos;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isNewAuto; 
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isOldAuto;
    
    /**
     * @ORM\Column(type="integer", length=3, nullable=true, options={"default": 0})
     */
    private $rating;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\DealerFoto", mappedBy="dealerInfo")
     */
    private $fotos;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Workinfo", mappedBy="dealer", cascade={"persist"})
     */
    private $workinfo;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\DealerSalon", mappedBy="dealerInfo")
     */
    private $salons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->autos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fotos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set company
     *
     * @param string $company
     * @return DealerInfo
     */
    public function setCompany($company)
    {
        $this->company = $company;
    
        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set firma
     *
     * @param string $firma
     * @return DealerInfo
     */
    public function setFirma($firma)
    {
        $this->firma = $firma;
    
        return $this;
    }

    /**
     * Get firma
     *
     * @return string 
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * Set nifNumber
     *
     * @param string $nifNumber
     * @return DealerInfo
     */
    public function setNifNumber($nifNumber)
    {
        $this->nifNumber = $nifNumber;
    
        return $this;
    }

    /**
     * Get nifNumber
     *
     * @return string 
     */
    public function getNifNumber()
    {
        return $this->nifNumber;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return DealerInfo
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return DealerInfo
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return DealerInfo
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set logotype
     *
     * @param string $logotype
     * @return DealerInfo
     */
    public function setLogotype($logotype)
    {
        $this->logotype = $logotype;
    
        return $this;
    }

    /**
     * Get logotype
     *
     * @return string 
     */
    public function getLogotype()
    {
        return $this->logotype;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return DealerInfo
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isNewAuto
     *
     * @param boolean $isNewAuto
     * @return DealerInfo
     */
    public function setIsNewAuto($isNewAuto)
    {
        $this->isNewAuto = $isNewAuto;
    
        return $this;
    }

    /**
     * Get isNewAuto
     *
     * @return boolean 
     */
    public function getIsNewAuto()
    {
        return $this->isNewAuto;
    }

    /**
     * Set isOldAuto
     *
     * @param boolean $isOldAuto
     * @return DealerInfo
     */
    public function setIsOldAuto($isOldAuto)
    {
        $this->isOldAuto = $isOldAuto;
    
        return $this;
    }

    /**
     * Get isOldAuto
     *
     * @return boolean 
     */
    public function getIsOldAuto()
    {
        return $this->isOldAuto;
    }

    /**
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return DealerInfo
     */
    public function setUser(\Dashboard\CommonBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set city
     *
     * @param \Dashboard\CommonBundle\Entity\City $city
     * @return DealerInfo
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
     * Set cityCode
     *
     * @param \Dashboard\CommonBundle\Entity\CityCode $cityCode
     * @return DealerInfo
     */
    public function setCityCode(\Dashboard\CommonBundle\Entity\CityCode $cityCode = null)
    {
        $this->cityCode = $cityCode;
    
        return $this;
    }

    /**
     * Get cityCode
     *
     * @return \Dashboard\CommonBundle\Entity\CityCode 
     */
    public function getCityCode()
    {
        return $this->cityCode;
    }

    /**
     * Add phones
     *
     * @param \Dashboard\CommonBundle\Entity\DealerPhone $phones
     * @return DealerInfo
     */
    public function addPhone(\Dashboard\CommonBundle\Entity\DealerPhone $phones)
    {
        $this->phones[] = $phones;
    
        return $this;
    }

    /**
     * Remove phones
     *
     * @param \Dashboard\CommonBundle\Entity\DealerPhone $phones
     */
    public function removePhone(\Dashboard\CommonBundle\Entity\DealerPhone $phones)
    {
        $this->phones->removeElement($phones);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add autos
     *
     * @param \Dashboard\CommonBundle\Entity\Category $autos
     * @return DealerInfo
     */
    public function addAuto(\Dashboard\CommonBundle\Entity\Category $autos)
    {
        $this->autos[] = $autos;
    
        return $this;
    }

    /**
     * Remove autos
     *
     * @param \Dashboard\CommonBundle\Entity\Category $autos
     */
    public function removeAuto(\Dashboard\CommonBundle\Entity\Category $autos)
    {
        $this->autos->removeElement($autos);
    }

    /**
     * Get autos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAutos()
    {
        return $this->autos;
    }

    /**
     * Add fotos
     *
     * @param \Dashboard\CommonBundle\Entity\DealerFoto $fotos
     * @return DealerInfo
     */
    public function addFoto(\Dashboard\CommonBundle\Entity\DealerFoto $fotos)
    {
        $this->fotos[] = $fotos;
    
        return $this;
    }

    /**
     * Remove fotos
     *
     * @param \Dashboard\CommonBundle\Entity\DealerFoto $fotos
     */
    public function removeFoto(\Dashboard\CommonBundle\Entity\DealerFoto $fotos)
    {
        $this->fotos->removeElement($fotos);
    }

    /**
     * Get fotos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFotos()
    {
        return $this->fotos;
    }

    /**
     * Set workinfo
     *
     * @param \Dashboard\CommonBundle\Entity\Workinfo $workinfo
     * @return DealerInfo
     */
    public function setWorkinfo(\Dashboard\CommonBundle\Entity\Workinfo $workinfo = null)
    {
        $this->workinfo = $workinfo;
    
        return $this;
    }

    /**
     * Get workinfo
     *
     * @return \Dashboard\CommonBundle\Entity\Workinfo 
     */
    public function getWorkinfo()
    {
        return $this->workinfo;
    }

    /**
     * Add salons
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalon $salons
     * @return DealerInfo
     */
    public function addSalon(\Dashboard\CommonBundle\Entity\DealerSalon $salons)
    {
        $this->salons[] = $salons;
    
        return $this;
    }

    /**
     * Remove salons
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalon $salons
     */
    public function removeSalon(\Dashboard\CommonBundle\Entity\DealerSalon $salons)
    {
        $this->salons->removeElement($salons);
    }

    /**
     * Get salons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSalons()
    {
        return $this->salons;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return DealerInfo
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set region
     *
     * @param \Dashboard\CommonBundle\Entity\Region $region
     * @return DealerInfo
     */
    public function setRegion(\Dashboard\CommonBundle\Entity\Region $region = null)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return \Dashboard\CommonBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }
}
