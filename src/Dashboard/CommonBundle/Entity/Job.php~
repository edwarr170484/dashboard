<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Job
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\JobCategory", inversedBy="jobs")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default": null})
     */
    private $sortorder;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\DealerSalon", mappedBy="jobs")
     */
    private $salons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->salons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Job
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
     * Set sortorder
     *
     * @param integer $sortorder
     * @return Job
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
     * Set category
     *
     * @param \Dashboard\CommonBundle\Entity\JobCategory $category
     * @return Job
     */
    public function setCategory(\Dashboard\CommonBundle\Entity\JobCategory $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Dashboard\CommonBundle\Entity\JobCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add salons
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalon $salons
     * @return Job
     */
    public function addSalon(\Dashboard\CommonBundle\Entity\DealerSalon $salons)
    {
        $this->salons[] = $salons;
    
        return $this;
    }

    /**
     * Remove salons
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalon $salons
     */
    public function removeSalon(\Dashboard\CommonBundle\Entity\DealerSalon $salons)
    {
        $this->salons->removeElement($salons);
    }

    /**
     * Get salons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSalons()
    {
        return $this->salons;
    }
}
