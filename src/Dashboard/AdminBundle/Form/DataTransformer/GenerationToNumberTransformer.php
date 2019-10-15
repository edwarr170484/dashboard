<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class GenerationToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($generation)
    {
        if (null === $generation) {
            return '';
        }

        return $generation->getId();
    }
    
    public function reverseTransform($generationId)
    {
        if (!$generationId) {
            return;
        }

        $generation = $this->manager->getRepository('DashboardCommonBundle:Generation')->find($generationId);

        if (null === $generation) {
            
            throw new TransformationFailedException(sprintf(
                'Generation with generation_id "%s" does not exist!',
                $generationId
            ));
        }

        return $generation;
    }
}



