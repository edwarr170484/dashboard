<?php
namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Route("/{_locale}/login", name="loginLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function loginAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $authenticationUtils = $this->get('security.authentication_utils');
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        if($settings->getIsShowCaptcha())
        {
            if($error)
                $this->get('session')->set('loginerror', '1');
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render(
            'DashboardCommonBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
                'info'          => ''
            )
        );
    }
    
    /**
     * @Route("/login_check", name="logincheck")
     */
    public function loginCheckAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }
    
    /**
     * @Route("/logout", name="logout")
     * 
     */
    public function logoutAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }
}

