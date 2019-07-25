<?php

namespace Dashboard\GalleryBundle\Form\DataTransformer;

use AppBundle\Entity\Issue;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class GalleryToNumberTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($gallery)
    {
        if (null === $gallery) {
            return '';
        }

        return $gallery->getId();
    }
    
    public function reverseTransform($galleryId)
    {
        if (!$galleryId) {
            return;
        }

        $gallery = $this->manager->getRepository('DashboardGalleryBundle:Gallery')->find($galleryId);

        if (null === $gallery) {
            
            throw new TransformationFailedException(sprintf(
                'Gallery with menu_id "%s" does not exist!',
                $galleryId
            ));
        }

        return $gallery;
    }
}



