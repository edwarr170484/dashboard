<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\DealerInfo;

use Dashboard\CommonBundle\Form\Type\DealerRegisterType;

class OfficeController extends Controller
{ 
    /**
     * @Route("/service", name="service")
     * @Route("/{_locale}/service", name="serviceLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function pageAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.locale=" . $locale->getId() . " AND p.isUserpage = 0 AND p.route = 'service'" );
        
        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $page = 0;
        }
        
        return $this->render('DashboardCommonBundle:Office:page.html.twig', array("page" => $page,
                                                                                  "locale" => $locale,
                                                                                  "settings" => $settings));
    }
    
    /**
     * @Route("/service/register", name="serviceRegister")
     * @Route("/{_locale}/service/register", name="serviceRegisterLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function registerAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $dealer = new User();
        $registerForm = $this->createForm(new DealerRegisterType($manager, NULL, $locale), $dealer);
        $registerForm->handleRequest($request);
        $success = 0;
        
        if ($registerForm->isSubmitted() && $registerForm->isValid()) 
        {
            if($settings->getIsShowCaptcha())
            {
                if(!$this->get('app.helpers')->checkCaptcha($request->request->get('g-recaptcha-response')))
                {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Ошибка!</strong> Не введено значение Captcha.') . '</div>'
                    );

                    return $this->render('DashboardCommonBundle:Dealer:register.html.twig', array('registerForm' => $registerForm->createView(),
                                                                                        'success' => $success,"settings" => $settings, "locale" => $locale));
                }
            }
            
            $query = $manager->createQuery("SELECT u FROM Dashboard\CommonBundle\Entity\User u WHERE u.username = '" . $registerForm['email']->getData() . "' OR u.email = '" . $registerForm['email']->getData() . "'");

            try{
                $userIs = $query->getSingleResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $userIs = 0;
            }
            
            if($userIs){
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Адрес электронной почты %message% уже существует в системе.',array("%message%" => $registerForm['email']->getData())) . '</div>'
                );
                
                return $this->render('DashboardCommonBundle:Dealer:register.html.twig', array('registerForm' => $registerForm->createView(),
                                                                                              'success' => $success,"settings" => $settings, "locale" => $locale));
            }
            
            $mailPassword = $dealer->getPassword();
            $role = $this->getDoctrine()->getRepository("DashboardCommonBundle:Role")->findOneByRole("ROLE_SERVICE");
            $password = $this->get('security.password_encoder')->encodePassword($dealer, $dealer->getPassword());
            
            $dealer->setUsername($dealer->getEmail()); 
            $dealer->setIsActive(1);
            if($settings->getIsModerate()){
                $dealer->setIsConfirm(0);
            }else{
                $dealer->setIsConfirm(1);
            }
            $dealer->addRole($role);
            $role->addUser($dealer);
            $dealer->setAdvertNumber(0);
            $dealer->setPassword($password);
            $dealer->getDealerinfo()->setUser($dealer);
            
            $manager->persist($dealer);
            $manager->persist($role);
            $manager->flush();
            
            $message = \Swift_Message::newInstance()
                ->setSubject($this->get('translator')->trans('Регистрация на портале') . $settings->getSiteName())
                ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                ->setTo($registerForm['email']->getData())
                ->setBody(
                    $this->renderView(
                        'Emails/userregistration.html.twig',
                        array('user' => $dealer, "settings" => $settings, "password" => $mailPassword)
                    ),
                    'text/html'
                );
            
            $this->get('mailer')->send($message);
            
            $success = 1;
        }
        
        return $this->render('DashboardCommonBundle:Office:register.html.twig', array("locale" => $locale,
                                                                                      "settings" => $settings,
                                                                                      "registerForm" => $registerForm->createView(),
                                                                                      "success" => $success));
    }
    
    /**
     * @Route("/services", name="services")
     * @Route("/{_locale}/services", name="servicesLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function dealersAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $categories = $manager->getRepository("DashboardCommonBundle:JobCategory")->findAll();
        $query = $manager->createQuery("SELECT jc FROM DashboardCommonBundle:JobCategory jc WHERE jc.image is null");
        
        $allCategories = $query->getResult();
        $jobsPerList = ceil(count($allCategories) / 2);
        
        return $this->render('DashboardCommonBundle:Office:services.html.twig', array("locale" => $locale,
                                                                                      "settings" => $settings,
                                                                                      "categories" => $categories,
                                                                                      "jobsPerList" => $jobsPerList,
                                                                                      "allCategories" => $allCategories));
    }
}