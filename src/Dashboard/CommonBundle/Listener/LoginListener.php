<?php

namespace Dashboard\CommonBundle\Listener;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginListener
{
    public function __construct(SecurityContextInterface $security, Session $session, EntityManager $entityManager, EventDispatcherInterface $dispatcher, Router $router)
    {
        $this->security = $security;
        $this->session = $session;
        $this->manager = $entityManager;
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }
    
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $this->security->getToken()->getUser();
        
        if(!$user->getIsConfirm()){
            throw new BadCredentialsException('Ваш аккаунт не подтвержден!');
        }
        
        if($this->session->get('loginerror'))
        {
            $request = $event->getRequest();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret'  => '6Lc6wCkTAAAAAH1glqiQycvfoR21pMHgLPqD_zOZ', 'response' => $request->request->get('g-recaptcha-response'))));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $server_output = curl_exec($ch);
            curl_close($ch);

            $captcha = json_decode($server_output, true);

            if(!$captcha['success'])
            {
                throw new BadCredentialsException('Вы не подтвердили капчу.!');
            }
            else
                $this->session->set('loginerror', '0');
        }
        
        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }
    
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $user = $this->security->getToken()->getUser();
        
        if($user){
            $user->setEntires($user->getEntires() + 1);
            $this->manager->persist($user);
            $this->manager->flush();
        }
        
        if($user->getRoles()[0]->getRole() == "ROLE_ADMIN"){
            $event->setResponse(new RedirectResponse($this->router->generate("admin_main")));
        }else{
            if($user->getEntires() > 1){
                $event->setResponse(new RedirectResponse($this->router->generate("account")));
            }else{
                $event->setResponse(new RedirectResponse($this->router->generate("account_settings")));
            }
        }
        
       
    }
}