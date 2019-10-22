<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Translation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Locale", inversedBy="translations")
     * @ORM\JoinColumn(name="locale_id", referencedColumnName="id")
     */
    private $locale;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category", inversedBy="translations")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Selltype", inversedBy="translations")
     * @ORM\JoinColumn(name="selltype_id", referencedColumnName="id")
     */
    private $selltype;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Mark", inversedBy="translations")
     * @ORM\JoinColumn(name="mark_id", referencedColumnName="id")
     */
    private $mark;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Filter", inversedBy="translations")
     * @ORM\JoinColumn(name="filter_id", referencedColumnName="id")
     */
    private $filter;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\FilterValue", inversedBy="translations")
     * @ORM\JoinColumn(name="filter_value_id", referencedColumnName="id")
     */
    private $filterValue;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\OrderStatus", inversedBy="translations")
     * @ORM\JoinColumn(name="order_status_id", referencedColumnName="id")
     */
    private $orderStatus;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Region", inversedBy="translations")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\City", inversedBy="translations")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Service", inversedBy="translations")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Modification", inversedBy="translations")
     * @ORM\JoinColumn(name="modification_id", referencedColumnName="id")
     */
    private $modification;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Generation", inversedBy="translations")
     * @ORM\JoinColumn(name="generation_id", referencedColumnName="id")
     */
    private $generation;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Pack", inversedBy="translations")
     * @ORM\JoinColumn(name="pack_id", referencedColumnName="id")
     */
    private $pack;
    
    /**
     * @ORM\Column(type="text",nullable=true, options={"default":"0"})
     */
    private $value;
    
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
     * Set value
     *
     * @param string $value
     * @return Translation
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set locale
     *
     * @param \Dashboard\CommonBundle\Entity\Locale $locale
     * @return Translation
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
     * Set category
     *
     * @param \Dashboard\CommonBundle\Entity\Category $category
     * @return Translation
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
     * Set selltype
     *
     * @param \Dashboard\CommonBundle\Entity\Selltype $selltype
     * @return Translation
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
     * Set mark
     *
     * @param \Dashboard\CommonBundle\Entity\Mark $mark
     * @return Translation
     */
    public function setMark(\Dashboard\CommonBundle\Entity\Mark $mark = null)
    {
        $this->mark = $mark;
    
        return $this;
    }

    /**
     * Get mark
     *
     * @return \Dashboard\CommonBundle\Entity\Mark 
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set filter
     *
     * @param \Dashboard\CommonBundle\Entity\Filter $filter
     * @return Translation
     */
    public function setFilter(\Dashboard\CommonBundle\Entity\Filter $filter = null)
    {
        $this->filter = $filter;
    
        return $this;
    }

    /**
     * Get filter
     *
     * @return \Dashboard\CommonBundle\Entity\Filter 
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set filterValue
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $filterValue
     * @return Translation
     */
    public function setFilterValue(\Dashboard\CommonBundle\Entity\FilterValue $filterValue = null)
    {
        $this->filterValue = $filterValue;
    
        return $this;
    }

    /**
     * Get filterValue
     *
     * @return \Dashboard\CommonBundle\Entity\FilterValue 
     */
    public function getFilterValue()
    {
        return $this->filterValue;
    }

    /**
     * Set orderStatus
     *
     * @param \Dashboard\CommonBundle\Entity\OrderStatus $orderStatus
     * @return Translation
     */
    public function setOrderStatus(\Dashboard\CommonBundle\Entity\OrderStatus $orderStatus = null)
    {
        $this->orderStatus = $orderStatus;
    
        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return \Dashboard\CommonBundle\Entity\OrderStatus 
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set region
     *
     * @param \Dashboard\CommonBundle\Entity\Region $region
     * @return Translation
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
     * @return Translation
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
     * Set service
     *
     * @param \Dashboard\CommonBundle\Entity\Service $service
     * @return Translation
     */
    public function setService(\Dashboard\CommonBundle\Entity\Service $service = null)
    {
        $this->service = $service;
    
        return $this;
    }

    /**
     * Get service
     *
     * @return \Dashboard\CommonBundle\Entity\Service 
     */
    public function getService()
    {
        return $this->service;
    }


    /**
     * Set modification
     *
     * @param \Dashboard\CommonBundle\Entity\Modification $modification
     * @return Translation
     */
    public function setModification(\Dashboard\CommonBundle\Entity\Modification $modification = null)
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * Get modification
     *
     * @return \Dashboard\CommonBundle\Entity\Modification 
     */
    public function getModification()
    {
        return $this->modification;
    }

    /**
     * Set generation
     *
     * @param \Dashboard\CommonBundle\Entity\Generation $generation
     * @return Translation
     */
    public function setGeneration(\Dashboard\CommonBundle\Entity\Generation $generation = null)
    {
        $this->generation = $generation;

        return $this;
    }

    /**
     * Get generation
     *
     * @return \Dashboard\CommonBundle\Entity\Generation 
     */
    public function getGeneration()
    {
        return $this->generation;
    }

    /**
     * Set pack
     *
     * @param \Dashboard\CommonBundle\Entity\Pack $pack
     * @return Translation
     */
    public function setPack(\Dashboard\CommonBundle\Entity\Pack $pack = null)
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * Get pack
     *
     * @return \Dashboard\CommonBundle\Entity\Pack 
     */
    public function getPack()
    {
        return $this->pack;
    }
}
