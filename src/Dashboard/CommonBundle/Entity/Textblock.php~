<?php

namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Textblock
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $howToSetPrice;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $userAgreement;
    

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
     * Set howToSetPrice
     *
     * @param string $howToSetPrice
     * @return Textblock
     */
    public function setHowToSetPrice($howToSetPrice)
    {
        $this->howToSetPrice = $howToSetPrice;
    
        return $this;
    }

    /**
     * Get howToSetPrice
     *
     * @return string 
     */
    public function getHowToSetPrice()
    {
        return $this->howToSetPrice;
    }

    /**
     * Set userAgreement
     *
     * @param string $userAgreement
     * @return Textblock
     */
    public function setUserAgreement($userAgreement)
    {
        $this->userAgreement = $userAgreement;
    
        return $this;
    }

    /**
     * Get userAgreement
     *
     * @return string 
     */
    public function getUserAgreement()
    {
        return $this->userAgreement;
    }
}
