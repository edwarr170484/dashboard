<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MarkToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($mark)
    {
        if (null === $mark) {
            return '';
        }

        return $mark->getId();
    }
    
    public function reverseTransform($markId)
    {
        if (!$markId) {
            return;
        }

        $mark = $this->manager->getRepository('DashboardCommonBundle:Mark')->find($markId);

        if (null === $mark) {
            
            throw new TransformationFailedException(sprintf(
                'Mark with markId "%s" does not exist!',
                $markId
            ));
        }

        return $mark;
    }
}

