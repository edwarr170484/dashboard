<?php

namespace Dashboard\MenuBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MenuToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($menu)
    {
        if (null === $menu) {
            return '';
        }

        return $menu->getId();
    }
    
    public function reverseTransform($menuId)
    {
        if (!$menuId) {
            return;
        }

        $menu = $this->manager->getRepository('DashboardMenuBundle:Menu')->find($menuId);

        if (null === $menu) {
            
            throw new TransformationFailedException(sprintf(
                'Menu with menu_id "%s" does not exist!',
                $pageId
            ));
        }

        return $menu;
    }
}



