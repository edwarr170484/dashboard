<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Settings
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $userDefaultGroup;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $userAdvertLimitText;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":"0"})
     */
    private $siteName;
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $siteDescription;
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $adminEmail;
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $categoryProductNumber;
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $topsellerBlockNumber;
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $mainpageAdvertsNumber;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $advertDaysShowNumber;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $siteLogo;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $watermark;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $catpagePremiumNumber;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $selectedAdvPrice;
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $premiumAdvPrice;
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $upAdvPrice;
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $conversationIndex;
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $aditionalAdvertPrice;
    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $dafaultOrderStatus;
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $successAddAdvertText;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $copyright;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isModerate;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isShowCaptcha;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isShowType;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $textblockHowToPrice;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $textblockUserAgreement;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $userAdvertWorkRight;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Currency", inversedBy="settings")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Locale", inversedBy="settings")
     * @ORM\JoinColumn(name="locale_id", referencedColumnName="id")
     */
    private $locale;
    

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
     * Set userDefaultGroup
     *
     * @param integer $userDefaultGroup
     * @return Settings
     */
    public function setUserDefaultGroup($userDefaultGroup)
    {
        $this->userDefaultGroup = $userDefaultGroup;
    
        return $this;
    }

    /**
     * Get userDefaultGroup
     *
     * @return integer 
     */
    public function getUserDefaultGroup()
    {
        return $this->userDefaultGroup;
    }

    /**
     * Set userAdvertLimitText
     *
     * @param string $userAdvertLimitText
     * @return Settings
     */
    public function setUserAdvertLimitText($userAdvertLimitText)
    {
        $this->userAdvertLimitText = $userAdvertLimitText;
    
        return $this;
    }

    /**
     * Get userAdvertLimitText
     *
     * @return string 
     */
    public function getUserAdvertLimitText()
    {
        return $this->userAdvertLimitText;
    }

    /**
     * Set siteName
     *
     * @param string $siteName
     * @return Settings
     */
    public function setSiteName($siteName)
    {
        $this->siteName = $siteName;
    
        return $this;
    }

    /**
     * Get siteName
     *
     * @return string 
     */
    public function getSiteName()
    {
        return $this->siteName;
    }

    /**
     * Set siteDescription
     *
     * @param string $siteDescription
     * @return Settings
     */
    public function setSiteDescription($siteDescription)
    {
        $this->siteDescription = $siteDescription;
    
        return $this;
    }

    /**
     * Get siteDescription
     *
     * @return string 
     */
    public function getSiteDescription()
    {
        return $this->siteDescription;
    }

    /**
     * Set adminEmail
     *
     * @param string $adminEmail
     * @return Settings
     */
    public function setAdminEmail($adminEmail)
    {
        $this->adminEmail = $adminEmail;
    
        return $this;
    }

    /**
     * Get adminEmail
     *
     * @return string 
     */
    public function getAdminEmail()
    {
        return $this->adminEmail;
    }

    /**
     * Set categoryProductNumber
     *
     * @param integer $categoryProductNumber
     * @return Settings
     */
    public function setCategoryProductNumber($categoryProductNumber)
    {
        $this->categoryProductNumber = $categoryProductNumber;
    
        return $this;
    }

    /**
     * Get categoryProductNumber
     *
     * @return integer 
     */
    public function getCategoryProductNumber()
    {
        return $this->categoryProductNumber;
    }

    /**
     * Set topsellerBlockNumber
     *
     * @param integer $topsellerBlockNumber
     * @return Settings
     */
    public function setTopsellerBlockNumber($topsellerBlockNumber)
    {
        $this->topsellerBlockNumber = $topsellerBlockNumber;
    
        return $this;
    }

    /**
     * Get topsellerBlockNumber
     *
     * @return integer 
     */
    public function getTopsellerBlockNumber()
    {
        return $this->topsellerBlockNumber;
    }

    /**
     * Set mainpageAdvertsNumber
     *
     * @param integer $mainpageAdvertsNumber
     * @return Settings
     */
    public function setMainpageAdvertsNumber($mainpageAdvertsNumber)
    {
        $this->mainpageAdvertsNumber = $mainpageAdvertsNumber;
    
        return $this;
    }

    /**
     * Get mainpageAdvertsNumber
     *
     * @return integer 
     */
    public function getMainpageAdvertsNumber()
    {
        return $this->mainpageAdvertsNumber;
    }

    /**
     * Set advertDaysShowNumber
     *
     * @param integer $advertDaysShowNumber
     * @return Settings
     */
    public function setAdvertDaysShowNumber($advertDaysShowNumber)
    {
        $this->advertDaysShowNumber = $advertDaysShowNumber;
    
        return $this;
    }

    /**
     * Get advertDaysShowNumber
     *
     * @return integer 
     */
    public function getAdvertDaysShowNumber()
    {
        return $this->advertDaysShowNumber;
    }

    /**
     * Set siteLogo
     *
     * @param string $siteLogo
     * @return Settings
     */
    public function setSiteLogo($siteLogo)
    {
        $this->siteLogo = $siteLogo;
    
        return $this;
    }

    /**
     * Get siteLogo
     *
     * @return string 
     */
    public function getSiteLogo()
    {
        return $this->siteLogo;
    }

    /**
     * Set watermark
     *
     * @param string $watermark
     * @return Settings
     */
    public function setWatermark($watermark)
    {
        $this->watermark = $watermark;
    
        return $this;
    }

    /**
     * Get watermark
     *
     * @return string 
     */
    public function getWatermark()
    {
        return $this->watermark;
    }

    /**
     * Set catpagePremiumNumber
     *
     * @param integer $catpagePremiumNumber
     * @return Settings
     */
    public function setCatpagePremiumNumber($catpagePremiumNumber)
    {
        $this->catpagePremiumNumber = $catpagePremiumNumber;
    
        return $this;
    }

    /**
     * Get catpagePremiumNumber
     *
     * @return integer 
     */
    public function getCatpagePremiumNumber()
    {
        return $this->catpagePremiumNumber;
    }

    /**
     * Set selectedAdvPrice
     *
     * @param integer $selectedAdvPrice
     * @return Settings
     */
    public function setSelectedAdvPrice($selectedAdvPrice)
    {
        $this->selectedAdvPrice = $selectedAdvPrice;
    
        return $this;
    }

    /**
     * Get selectedAdvPrice
     *
     * @return integer 
     */
    public function getSelectedAdvPrice()
    {
        return $this->selectedAdvPrice;
    }

    /**
     * Set premiumAdvPrice
     *
     * @param integer $premiumAdvPrice
     * @return Settings
     */
    public function setPremiumAdvPrice($premiumAdvPrice)
    {
        $this->premiumAdvPrice = $premiumAdvPrice;
    
        return $this;
    }

    /**
     * Get premiumAdvPrice
     *
     * @return integer 
     */
    public function getPremiumAdvPrice()
    {
        return $this->premiumAdvPrice;
    }

    /**
     * Set upAdvPrice
     *
     * @param integer $upAdvPrice
     * @return Settings
     */
    public function setUpAdvPrice($upAdvPrice)
    {
        $this->upAdvPrice = $upAdvPrice;
    
        return $this;
    }

    /**
     * Get upAdvPrice
     *
     * @return integer 
     */
    public function getUpAdvPrice()
    {
        return $this->upAdvPrice;
    }

    /**
     * Set conversationIndex
     *
     * @param integer $conversationIndex
     * @return Settings
     */
    public function setConversationIndex($conversationIndex)
    {
        $this->conversationIndex = $conversationIndex;
    
        return $this;
    }

    /**
     * Get conversationIndex
     *
     * @return integer 
     */
    public function getConversationIndex()
    {
        return $this->conversationIndex;
    }

    /**
     * Set aditionalAdvertPrice
     *
     * @param integer $aditionalAdvertPrice
     * @return Settings
     */
    public function setAditionalAdvertPrice($aditionalAdvertPrice)
    {
        $this->aditionalAdvertPrice = $aditionalAdvertPrice;
    
        return $this;
    }

    /**
     * Get aditionalAdvertPrice
     *
     * @return integer 
     */
    public function getAditionalAdvertPrice()
    {
        return $this->aditionalAdvertPrice;
    }

    /**
     * Set dafaultOrderStatus
     *
     * @param integer $dafaultOrderStatus
     * @return Settings
     */
    public function setDafaultOrderStatus($dafaultOrderStatus)
    {
        $this->dafaultOrderStatus = $dafaultOrderStatus;
    
        return $this;
    }

    /**
     * Get dafaultOrderStatus
     *
     * @return integer 
     */
    public function getDafaultOrderStatus()
    {
        return $this->dafaultOrderStatus;
    }

    /**
     * Set successAddAdvertText
     *
     * @param string $successAddAdvertText
     * @return Settings
     */
    public function setSuccessAddAdvertText($successAddAdvertText)
    {
        $this->successAddAdvertText = $successAddAdvertText;
    
        return $this;
    }

    /**
     * Get successAddAdvertText
     *
     * @return string 
     */
    public function getSuccessAddAdvertText()
    {
        return $this->successAddAdvertText;
    }

    /**
     * Set isModerate
     *
     * @param boolean $isModerate
     * @return Settings
     */
    public function setIsModerate($isModerate)
    {
        $this->isModerate = $isModerate;
    
        return $this;
    }

    /**
     * Get isModerate
     *
     * @return boolean 
     */
    public function getIsModerate()
    {
        return $this->isModerate;
    }

    /**
     * Set locale
     *
     * @param \Dashboard\CommonBundle\Entity\Locale $locale
     * @return Settings
     */
    public function setLocale(\Dashboard\CommonBundle\Entity\Locale $locale = null)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return \Dashboard\CommonBundle\Entity\Locale 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set currency
     *
     * @param \Dashboard\CommonBundle\Entity\Currency $currency
     * @return Settings
     */
    public function setCurrency(\Dashboard\CommonBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;
    
        return $this;
    }

    /**
     * Get currency
     *
     * @return \Dashboard\CommonBundle\Entity\Currency 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set copyright
     *
     * @param string $copyright
     * @return Settings
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    
        return $this;
    }

    /**
     * Get copyright
     *
     * @return string 
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * Set textblockHowToPrice
     *
     * @param string $textblockHowToPrice
     * @return Settings
     */
    public function setTextblockHowToPrice($textblockHowToPrice)
    {
        $this->textblockHowToPrice = $textblockHowToPrice;
    
        return $this;
    }

    /**
     * Get textblockHowToPrice
     *
     * @return string 
     */
    public function getTextblockHowToPrice()
    {
        return $this->textblockHowToPrice;
    }

    /**
     * Set textblockUserAgreement
     *
     * @param string $textblockUserAgreement
     * @return Settings
     */
    public function setTextblockUserAgreement($textblockUserAgreement)
    {
        $this->textblockUserAgreement = $textblockUserAgreement;
    
        return $this;
    }

    /**
     * Get textblockUserAgreement
     *
     * @return string 
     */
    public function getTextblockUserAgreement()
    {
        return $this->textblockUserAgreement;
    }

    /**
     * Set userAdvertWorkRight
     *
     * @param string $userAdvertWorkRight
     * @return Settings
     */
    public function setUserAdvertWorkRight($userAdvertWorkRight)
    {
        $this->userAdvertWorkRight = $userAdvertWorkRight;
    
        return $this;
    }

    /**
     * Get userAdvertWorkRight
     *
     * @return string 
     */
    public function getUserAdvertWorkRight()
    {
        return $this->userAdvertWorkRight;
    }

    /**
     * Set isShowCaptcha
     *
     * @param boolean $isShowCaptcha
     * @return Settings
     */
    public function setIsShowCaptcha($isShowCaptcha)
    {
        $this->isShowCaptcha = $isShowCaptcha;
    
        return $this;
    }

    /**
     * Get isShowCaptcha
     *
     * @return boolean 
     */
    public function getIsShowCaptcha()
    {
        return $this->isShowCaptcha;
    }

    /**
     * Set isShowType
     *
     * @param boolean $isShowType
     * @return Settings
     */
    public function setIsShowType($isShowType)
    {
        $this->isShowType = $isShowType;
    
        return $this;
    }

    /**
     * Get isShowType
     *
     * @return boolean 
     */
    public function getIsShowType()
    {
        return $this->isShowType;
    }
}
