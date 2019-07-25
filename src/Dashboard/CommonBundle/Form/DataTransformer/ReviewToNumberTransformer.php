<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ReviewToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($review)
    {
        if (null === $review) {
            return '';
        }

        return $review->getId();
    }
    
    public function reverseTransform($reviewId)
    {
        if (!$reviewId) {
            return;
        }

        $review = $this->manager->getRepository('DashboardCommonBundle:Review')->find($reviewId);
        
        if (null === $review) {
            
            throw new TransformationFailedException(sprintf(
                'Review with reviewId "%s" does not exist!',
                $reviewId
            ));
        }

        return $review;
    }
}

