<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DealerSalonToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($salon)
    {
        if (null === $salon) {
            return '';
        }

        return $salon->getId();
    }
    
    public function reverseTransform($salonId)
    {
        if (!$salonId) {
            return;
        }

        $salon = $this->manager->getRepository('DashboardCommonBundle:DealerSalon')->find($salonId);

        if (null === $salon) {
            
            throw new TransformationFailedException(sprintf(
                'DealerSalon with salonId "%s" does not exist!',
                $salonId
            ));
        }

        return $salon;
    }
}