<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class CategoryRate
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category", inversedBy="rates")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default": 0})
     */
    private $billId;
    
    /**
     * @ORM\Column(type="float", length=15, nullable=true, options={"default": 0})
     */
    private $price;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Rate")
     * @ORM\JoinColumn(name="rate_id", referencedColumnName="id")
     */
    private $rate;
    

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
     * Set billId
     *
     * @param integer $billId
     * @return CategoryRate
     */
    public function setBillId($billId)
    {
        $this->billId = $billId;
    
        return $this;
    }

    /**
     * Get billId
     *
     * @return integer 
     */
    public function getBillId()
    {
        return $this->billId;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return CategoryRate
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set category
     *
     * @param \Dashboard\CommonBundle\Entity\Category $category
     * @return CategoryRate
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
     * Set rate
     *
     * @param \Dashboard\CommonBundle\Entity\Rate $rate
     * @return CategoryRate
     */
    public function setRate(\Dashboard\CommonBundle\Entity\Rate $rate = null)
    {
        $this->rate = $rate;
    
        return $this;
    }

    /**
     * Get rate
     *
     * @return \Dashboard\CommonBundle\Entity\Rate 
     */
    public function getRate()
    {
        return $this->rate;
    }
}
