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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\UserInfo;
use Dashboard\CommonBundle\Entity\UserPurse;
use Dashboard\CommonBundle\Entity\Product;
use Dashboard\CommonBundle\Entity\FavoriteProducts;
use Dashboard\CommonBundle\Entity\ProductFotos;
use Dashboard\CommonBundle\Entity\ProductOptions;
use Dashboard\CommonBundle\Entity\Message;
use Dashboard\CommonBundle\Entity\Conversation;
use Dashboard\CommonBundle\Entity\Register;
use Dashboard\CommonBundle\Entity\Review;
use Dashboard\CommonBundle\Entity\Invite;
use Dashboard\CommonBundle\Entity\Friend;
use Dashboard\CommonBundle\Entity\UserPurseHistory;
use Dashboard\CommonBundle\Entity\UserActivity;
use Dashboard\CommonBundle\Entity\Blacklist;

use Dashboard\CommonBundle\Form\Type\UserType;
use Dashboard\CommonBundle\Form\Type\UserRegisterType;
use Dashboard\CommonBundle\Form\Type\UserPasswordType;
use Dashboard\CommonBundle\Form\Type\UserAlertsType;
use Dashboard\CommonBundle\Form\Type\UserAddAdvertType;
use Dashboard\CommonBundle\Form\Type\EditProductType;
use Dashboard\CommonBundle\Form\Type\MessageType;
use Dashboard\CommonBundle\Form\Type\ProfileMessageType;
use Dashboard\CommonBundle\Form\Type\ReviewType;


use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

class UserController extends Controller
{    
    /**
     * @Route("/register/{link}", name="register", defaults = {"link" : "0"})
     * @Route("/{_locale}/register/{link}", name="registerLocale", defaults={"_locale" : "es","link" : "0"}, requirements={"_locale" : "es|ru"})
     */
    public function registerAction($link, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $user = new User();
        
        $registerForm = $this->createForm(new UserRegisterType($link), $user);
        
        $registerForm->handleRequest($request);
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
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
                                $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs neesat apstiprinājis captcha.') . '</div>'
                            );

                    return $this->render('DashboardCommonBundle:User:register.html.twig', array('registerForm' => $registerForm->createView(),
                                                                                        'success' => $success,"settings" => $settings, "locale" => $locale));
                }
            }
            
            if(!$registerForm['email']->getData())
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs neesat aizpildījis e-pasta adresi.') . '</div>'
                );
                
                return $this->render('DashboardCommonBundle:User:register.html.twig', array('registerForm' => $registerForm->createView(),
                                                                                    'success' => $success,"settings" => $settings, "locale" => $locale));
            }
            
            if(!$registerForm['termsAccept']->getData())
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Jums ir jāpiekrīt lietotāja līguma noteikumiem.') . '</div>'
                );
                
                return $this->render('DashboardCommonBundle:User:register.html.twig', array('registerForm' => $registerForm->createView(),
                                                                                    'success' => $success,"settings" => $settings, "locale" => $locale));
            }
            
            //check if email exists
            $query = $manager->createQuery("SELECT u FROM Dashboard\CommonBundle\Entity\User u WHERE u.username = '" . $registerForm['email']->getData() . "' OR u.email = '" . $registerForm['email']->getData() . "'");

            try{
                $userIs = $query->getSingleResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $userIs = 0;
            }
            
            if($userIs)
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Kļūda!</strong> E-pasts %message% jau ir reģistrēts sistēmā. Ja aizmirsāt savu paroli, varat to <a href="/restore">atjaunot</a>.',array("%message%" => $registerForm['email']->getData())) . '</div>'
                );
                
                if($locale->getIsDefault())
                {
                     return $this->redirectToRoute("register");
                }
                else
                {
                     return $this->redirectToRoute("registerLocale", array("_locale" => $locale->getCode()));
                }
            }
            
            $userinfo = new UserInfo();
            $userinfo->setUser($user);
            $user->setUsername($user->getEmail()); 
            $user->setIsActive(1);
            
            $settings = $this->getDoctrine()->getRepository("DashboardCommonBundle:Settings")->find(1);

            $role = $this->getDoctrine()->getRepository("DashboardCommonBundle:Role")->findOneById($settings->getUserDefaultGroup());
            $user->addRole($role);
            
            $user->setAdvertNumber(0);
             
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            
            $userActivity = new UserActivity();
            $userActivity->setUser($user);
            $userActivity->setEnterCount(0);
            $userActivity->setLastActivity(new \DateTime("now"));
            
            $userpurse = new UserPurse();
            $userpurse->setUser($user);
            $userpurse->setBalanse(0);
            
            $em->persist($user);
            $em->persist($userinfo);
            $em->persist($userpurse);
            $em->persist($userActivity);
            $em->flush();
            
            $register = new Register();
            $key = md5(md5(md5($password . rand(1, 99999999)) . rand(1, 99999)) . $user->getEmail());
            $register->setConfirmKey($key);
            
            if($link)
            {
                $linkIs = $manager->getRepository("DashboardCommonBundle:Invite")->findOneByInviteCode($link);
                
                if($linkIs)
                {
                    $register->setInviteCode($link);
                }
            }
            
            $register->setDate(new \DateTime("now"));
            
            $query = $em->createQuery('SELECT u FROM Dashboard\CommonBundle\Entity\User u ORDER BY u.id ASC');
            $users = $query->getResult();
            $register->setUserId($users[count($users) - 1]->getId());
            
            $em->persist($register);
            $em->flush();
            
            //send confirmation link to email
            $message = \Swift_Message::newInstance()
            ->setSubject('Регистрация на портале gribupardot.sunweb.by')
            ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
            ->setTo($registerForm['email']->getData())
            ->setBody(
                $this->renderView(
                    'Emails/userregistration.html.twig',
                    array('key' => $key)
                ),
                'text/html'
            );
            
            $this->get('mailer')->send($message);
            
            $success = 1;
            
        }
        
        
        
        return $this->render('DashboardCommonBundle:User:register.html.twig', array('registerForm' => $registerForm->createView(),
                                                                                    'success' => $success,"settings" => $settings, "locale" => $locale,"email" => $user->getEmail()));
    }
    
    /**
     * @Route("/register/confirm/{key}", name="register_confirm")
     * @Route("/{_locale}/register/confirm/{key}", name="register_confirmLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function registerConfirmAction($key, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($key)
        {
            $confirmation = $manager->getRepository("DashboardCommonBundle:Register")->findOneByConfirmKey($key);
            
            if($confirmation)
            {
                $date = new \DateTime("now");
                $diff=$date->diff($confirmation->getDate()); 
                $user = $manager->getRepository("DashboardCommonBundle:User")->find($confirmation->getUserId());

                if((($diff->d * 24) + $diff->h) < 48)
                {
                    $user->setIsConfirm(1);
                    $userpurse = $user->getUserpurse();
                    $userpurse->setBalanse(10);
                    
                    $userPurseHistory = new UserPurseHistory();
                    $userPurseHistory->setActionDate(new \DateTime("now"));
                    $userPurseHistory->setAction($this->get('translator')->trans("Naudas līdzekļu uzskaite reģistrācijai. Kredīts 10"). " " . $settings->getCurrency()->getName() . ".");
                    $userPurseHistory->setCurrentBalanse(10);
                    $userPurseHistory->setUserpurse($userpurse);
                    
                    $manager->persist($user);
                    $manager->persist($userpurse);
                    $manager->persist($userPurseHistory);
                    
                    $invite = $confirmation->getInviteCode();
                    
                    if($invite)
                    {
                        $friend = new Friend();
                        $inviteCode = base64_decode($invite);
                        
                        $inviteData = unserialize($inviteCode);
                        
                        $userReferrer = $manager->getRepository("DashboardCommonBundle:User")->find($inviteData['user']);
                        
                        if($userReferrer)
                        {
                            $userReferrer->getUserpurse()->setBalanse($userReferrer->getUserpurse()->getBalanse() + 10);
                            
                            $userPurseHistory = new UserPurseHistory();
                            $userPurseHistory->setActionDate(new \DateTime("now"));
                            $userPurseHistory->setAction($this->get('translator')->trans("Kredītiestādes, lai piesaistītu jaunu lietotāju. Kredīts 10"). " " . $settings->getCurrency()->getName() . ".");
                            $userPurseHistory->setCurrentBalanse($userReferrer->getUserpurse()->getBalanse() + 10);
                            $userPurseHistory->setUserpurse($userReferrer->getUserpurse());
                            
                            $friend->setReferrer($userReferrer);
                            $friend->setUser($user);
                            $manager->persist($friend);
                            $manager->persist($userReferrer);
                            $manager->persist($userPurseHistory);
                        }
                    }
                    
                    $info = $this->get('translator')->trans('Jūs esat veiksmīgi apstiprinājis reģistrāciju. Tagad jūs varat pieteikties, izmantojot zemāk esošo veidlapu.');
                    $manager->remove($confirmation);
                    $manager->flush();
                }
                else
                {
                    $manager->remove($user->getUserinfo());
                    $manager->remove($user->getActivity());
                    if($user->getUserpurse()->getHistory())
                    {
                        foreach($user->getUserpurse()->getHistory() as $item)
                        {
                            $item->setUserpurse(null);
                            $manager->remove($item);
                        }
                    }
                    $manager->remove($user->getUserpurse());
                    $manager->remove($user);
                    $manager->remove($confirmation);
                    $manager->flush();
                    $info = $this->get('translator')->trans('Apstiprinājuma atslēga ir beidzies. Jums ir jāaizpilda reģistrācijas procedūra vēlreiz.');
                }
            }
            else 
            {
                if($locale->getIsDefault())
                {
                     return $this->redirectToRoute("login");
                }
                else
                {
                     return $this->redirectToRoute("loginLocale", array("_locale" => $locale->getCode()));
                }
            }
        }
        else
        {
            $info = $this->get('translator')->trans('Apstiprinājuma atslēga nav derīga. Varbūt tas beidzās vai neeksistē. Mēģiniet reģistrēties vēlreiz.');
        }
        
        return $this->render('DashboardCommonBundle:Security:login.html.twig', array('last_username' => '',
                                                                                     'error'         => '', 
                                                                                     'info'          => $info));
    }
    
    /**
     * @Route("/restore", name="restore")
     * @Route("/{_locale}/restore", name="restoreLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function restoreAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $success = 0;
        $error = 0;
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $user = new User();
        $restoreForm = $this->createFormBuilder($user)
                ->add('email', TextType::class, array('required' => true, 'label' => $this->get('translator')->trans('e-pasts'), 'attr' => array('class' => 'email','placeholder' => 'email')))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Atjaunot')))->getForm();
        
        $restoreForm->handleRequest($request);
        
        if ($restoreForm->isSubmitted() && $restoreForm->isValid())
        {
            if($settings->getIsShowCaptcha())
            {
                if(!$this->get('app.helpers')->checkCaptcha($request->request->get('g-recaptcha-response')))
                {
                    $error = $this->get('translator')->trans('Неверно введена капча');

                    return $this->render('DashboardCommonBundle:User:restore.html.twig', array("success" => $success,
                                                                                                "error" => $error,
                                                                                                "resotreForm" => $restoreForm->createView(),
                                                                                                "settings" => $settings,
                                                                                                "locale" => $locale,
                                                                                                "email" => $restoreForm['email']->getData()));
                }
            }
            
            $user = $manager->getRepository("DashboardCommonBundle:User")->findOneByEmail($restoreForm['email']->getData());
            
            if($user)
            {
                $newPassword = $this->generateNewPassword(8);
                $password = $this->get('security.password_encoder')->encodePassword($user, $newPassword);
                $user->setPassword($password);
                $manager->persist($user);
                $manager->flush();
                
                //send an email with password
                 $message = \Swift_Message::newInstance()
                ->setSubject('Восстановление забытого пароля на портале gribupardot.sunweb.by')
                ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'Emails/userrestorepassword.html.twig',
                        array('password' => $newPassword)
                    ),
                    'text/html'
                );

                $this->get('mailer')->send($message);
                
                $success = 1;
            }
            else 
            {
                $error = $this->get('translator')->trans('<strong>Ошибка!</strong> Такого email не существует в системе.');
            }
        }
        return $this->render('DashboardCommonBundle:User:restore.html.twig', array("success" => $success,
                                                                                   "error" => $error,
                                                                                   "resotreForm" => $restoreForm->createView(),
                                                                                   "settings" => $settings,
                                                                                   "locale" => $locale,
                                                                                   "email" => $restoreForm['email']->getData()));
    }
    
    private function generateNewPassword($length)
    {
        $symbols = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM123456789";
        $password = '';
        for($i = 0;$i < $length; $i++)
        {
            $password .= $symbols[rand(0, strlen($symbols) - 1)];
        }
        
        return $password;
    } 
    
    /**
     * @Route("/account/deleteorder/{orderId}/{route}", name="account_deleteorder")
     * @Route("/{_locale}/account/deleteorder/{orderId}/{route}", name="account_deleteorderLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function deleteOrderAction($orderId, $route,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $order = $manager->getRepository("DashboardCommonBundle:ProductOrder")->find($orderId);
        
        if($order)
        {
            $manager->remove($order);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                $this->get('translator')->trans('<strong>Gatavs!</strong> Pasūtījums ir veiksmīgi izdzēsts.') . '</div>'
            );
        }
        
        return $this->redirectToRoute($route);
    }
    
    /**
     * @Route("/{_locale}/account/changeorderstatus/{orderId}/{orderStatus}/{comment}", name="account_changeorderstatus", defaults={"comment" : 0, "_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function changeOrderStatusAction($orderId, $orderStatus, $comment, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $order = $manager->getRepository("DashboardCommonBundle:ProductOrder")->find($orderId);
        $orderStatusSelect = $manager->getRepository("DashboardCommonBundle:OrderStatus")->find($orderStatus);
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($order)
        {
                if($orderStatusSelect)
                {
                    if($order->getStatus() != 7)
                    {
                        $order->setStatus($orderStatus);
                        $order->setStatusComment($comment);
                        $order->setIsNew(0);
                        $manager->persist($order);
                        $manager->flush();
                        
                        //send information about new order to sellers email
                        if($order->getUserSended()->getAlerts())
                        {
                            $message = \Swift_Message::newInstance()
                            ->setSubject('Изменение статуса Вашего заказа №' . $order->getId() . ' на портале gribupardot.sunweb.by')
                            ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                            ->setTo($order->getUserSended()->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'Emails/changeorderstatus.html.twig',
                                    array("order" => $order, 
                                          "orderStatus" => $orderStatusSelect->getName(),
                                          "comment" => $comment)
                                ),
                                'text/html'
                            );

                            $this->get('mailer')->send($message);
                        }
                        
                        if($orderStatusSelect->getId() == 7)
                            return new Response($this->get('translator')->trans("Pabeigts"));
                        elseif ($orderStatusSelect->getId() == 2)
                            return new Response($this->get('translator')->trans("Pasūtījuma statuss ir mainīts. Iemesls").": " . $comment);
                        else
                            return new Response($this->get('translator')->trans("Pasūtījuma statuss ir mainīts"));
                    }
                    else
                        return new Response($this->get('translator')->trans("Pabeigts"));
                }
                else
                {
                    return new Response($this->get('translator')->trans("Nederīgs pasūtījuma statuss"));
                }
        }
        else
            return new Response($this->get('translator')->trans("Nederīgs pasūtījuma ID"));
    }

    /**
     * @Route("/user/profile/{userId}", name="profile", defaults={"userId" : 0} )
     * @Route("/{_locale}/user/profile/{userId}", name="profileLocale", defaults={"_locale" : "lv","userId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function profileAction($userId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("DashboardCommonBundle:User")->find($userId);  
        $isBlacklist = 0;
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($user && $user->getIsActive())
        {
            if($this->getUser())
            {
                $sessionUser = $this->get('security.context')->getToken()->getUser();
            }
            else
            {
                $sessionUser = 0;
            }
            
            if($sessionUser)
            {
                $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $sessionUser->getId(), "userTo" => $user->getId()));

                if($blacklistItem)
                    $isBlacklist = 1;
            }
            
            $message = new Message();
            $messageForm = $this->createForm(new ProfileMessageType($manager), $message);
            
            $messageForm->handleRequest($request);

            if ($messageForm->isSubmitted() && $messageForm->isValid())
            {
                if($sessionUser->getId() != $messageForm['userTo']->getData()->getId())
                {
                    $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $user->getId(), "userTo" => $sessionUser->getId()));
                    
                    if($blacklistItem)
                    {
                        $this->addFlash(
                                'notice',
                                '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                                $this->get('translator')->trans('<strong>Kļūda!</strong> Lietotājs jums ir iekļauts melnajā sarakstā. Jūs nevarat nosūtīt ziņojumu.') . '</div>'
                            );

                        
                        if($locale->getIsDefault())
                        {
                            return $this->redirectToRoute ("profile", array("userId" => $user->getId()));
                        }
                        else
                        {
                            return $this->redirectToRoute ("profileLocale", array("_locale" => $locale->getCode(),"userId" => $user->getId()));
                        }
                    }
                    
                     //check if conversation exists
                    $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Conversation c WHERE (c.userOne = " . $messageForm['userTo']->getData()->getId()  . " AND c.userTwo = " . $sessionUser->getId() . " ) "
                            . "OR (c.userOne = " . $sessionUser->getId() . " AND c.userTwo = " . $messageForm['userTo']->getData()->getId()  . ")");
                    
                    try{
                        $conversation = $query->getSingleResult();
                    }
                    catch(\Doctrine\ORM\NoResultException $e) {
                        $conversation = new Conversation();
                        $conversation->setUserOne($messageForm['userTo']->getData());
                        $conversation->setUserTwo($sessionUser);
                        $conversation->setUserDeleted(null);
                        $manager->persist($conversation);
                        $manager->flush();
                    }
                    
                    $message->setUserOwner($messageForm['userFrom']->getData());
                    $message->setIsNew(1);
                    $message->setIsDeleted(0);
                    $message->setSentDate(new \DateTime("now"));
                    $message->setReadedDate(new \DateTime("now"));
                    $message->setProduct(null);
                    $message->setConversation($conversation);
                    $manager->persist($message);
                    $manager->flush();
                    
                    $messageTwo = new Message();
                    $messageTwo = clone $message;
                    $messageTwo->setUserOwner($messageForm['userTo']->getData());
                    
                    $manager->persist($messageTwo);
                    $manager->flush();
                    
                    if($messageForm['userTo']->getData()->getAlerts())
                    {
                        $message = \Swift_Message::newInstance()
                        ->setSubject('Вам пришло новое сообщение на сайте gribupardot.sunweb.by')
                        ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                        ->setTo($messageForm['userTo']->getData()->getEmail())
                        ->setBody('Вы получили новое сообщение на сайте gribupardot.sunweb.by. '
                                . 'Вы можете прочитать его в <a href="' . $this->generateUrl('account_messages', array(), true) . '">личном кабинете</a>.','text/html');

                        $this->get('mailer')->send($message);
                    }
                    
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                        $this->get('translator')->trans('<strong>Veiksmīga!</strong> Jūsu ziņa ir nosūtīta.') . '</div>'
                    );
                    
                }
                else
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat rakstīt ziņojumus sev.') . '</div>'
                    );
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute ("profile", array("userId" => $user->getId()));
                }
                else
                {
                    return $this->redirectToRoute ("profileLocale", array("_locale" => $locale->getCode(),"userId" => $user->getId()));
                }
            }
            
            $query = $manager->createQuery('SELECT r FROM DashboardCommonBundle:Review r WHERE r.targetUser = ' . $user->getId() . ' ORDER BY r.dateAdded DESC')->setMaxResults(5);
            
            try{
                $reviews = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $reviews = 0;
            }
            
            return $this->render('DashboardCommonBundle:User:profile.html.twig', array("user" => $user,
                                                                                       "sessionUser" => $sessionUser,
                                                                                       "reviews" => $reviews,
                                                                                       "messageForm" => $messageForm->createView(),
                                                                                       "isBlacklist" => $isBlacklist,
                                                                                       "settings" => $settings,
                                                                                       "locale" => $locale));
        }
        else
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("notfound");
            }
            else
            {
                return $this->redirectToRoute("notfoundLocale", array("_locale" => $locale->getCode()));
            }
    }
    
    /**
     * @Route("/{_locale}/addfavorite/{productId}", name="addfavorite", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function addFavoriteProduct($productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
      
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
             return new Response(json_encode(array("error" => "error", "message" => $this->get('translator')->trans("Lai pievienotu reklāmu saviem favorītiem, jums jāievada jūsu personiskais kabinets"))));
        }
        
        if($productId)
        {
            $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\FavoriteProducts p WHERE p.productId = ' . $productId . ' AND p.userId = ' . $user->getId());

            try{
                $favoriteProductIs = $query->getSingleResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $favoriteProductIs = 0;
            }

            if($favoriteProductIs)
            {
                return new Response(json_encode(array("error" => "error", "message" => $this->get('translator')->trans("Šī reklāma jau ir pievienota jūsu favorites"))));
            }
            else
            {
               $favoriteProduct = new FavoriteProducts(); 
               $favoriteProduct->setUserId($user->getId());
               $favoriteProduct->setProductId($productId);
               
               $manager->persist($favoriteProduct);
               $manager->flush();
               
               return new Response(json_encode(array("error" => "ok", "message" => $this->get('translator')->trans("Reklāma ir pievienota favorītiem"))));
            }
        }
        
    }
    
    /**
     * @Route("/account/friends/{friendId}", name="account_friends", defaults={"friendId" : 0})
     * @Route("/{_locale}/account/friends/{friendId}", name="account_friendsLocale", defaults={"_locale" : "lv","friendId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function friendsAction($friendId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.locale=" . $locale->getId() . " AND p.isUserpage = 0 AND p.route = 'account_friends'" );

        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $page = 0;
        }
        
        if($friendId)
        {
            $friend = $manager->getRepository("DashboardCommonBundle:Friend")->findOneBy(array('user' => $friendId, "referrer" => $user->getId()));
            
            if($friend)
            {
                $manager->remove($friend);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Veiksmīga!</strong> Lietotājs ir noņemts no saviem draugiem.') . '</div>'
                );
            }
            else
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat izdzēst draugu no saviem draugiem.') . '</div>'
                );
                
            if($locale->getIsDefault())
            {
                $this->redirectToRoute("account_friends");
            }
            else
            {
                return $this->redirectToRoute("account_friendsLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        if(!$user->getInvite())
        {
            $link = base64_encode(serialize(array("user" => $user->getId(), "randomize" => rand(1, 999999999))));
            $invite = new Invite();
            $invite->setUser($user);
            $invite->setDateAdded(new \DateTime("now"));    
            $invite->setInviteCode($link);   
            $manager->persist($invite);
            $manager->flush();
        }
        else
        {
            $link = $user->getInvite()->getInviteCode();
        }
            
        $friendForm = $this->get('form.factory')->createNamedBuilder('friend', 'form')
                ->add('name', TextType::class, array('required' => true,'label' => $this->get('translator')->trans('Drauga vārds: *'), 'attr' => array('class' => 'form-control')))
                ->add('email', EmailType::class, array('required' => true,'label' => $this->get('translator')->trans('Drauga e-pasts: *'), 'attr' => array('class' => 'form-control')))
                ->add('link', HiddenType::class, array('required' => true,'data' => $link))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('SŪTĪT'), 'attr' => array('class' => 'send-tab-form')))->getForm();
        
        $friendForm->handleRequest($request);

        if ($friendForm->isSubmitted() && $friendForm->isValid() && $friendForm['link']->getData())
        {
            //check if email exists
            $query = $manager->createQuery("SELECT u FROM Dashboard\CommonBundle\Entity\User u WHERE u.username = '" . $friendForm['email']->getData() . "' OR u.email = '" . $friendForm['email']->getData() . "'");

            try{
                $userIs = $query->getSingleResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $userIs = 0;
            }
            
            $message = \Swift_Message::newInstance()
                    ->setSubject('Вы получили приглашение с сайта gribupardot.sunweb.by')
                    ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                    ->setTo($friendForm['email']->getData())
                    ->setBody(
                        $this->renderView(
                            'Emails/referrer.html.twig',
                            array("link" => $friendForm['link']->getData())
                        ),
                        'text/html'
                    );

            $this->get('mailer')->send($message);   
            
            $this->addFlash(
                'notice',
                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                $this->get('translator')->trans('<strong>Veiksmīga!</strong> Uzaicinājums tika veiksmīgi nosūtīts uz norādīto pastkasti.') . '</div>'
            );
            
            if($locale->getIsDefault())
            {
                $this->redirectToRoute("account_friends");
            }
            else
            {
                return $this->redirectToRoute("account_friendsLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        return $this->render('DashboardCommonBundle:User:friends.html.twig', array("user" => $user, "page" => $page,
                                                                                   "friendForm" => $friendForm->createView(),
                                                                                   "link" => $link,
                                                                                   "settings" => $settings,
                                                                                   "locale" => $locale));
    }
    
    private function deleteAdvert($productId, $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId);
        
        if($product)
        {
            if($product->getUser()->getId() == $user->getId())
            {
                //удаляем главное фото
                if($product->getMainfoto())
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $product->getMainfoto() ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $product->getMainfoto() );
                    }
                }
                
                //удаляем дополнительные фото
                if($product->getFotos())
                {
                    foreach($product->getFotos() as $foto)
                    {
                        if($foto->getFoto())
                        {
                            if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto() ))
                            {
                                $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto() );
                            }
                        }
                        
                        $foto->setProduct(null);
                        $manager->remove($foto);
                    }
                }
                
                //удаляем отзывы, привязанные к этому товару
                if($product->getReviews())
                {
                    foreach($product->getReviews() as $review)
                    {   
                        $review->setProduct(null);
                        $manager->persist($review);
                        $manager->flush();
                    }
                }
                
                //удаляем сообщения, привязанные к этому товару
                if($product->getMessages())
                {
                    foreach($product->getMessages() as $message)
                    {
                        $message->setProduct(null);
                        $manager->persist($message);
                        $manager->flush();
                    }
                }
                
                //удаляем жалобы на этот товар
                if($product->getComplaint())
                {
                    foreach($product->getComplaint() as $complaint)
                    {
                        $complaint->setProduct(null);
                        $complaint->setUser(null);
                        $manager->remove($complaint);
                    }
                }
                
                //удаляем услуги, привязанные к товару
                if($product->getService())
                {
                    $product->getService()->setProduct(null);
                    $manager->remove($product->getService());
                    $product->setService(null);
                }
                
                //удаляем заказы, привязанные к этому товару
                if($product->getOrders())
                {
                    foreach($product->getOrders() as $order)
                    {
                        $order->setProduct(null);
                        $manager->persist($order);
                        $manager->flush();
                    }
                }
                
                //удаляем из избранных
                $favProducts = $manager->getRepository("DashboardCommonBundle:FavoriteProducts")->findBy(array("productId" => $product->getId()));
                if($favProducts)
                {
                    foreach($favProducts as $favProduct)
                    {
                        $manager->remove($favProduct);
                    }
                }
                
                $manager->remove($product);
                $manager->flush();
                
                $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Veiksmīga!</strong> Šī reklāma ir noņemta.') . '</div>'
                    );
            }
            else
               $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat izdzēst savu reklāmu.') . '</div>'
                    ); 
        }
        else
            $this->addFlash(
                'notice',
                '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                $this->get('translator')->trans('<strong>Kļūda!</strong> Šo reklāmu nepastāv.') . '</div>'
            );
        
    }
    
    /**
     * @Route("/user/products/{userId}", name="user_products", defaults={"userId" : 0} )
     * @Route("/{_locale}/user/products/{userId}", name="user_productsLocale", defaults={"_locale" : "lv","userId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function userProductsAction($userId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("DashboardCommonBundle:User")->find($userId);
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($user)
        {
            if($this->getUser())
            {
                $sessionUser = $this->get('security.context')->getToken()->getUser();
            }
            else
            {
               $sessionUser = 0;
            }
            
            $message = new Message();
            $messageForm = $this->createForm(new ProfileMessageType($manager), $message);
            
            $messageForm->handleRequest($request);

            if ($messageForm->isSubmitted() && $messageForm->isValid())
            {
                if($sessionUser->getId() != $messageForm['userTo']->getData()->getId())
                {
                    $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $messageForm['userTo']->getData()->getId(), "userTo" => $sessionUser->getId()));
                    
                    if($blacklistItem)
                    {
                        $this->addFlash(
                                'notice',
                                '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                                $this->get('translator')->trans('<strong>Kļūda!</strong> Lietotājs jums ir iekļauts melnajā sarakstā. Jūs nevarat nosūtīt ziņojumu.') . '</div>'
                            );

                        if($locale->getIsDefault())
                        {
                            return $this->redirectToRoute ("user_products", array("userId" => $user->getId()));
                        }
                        else
                        {
                            return $this->redirectToRoute ("user_productsLocale", array("userId" => $user->getId(),"_locale" => $locale->getCode()));
                        }
                    }
                    
                    $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Conversation c WHERE (c.userOne = " . $messageForm['userTo']->getData()->getId()  . " AND c.userTwo = " . $messageForm['userFrom']->getData()->getId() . " ) "
                            . "OR (c.userOne = " . $messageForm['userFrom']->getData()->getId() . " AND c.userTwo = " . $messageForm['userTo']->getData()->getId()  . ")");
                    
                    try{
                        $conversation = $query->getSingleResult();
                    }
                    catch(\Doctrine\ORM\NoResultException $e) {
                        $conversation = new Conversation();
                        $conversation->setUserOne($messageForm['userTo']->getData());
                        $conversation->setUserTwo($messageForm['userFrom']->getData());
                        $conversation->setUserDeleted(null);
                        $manager->persist($conversation);
                        $manager->flush();
                    }
                    
                    $message->setUserOwner($messageForm['userFrom']->getData());
                    $message->setIsNew(1);
                    $message->setIsDeleted(0);
                    $message->setSentDate(new \DateTime("now"));
                    $message->setReadedDate(new \DateTime("now"));
                    $message->setConversation($conversation);
                    $message->setProduct(null);
                    
                    $manager->persist($message);
                    $manager->flush();
                    
                    $messageTwo = new Message();
                    $messageTwo = clone $message;
                    $messageTwo->setUserOwner($messageForm['userTo']->getData());
                    
                    $manager->persist($messageTwo);
                    $manager->flush();
                    
                    if($messageForm['userTo']->getData()->getAlerts())
                    {
                        $message = \Swift_Message::newInstance()
                        ->setSubject('Вам пришло новое сообщение на сайте gribupardot.sunweb.by')
                        ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                        ->setTo($messageForm['userTo']->getData()->getEmail())
                        ->setBody('Вы получили новое сообщение на сайте gribupardot.sunweb.by. '
                                . 'Вы можете прочитать его в <a href="' . $this->generateUrl('account_messages', array(), true) . '">личном кабинете</a>.','text/html');

                        $this->get('mailer')->send($message);
                    }
                    
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Veiksmīga!</strong> Jūsu ziņa ir nosūtīta.') . '</div>'
                    );
                    
                }
                else
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat rakstīt ziņojumus sev.') . '</div>'
                    );
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute ("user_products", array("userId" => $user->getId()));
                }
                else
                {
                    return $this->redirectToRoute ("user_productsLocale", array("userId" => $user->getId(),"_locale" => $locale->getCode()));
                }
            }
            
            
            return $this->render('DashboardCommonBundle:User:profileproducts.html.twig', array("user" => $user,
                                                                                       "sessionUser" => $sessionUser,
                                                                                       "messageForm" => $messageForm->createView(),
                                                                                       "settings" => $settings,
                                                                                       "locale" => $locale));
        }
        else
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("notfound");
            }
            else
            {
                return $this->redirectToRoute("notfoundLocale", array("_locale" => $locale->getCode()));
            }
    }
    
    /**
     * @Route("/user/reviews/{userId}", name="user_reviews", defaults={"userId" : 0} )
     * @Route("/{_locale}/user/reviews/{userId}", name="user_reviewsLocale", defaults={"_locale" : "lv","userId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function userReviewsAction($userId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("DashboardCommonBundle:User")->find($userId);
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($user)
        {
            if($this->getUser())
            {
                $sessionUser = $this->get('security.context')->getToken()->getUser();
            }
            else
            {
               $sessionUser = 0;
            }
            
            $message = new Message();
            $messageForm = $this->createForm(new ProfileMessageType($manager), $message);
            
            $messageForm->handleRequest($request);

            if ($messageForm->isSubmitted() && $messageForm->isValid())
            {
                if($sessionUser->getId() != $messageForm['userTo']->getData()->getId())
                {
                    $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $messageForm['userTo']->getData()->getId(), "userTo" => $sessionUser->getId()));
                    
                    if($blacklistItem)
                    {
                        $this->addFlash(
                            'notice',
                            '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                            $this->get('translator')->trans('<strong>Kļūda!</strong> Lietotājs jums ir iekļauts melnajā sarakstā. Jūs nevarat nosūtīt ziņojumu.') . '</div>'
                        );

                        
                        if($locale->getIsDefault())
                        {
                            return $this->redirectToRoute ("user_reviews", array("userId" => $user->getId()));
                        }
                        else
                        {
                            return $this->redirectToRoute ("user_reviewsLocale", array("userId" => $user->getId(),"_locale" => $locale->getCode()));
                            
                        }
                    }
                    
                    $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Conversation c WHERE (c.userOne = " . $messageForm['userTo']->getData()->getId()  . " AND c.userTwo = " . $messageForm['userFrom']->getData()->getId() . " ) "
                            . "OR (c.userOne = " . $messageForm['userFrom']->getData()->getId() . " AND c.userTwo = " . $messageForm['userTo']->getData()->getId()  . ")");
                    
                    try{
                        $conversation = $query->getSingleResult();
                    }
                    catch(\Doctrine\ORM\NoResultException $e) {
                        $conversation = new Conversation();
                        $conversation->setUserOne($messageForm['userTo']->getData());
                        $conversation->setUserTwo($messageForm['userFrom']->getData());
                        $conversation->setUserDeleted(null);
                        $manager->persist($conversation);
                        $manager->flush();
                    }
                    
                    $message->setUserOwner($messageForm['userFrom']->getData());
                    $message->setIsNew(1);
                    $message->setIsDeleted(0);
                    $message->setSentDate(new \DateTime("now"));
                    $message->setReadedDate(new \DateTime("now"));
                    $message->setConversation($conversation);
                    $message->setProduct(null);
                    
                    $manager->persist($message);
                    $manager->flush();
                    
                    $messageTwo = new Message();
                    $messageTwo = clone $message;
                    $messageTwo->setUserOwner($messageForm['userTo']->getData());
                    
                    $manager->persist($messageTwo);
                    $manager->flush();
                    
                    if($messageForm['userTo']->getData()->getAlerts())
                    {
                        $message = \Swift_Message::newInstance()
                        ->setSubject('Вам пришло новое сообщение на сайте gribupardot.sunweb.by')
                        ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                        ->setTo($messageForm['userTo']->getData()->getEmail())
                        ->setBody('Вы получили новое сообщение на сайте gribupardot.sunweb.by. '
                                . 'Вы можете прочитать его в <a href="' . $this->generateUrl('account_messages', array(), true) . '">личном кабинете</a>.','text/html');

                        $this->get('mailer')->send($message);
                    }
                    
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Veiksmīga!</strong> Jūsu ziņa ir nosūtīta.') . '</div>'
                    );
                    
                }
                else
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                        $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat rakstīt ziņojumus sev.') . '</div>'
                    );
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute ("user_reviews", array("userId" => $user->getId()));
                }
                else
                {
                    return $this->redirectToRoute ("user_reviewsLocale", array("userId" => $user->getId(),"_locale" => $locale->getCode()));       
                }
            }
            
            
            return $this->render('DashboardCommonBundle:User:profilereview.html.twig', array("user" => $user,
                                                                                       "sessionUser" => $sessionUser,
                                                                                       "messageForm" => $messageForm->createView(),
                                                                                       "settings" => $settings,
                                                                                       "locale" => $locale));
        }
        else
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("notfound");
            }
            else
            {
                return $this->redirectToRoute("notfoundLocale", array("_locale" => $locale->getCode()));
            }
    }
    
    /**
     * @Route("/account/blacklist/{userId}", name="account_blacklist", defaults={"userId" : 0} )
     * @Route("/{_locale}/account/blacklist/{userId}", name="account_blacklistLocale", defaults={"_locale" : "lv","userId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function blacklistAction($userId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($user->getId() == $userId)
        {
            $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat pievienot sevi melnajam sarakstam.') . '</div>'
                );
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute ("profile", array("userId" => $user->getId()));
            }
            else
            {
                return $this->redirectToRoute ("profileLocale", array("userId" => $user->getId(),"_locale" => $locale->getCode()));       
            }
        }
        
        $userTo = $manager->getRepository("DashboardCommonBundle:User")->find($userId);
        
        if($userTo)
        {
            $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $user->getId(), "userTo" => $userId));
            
            if($blacklistItem)
            {
                $manager->remove($blacklistItem);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                    $this->get('translator')->trans('<strong>Veiksmīga!</strong> Lietotājs ir noņemts no jūsu melnā saraksta.') . '</div>'
                );
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute ("profile", array("userId" => $user->getId()));
                }
                else
                {
                    return $this->redirectToRoute ("profileLocale", array("userId" => $user->getId(),"_locale" => $locale->getCode()));       
                }
            }
            else
            {
                $blacklistItem = new Blacklist();
                $blacklistItem->setUserAuthor($user->getId());
                $blacklistItem->setUserTo($userId);
                $manager->persist($blacklistItem);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Panākumi!</strong> Lietotājs ir pievienots jūsu melnajam sarakstam.') . '</div>'
                );
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute ("profile", array("userId" => $user->getId()));
                }
                else
                {
                    return $this->redirectToRoute ("profileLocale", array("userId" => $user->getId(),"_locale" => $locale->getCode()));       
                }
            }
        }
        else
        {
            $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Šajā datubāzē nav šāda lietotāja.') . '</div>'
                );
        }
        
        if($locale->getIsDefault())
        {
            return $this->redirectToRoute ("profile", array("userId" => $user->getId()));
        }
        else
        {
            return $this->redirectToRoute ("profileLocale", array("userId" => $user->getId(),"_locale" => $locale->getCode()));       
        }
    }
        
    /**
     * @Route("/{route}", name="pagesCheck")
     * @Route("/{_locale}/{route}", name="pagesCheckLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function pagesCheckAction($route, Request $request)        
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
                
        if($route)
        {
            $page = $manager->getRepository("DashboardCommonBundle:Page")->findOneBy(array("route" => $route, "locale" => $locale));
            
            if($page)
                return $this->render('DashboardCommonBundle:Default:page.html.twig', array("page" => $page)); 
            else
               throw $this->createNotFoundException(); 
        }
        else
            throw $this->createNotFoundException();
    }
}

