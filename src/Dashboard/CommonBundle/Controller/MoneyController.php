<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\ProductOrder;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Dashboard\CommonBundle\Entity\UserPurseHistory;

use Dashboard\CommonBundle\Model\LiqPay;

class MoneyController extends Controller
{   
    /**
     * @Route("/account/userpurse/history", name="account_userpurse_history")
     * @Route("/{_locale}/account/userpurse/history", name="account_userpurse_historyLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function purseAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $history = $manager->getRepository("DashboardCommonBundle:UserPurseHistory")->findBy(array("userpurse" => $user->getUserpurse()));
        
        return $this->render('DashboardCommonBundle:Money:history.html.twig', array("history" => $history,
                                                                                    "user" => $user,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale));
    }
    
    /**
     * @Route("/account/userpurse/payment", name="account_userpurse_payment")
     * @Route("/{_locale}/account/userpurse/payment", name="account_userpurse_paymentLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function paymentAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($request->isXmlHttpRequest())
        {
            if($request->request->get('paymentAmount') && $request->request->get('paymentMethod'))
            {
                switch($request->request->get('paymentMethod'))
                {
                    case 'Liqpay':
                        $liqpaySettings = $manager->getRepository("DashboardCommonBundle:Liqpay")->find(1);
                        
                        if($liqpaySettings)
                        {
                            $liqpay = new LiqPay($liqpaySettings->getPublicKey(), $liqpaySettings->getPrivateKey());
                            $html = $liqpay->cnb_form(array(
                                'action'         => 'pay',
                                'amount'         => $request->request->get('paymentAmount'),
                                'sandbox'        => $liqpaySettings->getSandbox(),
                                'currency'       => $liqpaySettings->getCurrency(),
                                'description'    => $this->get('translator')->trans('Vietnes lietotāja konta papildināšana') . ' ' . $settings->getSiteName(),
                                'customer'       => $user->getId(),
                                'order_id'       => rand(1, 99999999),
                                'server_url'     => $this->generateUrl('userpurse_payment_confirm', array(), true),
                                'result_url'     => $this->generateUrl('account_userpurse_payment', array(), true)
                            ));
                        }
                        
                        return $this->render('DashboardCommonBundle:Money:liqpayform.html.twig', array("html" => $html,
                                                                                                       "paymentAmount" => $request->request->get('paymentAmount'),
                                                                                                       "paymentMethod" => $request->request->get('paymentMethod'),
                                                                                                       "paymentCurrency" => $liqpaySettings->getCurrency()));
                        
                    break;
                }
            }   
        }
        return $this->render('DashboardCommonBundle:Money:payment.html.twig', array("user" => $user,"settings" => $settings,"locale" => $locale));
    }
    
    /**
     * @Route("/userpurse/payment/confirm", name="userpurse_payment_confirm")
     */
    public function paymentConfirmAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $liqpaySettings = $manager->getRepository("DashboardCommonBundle:Liqpay")->find(1);
        
        if($request->request->get('data') && $request->request->get('signature'))
        {
            $data = $request->request->get('data');
            $signature = base64_encode( sha1( $liqpaySettings->getPrivateKey() . $data . $liqpaySettings->getPrivateKey(), 1 ));
            
            if($signature == $request->request->get('signature'))
            {
                $paymentParams = json_decode(base64_decode($data), true);
                
                /*$fp = fopen("payment.txt", "a");
                fwrite($fp, $paymentParams['status']);
                fclose($fp);*/
                
                if($paymentParams['status'] == 'success' || $paymentParams['status'] == 'sandbox' || $paymentParams['status'] == 'wait_accept')
                {
                    $user = $manager->getRepository("DashboardCommonBundle:User")->find($paymentParams['customer']);
                    
                    if($user)
                    {
                        $user->getUserpurse()->setBalanse($user->getUserpurse()->getBalanse() + $paymentParams['amount']);
                        $manager->persist($user);
                        
                        $userPurseHistory = new UserPurseHistory();
                        $userPurseHistory->setActionDate(new \DateTime("now"));
                        $userPurseHistory->setAction($this->get('translator')->trans("Naudas līdzekļu ieskaitīšana kontā. Kredīts") . " " . $paymentParams['amount'] . $settings->getCurrency()->getName() . ".");
                        $userPurseHistory->setCurrentBalanse($userPurseHistory->getCurrentBalanse() + $paymentParams['amount']);
                        $userPurseHistory->setUserpurse($user->getUserpurse());
                        
                        $manager->persist($userPurseHistory);
                        $manager->flush();
                        
                        return new Response(1);
                    }
                }
            }
        }
        
        return new Response(0);
    }
    
    /**
     * @Route("/account/productservices", name="account_product_services")
     * @Route("/{_locale}/account/productservices", name="account_product_servicesLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    
    public function productServicesAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p JOIN p.service ps WHERE p.user = " . $user->getId() . " ORDER BY ps.dateAdded DESC");
        
        try{
            $productServices = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $productServices = 0;
        }
        
        return $this->render('DashboardCommonBundle:User:productsservices.html.twig', array("productServices" => $productServices, 
                                                                                            "user" => $user,
                                                                                            "settings" => $settings,
                                                                                            "locale" => $locale));
        
    }
    
    /**
     * @Route("/account/buyslots", name="account_buyslots")
     * @Route("/{_locale}/account/buyslots", name="account_buyslotsLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function productBuyslotsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $currentAdvertNumber = $user->getAdvertNumber();
        $roles = $user->getRoles();
        
        $form = $this->get('form.factory')->createNamedBuilder('friend', 'form', $user)
                ->add('advertNumber', TextType::class, array('required' => true,'data' => '','label' => $this->get('translator')->trans('Slotu skaits: *'), 'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('PIRKTIESIESIES'), 'attr' => array('class' => 'send-tab-form')))->getForm();
        
        $form->handleRequest($request);
        
        $validator = $this->get('validator');
        $errorsValid = $validator->validate($user);
            
        if (count($errorsValid) > 0) 
        {
            
            foreach($errors as $error)
                    $this->addFlash(
                           'notice',
                           '<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                            <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true"> x </span></button>' .
                            $this->get('translator')->trans('<strong>Kļūda!</strong>%message%.</div>', array("%message%" => $error->getMessage()))
                   );
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_buyslots");
            }
            else
            {
                return $this->redirectToRoute("account_buyslotsLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $amount = $form['advertNumber']->getData() * $settings->getAditionalAdvertPrice();
            
            if($amount > $user->getUserpurse()->getBalanse())
            {
                $this->addFlash(
                           'notice',
                           '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                           $this->get('translator')->trans('<strong>Kļūda!</strong> Jūsu pašreizējā kontā nav pietiekami daudz naudas, lai iegādātos pieprasīto vietu skaitu.') . '</div>'
                   );
            }
            else
            {
                $currentAdvertNumber += $form['advertNumber']->getData();
                
                $user->setAdvertNumber($currentAdvertNumber);
                
                $user->getUserpurse()->setBalanse($user->getUserpurse()->getBalanse() - ($form['advertNumber']->getData() * $settings->getAditionalAdvertPrice()));
                
                $userPurseHistory = new UserPurseHistory();
                $userPurseHistory->setActionDate(new \DateTime("now"));
                $userPurseHistory->setAction($this->get('translator')->trans("Iegādāties %message% no reklāmas papildu vietām",array("%message%" => $form['advertNumber']->getData())). $this->get('translator')->trans("Izrakstīts") . " " . ($form['advertNumber']->getData() * $settings->getAditionalAdvertPrice()) . $settings->getCurrency()->getName() .".");
                $userPurseHistory->setCurrentBalanse($userPurseHistory->getCurrentBalanse() - ($form['advertNumber']->getData() * $settings->getAditionalAdvertPrice()));
                $userPurseHistory->setUserpurse($user->getUserpurse());
                        
                $manager->persist($userPurseHistory);
                $manager->persist($user);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                    $this->get('translator')->trans('<strong>Veiksmīgi!</strong> Tagad jūs varat ievietot %mess% papildu reklāmas.', array("%mess%" => $user->getAdvertNumber())) . '</div>'
                   );
            }
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_buyslots");
            }
            else
            {
                return $this->redirectToRoute("account_buyslotsLocale", array("_locale" => $locale->getCode()));
            }
        }
        return $this->render('DashboardCommonBundle:Money:buyslots.html.twig', array("user" => $user, "settings" => $settings, "locale" => $locale, "form" => $form->createView(),"role" => $roles[0]));
    }

}
