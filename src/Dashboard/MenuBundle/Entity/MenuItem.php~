<?php

namespace Dashboard\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="menu_items")
 * @ORM\Entity()
 */

class MenuItem
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
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="items")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     **/
    private $menu;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\MenuBundle\Entity\MenuItem", mappedBy="parent")
     * @ORM\OrderBy({"sortorder" = "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\MenuBundle\Entity\MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @ORM\OrderBy({"sortorder" = "ASC"})
     */
    private $parent;
    
    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $title;
    
    /**
     * @ORM\Column(name="link", type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $link;
        
    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $sortorder;
    
    /**
     * @ORM\Column(name="block", type="integer", nullable=true, options={"default":"null"})
     */
    private $block;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return MenuItem
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
     * Set link
     *
     * @param string $link
     * @return MenuItem
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
     * Set sortorder
     *
     * @param integer $sortorder
     * @return MenuItem
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
     * Set block
     *
     * @param integer $block
     * @return MenuItem
     */
    public function setBlock($block)
    {
        $this->block = $block;
    
        return $this;
    }

    /**
     * Get block
     *
     * @return integer 
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * Set menu
     *
     * @param \Dashboard\MenuBundle\Entity\Menu $menu
     * @return MenuItem
     */
    public function setMenu(\Dashboard\MenuBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;
    
        return $this;
    }

    /**
     * Get menu
     *
     * @return \Dashboard\MenuBundle\Entity\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set page
     *
     * @param \Dashboard\CommonBundle\Entity\Page $page
     * @return MenuItem
     */
    public function setPage(\Dashboard\CommonBundle\Entity\Page $page = null)
    {
        $this->page = $page;
    
        return $this;
    }

    /**
     * Get page
     *
     * @return \Dashboard\CommonBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set category
     *
     * @param \Dashboard\CommonBundle\Entity\Category $category
     * @return MenuItem
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
     * Add children
     *
     * @param \Dashboard\MenuBundle\Entity\MenuItem $children
     * @return MenuItem
     */
    public function addChild(\Dashboard\MenuBundle\Entity\MenuItem $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Dashboard\MenuBundle\Entity\MenuItem $children
     */
    public function removeChild(\Dashboard\MenuBundle\Entity\MenuItem $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Dashboard\MenuBundle\Entity\MenuItem $parent
     * @return MenuItem
     */
    public function setParent(\Dashboard\MenuBundle\Entity\MenuItem $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Dashboard\MenuBundle\Entity\MenuItem 
     */
    public function getParent()
    {
        return $this->parent;
    }
}
