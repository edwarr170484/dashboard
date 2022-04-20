<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

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

        $service = $this->manager->getRepository('DashboardCommonBundle:Region')->find($serviceId);

        if (null === $service) {
            
            throw new TransformationFailedException(sprintf(
                'Service with service_id "%s" does not exist!',
                $serviceId
            ));
        }

        return $service;
    }
}



