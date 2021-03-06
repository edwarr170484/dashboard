<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="category")
 * @ORM\Entity()
 */
class Category 
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Category", mappedBy="parent")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    private $parent;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $hTitle;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":null})
     */
    private $description;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":null})
     */
    private $image;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $yearFrom;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $yearTo;
    
    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $sortorder;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default":1})
     */
    private $isActive;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default":1})
     */
    private $isShowFilters;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default": 0})
     */
    private $isUseChildrensLikeMark;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default": 0})
     */
    private $isUseChildrensLikeModel;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default": 0})
     */
    private $isUseChildrensLikeType;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default": 0})
     */
    private $isShowGenerationFilter;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default": 0})
     */
    private $isBreakRedirect;
    
    /**
     * @ORM\Column(type="boolean",nullable=true, options={"default":1})
     */
    private $isShowPriceFilter;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     */
    private $metaTagTitle;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":null})
     */
    private $metaTagDescription;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":null})
     */
    private $metaTagAuthor;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":null})
     */
    private $metaTagRobots;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":null})
     */
    private $metaTagKeywords;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Product", mappedBy="category")
     */
    private $product;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\CategoryDescription", mappedBy="category")
     */
    private $descriptions;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Filter", mappedBy="categories", cascade={"persist"})
     * @ORM\OrderBy({"sortorder" = "ASC"})
     */
    private $filters;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Banner", mappedBy="categories", cascade={"persist"})
     */
    private $banners;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="category", cascade={"persist"})
     */
    private $translations;
        
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Generation", mappedBy="category", cascade={"persist"})
     */
    private $generations;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\CategoryRate", mappedBy="category")
     */
    private $rates;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\DealerInfo", mappedBy="autos", cascade={"persist"})
     */
    private $dealers;
    
    /**
     * @ORM\Column(type="integer", nullable=true, options={"default": 1})
     */
    private $formType;
    
    private $allProductsNumber;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->descriptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->filters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->banners = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->generations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dealers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
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
     * Set title
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
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
     * Set image
     *
     * @param string $image
     * @return Category
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set yearFrom
     *
     * @param string $yearFrom
     * @return Category
     */
    public function setYearFrom($yearFrom)
    {
        $this->yearFrom = $yearFrom;
    
        return $this;
    }

    /**
     * Get yearFrom
     *
     * @return string 
     */
    public function getYearFrom()
    {
        return $this->yearFrom;
    }

    /**
     * Set yearTo
     *
     * @param string $yearTo
     * @return Category
     */
    public function setYearTo($yearTo)
    {
        $this->yearTo = $yearTo;
    
        return $this;
    }

    /**
     * Get yearTo
     *
     * @return string 
     */
    public function getYearTo()
    {
        return $this->yearTo;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return Category
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
     * @return Category
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
     * Set isShowFilters
     *
     * @param boolean $isShowFilters
     * @return Category
     */
    public function setIsShowFilters($isShowFilters)
    {
        $this->isShowFilters = $isShowFilters;
    
        return $this;
    }

    /**
     * Get isShowFilters
     *
     * @return boolean 
     */
    public function getIsShowFilters()
    {
        return $this->isShowFilters;
    }

    /**
     * Set isUseChildrensLikeMark
     *
     * @param boolean $isUseChildrensLikeMark
     * @return Category
     */
    public function setIsUseChildrensLikeMark($isUseChildrensLikeMark)
    {
        $this->isUseChildrensLikeMark = $isUseChildrensLikeMark;
    
        return $this;
    }

    /**
     * Get isUseChildrensLikeMark
     *
     * @return boolean 
     */
    public function getIsUseChildrensLikeMark()
    {
        return $this->isUseChildrensLikeMark;
    }

    /**
     * Set isUseChildrensLikeModel
     *
     * @param boolean $isUseChildrensLikeModel
     * @return Category
     */
    public function setIsUseChildrensLikeModel($isUseChildrensLikeModel)
    {
        $this->isUseChildrensLikeModel = $isUseChildrensLikeModel;
    
        return $this;
    }

    /**
     * Get isUseChildrensLikeModel
     *
     * @return boolean 
     */
    public function getIsUseChildrensLikeModel()
    {
        return $this->isUseChildrensLikeModel;
    }

    /**
     * Set isShowPriceFilter
     *
     * @param boolean $isShowPriceFilter
     * @return Category
     */
    public function setIsShowPriceFilter($isShowPriceFilter)
    {
        $this->isShowPriceFilter = $isShowPriceFilter;
    
        return $this;
    }

    /**
     * Get isShowPriceFilter
     *
     * @return boolean 
     */
    public function getIsShowPriceFilter()
    {
        return $this->isShowPriceFilter;
    }

    /**
     * Set metaTagTitle
     *
     * @param string $metaTagTitle
     * @return Category
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
     * @return Category
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
     * Set metaTagAuthor
     *
     * @param string $metaTagAuthor
     * @return Category
     */
    public function setMetaTagAuthor($metaTagAuthor)
    {
        $this->metaTagAuthor = $metaTagAuthor;
    
        return $this;
    }

    /**
     * Get metaTagAuthor
     *
     * @return string 
     */
    public function getMetaTagAuthor()
    {
        return $this->metaTagAuthor;
    }

    /**
     * Set metaTagRobots
     *
     * @param string $metaTagRobots
     * @return Category
     */
    public function setMetaTagRobots($metaTagRobots)
    {
        $this->metaTagRobots = $metaTagRobots;
    
        return $this;
    }

    /**
     * Get metaTagRobots
     *
     * @return string 
     */
    public function getMetaTagRobots()
    {
        return $this->metaTagRobots;
    }

    /**
     * Set metaTagKeywords
     *
     * @param string $metaTagKeywords
     * @return Category
     */
    public function setMetaTagKeywords($metaTagKeywords)
    {
        $this->metaTagKeywords = $metaTagKeywords;
    
        return $this;
    }

    /**
     * Get metaTagKeywords
     *
     * @return string 
     */
    public function getMetaTagKeywords()
    {
        return $this->metaTagKeywords;
    }

    /**
     * Set formType
     *
     * @param integer $formType
     * @return Category
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;
    
        return $this;
    }

    /**
     * Get formType
     *
     * @return integer 
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * Add children
     *
     * @param \Dashboard\CommonBundle\Entity\Category $children
     * @return Category
     */
    public function addChild(\Dashboard\CommonBundle\Entity\Category $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Dashboard\CommonBundle\Entity\Category $children
     */
    public function removeChild(\Dashboard\CommonBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Dashboard\CommonBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\Dashboard\CommonBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Dashboard\CommonBundle\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     * @return Category
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
     * Add descriptions
     *
     * @param \Dashboard\CommonBundle\Entity\CategoryDescription $descriptions
     * @return Category
     */
    public function addDescription(\Dashboard\CommonBundle\Entity\CategoryDescription $descriptions)
    {
        $this->descriptions[] = $descriptions;
    
        return $this;
    }

    /**
     * Remove descriptions
     *
     * @param \Dashboard\CommonBundle\Entity\CategoryDescription $descriptions
     */
    public function removeDescription(\Dashboard\CommonBundle\Entity\CategoryDescription $descriptions)
    {
        $this->descriptions->removeElement($descriptions);
    }

    /**
     * Get descriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescriptions()
    {
        return $this->descriptions;
    }

    /**
     * Add filters
     *
     * @param \Dashboard\CommonBundle\Entity\Filter $filters
     * @return Category
     */
    public function addFilter(\Dashboard\CommonBundle\Entity\Filter $filters)
    {
        $this->filters[] = $filters;
    
        return $this;
    }

    /**
     * Remove filters
     *
     * @param \Dashboard\CommonBundle\Entity\Filter $filters
     */
    public function removeFilter(\Dashboard\CommonBundle\Entity\Filter $filters)
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
     * Add banners
     *
     * @param \Dashboard\CommonBundle\Entity\Banner $banners
     * @return Category
     */
    public function addBanner(\Dashboard\CommonBundle\Entity\Banner $banners)
    {
        $this->banners[] = $banners;
    
        return $this;
    }

    /**
     * Remove banners
     *
     * @param \Dashboard\CommonBundle\Entity\Banner $banners
     */
    public function removeBanner(\Dashboard\CommonBundle\Entity\Banner $banners)
    {
        $this->banners->removeElement($banners);
    }

    /**
     * Get banners
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBanners()
    {
        return $this->banners;
    }

    /**
     * Add translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     * @return Category
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
     * Add generations
     *
     * @param \Dashboard\CommonBundle\Entity\Generation $generations
     * @return Category
     */
    public function addGeneration(\Dashboard\CommonBundle\Entity\Generation $generations)
    {
        $this->generations[] = $generations;
    
        return $this;
    }

    /**
     * Remove generations
     *
     * @param \Dashboard\CommonBundle\Entity\Generation $generations
     */
    public function removeGeneration(\Dashboard\CommonBundle\Entity\Generation $generations)
    {
        $this->generations->removeElement($generations);
    }

    /**
     * Get generations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGenerations()
    {
        return $this->generations;
    }

    /**
     * Add rates
     *
     * @param \Dashboard\CommonBundle\Entity\CategoryRate $rates
     * @return Category
     */
    public function addRate(\Dashboard\CommonBundle\Entity\CategoryRate $rates)
    {
        $this->rates[] = $rates;
    
        return $this;
    }

    /**
     * Remove rates
     *
     * @param \Dashboard\CommonBundle\Entity\CategoryRate $rates
     */
    public function removeRate(\Dashboard\CommonBundle\Entity\CategoryRate $rates)
    {
        $this->rates->removeElement($rates);
    }

    /**
     * Get rates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Add dealers
     *
     * @param \Dashboard\CommonBundle\Entity\DealerInfo $dealers
     * @return Category
     */
    public function addDealer(\Dashboard\CommonBundle\Entity\DealerInfo $dealers)
    {
        $this->dealers[] = $dealers;
    
        return $this;
    }

    /**
     * Remove dealers
     *
     * @param \Dashboard\CommonBundle\Entity\DealerInfo $dealers
     */
    public function removeDealer(\Dashboard\CommonBundle\Entity\DealerInfo $dealers)
    {
        $this->dealers->removeElement($dealers);
    }

    /**
     * Get dealers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDealers()
    {
        return $this->dealers;
    }
    
    public function getAllProductsNumber(){
        return $this->allProductsNumber;
    }
    
    public function setAllProductsNumber($allProductsNumber){
        $this->allProductsNumber = $allProductsNumber;
        
        return $this;
    }

    /**
     * Set isUseChildrensLikeType
     *
     * @param boolean $isUseChildrensLikeType
     * @return Category
     */
    public function setIsUseChildrensLikeType($isUseChildrensLikeType)
    {
        $this->isUseChildrensLikeType = $isUseChildrensLikeType;
    
        return $this;
    }

    /**
     * Get isUseChildrensLikeType
     *
     * @return boolean 
     */
    public function getIsUseChildrensLikeType()
    {
        return $this->isUseChildrensLikeType;
    }

    /**
     * Set isShowGenerationFilter
     *
     * @param boolean $isShowGenerationFilter
     * @return Category
     */
    public function setIsShowGenerationFilter($isShowGenerationFilter)
    {
        $this->isShowGenerationFilter = $isShowGenerationFilter;
    
        return $this;
    }

    /**
     * Get isShowGenerationFilter
     *
     * @return boolean 
     */
    public function getIsShowGenerationFilter()
    {
        return $this->isShowGenerationFilter;
    }

    /**
     * Set isBreakRedirect
     *
     * @param boolean $isBreakRedirect
     * @return Category
     */
    public function setIsBreakRedirect($isBreakRedirect)
    {
        $this->isBreakRedirect = $isBreakRedirect;
    
        return $this;
    }

    /**
     * Get isBreakRedirect
     *
     * @return boolean 
     */
    public function getIsBreakRedirect()
    {
        return $this->isBreakRedirect;
    }

    /**
     * Set hTitle
     *
     * @param string $hTitle
     * @return Category
     */
    public function setHTitle($hTitle)
    {
        $this->hTitle = $hTitle;
    
        return $this;
    }

    /**
     * Get hTitle
     *
     * @return string 
     */
    public function getHTitle()
    {
        return $this->hTitle;
    }
}
