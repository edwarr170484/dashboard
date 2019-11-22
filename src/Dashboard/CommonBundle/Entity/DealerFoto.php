<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class DealerFoto
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\DealerInfo", inversedBy="fotos")
     * @ORM\JoinColumn(name="dealer_info_id", referencedColumnName="id")
     */
    private $dealerInfo;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": null})
     */
    private $image; 

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
     * @return DealerFoto
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
     * Set dealerInfo
     *
     * @param \Dashboard\CommonBundle\Entity\DealerInfo $dealerInfo
     * @return DealerFoto
     */
    public function setDealerInfo(\Dashboard\CommonBundle\Entity\DealerInfo $dealerInfo = null)
    {
        $this->dealerInfo = $dealerInfo;
    
        return $this;
    }

    /**
     * Get dealerInfo
     *
     * @return \Dashboard\CommonBundle\Entity\DealerInfo 
     */
    public function getDealerInfo()
    {
        return $this->dealerInfo;
    }
}
