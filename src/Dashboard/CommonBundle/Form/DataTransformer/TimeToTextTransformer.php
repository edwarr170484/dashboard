<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

class TimeToTextTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($time)
    {
        if (null === $time) {
            return '';
        }

        return $time->format("H:i");
    }
    
    public function reverseTransform($timeText)
    {
        if (!$timeText) {
            return;
        }

        return new\DateTime($timeText);
    }
}