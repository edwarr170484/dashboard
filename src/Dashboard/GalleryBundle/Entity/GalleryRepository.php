<?php

namespace Dashboard\GalleryBundle\Entity;

class GalleryRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllGallery()
    {       
        $em = $this->getEntityManager();
        $gallery = $em->createQuery("SELECT g FROM DashboardGalleryBundle:Gallery g ORDER BY g.sort ASC");
        
        return $gallery->getResult();
    }
    
    public function galleryCount()
    {
        $em = $this->getEntityManager();
        $gallery = $em->createQuery("SELECT g FROM DashboardGalleryBundle:Gallery g")->getResult();
        
        return count($gallery);
    }
}
