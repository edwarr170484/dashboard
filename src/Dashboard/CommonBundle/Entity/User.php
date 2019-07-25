<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Dashboard\CommonBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    
     /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * 
     */
    private $roles;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $alerts;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"0"})
     */
    private $isConfirm;
    
    /**
     * @ORM\Column(type="integer", length=15)
     */
    private $advertNumber;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $vkID;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":"0"})
     */
    private $fbID;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\UserInfo", mappedBy="user", cascade={"persist"})
     */
    private $userinfo;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\UserPurse", mappedBy="user", cascade={"persist"})
     */
    private $userpurse;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\UserActivity", mappedBy="user", cascade={"persist"})
     */
    private $activity;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Product", mappedBy="user")
     */
    private $products;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Friend", mappedBy="referrer")
     */
    private $friends;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Complaint", mappedBy="user")
     */
    private $complaint;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\ProductOrder", mappedBy="userReceived")
     */
    private $receivedOrders;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\ProductOrder", mappedBy="userSended")
     */
    private $sendedOrders;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Review", mappedBy="user")
     */
    private $reviews;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Review", mappedBy="targetUser")
     */
    private $targetReviews;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Message", mappedBy="userTo")
     */
    private $messageInbox;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Message", mappedBy="userFrom")
     */
    private $messageOutbox;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Message", mappedBy="userOwner")
     */
    private $messageOwner;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Conversation", mappedBy="userOne")
     */
    private $conversationOne;
    
    /**
     * @ORM\OneToMany(targetEntity="Dashboard\CommonBundle\Entity\Conversation", mappedBy="userTwo")
     */
    private $conversationTwo;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Invite", mappedBy="user")
     */
    private $invite;
    
    private $favoriteProducts;
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
	
    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return $this->roles->toArray();
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();
        $this->targetReviews = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messageInbox = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messageOutbox = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set alerts
     *
     * @param boolean $alerts
     * @return User
     */
    public function setAlerts($alerts)
    {
        $this->alerts = $alerts;

        return $this;
    }

    /**
     * Get alerts
     *
     * @return boolean 
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * Set isConfirm
     *
     * @param boolean $isConfirm
     * @return User
     */
    public function setIsConfirm($isConfirm)
    {
        $this->isConfirm = $isConfirm;

        return $this;
    }

    /**
     * Get isConfirm
     *
     * @return boolean 
     */
    public function getIsConfirm()
    {
        return $this->isConfirm;
    }

    /**
     * Add roles
     *
     * @param \Dashboard\CommonBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Dashboard\CommonBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Dashboard\CommonBundle\Entity\Role $roles
     */
    public function removeRole(\Dashboard\CommonBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Set userinfo
     *
     * @param \Dashboard\CommonBundle\Entity\UserInfo $userinfo
     * @return User
     */
    public function setUserinfo(\Dashboard\CommonBundle\Entity\UserInfo $userinfo = null)
    {
        $this->userinfo = $userinfo;

        return $this;
    }

    /**
     * Get userinfo
     *
     * @return \Dashboard\CommonBundle\Entity\UserInfo 
     */
    public function getUserinfo()
    {
        return $this->userinfo;
    }

    /**
     * Add products
     *
     * @param \Dashboard\CommonBundle\Entity\Product $products
     * @return User
     */
    public function addProduct(\Dashboard\CommonBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Dashboard\CommonBundle\Entity\Product $products
     */
    public function removeProduct(\Dashboard\CommonBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add reviews
     *
     * @param \Dashboard\CommonBundle\Entity\Review $reviews
     * @return User
     */
    public function addReview(\Dashboard\CommonBundle\Entity\Review $reviews)
    {
        $this->reviews[] = $reviews;

        return $this;
    }

    /**
     * Remove reviews
     *
     * @param \Dashboard\CommonBundle\Entity\Review $reviews
     */
    public function removeReview(\Dashboard\CommonBundle\Entity\Review $reviews)
    {
        $this->reviews->removeElement($reviews);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Add targetReviews
     *
     * @param \Dashboard\CommonBundle\Entity\Review $targetReviews
     * @return User
     */
    public function addTargetReview(\Dashboard\CommonBundle\Entity\Review $targetReviews)
    {
        $this->targetReviews[] = $targetReviews;

        return $this;
    }

    /**
     * Remove targetReviews
     *
     * @param \Dashboard\CommonBundle\Entity\Review $targetReviews
     */
    public function removeTargetReview(\Dashboard\CommonBundle\Entity\Review $targetReviews)
    {
        $this->targetReviews->removeElement($targetReviews);
    }

    /**
     * Get targetReviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTargetReviews()
    {
        return $this->targetReviews;
    }

    /**
     * Add messageInbox
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messageInbox
     * @return User
     */
    public function addMessageInbox(\Dashboard\CommonBundle\Entity\Message $messageInbox)
    {
        $this->messageInbox[] = $messageInbox;

        return $this;
    }

    /**
     * Remove messageInbox
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messageInbox
     */
    public function removeMessageInbox(\Dashboard\CommonBundle\Entity\Message $messageInbox)
    {
        $this->messageInbox->removeElement($messageInbox);
    }

    /**
     * Get messageInbox
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessageInbox()
    {
        return $this->messageInbox;
    }

    /**
     * Add messageOutbox
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messageOutbox
     * @return User
     */
    public function addMessageOutbox(\Dashboard\CommonBundle\Entity\Message $messageOutbox)
    {
        $this->messageOutbox[] = $messageOutbox;

        return $this;
    }

    /**
     * Remove messageOutbox
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messageOutbox
     */
    public function removeMessageOutbox(\Dashboard\CommonBundle\Entity\Message $messageOutbox)
    {
        $this->messageOutbox->removeElement($messageOutbox);
    }

    /**
     * Get messageOutbox
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessageOutbox()
    {
        return $this->messageOutbox;
    }

    /**
     * Set userpurse
     *
     * @param \Dashboard\CommonBundle\Entity\UserPurse $userpurse
     * @return User
     */
    public function setUserpurse(\Dashboard\CommonBundle\Entity\UserPurse $userpurse = null)
    {
        $this->userpurse = $userpurse;

        return $this;
    }

    /**
     * Get userpurse
     *
     * @return \Dashboard\CommonBundle\Entity\UserPurse 
     */
    public function getUserpurse()
    {
        return $this->userpurse;
    }

    /**
     * Add messageOwner
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messageOwner
     * @return User
     */
    public function addMessageOwner(\Dashboard\CommonBundle\Entity\Message $messageOwner)
    {
        $this->messageOwner[] = $messageOwner;

        return $this;
    }

    /**
     * Remove messageOwner
     *
     * @param \Dashboard\CommonBundle\Entity\Message $messageOwner
     */
    public function removeMessageOwner(\Dashboard\CommonBundle\Entity\Message $messageOwner)
    {
        $this->messageOwner->removeElement($messageOwner);
    }

    /**
     * Get messageOwner
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessageOwner()
    {
        return $this->messageOwner;
    }


    /**
     * Add receivedOrders
     *
     * @param \Dashboard\CommonBundle\Entity\ProductOrder $receivedOrders
     * @return User
     */
    public function addReceivedOrder(\Dashboard\CommonBundle\Entity\ProductOrder $receivedOrders)
    {
        $this->receivedOrders[] = $receivedOrders;

        return $this;
    }

    /**
     * Remove receivedOrders
     *
     * @param \Dashboard\CommonBundle\Entity\ProductOrder $receivedOrders
     */
    public function removeReceivedOrder(\Dashboard\CommonBundle\Entity\ProductOrder $receivedOrders)
    {
        $this->receivedOrders->removeElement($receivedOrders);
    }

    /**
     * Get receivedOrders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReceivedOrders()
    {
        return $this->receivedOrders;
    }

    /**
     * Add sendedOrders
     *
     * @param \Dashboard\CommonBundle\Entity\ProductOrder $sendedOrders
     * @return User
     */
    public function addSendedOrder(\Dashboard\CommonBundle\Entity\ProductOrder $sendedOrders)
    {
        $this->sendedOrders[] = $sendedOrders;

        return $this;
    }

    /**
     * Remove sendedOrders
     *
     * @param \Dashboard\CommonBundle\Entity\ProductOrder $sendedOrders
     */
    public function removeSendedOrder(\Dashboard\CommonBundle\Entity\ProductOrder $sendedOrders)
    {
        $this->sendedOrders->removeElement($sendedOrders);
    }

    /**
     * Get sendedOrders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSendedOrders()
    {
        return $this->sendedOrders;
    }

    /**
     * Add complaint
     *
     * @param \Dashboard\CommonBundle\Entity\Complaint $complaint
     * @return User
     */
    public function addComplaint(\Dashboard\CommonBundle\Entity\Complaint $complaint)
    {
        $this->complaint[] = $complaint;

        return $this;
    }

    /**
     * Remove complaint
     *
     * @param \Dashboard\CommonBundle\Entity\Complaint $complaint
     */
    public function removeComplaint(\Dashboard\CommonBundle\Entity\Complaint $complaint)
    {
        $this->complaint->removeElement($complaint);
    }

    /**
     * Get complaint
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComplaint()
    {
        return $this->complaint;
    }

    /**
     * Add friends
     *
     * @param \Dashboard\CommonBundle\Entity\Friend $friends
     * @return User
     */
    public function addFriend(\Dashboard\CommonBundle\Entity\Friend $friends)
    {
        $this->friends[] = $friends;

        return $this;
    }

    /**
     * Remove friends
     *
     * @param \Dashboard\CommonBundle\Entity\Friend $friends
     */
    public function removeFriend(\Dashboard\CommonBundle\Entity\Friend $friends)
    {
        $this->friends->removeElement($friends);
    }

    /**
     * Get friends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * Set activity
     *
     * @param \Dashboard\CommonBundle\Entity\UserActivity $activity
     * @return User
     */
    public function setActivity(\Dashboard\CommonBundle\Entity\UserActivity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \Dashboard\CommonBundle\Entity\UserActivity 
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set invite
     *
     * @param \Dashboard\CommonBundle\Entity\Invite $invite
     * @return User
     */
    public function setInvite(\Dashboard\CommonBundle\Entity\Invite $invite = null)
    {
        $this->invite = $invite;

        return $this;
    }

    /**
     * Get invite
     *
     * @return \Dashboard\CommonBundle\Entity\Invite 
     */
    public function getInvite()
    {
        return $this->invite;
    }

    /**
     * Set advertNumber
     *
     * @param integer $advertNumber
     * @return User
     */
    public function setAdvertNumber($advertNumber)
    {
        $this->advertNumber = $advertNumber;

        return $this;
    }

    /**
     * Get advertNumber
     * @Assert\Type(type="numeric", message = "Количество слотов должно содержать только цифры.")
     * @return integer 
     */
    public function getAdvertNumber()
    {
        return $this->advertNumber;
    }

    /**
     * Set vkID
     *
     * @param string $vkID
     * @return User
     */
    public function setVkID($vkID)
    {
        $this->vkID = $vkID;

        return $this;
    }

    /**
     * Get vkID
     *
     * @return string 
     */
    public function getVkID()
    {
        return $this->vkID;
    }

    /**
     * Set fbID
     *
     * @param string $fbID
     * @return User
     */
    public function setFbID($fbID)
    {
        $this->fbID = $fbID;

        return $this;
    }

    /**
     * Get fbID
     *
     * @return string 
     */
    public function getFbID()
    {
        return $this->fbID;
    }

    /**
     * Add conversationOne
     *
     * @param \Dashboard\CommonBundle\Entity\Conversation $conversationOne
     * @return User
     */
    public function addConversationOne(\Dashboard\CommonBundle\Entity\Conversation $conversationOne)
    {
        $this->conversationOne[] = $conversationOne;

        return $this;
    }

    /**
     * Remove conversationOne
     *
     * @param \Dashboard\CommonBundle\Entity\Conversation $conversationOne
     */
    public function removeConversationOne(\Dashboard\CommonBundle\Entity\Conversation $conversationOne)
    {
        $this->conversationOne->removeElement($conversationOne);
    }

    /**
     * Get conversationOne
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConversationOne()
    {
        return $this->conversationOne;
    }

    /**
     * Add conversationTwo
     *
     * @param \Dashboard\CommonBundle\Entity\Conversation $conversationTwo
     * @return User
     */
    public function addConversationTwo(\Dashboard\CommonBundle\Entity\Conversation $conversationTwo)
    {
        $this->conversationTwo[] = $conversationTwo;

        return $this;
    }

    /**
     * Remove conversationTwo
     *
     * @param \Dashboard\CommonBundle\Entity\Conversation $conversationTwo
     */
    public function removeConversationTwo(\Dashboard\CommonBundle\Entity\Conversation $conversationTwo)
    {
        $this->conversationTwo->removeElement($conversationTwo);
    }

    /**
     * Get conversationTwo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConversationTwo()
    {
        return $this->conversationTwo;
    }
    
    public function getFavoriteProducts()
    {
        return $this->favoriteProducts;
    }
    
    
    public function setFavoriteProducts(array $favoriteProducts)
    {
        $this->favoriteProducts = $favoriteProducts;
    }
}
