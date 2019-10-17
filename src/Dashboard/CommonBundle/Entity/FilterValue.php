<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class FilterValue
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Filter", inversedBy="values")
     * @ORM\JoinColumn(name="filter_id", referencedColumnName="id")
     */
    private $filter;
    
    /**
     * @ORM\Column(type="string", length=512)
     */
    private $value;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Product", mappedBy="filters", cascade={"persist"})
     */
    private $products;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\FilterValue", mappedBy="linkedValues", cascade={"persist"})
     */
    private $linkToValues;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\FilterValue", inversedBy="linkToValues")
     * @ORM\JoinTable(name="filter_linked_values")
     */
    private $linkedValues;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Filter", inversedBy="linkToValues")
     * @ORM\JoinTable(name="value_linked_filters")
     */
    private $linkedFilters;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="filterValue", cascade={"persist"})
     */
    private $translations;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->linkToValues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->linkedValues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->linkedFilters = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set value
     *
     * @param string $value
     * @return FilterValue
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
     * Set filter
     *
     * @param \Dashboard\CommonBundle\Entity\Filter $filter
     * @return FilterValue
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
     * Add products
     *
     * @param \Dashboard\CommonBundle\Entity\Product $products
     * @return FilterValue
     */
    public function addProduct(\Dashboard\CommonBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Dashboard\CommonBundle\Entity\Product $products
     */
    public function removeProduct(\Dashboard\CommonBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add linkToValues
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $linkToValues
     * @return FilterValue
     */
    public function addLinkToValue(\Dashboard\CommonBundle\Entity\FilterValue $linkToValues)
    {
        $this->linkToValues[] = $linkToValues;

        return $this;
    }

    /**
     * Remove linkToValues
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $linkToValues
     */
    public function removeLinkToValue(\Dashboard\CommonBundle\Entity\FilterValue $linkToValues)
    {
        $this->linkToValues->removeElement($linkToValues);
    }

    /**
     * Get linkToValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinkToValues()
    {
        return $this->linkToValues;
    }

    /**
     * Add linkedValues
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $linkedValues
     * @return FilterValue
     */
    public function addLinkedValue(\Dashboard\CommonBundle\Entity\FilterValue $linkedValues)
    {
        $this->linkedValues[] = $linkedValues;

        return $this;
    }

    /**
     * Remove linkedValues
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $linkedValues
     */
    public function removeLinkedValue(\Dashboard\CommonBundle\Entity\FilterValue $linkedValues)
    {
        $this->linkedValues->removeElement($linkedValues);
    }

    /**
     * Get linkedValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinkedValues()
    {
        return $this->linkedValues;
    }

    /**
     * Add linkedFilters
     *
     * @param \Dashboard\CommonBundle\Entity\Filter $linkedFilters
     * @return FilterValue
     */
    public function addLinkedFilter(\Dashboard\CommonBundle\Entity\Filter $linkedFilters)
    {
        $this->linkedFilters[] = $linkedFilters;

        return $this;
    }

    /**
     * Remove linkedFilters
     *
     * @param \Dashboard\CommonBundle\Entity\Filter $linkedFilters
     */
    public function removeLinkedFilter(\Dashboard\CommonBundle\Entity\Filter $linkedFilters)
    {
        $this->linkedFilters->removeElement($linkedFilters);
    }

    /**
     * Get linkedFilters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinkedFilters()
    {
        return $this->linkedFilters;
    }

    /**
     * Add translations
     *
     * @param \Dashboard\CommonBundle\Entity\Translation $translations
     * @return FilterValue
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
}
