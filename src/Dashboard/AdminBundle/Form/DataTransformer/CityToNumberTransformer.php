<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CityToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($city)
    {
        if (null === $city) {
            return '';
        }

        return $city->getId();
    }
    
    public function reverseTransform($cityId)
    {
        if (!$cityId) {
            return;
        }

        $city = $this->manager->getRepository('DashboardCommonBundle:Region')->find($cityId);

        if (null === $city) {
            
            throw new TransformationFailedException(sprintf(
                'City with city_id "%s" does not exist!',
                $cityId
            ));
        }

        return $city;
    }
}



