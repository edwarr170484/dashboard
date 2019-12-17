<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class PageBlock 
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Page", inversedBy="blocks")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":0})
     */
    private $blockTitle;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":null})
     */
    private $blockContent;
    
    /**
     * @ORM\Column(type="integer", length = 15, nullable=true, options={"default":"null"})
     */
    private $sortorder;


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
     * Set blockTitle
     *
     * @param string $blockTitle
     * @return PageBlock
     */
    public function setBlockTitle($blockTitle)
    {
        $this->blockTitle = $blockTitle;
    
        return $this;
    }

    /**
     * Get blockTitle
     *
     * @return string 
     */
    public function getBlockTitle()
    {
        return $this->blockTitle;
    }

    /**
     * Set blockContent
     *
     * @param string $blockContent
     * @return PageBlock
     */
    public function setBlockContent($blockContent)
    {
        $this->blockContent = $blockContent;
    
        return $this;
    }

    /**
     * Get blockContent
     *
     * @return string 
     */
    public function getBlockContent()
    {
        return $this->blockContent;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return PageBlock
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
     * Set page
     *
     * @param \Dashboard\CommonBundle\Entity\Page $page
     * @return PageBlock
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
}
