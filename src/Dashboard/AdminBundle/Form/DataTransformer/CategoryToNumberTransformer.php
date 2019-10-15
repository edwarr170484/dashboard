<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

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
                'Category with category_id "%s" does not exist!',
                $categoryId
            ));
        }

        return $category;
    }
}



