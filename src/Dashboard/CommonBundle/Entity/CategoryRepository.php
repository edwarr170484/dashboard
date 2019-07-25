<?php

namespace Dashboard\CommonBundle\Entity;


class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function getChildCategories()
    {   
        $em = $this->getEntityManager();
        $categories = $em->createQuery("SELECT c FROM DashboardCommonBundle:Category c WHERE c.parent IS NOT NULL");
        
        try{
            return $categories->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
    }
}


