<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_info")
 * @ORM\Entity()
 */
class UserInfo
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $firstname;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $midname;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $lastname;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $sex;
    /**
     * @ORM\Column(type="string", length=50, nullable=true, options={"default":"null"})
     */
    private $phone;
    
    /**
     * @ORM\Column(type="integer", length=3, nullable=true, options={"default":"null"})
     */
    private $birthdayday;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true, options={"default":"null"})
     */
    private $birthdaymonth;
    
    /**
     * @ORM\Column(type="string", length=5, nullable=true, options={"default":"null"})
     */
    private $birthdayyear;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Region", inversedBy="user")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\City", inversedBy="user")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $avatar;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"0"})
     */
    private $emailmessagesalerts;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"0"})
     */
    private $emailmessagesreminders;
    
    /**
     * @ORM\Column(type="integer", length=3, nullable=true, options={"default":"0"})
     */
    private $rating;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="userinfo")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * Set firstname
     *
     * @param string $firstname
     * @return UserInfo
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
     * Set midname
     *
     * @param string $midname
     * @return UserInfo
     */
    public function setMidname($midname)
    {
        $this->midname = $midname;

        return $this;
    }

    /**
     * Get midname
     *
     * @return string 
     */
    public function getMidname()
    {
        return $this->midname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return UserInfo
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
     * Set phone
     *
     * @param string $phone
     * @return UserInfo
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
     * Set birthdayday
     *
     * @param integer $birthdayday
     * @return UserInfo
     */
    public function setBirthdayday($birthdayday)
    {
        $this->birthdayday = $birthdayday;

        return $this;
    }

    /**
     * Get birthdayday
     *
     * @return integer 
     */
    public function getBirthdayday()
    {
        return $this->birthdayday;
    }

    /**
     * Set birthdaymonth
     *
     * @param string $birthdaymonth
     * @return UserInfo
     */
    public function setBirthdaymonth($birthdaymonth)
    {
        $this->birthdaymonth = $birthdaymonth;

        return $this;
    }

    /**
     * Get birthdaymonth
     *
     * @return string 
     */
    public function getBirthdaymonth()
    {
        return $this->birthdaymonth;
    }

    /**
     * Set birthdayyear
     *
     * @param string $birthdayyear
     * @return UserInfo
     */
    public function setBirthdayyear($birthdayyear)
    {
        $this->birthdayyear = $birthdayyear;

        return $this;
    }

    /**
     * Get birthdayyear
     *
     * @return string 
     */
    public function getBirthdayyear()
    {
        return $this->birthdayyear;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return UserInfo
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
     * Set emailmessagesalerts
     *
     * @param boolean $emailmessagesalerts
     * @return UserInfo
     */
    public function setEmailmessagesalerts($emailmessagesalerts)
    {
        $this->emailmessagesalerts = $emailmessagesalerts;

        return $this;
    }

    /**
     * Get emailmessagesalerts
     *
     * @return boolean 
     */
    public function getEmailmessagesalerts()
    {
        return $this->emailmessagesalerts;
    }

    /**
     * Set emailmessagesreminders
     *
     * @param boolean $emailmessagesreminders
     * @return UserInfo
     */
    public function setEmailmessagesreminders($emailmessagesreminders)
    {
        $this->emailmessagesreminders = $emailmessagesreminders;

        return $this;
    }

    /**
     * Get emailmessagesreminders
     *
     * @return boolean 
     */
    public function getEmailmessagesreminders()
    {
        return $this->emailmessagesreminders;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     * @return UserInfo
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
     * @return UserInfo
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

    /**
     * Set city
     *
     * @param \Dashboard\CommonBundle\Entity\City $city
     * @return UserInfo
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
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return UserInfo
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
     * Set sex
     *
     * @param string $sex
     * @return UserInfo
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex()
    {
        return $this->sex;
    }
}
