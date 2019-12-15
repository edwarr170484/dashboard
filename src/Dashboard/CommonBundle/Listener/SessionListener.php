<?php

namespace Dashboard\CommonBundle\Listener;

use Symfony\Component\Security\Core\SecurityContextInterface;

use AppBundle\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionListener
{
    public function __construct(Session $session)
    {
           $this->session = $session;
    }
    
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        
        if($request->server->get('REQUEST_METHOD') == "POST" && $request->request->get('selectFilterRegionCity'))
        {
            if(isset($request->request->get('regionFilter')["name"]))
            {
                $this->session->set('sessionCity', $request->request->get('regionFilter')["name"]);
            }
            
            if(!isset($request->request->get('regionFilter')["name"]))
            {
                $this->session->remove('sessionCity');
            }
        }

        if(!$this->session->has('sessionToken'))
        {
            $this->session->set('sessionToken', base64_encode(serialize(array("products" => array()))));
        }
    }
}