<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\MenuBundle\Entity\MenuItems;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Currency
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length = 255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length = 255)
     */
    private $code;
    
    /**
     * @ORM\Column(type="float", length = 255)
     */
    private $kurs;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"0"})
     */
    private $isDefault;
    
    /**
     * @ORM\Column(type="integer", length = 15, nullable=true, options={"default":"0"})
     */
    private $sortorder;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Locale", mappedBy="currency", cascade={"persist"})
     */
    private $locale;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Settings", mappedBy="currency", cascade={"persist"})
     */
    private $settings;
    

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
     * Set name
     *
     * @param string $name
     * @return Currency
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Currency
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set kurs
     *
     * @param float $kurs
     * @return Currency
     */
    public function setKurs($kurs)
    {
        $this->kurs = $kurs;
    
        return $this;
    }

    /**
     * Get kurs
     *
     * @return float 
     */
    public function getKurs()
    {
        return $this->kurs;
    }

    /**
     * Set isDefault
     *
     * @param boolean $isDefault
     * @return Currency
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    
        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean 
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     * @return Currency
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
     * Set locale
     *
     * @param \Dashboard\CommonBundle\Entity\Locale $locale
     * @return Currency
     */
    public function setLocale(\Dashboard\CommonBundle\Entity\Locale $locale = null)
    {
        $this->locale = $locale;
    
        return $this;
    }

    /**
     * Get locale
     *
     * @return \Dashboard\CommonBundle\Entity\Locale 
     */
    public function getLocale()
    {
        return $this->locale;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->settings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add settings
     *
     * @param \Dashboard\CommonBundle\Entity\Settings $settings
     * @return Currency
     */
    public function addSetting(\Dashboard\CommonBundle\Entity\Settings $settings)
    {
        $this->settings[] = $settings;
    
        return $this;
    }

    /**
     * Remove settings
     *
     * @param \Dashboard\CommonBundle\Entity\Settings $settings
     */
    public function removeSetting(\Dashboard\CommonBundle\Entity\Settings $settings)
    {
        $this->settings->removeElement($settings);
    }

    /**
     * Get settings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
