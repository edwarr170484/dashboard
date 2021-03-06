<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity()
 */
class Product 
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category", inversedBy="product")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category")
     * @ORM\JoinColumn(name="base_category_id", referencedColumnName="id")
     */
    private $baseCategory;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="products")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $authorName;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $authorEmail;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $authorPhone;
        
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Region", inversedBy="product")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\City", inversedBy="product")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\CityCode", inversedBy="products")
     * @ORM\JoinColumn(name="city_code_id", referencedColumnName="id")
     */
    private $cityCode;
    
    /**
     * @ORM\Column(type="string", length=512)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=512)
     */
    private $translit;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $mainfoto;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\ProductFotos", mappedBy="product", cascade={"persist"})
     * @ORM\OrderBy({"sortorder" = "ASC"})
     */
    private $fotos;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\ProductOrder", mappedBy="product", cascade={"persist"})
     */
    private $orders;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Complaint", mappedBy="product")
     */
    private $complaint;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Note", mappedBy="product")
     */
    private $notes;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isActive;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isConfirm;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isBlocked;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default": 0})
     */
    private $isDraft;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default": null})
     */
    private $correctReason;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEdited;
    
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": null})
     */
    private $dateStart;
    
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": null})
     */
    private $dateEnd;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default": 0})
     */
    private $views;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default": 0})
     */
    private $viewsPerDate;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Message", mappedBy="product", cascade={"persist"})
     */
    private $messages;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\FilterValue", inversedBy="products")
     * @ORM\JoinTable(name="product_filters")
     */
    private $filters;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\ProductService", mappedBy="product", cascade={"persist"})
     */
    private $services;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\ProductInfo", mappedBy="product", cascade={"persist"})
     */
    private $info;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Bill", mappedBy="products")
     */
    private $bills;
    
    private $serviceName;
    
    private $daysLeft;
    
    private $isFavorite;
    
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    
        return $this;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }
    
    public function getDaysLeft(){
        
        $today = new \DateTime("now");
        $endDate = $this->getDateEnd();
        $interval = $today->diff($endDate);        

        if($interval->invert){
            return 0;
        }else{
            return $interval->days + 1;
        }
    }
    
    public function getIsFavorite(){
        return $this->isFavorite;
    }
    
    public function setIsFavorite($isFavorite){
        $this->isFavorite = $isFavorite;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fotos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->complaint = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bills = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set authorName
     *
     * @param string $authorName
     * @return Product
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    
        return $this;
    }

    /**
     * Get authorName
     *
     * @return string 
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorEmail
     *
     * @param string $authorEmail
     * @return Product
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    
        return $this;
    }

    /**
     * Get authorEmail
     *
     * @return string 
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * Set authorPhone
     *
     * @param string $authorPhone
     * @return Product
     */
    public function setAuthorPhone($authorPhone)
    {
        $this->authorPhone = $authorPhone;
    
        return $this;
    }

    /**
     * Get authorPhone
     *
     * @return string 
     */
    public function getAuthorPhone()
    {
        return $this->authorPhone;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
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
     * Set translit
     *
     * @param string $translit
     * @return Product
     */
    public function setTranslit($translit)
    {
        $this->translit = $translit;
    
        return $this;
    }

    /**
     * Get translit
     *
     * @return string 
     */
    public function getTranslit()
    {
        return $this->translit;
    }

    /**
     * Set mainfoto
     *
     * @param string $mainfoto
     * @return Product
     */
    public function setMainfoto($mainfoto)
    {
        $this->mainfoto = $mainfoto;
    
        return $this;
    }

    /**
     * Get mainfoto
     *
     * @return string 
     */
    public function getMainfoto()
    {
        return $this->mainfoto;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Product
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isConfirm
     *
     * @param boolean $isConfirm
     * @return Product
     */
    public function setIsConfirm($isConfirm)
    {
        $this->isConfirm = $isConfirm;
    
        return $this;
    }

    /**
     * Get isConfirm
     *
     * @return boolean 
     */
    public function getIsConfirm()
    {
        return $this->isConfirm;
    }

    /**
     * Set isBlocked
     *
     * @param boolean $isBlocked
     * @return Product
     */
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;
    
        return $this;
    }

    /**
     * Get isBlocked
     *
     * @return boolean 
     */
    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

    /**
     * Set isDraft
     *
     * @param boolean $isDraft
     * @return Product
     */
    public function setIsDraft($isDraft)
    {
        $this->isDraft = $isDraft;
    
        return $this;
    }

    /**
     * Get isDraft
     *
     * @return boolean 
     */
    public function getIsDraft()
    {
        return $this->isDraft;
    }

    /**
     * Set correctReason
     *
     * @param string $correctReason
     * @return Product
     */
    public function setCorrectReason($correctReason)
    {
        $this->correctReason = $correctReason;
    
        return $this;
    }

    /**
     * Get correctReason
     *
     * @return string 
     */
    public function getCorrectReason()
    {
        return $this->correctReason;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Product
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    
        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set dateEdited
     *
     * @param \DateTime $dateEdited
     * @return Product
     */
    public function setDateEdited($dateEdited)
    {
        $this->dateEdited = $dateEdited;
    
        return $this;
    }

    /**
     * Get dateEdited
     *
     * @return \DateTime 
     */
    public function getDateEdited()
    {
        return $this->dateEdited;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return Product
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    
        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime 
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return Product
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    
        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Product
     */
    public function setViews($views)
    {
        $this->views = $views;
    
        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set viewsPerDate
     *
     * @param integer $viewsPerDate
     * @return Product
     */
    public function setViewsPerDate($viewsPerDate)
    {
        $this->viewsPerDate = $viewsPerDate;
    
        return $this;
    }

    /**
     * Get viewsPerDate
     *
     * @return integer 
     */
    public function getViewsPerDate()
    {
        return $this->viewsPerDate;
    }

    /**
     * Set category
     *
     * @param \Dashboard\CommonBundle\Entity\Category $category
     * @return Product
     */
    public function setCategory(\Dashboard\CommonBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Dashboard\CommonBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set baseCategory
     *
     * @param \Dashboard\CommonBundle\Entity\Category $baseCategory
     * @return Product
     */
    public function setBaseCategory(\Dashboard\CommonBundle\Entity\Category $baseCategory = null)
    {
        $this->baseCategory = $baseCategory;
    
        return $this;
    }

    /**
     * Get baseCategory
     *
     * @return \Dashboard\CommonBundle\Entity\Category 
     */
    public function getBaseCategory()
    {
        return $this->baseCategory;
    }

    /**
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return Product
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
     * Set region
     *
     * @param \Dashboard\CommonBundle\Entity\Region $region
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * Add fotos
     *
     * @param \Dashboard\CommonBundle\Entity\ProductFotos $fotos
     * @return Product
     */
    public function addFoto(\Dashboard\CommonBundle\Entity\ProductFotos $fotos)
    {
        $this->fotos[] = $fotos;
    
        return $this;
    }

    /**
     * Remove fotos
     *
     * @param \Dashboard\CommonBundle\Entity\ProductFotos $fotos
     */
    public function removeFoto(\Dashboard\CommonBundle\Entity\ProductFotos $fotos)
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
     * Add orders
     *
     * @param \Dashboard\CommonBundle\Entity\ProductOrder $orders
     * @return Product
     */
    public function addOrder(\Dashboard\CommonBundle\Entity\ProductOrder $orders)
    {
        $this->orders[] = $orders;
    
        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Dashboard\CommonBundle\Entity\ProductOrder $orders
     */
    public function removeOrder(\Dashboard\CommonBundle\Entity\ProductOrder $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add complaint
     *
     * @param \Dashboard\CommonBundle\Entity\Complaint $complaint
     * @return Product
     */
    public function addComplaint(\Dashboard\CommonBundle\Entity\Complaint $complaint)
    {
        $this->complaint[] = $complaint;
    
        return $this;
    }

    /**
     * Remove complaint
     *
     * @param \Dashboard\CommonBundle\Entity\Complaint $complaint
     */
    public function removeComplaint(\Dashboard\CommonBundle\Entity\Complaint $complaint)
    {
        $this->complaint->removeElement($complaint);
    }

    /**
     * Get complaint
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComplaint()
    {
        return $this->complaint;
    }

    /**
     * Add notes
     *
     * @param \Dashboard\CommonBundle\Entity\Note $notes
     * @return Product
     */
    public function addNote(\Dashboard\CommonBundle\Entity\Note $notes)
    {
        $this->notes[] = $notes;
    
        return $this;
    }

    /**
     * Remove notes
     *
     * @param \Dashboard\CommonBundle\Entity\Note $notes
     */
    public function removeNote(\Dashboard\CommonBundle\Entity\Note $notes)
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add messages
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messages
     * @return Product
     */
    public function addMessage(\Dashboard\CommonBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;
    
        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messages
     */
    public function removeMessage(\Dashboard\CommonBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add filters
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $filters
     * @return Product
     */
    public function addFilter(\Dashboard\CommonBundle\Entity\FilterValue $filters)
    {
        $this->filters[] = $filters;
    
        return $this;
    }

    /**
     * Remove filters
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $filters
     */
    public function removeFilter(\Dashboard\CommonBundle\Entity\FilterValue $filters)
    {
        $this->filters->removeElement($filters);
    }

    /**
     * Get filters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Add services
     *
     * @param \Dashboard\CommonBundle\Entity\ProductService $services
     * @return Product
     */
    public function addService(\Dashboard\CommonBundle\Entity\ProductService $services)
    {
        $this->services[] = $services;
    
        return $this;
    }

    /**
     * Remove services
     *
     * @param \Dashboard\CommonBundle\Entity\ProductService $services
     */
    public function removeService(\Dashboard\CommonBundle\Entity\ProductService $services)
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
     * Set info
     *
     * @param \Dashboard\CommonBundle\Entity\ProductInfo $info
     * @return Product
     */
    public function setInfo(\Dashboard\CommonBundle\Entity\ProductInfo $info = null)
    {
        $this->info = $info;
    
        return $this;
    }

    /**
     * Get info
     *
     * @return \Dashboard\CommonBundle\Entity\ProductInfo 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Add bills
     *
     * @param \Dashboard\CommonBundle\Entity\Bill $bills
     * @return Product
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
}
