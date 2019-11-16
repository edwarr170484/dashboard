<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class ProductOrder
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="receivedOrders")
     * @ORM\JoinColumn(name="user_received_id", referencedColumnName="id")
     */
    private $userReceived;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="sendedOrders")
     * @ORM\JoinColumn(name="user_sended_id", referencedColumnName="id")
     */
    private $userSended;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Product", inversedBy="orders")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\OrderStatus", inversedBy="orders")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\CityCode")
     * @ORM\JoinColumn(name="city_code_id", referencedColumnName="id")
     */
    private $cityCode;
    
    /**
     * @ORM\Column(type="text", nullable=true, options={"default":"0"})
     */
    private $comment;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":"0"})
     */
    private $statusComment;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"1"})
     */
    private $isNew;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\ProductOrderInfo", mappedBy="order")
     */
    private $info;

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
     * @return Order
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
     * Set email
     *
     * @param string $email
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Order
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
     * Set comment
     *
     * @param string $comment
     * @return Order
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Order
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set userReceived
     *
     * @param \Dashboard\CommonBundle\Entity\User $userReceived
     * @return Order
     */
    public function setUserReceived(\Dashboard\CommonBundle\Entity\User $userReceived = null)
    {
        $this->userReceived = $userReceived;

        return $this;
    }

    /**
     * Get userReceived
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUserReceived()
    {
        return $this->userReceived;
    }

    /**
     * Set userSended
     *
     * @param \Dashboard\CommonBundle\Entity\User $userSended
     * @return Order
     */
    public function setUserSended(\Dashboard\CommonBundle\Entity\User $userSended = null)
    {
        $this->userSended = $userSended;

        return $this;
    }

    /**
     * Get userSended
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUserSended()
    {
        return $this->userSended;
    }

    /**
     * Set product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     * @return Order
     */
    public function setProduct(\Dashboard\CommonBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Dashboard\CommonBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set isNew
     *
     * @param boolean $isNew
     * @return ProductOrder
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew
     *
     * @return boolean 
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set statusComment
     *
     * @param string $statusComment
     * @return ProductOrder
     */
    public function setStatusComment($statusComment)
    {
        $this->statusComment = $statusComment;

        return $this;
    }

    /**
     * Get statusComment
     *
     * @return string 
     */
    public function getStatusComment()
    {
        return $this->statusComment;
    }

    /**
     * Set status
     *
     * @param \Dashboard\CommonBundle\Entity\OrderStatus $status
     * @return ProductOrder
     */
    public function setStatus(\Dashboard\CommonBundle\Entity\OrderStatus $status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return \Dashboard\CommonBundle\Entity\OrderStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->info = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add info
     *
     * @param \Dashboard\CommonBundle\Entity\ProductOrderInfo $info
     * @return ProductOrder
     */
    public function addInfo(\Dashboard\CommonBundle\Entity\ProductOrderInfo $info)
    {
        $this->info[] = $info;
    
        return $this;
    }

    /**
     * Remove info
     *
     * @param \Dashboard\CommonBundle\Entity\ProductOrderInfo $info
     */
    public function removeInfo(\Dashboard\CommonBundle\Entity\ProductOrderInfo $info)
    {
        $this->info->removeElement($info);
    }

    /**
     * Get info
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set city
     *
     * @param \Dashboard\CommonBundle\Entity\City $city
     * @return ProductOrder
     */
    public function setCity(\Dashboard\CommonBundle\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return \Dashboard\CommonBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set cityCode
     *
     * @param \Dashboard\CommonBundle\Entity\CityCode $cityCode
     * @return ProductOrder
     */
    public function setCityCode(\Dashboard\CommonBundle\Entity\CityCode $cityCode = null)
    {
        $this->cityCode = $cityCode;
    
        return $this;
    }

    /**
     * Get cityCode
     *
     * @return \Dashboard\CommonBundle\Entity\CityCode 
     */
    public function getCityCode()
    {
        return $this->cityCode;
    }
}
