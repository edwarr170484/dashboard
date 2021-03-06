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
    private $categoryPanelItemsNumber;
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $mainpageAdvertsNumber;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $userMessagesNumber;
    
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
     * @ORM\Column(type="float", length=15, nullable=true, options={"default":"0"})
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
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\OrderStatus")
     * @ORM\JoinColumn(name="default_orderstatus_id", referencedColumnName="id")
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
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category")
     * @ORM\JoinColumn(name="default_category_id", referencedColumnName="id")
     */
    private $mainPageDefaultCategory;
    
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
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $serviceTabText;
    
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
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $googleMapsKey;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $centerLat;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $centerLng;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\ReviewStatus")
     * @ORM\JoinColumn(name="review_status_id", referencedColumnName="id")
     */
    private $newReviewStatus;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\ReviewStatus")
     * @ORM\JoinColumn(name="review_public_status_id", referencedColumnName="id")
     */
    private $publicReviewStatus;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\OrderStatus")
     * @ORM\JoinColumn(name="review_order_status_id", referencedColumnName="id")
     */
    private $orderReviewStatus;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Service")
     * @ORM\JoinColumn(name="premium_service_id", referencedColumnName="id")
     */
    private $premiumService;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Service")
     * @ORM\JoinColumn(name="special_service_id", referencedColumnName="id")
     */
    private $specialService;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Service")
     * @ORM\JoinColumn(name="selected_service_id", referencedColumnName="id")
     */
    private $selectedService;


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
     * Set categoryPanelItemsNumber
     *
     * @param integer $categoryPanelItemsNumber
     * @return Settings
     */
    public function setCategoryPanelItemsNumber($categoryPanelItemsNumber)
    {
        $this->categoryPanelItemsNumber = $categoryPanelItemsNumber;

        return $this;
    }

    /**
     * Get categoryPanelItemsNumber
     *
     * @return integer 
     */
    public function getCategoryPanelItemsNumber()
    {
        return $this->categoryPanelItemsNumber;
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
     * Set userMessagesNumber
     *
     * @param integer $userMessagesNumber
     * @return Settings
     */
    public function setUserMessagesNumber($userMessagesNumber)
    {
        $this->userMessagesNumber = $userMessagesNumber;

        return $this;
    }

    /**
     * Get userMessagesNumber
     *
     * @return integer 
     */
    public function getUserMessagesNumber()
    {
        return $this->userMessagesNumber;
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
     * @param float $premiumAdvPrice
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
     * @return float 
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
     * Set serviceTabText
     *
     * @param string $serviceTabText
     * @return Settings
     */
    public function setServiceTabText($serviceTabText)
    {
        $this->serviceTabText = $serviceTabText;

        return $this;
    }

    /**
     * Get serviceTabText
     *
     * @return string 
     */
    public function getServiceTabText()
    {
        return $this->serviceTabText;
    }

    /**
     * Set googleMapsKey
     *
     * @param string $googleMapsKey
     * @return Settings
     */
    public function setGoogleMapsKey($googleMapsKey)
    {
        $this->googleMapsKey = $googleMapsKey;

        return $this;
    }

    /**
     * Get googleMapsKey
     *
     * @return string 
     */
    public function getGoogleMapsKey()
    {
        return $this->googleMapsKey;
    }

    /**
     * Set centerLat
     *
     * @param string $centerLat
     * @return Settings
     */
    public function setCenterLat($centerLat)
    {
        $this->centerLat = $centerLat;

        return $this;
    }

    /**
     * Get centerLat
     *
     * @return string 
     */
    public function getCenterLat()
    {
        return $this->centerLat;
    }

    /**
     * Set centerLng
     *
     * @param string $centerLng
     * @return Settings
     */
    public function setCenterLng($centerLng)
    {
        $this->centerLng = $centerLng;

        return $this;
    }

    /**
     * Get centerLng
     *
     * @return string 
     */
    public function getCenterLng()
    {
        return $this->centerLng;
    }

    /**
     * Set dafaultOrderStatus
     *
     * @param \Dashboard\CommonBundle\Entity\OrderStatus $dafaultOrderStatus
     * @return Settings
     */
    public function setDafaultOrderStatus(\Dashboard\CommonBundle\Entity\OrderStatus $dafaultOrderStatus = null)
    {
        $this->dafaultOrderStatus = $dafaultOrderStatus;

        return $this;
    }

    /**
     * Get dafaultOrderStatus
     *
     * @return \Dashboard\CommonBundle\Entity\OrderStatus 
     */
    public function getDafaultOrderStatus()
    {
        return $this->dafaultOrderStatus;
    }

    /**
     * Set mainPageDefaultCategory
     *
     * @param \Dashboard\CommonBundle\Entity\Category $mainPageDefaultCategory
     * @return Settings
     */
    public function setMainPageDefaultCategory(\Dashboard\CommonBundle\Entity\Category $mainPageDefaultCategory = null)
    {
        $this->mainPageDefaultCategory = $mainPageDefaultCategory;

        return $this;
    }

    /**
     * Get mainPageDefaultCategory
     *
     * @return \Dashboard\CommonBundle\Entity\Category 
     */
    public function getMainPageDefaultCategory()
    {
        return $this->mainPageDefaultCategory;
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
     * Set newReviewStatus
     *
     * @param \Dashboard\CommonBundle\Entity\ReviewStatus $newReviewStatus
     * @return Settings
     */
    public function setNewReviewStatus(\Dashboard\CommonBundle\Entity\ReviewStatus $newReviewStatus = null)
    {
        $this->newReviewStatus = $newReviewStatus;

        return $this;
    }

    /**
     * Get newReviewStatus
     *
     * @return \Dashboard\CommonBundle\Entity\ReviewStatus 
     */
    public function getNewReviewStatus()
    {
        return $this->newReviewStatus;
    }

    /**
     * Set publicReviewStatus
     *
     * @param \Dashboard\CommonBundle\Entity\ReviewStatus $publicReviewStatus
     * @return Settings
     */
    public function setPublicReviewStatus(\Dashboard\CommonBundle\Entity\ReviewStatus $publicReviewStatus = null)
    {
        $this->publicReviewStatus = $publicReviewStatus;

        return $this;
    }

    /**
     * Get publicReviewStatus
     *
     * @return \Dashboard\CommonBundle\Entity\ReviewStatus 
     */
    public function getPublicReviewStatus()
    {
        return $this->publicReviewStatus;
    }

    /**
     * Set orderReviewStatus
     *
     * @param \Dashboard\CommonBundle\Entity\OrderStatus $orderReviewStatus
     * @return Settings
     */
    public function setOrderReviewStatus(\Dashboard\CommonBundle\Entity\OrderStatus $orderReviewStatus = null)
    {
        $this->orderReviewStatus = $orderReviewStatus;

        return $this;
    }

    /**
     * Get orderReviewStatus
     *
     * @return \Dashboard\CommonBundle\Entity\OrderStatus 
     */
    public function getOrderReviewStatus()
    {
        return $this->orderReviewStatus;
    }

    /**
     * Set premiumService
     *
     * @param \Dashboard\CommonBundle\Entity\Service $premiumService
     * @return Settings
     */
    public function setPremiumService(\Dashboard\CommonBundle\Entity\Service $premiumService = null)
    {
        $this->premiumService = $premiumService;
    
        return $this;
    }

    /**
     * Get premiumService
     *
     * @return \Dashboard\CommonBundle\Entity\Service 
     */
    public function getPremiumService()
    {
        return $this->premiumService;
    }

    /**
     * Set specialService
     *
     * @param \Dashboard\CommonBundle\Entity\Service $specialService
     * @return Settings
     */
    public function setSpecialService(\Dashboard\CommonBundle\Entity\Service $specialService = null)
    {
        $this->specialService = $specialService;
    
        return $this;
    }

    /**
     * Get specialService
     *
     * @return \Dashboard\CommonBundle\Entity\Service 
     */
    public function getSpecialService()
    {
        return $this->specialService;
    }

    /**
     * Set selectedService
     *
     * @param \Dashboard\CommonBundle\Entity\Service $selectedService
     * @return Settings
     */
    public function setSelectedService(\Dashboard\CommonBundle\Entity\Service $selectedService = null)
    {
        $this->selectedService = $selectedService;
    
        return $this;
    }

    /**
     * Get selectedService
     *
     * @return \Dashboard\CommonBundle\Entity\Service 
     */
    public function getSelectedService()
    {
        return $this->selectedService;
    }
}
