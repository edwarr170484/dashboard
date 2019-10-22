<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PackToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($pack)
    {
        if (null === $pack) {
            return '';
        }

        return $pack->getId();
    }
    
    public function reverseTransform($packId)
    {
        if (!$packId) {
            return;
        }

        $pack = $this->manager->getRepository('DashboardCommonBundle:Pack')->find($packId);

        if (null === $pack) {
            
            throw new TransformationFailedException(sprintf(
                'Pack with pack_id "%s" does not exist!',
                $packId
            ));
        }

        return $pack;
    }
}



