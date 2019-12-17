<?php

namespace Dashboard\AdminBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PageToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($page)
    {
        if (null === $page) {
            return '';
        }

        return $page->getId();
    }
    
    public function reverseTransform($pageId)
    {
        if (!$pageId) {
            return;
        }

        $page = $this->manager->getRepository('DashboardCommonBundle:Page')->find($pageId);

        if (null === $page) {
            
            throw new TransformationFailedException(sprintf(
                'Page with pageId "%s" does not exist!',
                $pageId
            ));
        }

        return $page;
    }
}



