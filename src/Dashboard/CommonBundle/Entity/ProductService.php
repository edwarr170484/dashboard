<?php
namespace Dashboard\CommonBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class ProductService
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Product", inversedBy="service")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Service", inversedBy="products")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;
    
    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isActive;
    

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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return ProductService
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return ProductService
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
     * Set product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     * @return ProductService
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

    /**
     * Set service
     *
     * @param \Dashboard\CommonBundle\Entity\Service $service
     * @return ProductService
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
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return ProductService
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }
}
