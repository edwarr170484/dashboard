<?php

namespace Dashboard\CommonBundle\Entity;


class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllUsers($pageId)
    {
        /*$stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare("SELECT u.id,u.name,u.username,u.is_active,u.avatar,r.role FROM users u INNER JOIN user_role ur ON (u.id=ur.user_id) INNER JOIN role r ON (ur.role_id=r.id) ");
        
        $stmt->execute();*/
        
        $em = $this->getEntityManager();
        $users = $em->createQuery("SELECT u FROM ShopUserBundle:User u")->setFirstResult($pageId * 10)->setMaxResults(10);
        
        //return $stmt->fetchAll();
        
        return $users->getResult();
    }
    public function usersCount()
    {
        $em = $this->getEntityManager();
        $users = $em->createQuery("SELECT u FROM ShopUserBundle:User u")->getResult();
        
        return count($users);
    }
    
}
