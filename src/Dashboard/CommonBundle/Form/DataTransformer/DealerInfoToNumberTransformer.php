<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DealerInfoToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($info)
    {
        if (null === $info) {
            return '';
        }

        return $info->getId();
    }
    
    public function reverseTransform($infoId)
    {
        if (!$infoId) {
            return;
        }

        $info = $this->manager->getRepository('DashboardCommonBundle:DealerInfo')->find($infoId);

        if (null === $info) {
            
            throw new TransformationFailedException(sprintf(
                'DealerInfo with infoId "%s" does not exist!',
                $infoId
            ));
        }

        return $info;
    }
}