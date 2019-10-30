<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class GenerationBoard 
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\FilterValue")
     * @ORM\JoinColumn(name="board_id", referencedColumnName="id")
     */
    private $board;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"null"})
     */
    private $image;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Generation", inversedBy="boards")
     * @ORM\JoinColumn(name="generation_id", referencedColumnName="id")
     */
    private $generation;
    

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
     * Set image
     *
     * @param string $image
     * @return GenerationBoard
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
     * Set board
     *
     * @param \Dashboard\CommonBundle\Entity\FilterValue $board
     * @return GenerationBoard
     */
    public function setBoard(\Dashboard\CommonBundle\Entity\FilterValue $board = null)
    {
        $this->board = $board;
    
        return $this;
    }

    /**
     * Get board
     *
     * @return \Dashboard\CommonBundle\Entity\FilterValue 
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set generation
     *
     * @param \Dashboard\CommonBundle\Entity\Generation $generation
     * @return GenerationBoard
     */
    public function setGeneration(\Dashboard\CommonBundle\Entity\Generation $generation = null)
    {
        $this->generation = $generation;
    
        return $this;
    }

    /**
     * Get generation
     *
     * @return \Dashboard\CommonBundle\Entity\Generation 
     */
    public function getGeneration()
    {
        return $this->generation;
    }
}
