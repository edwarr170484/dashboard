<?php
namespace Dashboard\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Review
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Review")
     * @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     */
    private $answer;
    
    /**
     * @ORM\OneToOne(targetEntity="Dashboard\CommonBundle\Entity\Review")
     * @ORM\JoinColumn(name="answer_to_id", referencedColumnName="id")
     */
    private $answerTo;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\User", inversedBy="targetReviews")
     * @ORM\JoinColumn(name="user_target_id", referencedColumnName="id")
     */
    private $targetUser;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\DealerSalon", inversedBy="reviews")
     * @ORM\JoinColumn(name="dealer_salon_id", referencedColumnName="id")
     */
    private $salons;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": 0})
     */
    private $reviewReason;
    
    /**
     * @ORM\Column(type="text")
     */
    private $reviewText;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    
    /**
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\ReviewStatus", inversedBy="reviews")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;
    
    /**
     * @ORM\Column(type="integer", length=2, nullable=true, options={"default": 0})
     */
    private $rating;
    

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
     * Set reviewReason
     *
     * @param string $reviewReason
     * @return Review
     */
    public function setReviewReason($reviewReason)
    {
        $this->reviewReason = $reviewReason;
    
        return $this;
    }

    /**
     * Get reviewReason
     *
     * @return string 
     */
    public function getReviewReason()
    {
        return $this->reviewReason;
    }

    /**
     * Set reviewText
     *
     * @param string $reviewText
     * @return Review
     */
    public function setReviewText($reviewText)
    {
        $this->reviewText = $reviewText;
    
        return $this;
    }

    /**
     * Get reviewText
     *
     * @return string 
     */
    public function getReviewText()
    {
        return $this->reviewText;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Review
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
     * Set rating
     *
     * @param integer $rating
     * @return Review
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set answer
     *
     * @param \Dashboard\CommonBundle\Entity\Review $answer
     * @return Review
     */
    public function setAnswer(\Dashboard\CommonBundle\Entity\Review $answer = null)
    {
        $this->answer = $answer;
    
        return $this;
    }

    /**
     * Get answer
     *
     * @return \Dashboard\CommonBundle\Entity\Review 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set answerTo
     *
     * @param \Dashboard\CommonBundle\Entity\Review $answerTo
     * @return Review
     */
    public function setAnswerTo(\Dashboard\CommonBundle\Entity\Review $answerTo = null)
    {
        $this->answerTo = $answerTo;
    
        return $this;
    }

    /**
     * Get answerTo
     *
     * @return \Dashboard\CommonBundle\Entity\Review 
     */
    public function getAnswerTo()
    {
        return $this->answerTo;
    }

    /**
     * Set user
     *
     * @param \Dashboard\CommonBundle\Entity\User $user
     * @return Review
     */
    public function setUser(\Dashboard\CommonBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set targetUser
     *
     * @param \Dashboard\CommonBundle\Entity\User $targetUser
     * @return Review
     */
    public function setTargetUser(\Dashboard\CommonBundle\Entity\User $targetUser = null)
    {
        $this->targetUser = $targetUser;
    
        return $this;
    }

    /**
     * Get targetUser
     *
     * @return \Dashboard\CommonBundle\Entity\User 
     */
    public function getTargetUser()
    {
        return $this->targetUser;
    }

    /**
     * Set salons
     *
     * @param \Dashboard\CommonBundle\Entity\DealerSalon $salons
     * @return Review
     */
    public function setSalons(\Dashboard\CommonBundle\Entity\DealerSalon $salons = null)
    {
        $this->salons = $salons;
    
        return $this;
    }

    /**
     * Get salons
     *
     * @return \Dashboard\CommonBundle\Entity\DealerSalon 
     */
    public function getSalons()
    {
        return $this->salons;
    }

    /**
     * Set status
     *
     * @param \Dashboard\CommonBundle\Entity\ReviewStatus $status
     * @return Review
     */
    public function setStatus(\Dashboard\CommonBundle\Entity\ReviewStatus $status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return \Dashboard\CommonBundle\Entity\ReviewStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
