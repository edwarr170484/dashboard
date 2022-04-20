<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($product)
    {
        if (null === $product) {
            return '';
        }

        return $product->getId();
    }
    
    public function reverseTransform($productId)
    {
        if (!$productId) {
            return;
        }

        $product = $this->manager->getRepository('DashboardCommonBundle:Product')->find($productId);
        
        if (null === $product) {
            
            throw new TransformationFailedException(sprintf(
                'Product with productId "%s" does not exist!',
                $productId
            ));
        }

        return $product;
    }
}

