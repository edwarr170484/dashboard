<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class RateToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($rate)
    {
        if (null === $rate) {
            return '';
        }

        return $rate->getId();
    }
    
    public function reverseTransform($rateId)
    {
        if (!$rateId) {
            return;
        }

        $rate = $this->manager->getRepository('DashboardCommonBundle:Rate')->find($rateId);

        if (null === $rate) {
            
            throw new TransformationFailedException(sprintf(
                'Rate with rate_id "%s" does not exist!',
                $rateId
            ));
        }

        return $rate;
    }
}