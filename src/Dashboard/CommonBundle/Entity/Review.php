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
     * @ORM\ManyToOne(targetEntity="Dashboard\CommonBundle\Entity\Product", inversedBy="reviews")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;
    
    /**
     * @ORM\Column(type="string", length=512, nullable=true, options={"default":"0"})
     */
    private $productMark;
    
    /**
     * @ORM\Column(type="text")
     */
    private $reviewText;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;
    
    /**
     * @ORM\Column(type="integer", length=4, nullable=true, options={"default":"0"})
     */
    private $status;
    
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
     * Set product
     *
     * @param \Dashboard\CommonBundle\Entity\Product $product
     * @return Review
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
     * Set status
     *
     * @param integer $status
     * @return Review
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
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
     * Set productMark
     *
     * @param string $productMark
     * @return Review
     */
    public function setProductMark($productMark)
    {
        $this->productMark = $productMark;

        return $this;
    }

    /**
     * Get productMark
     *
     * @return string 
     */
    public function getProductMark()
    {
        return $this->productMark;
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
}
