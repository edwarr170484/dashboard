<?php

namespace Dashboard\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

use Dashboard\GalleryBundle\Entity\GalleryItems;

/**
 * @ORM\Table(name="gallery")
 * @ORM\Entity(repositoryClass="Dashboard\GalleryBundle\Entity\GalleryRepository")
 */
class Gallery
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $translit;
    
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true, options={"default":"null"})
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     */
    private $sort;
    
    /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $isShow;
    
    /**
     * @ORM\OneToMany(targetEntity="GalleryItems", mappedBy="gallery", cascade={"persist"})
     * @ORM\OrderBy({"sort"="ASC"})
     **/
    private $items;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Locale", inversedBy="galleries")
     * @ORM\JoinColumn(name="locale_id", referencedColumnName="id")
     */
    private $locale;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Gallery
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
     * Set translit
     *
     * @param string $translit
     * @return Gallery
     */
    public function setTranslit($translit)
    {
        $this->translit = $translit;

        return $this;
    }

    /**
     * Get translit
     *
     * @return string 
     */
    public function getTranslit()
    {
        return $this->translit;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Gallery
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Gallery
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Add items
     *
     * @param \Dashboard\GalleryBundle\Entity\GalleryItems $items
     * @return Gallery
     */
    public function addItem(\Dashboard\GalleryBundle\Entity\GalleryItems $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Dashboard\GalleryBundle\Entity\GalleryItems $items
     */
    public function removeItem(\Dashboard\GalleryBundle\Entity\GalleryItems $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set isShow
     *
     * @param boolean $isShow
     * @return Gallery
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
     * Set locale
     *
     * @param \Dashboard\CommonBundle\Entity\Locale $locale
     * @return Gallery
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
}
