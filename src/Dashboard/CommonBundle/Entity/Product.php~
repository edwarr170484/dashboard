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
     * @ORM\Column(type="boolean")
     */
    private $typeno;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $typebu;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $typenew;
    
    /**
     * @ORM\Column(type="string", length=512)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=512)
     */
    private $translit;
    
    /**
     * @ORM\Column(type="text")
     */
    private $description;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"null"})
     */
    private $price;
    
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
     * @ORM\Column(type="boolean")
     */
    private $viewcommon;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $viewpremium;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $viewselected;
       
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Selltype", inversedBy="product")
     * @ORM\JoinColumn(name="selltype_id", referencedColumnName="id")
     */
    private $selltype;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"null"})
     */
    private $sortorder;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     */
    private $isActive;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isConfirm;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"0"})
     */
    private $isBlocked;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"0"})
     */
    private $isCorrect;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
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
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"null"})
     */
    private $ratingLikes;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"null"})
     */
    private $ratingDislikes;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"null"})
     */
    private $views;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"null"})
     */
    private $viewsPerDate;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Review", mappedBy="product", cascade={"persist"})
     */
    private $reviews;
    
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
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\ProductService", mappedBy="product", cascade={"persist"})
     */
    private $service;
    
    
    private $daysLeft;
    
     /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $metaTagTitle;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":"null"})
     */
    private $metaTagDescription;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"null"})
     */
    private $term;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fotos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @Assert\Type(type="numeric", message = "Номер телефона должен содержать только цифры")
     * @return string 
     */
    public function getAuthorPhone()
    {
        return $this->authorPhone;
    }

    /**
     * Set typeno
     *
     * @param boolean $typeno
     * @return Product
     */
    public function setTypeno($typeno)
    {
        $this->typeno = $typeno;

        return $this;
    }

    /**
     * Get typeno
     *
     * @return boolean 
     */
    public function getTypeno()
    {
        return $this->typeno;
    }

    /**
     * Set typebu
     *
     * @param boolean $typebu
     * @return Product
     */
    public function setTypebu($typebu)
    {
        $this->typebu = $typebu;

        return $this;
    }

    /**
     * Get typebu
     *
     * @return boolean 
     */
    public function getTypebu()
    {
        return $this->typebu;
    }

    /**
     * Set typenew
     *
     * @param boolean $typenew
     * @return Product
     */
    public function setTypenew($typenew)
    {
        $this->typenew = $typenew;

        return $this;
    }

    /**
     * Get typenew
     *
     * @return boolean 
     */
    public function getTypenew()
    {
        return $this->typenew;
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
     * Set description
     *
     * @param string $description
     * @return Product
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
     * Set price
     *
     * @param integer $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     * @Assert\Type(type="numeric", message = "Цена должна содержать только цифры")
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
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
     * Set viewcommon
     *
     * @param boolean $viewcommon
     * @return Product
     */
    public function setViewcommon($viewcommon)
    {
        $this->viewcommon = $viewcommon;

        return $this;
    }

    /**
     * Get viewcommon
     *
     * @return boolean 
     */
    public function getViewcommon()
    {
        return $this->viewcommon;
    }

    /**
     * Set viewpremium
     *
     * @param boolean $viewpremium
     * @return Product
     */
    public function setViewpremium($viewpremium)
    {
        $this->viewpremium = $viewpremium;

        return $this;
    }

    /**
     * Get viewpremium
     *
     * @return boolean 
     */
    public function getViewpremium()
    {
        return $this->viewpremium;
    }

    /**
     * Set viewselected
     *
     * @param boolean $viewselected
     * @return Product
     */
    public function setViewselected($viewselected)
    {
        $this->viewselected = $viewselected;

        return $this;
    }

    /**
     * Get viewselected
     *
     * @return boolean 
     */
    public function getViewselected()
    {
        return $this->viewselected;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return Product
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
     * Set ratingLikes
     *
     * @param integer $ratingLikes
     * @return Product
     */
    public function setRatingLikes($ratingLikes)
    {
        $this->ratingLikes = $ratingLikes;

        return $this;
    }

    /**
     * Get ratingLikes
     *
     * @return integer 
     */
    public function getRatingLikes()
    {
        return $this->ratingLikes;
    }

    /**
     * Set ratingDislikes
     *
     * @param integer $ratingDislikes
     * @return Product
     */
    public function setRatingDislikes($ratingDislikes)
    {
        $this->ratingDislikes = $ratingDislikes;

        return $this;
    }

    /**
     * Get ratingDislikes
     *
     * @return integer 
     */
    public function getRatingDislikes()
    {
        return $this->ratingDislikes;
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
     * Set selltype
     *
     * @param \Dashboard\CommonBundle\Entity\Selltype $selltype
     * @return Product
     */
    public function setSelltype(\Dashboard\CommonBundle\Entity\Selltype $selltype = null)
    {
        $this->selltype = $selltype;

        return $this;
    }

    /**
     * Get selltype
     *
     * @return \Dashboard\CommonBundle\Entity\Selltype 
     */
    public function getSelltype()
    {
        return $this->selltype;
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
    
    public function calculateDaysLeft()
    {
        $now = new \DateTime("now");
        $dateAdded = $this->dateAdded;
        
        $this->daysLeft=$now->diff($this->dateAdded);
        
        return $this->daysLeft->d;
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
     * Add reviews
     *
     * @param \Dashboard\CommonBundle\Entity\Review $reviews
     * @return Product
     */
    public function addReview(\Dashboard\CommonBundle\Entity\Review $reviews)
    {
        $this->reviews[] = $reviews;

        return $this;
    }

    /**
     * Remove reviews
     *
     * @param \Dashboard\CommonBundle\Entity\Review $reviews
     */
    public function removeReview(\Dashboard\CommonBundle\Entity\Review $reviews)
    {
        $this->reviews->removeElement($reviews);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReviews()
    {
        return $this->reviews;
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
     * Set service
     *
     * @param \Dashboard\CommonBundle\Entity\ProductService $service
     * @return Product
     */
    public function setService(\Dashboard\CommonBundle\Entity\ProductService $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Dashboard\CommonBundle\Entity\ProductService 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set metaTagTitle
     *
     * @param string $metaTagTitle
     * @return Product
     */
    public function setMetaTagTitle($metaTagTitle)
    {
        $this->metaTagTitle = $metaTagTitle;

        return $this;
    }

    /**
     * Get metaTagTitle
     *
     * @return string 
     */
    public function getMetaTagTitle()
    {
        return $this->metaTagTitle;
    }

    /**
     * Set metaTagDescription
     *
     * @param string $metaTagDescription
     * @return Product
     */
    public function setMetaTagDescription($metaTagDescription)
    {
        $this->metaTagDescription = $metaTagDescription;

        return $this;
    }

    /**
     * Get metaTagDescription
     *
     * @return string 
     */
    public function getMetaTagDescription()
    {
        return $this->metaTagDescription;
    }

    /**
     * Set isCorrect
     *
     * @param boolean $isCorrect
     * @return Product
     */
    public function setIsCorrect($isCorrect)
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    /**
     * Get isCorrect
     *
     * @return boolean 
     */
    public function getIsCorrect()
    {
        return $this->isCorrect;
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
     * Set term
     *
     * @param integer $term
     * @return Product
     */
    public function setTerm($term)
    {
        $this->term = $term;
    
        return $this;
    }

    /**
     * Get term
     *
     * @return integer 
     */
    public function getTerm()
    {
        return $this->term;
    }
}
