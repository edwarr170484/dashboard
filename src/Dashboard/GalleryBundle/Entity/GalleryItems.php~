<?php

namespace Dashboard\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Dashboard\GalleryBundle\Entity\Gallery;

/**
 * @ORM\Table(name="gallery_items")
 * @ORM\Entity()
 */

class GalleryItems
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
     * @ORM\ManyToOne(targetEntity="Dashboard\GalleryBundle\Entity\Gallery")
     **/
    private $gallery;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true, options={"default":"null"})
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="string", length=255)
     */
    private $thumb;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;
    
    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $alt;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $title;
        
    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="is_main", type="boolean", length=1)
     */
    private $isMain = 0;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

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
     * Set description
     *
     * @param string $description
     *
     * @return GalleryItems
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
     * Set thumb
     *
     * @param string $thumb
     *
     * @return GalleryItems
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
    
        return $this;
    }

    /**
     * Get thumb
     *
     * @return string
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return GalleryItems
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
     * Set alt
     *
     * @param string $alt
     *
     * @return GalleryItems
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    
        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return GalleryItems
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
     * Set sort
     *
     * @param integer $sort
     *
     * @return GalleryItems
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
     * Set isMain
     *
     * @param boolean $isMain
     *
     * @return GalleryItems
     */
    public function setIsMain($isMain)
    {
        $this->isMain = $isMain;
    
        return $this;
    }

    /**
     * Get isMain
     *
     * @return boolean
     */
    public function getIsMain()
    {
        return $this->isMain;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return GalleryItems
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set gallery
     *
     * @param \Dashboard\GalleryBundle\Entity\Gallery $gallery
     *
     * @return GalleryItems
     */
    public function setGallery(\Dashboard\GalleryBundle\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;
    
        return $this;
    }

    /**
     * Get gallery
     *
     * @return \Dashboard\GalleryBundle\Entity\Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }
}
