<?php

namespace Dashboard\CommonBundle\Listener;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    public function __construct(SecurityContextInterface $security, Session $session)
    {
           $this->security = $security;
           $this->session = $session;
    }
    
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
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
    }
}