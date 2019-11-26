<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class DealerPhone
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\DealerInfo", inversedBy="phones")
     * @ORM\JoinColumn(name="dealer_info_id", referencedColumnName="id")
     */
    private $dealerInfo;
    

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
     * Set phone
     *
     * @param string $phone
     * @return DealerPhone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set dealerInfo
     *
     * @param \Dashboard\CommonBundle\Entity\DealerInfo $dealerInfo
     * @return DealerPhone
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
