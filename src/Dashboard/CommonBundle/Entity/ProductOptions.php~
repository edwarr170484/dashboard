<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class ProductOptions
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Product", inversedBy="options")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\Column(type="string", length=512)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=512)
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
     * Set name
     *
     * @param string $name
     * @return ProductOptions
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
     * Set value
     *
     * @param string $value
     * @return ProductOptions
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
     * Set product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     * @return ProductOptions
     */
    public function setProduct(\Dashboard\CommonBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Dashboard\CommonBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
