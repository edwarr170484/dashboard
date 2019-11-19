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
    private $nifNumber;
    
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
    private $website;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $firstname;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $lastname;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $position;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true, options={"default": null})
     */
    private $phone;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $avatar;
    
    /**
     * @ORM\Column(type="integer", length=3, nullable=true, options={"default": 0})
     */
    private $rating;
    

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
     * Set firstname
     *
     * @param string $firstname
     * @return DealerInfo
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return DealerInfo
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return DealerInfo
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return DealerInfo
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return DealerInfo
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
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
}
