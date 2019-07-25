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
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Translation", mappedBy="filterValue", cascade={"persist"})
     */
    private $translations;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
