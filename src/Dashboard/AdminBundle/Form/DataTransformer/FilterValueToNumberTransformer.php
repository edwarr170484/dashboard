<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FilterValueToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($filterValue)
    {
        if (null === $filterValue) {
            return '';
        }

        return $filterValue->getId();
    }
    
    public function reverseTransform($filterValueId)
    {
        if (!$filterValueId) {
            return;
        }

        $filterValue = $this->manager->getRepository('DashboardCommonBundle:FilterValue')->find($filterValueId);

        if (null === $filterValue) {
            
            throw new TransformationFailedException(sprintf(
                'FilterValue with filterValue_id "%s" does not exist!',
                $filterValueId
            ));
        }

        return $filterValue;
    }
}



