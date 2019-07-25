<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Component\Security\Core\SecurityContextInterface;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class EventListener
{
    protected $em;
    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        
        if($request->attributes->get('_locale'))
        {
            $locale = $this->em->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->attributes->get('_locale')));
            
            if($locale)
            {
                $request->setLocale($locale->getCode());
            }
            else
            {
                $locale = $this->em->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("isDefault" => true));
                if($locale)
                {
                    $request->setLocale($locale->getCode());
                }
                else 
                {
                    $request->setLocale("lv");
                }
            }   
        }
        else
        {
            $locale = $this->em->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("isDefault" => true));
            if($locale)
            {
                $request->setLocale($locale->getCode());
            }
            else 
            {
                $request->setLocale("lv");
            }
        }
        
    }
}