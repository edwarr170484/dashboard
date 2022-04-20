<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Locale
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length = 255, nullable=true, options={"default":"null"})
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length = 10, nullable=true, options={"default":"null"})
     */
    private $code;
    
    /**
     * @ORM\Column(type="string", length = 255, nullable=true, options={"default":"null"})
     */
    private $country;
    
    /**
     * @ORM\Column(type="integer", length = 15, nullable=true, options={"default":"null"})
     */
    private $sortorder;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"0"})
     */
    private $isActive;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"0"})
     */
    private $isDefault;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Settings", mappedBy="locale", cascade={"persist"})
     */
    private $settings;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Currency", inversedBy="locale")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Page", mappedBy="locale", cascade={"persist"})
     */
    private $pages;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\GalleryBundle\Entity\Gallery", mappedBy="locale", cascade={"persist"})
     */
    private $galleries;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="locale", cascade={"persist"})
     */
    private $translations;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\CategoryDescription", mappedBy="locale", cascade={"persist"})
     */
    private $descriptions;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Locale
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
     * Set code
     *
     * @param string $code
     * @return Locale
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Locale
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return Locale
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
     * @return Locale
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
     * Set isDefault
     *
     * @param boolean $isDefault
     * @return Locale
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    
        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean 
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set settings
     *
     * @param \Dashboard\CommonBundle\Entity\Settings $settings
     * @return Locale
     */
    public function setSettings(\Dashboard\CommonBundle\Entity\Settings $settings = null)
    {
        $this->settings = $settings;
    
        return $this;
    }

    /**
     * Get settings
     *
     * @return \Dashboard\CommonBundle\Entity\Settings 
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set currency
     *
     * @param \Dashboard\CommonBundle\Entity\Currency $currency
     * @return Locale
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
     * Add pages
     *
     * @param \Dashboard\CommonBundle\Entity\Page $pages
     * @return Locale
     */
    public function addPage(\Dashboard\CommonBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;
    
        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Dashboard\CommonBundle\Entity\Page $pages
     */
    public function removePage(\Dashboard\CommonBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Add translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     * @return Locale
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
     * Add galleries
     *
     * @param \Dashboard\GalleryBundle\Entity\Gallery $galleries
     * @return Locale
     */
    public function addGallery(\Dashboard\GalleryBundle\Entity\Gallery $galleries)
    {
        $this->galleries[] = $galleries;
    
        return $this;
    }

    /**
     * Remove galleries
     *
     * @param \Dashboard\GalleryBundle\Entity\Gallery $galleries
     */
    public function removeGallery(\Dashboard\GalleryBundle\Entity\Gallery $galleries)
    {
        $this->galleries->removeElement($galleries);
    }

    /**
     * Get galleries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGalleries()
    {
        return $this->galleries;
    }

    /**
     * Add descriptions
     *
     * @param \Dashboard\CommonBundle\Entity\CategoryDescription $descriptions
     * @return Locale
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
}
