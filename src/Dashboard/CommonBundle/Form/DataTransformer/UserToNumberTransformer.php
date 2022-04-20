<?php

namespace Dashboard\CommonBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($user)
    {
        if (null === $user) {
            return '';
        }

        return $user->getId();
    }
    
    public function reverseTransform($userId)
    {
        if (!$userId) {
            return;
        }

        $user = $this->manager->getRepository('DashboardCommonBundle:User')->find($userId);

        if (null === $user) {
            
            throw new TransformationFailedException(sprintf(
                'User with userId "%s" does not exist!',
                $userId
            ));
        }

        return $user;
    }
}

