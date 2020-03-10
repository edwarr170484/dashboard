<?php

namespace Dashboard\MenuBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MenuItemsToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($menuItems)
    {
        if (null === $menuItems) {
            return '';
        }

        return $menuItems->getId();
    }
    
    public function reverseTransform($menuItemsId)
    {
        if (!$menuItemsId) {
            return;
        }

        $menuItems = $this->manager->getRepository('DashboardMenuBundle:MenuItems')->find($menuItemsId);

        if (null === $menuItems) {
            
            throw new TransformationFailedException(sprintf(
                'Menu items with id "%s" does not exist!',
                $menuItemsId
            ));
        }

        return $menuItems;
    }
}

