<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class DealerSalonPhone
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
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\DealerSalon", inversedBy="phones")
     * @ORM\JoinColumn(name="dealer_salon_id", referencedColumnName="id")
     */
    private $dealerSalon;


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
     * @return DealerSalonPhone
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
     * Set dealerSalon
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalon $dealerSalon
     * @return DealerSalonPhone
     */
    public function setDealerSalon(\Dashboard\CommonBundle\Entity\DealerSalon $dealerSalon = null)
    {
        $this->dealerSalon = $dealerSalon;
    
        return $this;
    }

    /**
     * Get dealerSalon
     *
     * @return \Dashboard\CommonBundle\Entity\DealerSalon 
     */
    public function getDealerSalon()
    {
        return $this->dealerSalon;
    }
}
