<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SelltypeToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($selltype)
    {
        if (null === $selltype) {
            return '';
        }

        return $selltype->getId();
    }
    
    public function reverseTransform($selltypeId)
    {
        if (!$selltypeId) {
            return;
        }

        $selltype = $this->manager->getRepository('DashboardCommonBundle:Selltype')->find($selltypeId);

        if (null === $selltype) {
            
            throw new TransformationFailedException(sprintf(
                'Selltype with selltypeId "%s" does not exist!',
                $selltypeId
            ));
        }

        return $selltype;
    }
}

