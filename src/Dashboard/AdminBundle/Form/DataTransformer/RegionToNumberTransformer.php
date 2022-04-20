<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class RegionToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($region)
    {
        if (null === $region) {
            return '';
        }

        return $region->getId();
    }
    
    public function reverseTransform($regionId)
    {
        if (!$regionId) {
            return;
        }

        $region = $this->manager->getRepository('DashboardCommonBundle:Region')->find($regionId);

        if (null === $region) {
            
            throw new TransformationFailedException(sprintf(
                'Region with region_id "%s" does not exist!',
                $regionId
            ));
        }

        return $region;
    }
}



