<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Liqpay
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
    private $publicKey;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $privateKey;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $currency;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $sandbox;

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
     * Set publicKey
     *
     * @param string $publicKey
     * @return Liqpay
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Get publicKey
     *
     * @return string 
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set privateKey
     *
     * @param string $privateKey
     * @return Liqpay
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Get privateKey
     *
     * @return string 
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Liqpay
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set sandbox
     *
     * @param boolean $sandbox
     * @return Liqpay
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;

        return $this;
    }

    /**
     * Get sandbox
     *
     * @return boolean 
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }
}
