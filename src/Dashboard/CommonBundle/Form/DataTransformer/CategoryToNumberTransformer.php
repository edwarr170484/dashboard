<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CategoryToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($category)
    {
        if (null === $category) {
            return '';
        }

        return $category->getId();
    }
    
    public function reverseTransform($categoryId)
    {
        if (!$categoryId) {
            return;
        }

        $category = $this->manager->getRepository('DashboardCommonBundle:Category')->find($categoryId);

        if (null === $category) {
            
            throw new TransformationFailedException(sprintf(
                'Conversation with categoryId "%s" does not exist!',
                $categoryId
            ));
        }

        return $category;
    }
}

