<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Filter
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     */
    private $isShow;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isRequired;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isSearch;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isSelltype;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isShowCard;
    
    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $sortorder;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\FilterValue", mappedBy="filter", cascade={"persist"})
     */
    private $values;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Category", inversedBy="filters")
     * @ORM\JoinTable(name="category_filters")
     */
    private $categories;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="filter", cascade={"persist"})
     */
    private $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Filter
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
     * Set type
     *
     * @param string $type
     * @return Filter
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set isShow
     *
     * @param boolean $isShow
     * @return Filter
     */
    public function setIsShow($isShow)
    {
        $this->isShow = $isShow;
    
        return $this;
    }

    /**
     * Get isShow
     *
     * @return boolean 
     */
    public function getIsShow()
    {
        return $this->isShow;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return Filter
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
     * Add values
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $values
     * @return Filter
     */
    public function addValue(\Dashboard\CommonBundle\Entity\FilterValue $values)
    {
        $this->values[] = $values;
    
        return $this;
    }

    /**
     * Remove values
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $values
     */
    public function removeValue(\Dashboard\CommonBundle\Entity\FilterValue $values)
    {
        $this->values->removeElement($values);
    }

    /**
     * Get values
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Add categories
     *
     * @param \Dashboard\CommonBundle\Entity\Category $categories
     * @return Filter
     */
    public function addCategory(\Dashboard\CommonBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Dashboard\CommonBundle\Entity\Category $categories
     */
    public function removeCategory(\Dashboard\CommonBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     * @return Filter
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
     * Set isRequired
     *
     * @param boolean $isRequired
     * @return Filter
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;
    
        return $this;
    }

    /**
     * Get isRequired
     *
     * @return boolean 
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }

    /**
     * Set isSearch
     *
     * @param boolean $isSearch
     * @return Filter
     */
    public function setIsSearch($isSearch)
    {
        $this->isSearch = $isSearch;
    
        return $this;
    }

    /**
     * Get isSearch
     *
     * @return boolean 
     */
    public function getIsSearch()
    {
        return $this->isSearch;
    }

    /**
     * Set isSelltype
     *
     * @param boolean $isSelltype
     * @return Filter
     */
    public function setIsSelltype($isSelltype)
    {
        $this->isSelltype = $isSelltype;
    
        return $this;
    }

    /**
     * Get isSelltype
     *
     * @return boolean 
     */
    public function getIsSelltype()
    {
        return $this->isSelltype;
    }

    /**
     * Set isShowCard
     *
     * @param boolean $isShowCard
     * @return Filter
     */
    public function setIsShowCard($isShowCard)
    {
        $this->isShowCard = $isShowCard;
    
        return $this;
    }

    /**
     * Get isShowCard
     *
     * @return boolean 
     */
    public function getIsShowCard()
    {
        return $this->isShowCard;
    }
}
