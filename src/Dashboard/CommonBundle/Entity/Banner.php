<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Banner
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
    private $image;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":"0"})
     */
    private $link;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $code;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":0})
     */
    private $dateFrom;
    
    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":0})
     */
    private $dateTo;
    
    /**
     * @ORM\Column(type="integer", length=15, nullable=true, options={"default":"0"})
     */
    private $clicks;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Category", inversedBy="banners")
     * @ORM\JoinTable(name="category_banners")
     */
    private $categories;
    
    /**
     * @ORM\ManyToMany(targetEntity="Dashboard\CommonBundle\Entity\Page", inversedBy="banners")
     * @ORM\JoinTable(name="pages_banners")
     */
    private $pages;
    

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
     * @return Banner
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
     * Set image
     *
     * @param string $image
     * @return Banner
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Banner
     */
    public function setLink($link)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Banner
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
     * Set position
     *
     * @param string $position
     * @return Banner
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Banner
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
     * Set dateFrom
     *
     * @param \DateTime $dateFrom
     * @return Banner
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    
        return $this;
    }

    /**
     * Get dateFrom
     *
     * @return \DateTime 
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * Set dateTo
     *
     * @param \DateTime $dateTo
     * @return Banner
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    
        return $this;
    }

    /**
     * Get dateTo
     *
     * @return \DateTime 
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     * @return Banner
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
    
        return $this;
    }

    /**
     * Get clicks
     *
     * @return integer 
     */
    public function getClicks()
    {
        return $this->clicks;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categories
     *
     * @param \Dashboard\CommonBundle\Entity\Category $categories
     * @return Banner
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
     * Add pages
     *
     * @param \Dashboard\CommonBundle\Entity\Page $pages
     * @return Banner
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
}
