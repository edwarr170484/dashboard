<?php

namespace Dashboard\MenuBundle\Entity;

class MenuRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllMenus()
    {       
        $em = $this->getEntityManager();
        $menu = $em->createQuery("SELECT m FROM DashboardMenuBundle:Menu m ORDER BY m.sort ASC");
        
        return $menu->getResult();
    }
    
    public function menuCount()
    {
        $em = $this->getEntityManager();
        $menu = $em->createQuery("SELECT m FROM DashboardMenuBundle:Menu m")->getResult();
        
        return count($menu);
    }
}
