<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

use AppBundle\Entity\Issue;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FilterToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($filter)
    {
        if (null === $filter) {
            return '';
        }

        return $filter->getId();
    }
    
    public function reverseTransform($filterId)
    {
        if (!$filterId) {
            return;
        }

        $filter = $this->manager->getRepository('DashboardCommonBundle:Filter')->find($filterId);

        if (null === $filter) {
            
            throw new TransformationFailedException(sprintf(
                'Filter with filter_id "%s" does not exist!',
                $filterId
            ));
        }

        return $filter;
    }
}



