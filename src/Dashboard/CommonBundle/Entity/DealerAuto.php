<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class DealerAuto
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\DealerInfo", inversedBy="autos")
     * @ORM\JoinColumn(name="dealer_info_id", referencedColumnName="id")
     */
    private $dealerInfo;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Category")
     * @ORM\JoinColumn(name="auto_id", referencedColumnName="id")
     */
    private $auto;
    

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
     * Set dealerInfo
     *
     * @param \Dashboard\CommonBundle\Entity\DealerInfo $dealerInfo
     * @return DealerAuto
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

    /**
     * Set auto
     *
     * @param \Dashboard\CommonBundle\Entity\Category $auto
     * @return DealerAuto
     */
    public function setAuto(\Dashboard\CommonBundle\Entity\Category $auto = null)
    {
        $this->auto = $auto;
    
        return $this;
    }

    /**
     * Get auto
     *
     * @return \Dashboard\CommonBundle\Entity\Category 
     */
    public function getAuto()
    {
        return $this->auto;
    }
}
