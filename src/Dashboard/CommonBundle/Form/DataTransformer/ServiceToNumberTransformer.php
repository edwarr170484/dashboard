<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ServiceToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($service)
    {
        if (null === $service) {
            return '';
        }

        return $service->getId();
    }
    
    public function reverseTransform($serviceId)
    {
        if (!$serviceId) {
            return;
        }

        $service = $this->manager->getRepository('DashboardCommonBundle:Service')->find($serviceId);
        
        if (null === $service) {
            
            throw new TransformationFailedException(sprintf(
                'Service with serviceId "%s" does not exist!',
                $serviceId
            ));
        }

        return $service;
    }
}

