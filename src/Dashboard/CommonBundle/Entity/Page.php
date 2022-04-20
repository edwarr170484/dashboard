<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Page
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $route;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $text;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isUserpage;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isFooterMenu;
    
    /**
     * @ORM\Column(type="integer", length=1, nullable=true, options={"default":"0"})
     */
    private $footerMenuSection;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $sortorder;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $metaTagTitle;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":"null"})
     */
    private $metaTagDescription;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":"null"})
     */
    private $metaTagAuthor;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":"null"})
     */
    private $metaTagRobots;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":"null"})
     */
    private $metaTagKeywords;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Locale", inversedBy="pages")
     * @ORM\JoinColumn(name="locale_id", referencedColumnName="id")
     */
    private $locale;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Banner", mappedBy="pages", cascade={"persist"})
     */
    private $banners;
    

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
     * Set title
     *
     * @param string $title
     * @return Page
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
     * Set route
     *
     * @param string $route
     * @return Page
     */
    public function setRoute($route)
    {
        $this->route = $route;
    
        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Page
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set isUserpage
     *
     * @param boolean $isUserpage
     * @return Page
     */
    public function setIsUserpage($isUserpage)
    {
        $this->isUserpage = $isUserpage;
    
        return $this;
    }

    /**
     * Get isUserpage
     *
     * @return boolean 
     */
    public function getIsUserpage()
    {
        return $this->isUserpage;
    }

    /**
     * Set isFooterMenu
     *
     * @param boolean $isFooterMenu
     * @return Page
     */
    public function setIsFooterMenu($isFooterMenu)
    {
        $this->isFooterMenu = $isFooterMenu;
    
        return $this;
    }

    /**
     * Get isFooterMenu
     *
     * @return boolean 
     */
    public function getIsFooterMenu()
    {
        return $this->isFooterMenu;
    }

    /**
     * Set footerMenuSection
     *
     * @param integer $footerMenuSection
     * @return Page
     */
    public function setFooterMenuSection($footerMenuSection)
    {
        $this->footerMenuSection = $footerMenuSection;
    
        return $this;
    }

    /**
     * Get footerMenuSection
     *
     * @return integer 
     */
    public function getFooterMenuSection()
    {
        return $this->footerMenuSection;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return Page
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
     * Set metaTagTitle
     *
     * @param string $metaTagTitle
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * Set locale
     *
     * @param \Dashboard\CommonBundle\Entity\Locale $locale
     * @return Page
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
     * Constructor
     */
    public function __construct()
    {
        $this->banners = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add banners
     *
     * @param \Dashboard\CommonBundle\Entity\Banner $banners
     * @return Page
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
}
