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
    
    public function getSidebarAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $countNewMessages = 0;
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId());
        $allProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 0 AND p.isConfirm = 1 AND p.isActive = 1 AND p.isBlocked = 0');
        $currentProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 0 AND p.isConfirm = 0 AND p.isActive = 0 AND p.isBlocked = 0');
        $confirmProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 1 AND p.isConfirm = 0 AND p.isActive = 0 AND p.isBlocked = 0');
        $correctProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 0 AND p.isConfirm = 1 AND p.isActive = 0 AND p.isBlocked = 0');
        $stoppedProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isBlocked = 1');
        $blockedProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT m FROM Dashboard\CommonBundle\Entity\Message m WHERE m.isDeleted <> 1 AND m.userTo = ' . $user->getId() . ' AND m.isNew = 1 AND m.userOwner = ' . $user->getId());
            
        $messages = $query->getResult();
        
        $query = $manager->createQuery('SELECT m FROM Dashboard\CommonBundle\Entity\Message m WHERE m.isDeleted <> 1 AND m.userTo = ' . $user->getId() .' AND m.userOwner = ' . $user->getId());
        $messagesInbox = $query->getResult();
        
        $query = $manager->createQuery('SELECT m FROM Dashboard\CommonBundle\Entity\Message m WHERE m.isDeleted <> 1 AND m.userFrom = ' . $user->getId() .' AND m.userOwner = ' . $user->getId());   
        $messagesSent = $query->getResult();
        
        $query = $manager->createQuery('SELECT m FROM Dashboard\CommonBundle\Entity\Message m WHERE m.isDeleted = 1 AND m.userOwner = ' . $user->getId());    
        $messagesTrash = $query->getResult();
        
        $query = $manager->createQuery('SELECT o FROM Dashboard\CommonBundle\Entity\ProductOrder o WHERE o.userReceived = ' . $user->getId() . ' AND o.isNew = 1');    
        $orderReceived = $query->getResult();
        
        $query = $manager->createQuery('SELECT o FROM Dashboard\CommonBundle\Entity\ProductOrder o WHERE o.userSended = ' . $user->getId() . ' AND o.status = 2');
        $orderBanned = $query->getResult();
        
        return $this->render('DashboardCommonBundle:User:sidebar.html.twig', 
                array("allProducts" => $allProducts,
                      "currentProducts" => $currentProducts,
                      "confirmProducts" => $confirmProducts,
                      "stoppedProducts" => $stoppedProducts,
                      "blockedProducts" => $blockedProducts,
                      "newMessages" => count($messages),
                      "messagesInbox" => $messagesInbox,
                      "messagesSent" => $messagesSent,
                      "messagesTrash" => $messagesTrash,
                      "orderReceived" => $orderReceived,
                      "orderBanned" => $orderBanned,
                      "correctProducts" => $correctProducts,
                      "settings" => $settings,
                      "locale" => $locale));
    }
    
    /**
     * @Route("/register/{link}", name="register", defaults = {"link" : "0"})
     * @Route("/{_locale}/register/{link}", name="registerLocale", defaults={"_locale" : "lv","link" : "0"}, requirements={"_locale" : "lv|ru"})
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
                                                                                    'success' => $success,"settings" => $settings, "locale" => $locale));
    }
    
    /**
     * @Route("/register/confirm/{key}", name="register_confirm")
     * @Route("/{_locale}/register/confirm/{key}", name="register_confirmLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
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
                ->add('email', TextType::class, array('required' => true, 'label' => $this->get('translator')->trans('e-pasts'), 'attr' => array('placeholder' => 'email')))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Atjaunot')))->getForm();
        
        $restoreForm->handleRequest($request);
        
        if ($restoreForm->isSubmitted() && $restoreForm->isValid())
        {
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
                $error = 1;
            }
        }
        return $this->render('DashboardCommonBundle:User:restore.html.twig', array("success" => $success,
                                                                                   "error" => $error,
                                                                                   "resotreForm" => $restoreForm->createView(),
                                                                                   "settings" => $settings,
                                                                                   "locale" => $locale));
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
     * @Route("/account", name="account")
     * @Route("/{_locale}/account", name="accountLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function accountAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $allProducts = $manager->getRepository("Dashboard\CommonBundle\Entity\Product")->findByUser($user);
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($user->getActivity())
        {
            if($user->getActivity()->getEnterCount() == 0)
            {
                $user->getActivity()->setEnterCount(1);
                $user->getActivity()->setLastActivity(new \DateTime("now"));

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                        'notice',
                        '<div class="alert alert-warning alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Informācija!</strong> Lai pievienotu reklāmas, aizpildiet savu profilu.') . '</div>'
                );

                if($locale->getIsDefault())
                {
                     return $this->redirectToRoute("account_settings");
                }
                else
                {
                     return $this->redirectToRoute("account_settingsLocale", array("_locale" => $locale->getCode()));
                }
            }
        }
        
        
        //current products
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' ORDER BY p.dateAdded DESC')->setMaxResults(4);

        try
        {
            $products = $query->getResult();
        }
            catch(\Doctrine\ORM\NoResultException $e) {
                
            $products = 0;
        }
        
        //favorite products
        $productsId = $manager->getRepository("DashboardCommonBundle:FavoriteProducts")->findByUserId($user->getId());
        
        $favProducts = array();
        
        if($productsId)
        {
            foreach($productsId as $productId)
            {
                $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId->getProductId());
                if($product)
                    array_push($favProducts, $product);
            }
        }
        
        //new messages
        $query = $manager->createQuery('SELECT m FROM Dashboard\CommonBundle\Entity\Message m WHERE m.isNew = 1 AND m.userTo = ' . $user->getId() .' AND m.userOwner = ' . $user->getId(). ' ORDER BY m.sentDate DESC')->setMaxResults(2);
        
        $messages = $query->getResult();
        
        return $this->render('DashboardCommonBundle:User:account.html.twig', array("user" => $user,
                                                                                   "countProducts" => count($allProducts),
                                                                                   "products" => $products,
                                                                                   "favProducts" => $favProducts,
                                                                                   "messages" => $messages,
                                                                                   "services" => $services,
                                                                                   "locale" => $locale,
                                                                                   "settings" => $settings));
    }
    
    /**
     * @Route("/account/messages/{messageId}", name="account_messages", defaults={"messageId" : 0})
     * @Route("/{_locale}/account/messages/{messageId}", name="account_messagesLocale", defaults={"_locale" : "lv","messageId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function messagesAction($messageId,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        //get all conversations
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Conversation c WHERE c.userOne = " . $user->getId() . " OR c.userTwo = " . $user->getId() . " ORDER BY c.id DESC");
                                    
        try{
            $conversations = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $conversations = 0;
        }
            
        return $this->render('DashboardCommonBundle:User:messages.html.twig', array("user" =>$user,
                                                                                    "conversations" => $conversations,
                                                                                    "pagination" => 0,
                                                                                    "locale" => $locale,
                                                                                    "settings" => $settings));
    }
    
    /**
     * @Route("/account/editmessage/{conversationId}", name="account_message_edit", defaults={"conversationId" : 0})
     * @Route("/{_locale}/account/editmessage/{conversationId}", name="account_message_editLocale", defaults={"_locale" : "lv","conversationId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function editMessageAction($conversationId,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $conversation = $manager->getRepository("DashboardCommonBundle:Conversation")->find($conversationId);
        
        foreach($conversation->getMessages() as $message)
        {
            if($user->getId() == $message->getUserTo()->getId())
            {
                $message->setIsNew(0);
                $message->setReadedDate(new \DateTime("now"));
                $manager->persist($message);
                $manager->flush();
            }
        }
        
        //load all conversation with this user
        $query = $manager->createQuery("SELECT m FROM DashboardCommonBundle:Message m WHERE m.conversation = " . $conversationId . " AND m.userOwner = " . $user->getId() . " ORDER BY m.sentDate ASC");
               
        try{
            $messages = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $messages = 0;
        }
        
        $extentions = array("jpg", "jpeg", "png", "gif");
        
        $newMessage = new Message();
        $newMessageTwo = new Message();
        
        $formMessage = $this->get('form.factory')->createNamedBuilder('message', 'form',$newMessage)
                ->add('message', TextareaType::class, array('required' => true, 'label' => $this->get('translator')->trans('Sludinājuma teksts: *'), 'attr' => array('class' => 'send-message-textarea')))
                ->add('image', FileType::class, array('required' => false, 'mapped' => false, 'label' => $this->get('translator')->trans('Pievienojiet attēlu')))
                ->add('userFrom',HiddenType::class,array('mapped' => false, 'data' => $user->getId()))
                ->add('userTo',HiddenType::class,array('mapped' => false, 'data' => ($conversation->getUserOne()->getId() == $user->getId()) ? $conversation->getUserTwo()->getId() : $conversation->getUserOne()->getId()))
                ->add('conversation',HiddenType::class,array('mapped' => false, 'data' => $conversation->getId()))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Sūtīt'), 'attr' => array('class' => 'message-button-answer')))->getForm();
         
        
        $formMessage->handleRequest($request);
        
        if($formMessage->isValid()&& $formMessage->isSubmitted())       
        {
            $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $formMessage['userTo']->getData(), "userTo" => $user->getId()));
                    
            if($blacklistItem)
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Пользователь внес Вас в черный список. Отправка сообщения невозможна.') . '</div>'
                );

                if($locale->getIsDefault())
                {
                     return $this->redirectToRoute("account_messages");
                }
                else
                {
                     return $this->redirectToRoute("account_messagesLocale", array("_locale" => $locale->getCode()));
                }
            }
                    
            $image = $formMessage['image']->getData();
                        
            if($image)
            {
                $extention = $image->getClientOriginalExtension();
                
                if(in_array($extention, $extentions) && ($image->getClientSize() < 2097152))
                {
                    $localImageName = rand(1, 99999).'.'.$extention;
                    $image->move('bundles/images/messages',$localImageName);
                    $newMessage->setImage($localImageName);
                }
                else
                {
                    $this->addFlash(
                            'notice',
                            '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                            $this->get('translator')->trans('<strong>Kļūda!</strong> Attēls neatbilst prasībām. Derīgi paplašinājumi: jpg, jpeg, png, gif. Maksimālais izmērs ir 2 MB. Ziņojums tika nosūtīts bez attēla.') . '</div>'
                    );
                }
            }
            
            $newMessage->setUserFrom($user);
            $newMessage->setUserOwner($user);
            
            $userTo = $manager->getRepository("DashboardCommonBundle:User")->find($formMessage['userTo']->getData());
            if($userTo)
            {
                $newMessage->setUserTo($userTo);
            }
            
            $newMessage->setProduct(null);
            
            $newMessage->setIsNew(1);
            $newMessage->setIsDeleted(0);
            $newMessage->setSentDate(new \DateTime("now"));
            $newMessage->setReadedDate(new \DateTime("now"));
            $newMessage->setConversation($conversation);
            
            $manager->persist($newMessage);
            $manager->flush();
            
            $newMessageTwo = clone $newMessage;
            
            $newMessageTwo->setUserOwner($userTo);
            
            $manager->persist($newMessageTwo);
            $manager->flush();
            
            //send an email
            if($userTo)
            {
                if($userTo->getAlerts())
                {
                    $message = \Swift_Message::newInstance()
                    ->setSubject('Вам пришло новое сообщение на сайте gribupardot.sunweb.by')
                    ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                    ->setTo($userTo->getEmail())
                    ->setBody('Вы получили новое сообщение на сайте gribupardot.sunweb.by. '
                            . 'Вы можете прочитать его в <a href="' . $this->generateUrl('account_messages', array(), true) . '">личном кабинете</a>.','text/html');

                    $this->get('mailer')->send($message);
                }
            }

            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_message_edit", array("conversationId" => $conversationId));
            }
            else
            {
                return $this->redirectToRoute("account_message_editLocale", array("_locale" => $locale->getCode(),"conversationId" => $conversationId));
            }
        }
        
        return $this->render('DashboardCommonBundle:User:editmessage.html.twig', array("lastmessage" => $message,
                                                                                       "user" => $user,
                                                                                       "formMessage" => $formMessage->createView(),
                                                                                       "messages" => $messages,
                                                                                       "conversation" => $conversation,
                                                                                       "settings" => $settings,
                                                                                       "locale" => $locale));
    }
    
     /**
     * @Route("/account/deletemessage/{conversationId}", name="account_conversation_delete", defaults={"conversationId" : 0}) 
     */
    public function deleteConversationAction($conversationId,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $conversation = $manager->getRepository("DashboardCommonBundle:Conversation")->find($conversationId);
        
        if($conversation)
        {
            foreach($conversation->getMessages() as $message)
            {
                if($message->getImage())
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/messages/' . $message->getImage()))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/messages/' . $message->getImage());
                    }
                }

                $message->setUserOwner(null);
                $message->setProduct(null);
                $message->setUserTo(null);
                $message->setUserFrom(null);

                $manager->remove($message);
                $manager->flush();
            }
            
            $conversation->setUserOne(null);
            $conversation->setUserTwo(null);
            $manager->remove($conversation);
            $manager->flush();
            
            $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Veiksmīga!</strong> Saruna tika dzēsta.') . '</div>'
            );
        }
        
        if($locale->getIsDefault())
        {
            return $this->redirectToRoute("account_messages");
        }
        else
        {
            return $this->redirectToRoute("account_messagesLocale", array("_locale" => $locale->getCode()));
        }
    }
    
    
    /**
     * @Route("/account/settings", name="account_settings")
     * @Route("/{_locale}/account/settings", name="account_settingsLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
    */
    
    public function settingsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $fm = new Filesystem();
        $socialAccounts = 0;
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $formMain = $this->createForm(new UserType($this->getDoctrine()->getManager(), $user->getUserinfo(), $locale), $user);
        $formPassword = $this->createForm(new UserPasswordType($this->getDoctrine()->getManager()), $user);
        
        
        $formMain->handleRequest($request);
        $formPassword->handleRequest($request);
        
        if($formMain->isValid())
        {
            //check if email exists
            $query = $manager->createQuery("SELECT u FROM Dashboard\CommonBundle\Entity\User u WHERE (u.username = '" . $formMain['email']->getData() . "' OR u.email = '" . $formMain['email']->getData() . "') AND u.id <> " . $user->getId());

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
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Email %mess% pieder citam lietotājam.', array("%mess%" => $formMain['email']->getData())) . '</div>'
                );
                
                return $this->render('DashboardCommonBundle:User:settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
                                                                                    "formMain" => $formMain->createView(),
                                                                                    "formPassword" => $formPassword->createView(),
                                                                                    "user" => $user,
                                                                                    "socialAccounts" => $socialAccounts,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale));
            }
            
            $avatar = $formMain['userinfo']['avatarNew']->getData();
            $oldAvatar = $formMain['userinfo']['avatar']->getData();
            
            if($avatar)
            {
                if($oldAvatar)
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/users/avatars/' .$oldAvatar ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/users/avatars/' .$oldAvatar );
                    }
                }
                $extention = $avatar->getClientOriginalExtension();
                $localAvatarName = rand(1, 99999).'.'.$extention;
                $avatar->move('bundles/images/users/avatars',$localAvatarName);
                $user->getUserinfo()->setAvatar($localAvatarName);
            }
            
            $user->setUsername($user->getEmail());
            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                $this->get('translator')->trans('<strong>Gatavs!</strong> Lietotāja dati tika veiksmīgi saglabāti.') . '</div>'
            );
            
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_settings");
            }
            else
            {
                return $this->redirectToRoute("account_settingsLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        if($formPassword->isValid())
        {
            $password = $formPassword['passwordNew']->getData();
            $passwordConfirm = $formPassword['passwordConfirm']->getData();
            
            if($password == $passwordConfirm)
            {
                $encoder = $this->container->get('security.password_encoder');
                $passwordNew = $encoder->encodePassword($user, $password);
                
                $user->setPassword($passwordNew);
                $manager->persist($user);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Gatavs!</strong> Parole ir veiksmīgi atjaunināta.') . '</div>'
                );
            }
            else
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Nav ieviests!</strong> Neizdevās atjaunināt paroli.') . '</div>'
                );
            }
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_settings");
            }
            else
            {
                return $this->redirectToRoute("account_settingsLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        return $this->render('DashboardCommonBundle:User:settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
                                                                                    "formMain" => $formMain->createView(),
                                                                                    "formPassword" => $formPassword->createView(),
                                                                                    "user" => $user,
                                                                                    "socialAccounts" => $socialAccounts,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale));
    }
    
    /**
     * @Route("/account/products/{productId}", name="account_products", defaults={"productId" : 0})
     * @Route("/{_locale}/account/products/{productId}", name="account_productsLocale", defaults={"_locale" : "lv","productId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function productsAction($productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        
        if($productId)
        {
            $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.id=' . $productId . ' AND p.user = ' . $user->getId());
        
            try
            {
                $product = $query->getSingleResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $product = 0;
            }
            
            if($product)
            {
                $product->setIsActive(0);
                $manager->persist($product);
                $manager->flush();
                
                $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                        $this->get('translator')->trans('<strong>Veiksmīgi!</strong> Šī reklāma ir pārvietota uz Izpildīto sadaļu.') . '</div>'
                    );
                
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("account_products_stopped");
                }
                else
                {
                    return $this->redirectToRoute("account_products_stoppedLocale", array("_locale" => $locale->getCode()));
                }
            }
        }
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' ORDER BY p.isActive DESC');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        return $this->render('DashboardCommonBundle:User:products.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings, 
                                                                                    "user" => $user, 
                                                                                    "title" => "Мои объявления",
                                                                                    "services" => $services,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale));
    }
    
    /**
     * @Route("/account/currentproducts", name="account_products_current")
     * @Route("/{_locale}/account/currentproducts", name="account_products_currentLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function productsCurrentAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 0 AND p.isConfirm = 1 AND p.isActive = 1 AND p.isBlocked = 0');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        //вычисляем сколько осталось дней
        
        return $this->render('DashboardCommonBundle:User:products.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale, 
                                                                                    "user" => $user, 
                                                                                    "title" => $this->get('translator')->trans("Pašreizējās reklāmas"),
                                                                                    "services" => $services));
    }
    
    /**
     * @Route("/account/confirmproducts", name="account_products_confirm")
     * @Route("/{_locale}/account/confirmproducts", name="account_products_confirmLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function productsConfirmAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 0 AND p.isConfirm = 0 AND p.isActive = 0 AND p.isBlocked = 0');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        //вычисляем сколько осталось дней
        
        return $this->render('DashboardCommonBundle:User:productsconfirm.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings, 
                                                                                    "user" => $user, 
                                                                                    "title" => "Объявления на модерации",
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,));
    }
    
    /**
     * @Route("/account/correctproducts", name="account_products_correct")
     * @Route("/{_locale}/account/correctproducts", name="account_products_correctLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function productsCorrectAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 1 AND p.isConfirm = 0 AND p.isActive = 0 AND p.isBlocked = 0');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        //вычисляем сколько осталось дней
        
        return $this->render('DashboardCommonBundle:User:productscorrect.html.twig', array("products" => $products,  
                                                                                    "user" => $user, 
                                                                                    "title" => $this->get('translator')->trans("Reklāmas par korekciju"),
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale));
    }
    
    /**
     * @Route("/account/stoppedproducts/{productId}", name="account_products_stopped", defaults={"productId" : "0"})
     * @Route("/{_locale}/account/stoppedproducts/{productId}", name="account_products_stoppedLocale", defaults={"_locale" : "lv","productId" : "0"}, requirements={"_locale" : "lv|ru"})
     */
    public function productsStoppedAction($productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        
        if($productId)
        {
            $this->deleteAdvert($productId, $request);
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_products_stopped");
            }
            else
            {
                return $this->redirectToRoute("account_products_stoppedLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 0 AND p.isConfirm = 1 AND p.isActive = 0 AND p.isBlocked = 0');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        return $this->render('DashboardCommonBundle:User:productsstopped.html.twig', array("products" => $products,  
                                                                                           "user" => $user, 
                                                                                           "title" => $this->get('translator')->trans("Pabeigtas reklāmas"),
                                                                                           "services" => $services,
                                                                                           "settings" => $settings,
                                                                                           "locale" => $locale));
    }
    
    /**
     * @Route("/account/blockedproducts/{productId}", name="account_products_blocked", defaults={"productId" : 0})
     * @Route("/{_locale}/account/blockedproducts/{productId}", name="account_products_blockedLocale", defaults={"_locale" : "lv","productId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function productsBlockedAction($productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($productId)
        {
            $this->deleteAdvert($productId, $request);

            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_products_blocked");
            }
            else
            {
                return $this->redirectToRoute("account_products_blockedLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isBlocked = 1');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        return $this->render('DashboardCommonBundle:User:productsblocked.html.twig', array("products" => $products, "settings" => $settings,"user" => $user, "title" => $this->get('translator')->trans("Bloķētās reklāmas"),"settings" => $settings,"locale" => $locale));
    }    
    
    /**
     * @Route("/account/favproducts/{productId}", name="account_favproducts", defaults={"productId" : 0})
     * @Route("/{_locale}/account/favproducts/{productId}", name="account_favproductsLocale", defaults={"_locale" : "lv","productId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function favproductsAction($productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($productId)
        {
            $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\FavoriteProducts p WHERE p.userId = ' . $user->getId() . ' AND p.productId = ' . $productId);
            
            try
            {
                $product = $query->getSingleResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $product = 0;
            }
            
            if($product)
            {
                $manager->remove($product);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Gatavs!</strong> Šī reklāma ir noņemta no sadaļas Izlase.') . '</div>'
                );

                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("account");
                }
                else
                {
                    return $this->redirectToRoute("accountLocale", array("_locale" => $locale->getCode()));
                }
            }
        }
        
        $productsId = $manager->getRepository("DashboardCommonBundle:FavoriteProducts")->findByUserId($user->getId());
        
        $products = array();
        
        if($productsId)
        {
            foreach($productsId as $productId)
            {
                $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId->getProductId());
                if($product)
                    array_push($products, $product);
            }
        }
        
        return $this->render('DashboardCommonBundle:User:favproducts.html.twig', array("products" => $products,
                                                                                    "user" => $user, 
                                                                                    "title" => $this->get('translator')->trans("Piedāvātās reklāmas"),
                                                                                    "services" => $services,
                                                                                    "settings" => $settings,"locale" => $locale));
    }
    
    /**
     * @Route("/account/orders/{page}", name="account_orders", defaults={"page" : 0})
     * @Route("/{_locale}/account/orders/{page}", name="account_ordersLocale", defaults={"_locale" : "lv","page" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function ordersAction($page, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $orderStatuses = $manager->getRepository("DashboardCommonBundle:OrderStatus")->findAll();
        //$orders = array_reverse($user->getReceivedOrders()->toArray());
        
        
        $query = $manager->createQuery("SELECT o FROM DashboardCommonBundle:ProductOrder o WHERE o.userReceived = " . $user->getId() ." ORDER BY o.id DESC" );

        try{
            $orders = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $orders = 0;
        }
        
        $totalOrders = count($orders);
        
        $query = $manager->createQuery("SELECT o FROM DashboardCommonBundle:ProductOrder o WHERE o.userReceived = " . $user->getId() ." ORDER BY o.id DESC" )->setFirstResult((($page > 0) ? ($page - 1) : 0) * 10)->setMaxResults(10);

        try{
            $orders = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $orders = 0;
        }
        
        $helper = $this->get("app.helpers");
        $pagination = $helper->paginator(($page > 0) ? (int)$page : 1, $totalOrders, 10, "/account/orders");
        
        return $this->render('DashboardCommonBundle:User:orders.html.twig', array("user" => $user,
                                                                                  "orders" =>$orders,
                                                                                  "orderStatuses" => $orderStatuses,
                                                                                  "pagination" => $pagination,
                                                                                  "locale" => $locale,
                                                                                  "settings" => $settings));
    }
    
    /**
     * @Route("/account/myorders", name="account_myorders")
     * @Route("/{_locale}/account/myorders", name="account_myordersLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function myOrdersAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $orderStatuses = $manager->getRepository("DashboardCommonBundle:OrderStatus")->findAll();
        $orders = array_reverse($user->getSendedOrders()->toArray());
        
        $review = new Review();
        $reviewForm = $this->createForm(new ReviewType($manager, $locale), $review);
        
        $reviewForm->handleRequest($request);
            
        if ($reviewForm->isSubmitted() && $reviewForm->isValid())
        {
            $product = $manager->getRepository("DashboardCommonBundle:Product")->find($reviewForm['product']->getData());

            if($product)
            {
                if($product->getUser()->getId() != $user->getId())
                {
                    $isReview = $manager->getRepository("DashboardCommonBundle:Review")->findOneBy(array("user" => $user, "targetUser" => $product->getUser(), "product" => $product));
                    
                    if($isReview)
                    {
                        $this->addFlash(
                            'notice',
                            '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                            $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs jau esat nosūtījis atsauksmi šim reklāmdevējam par šo reklāmu.') . '</div>'
                        );

                        if($locale->getIsDefault())
                        {
                            return $this->redirectToRoute("account_myorders");
                        }
                        else
                        {
                            return $this->redirectToRoute("account_myordersLocale", array("_locale" => $locale->getCode()));
                        }
                    }
                    
                    $review->setUser($user);
                    $review->setTargetUser($product->getUser());
                    $review->setProduct($product);
                    $review->setDateAdded(new \DateTime("now"));
                    
                    if($reviewForm['mark']->getData())
                        $review->setProductMark($reviewForm['mark']->getData()->getTitle());
                    
                    if($product->getUser()->getAlerts())
                    {
                        $message = \Swift_Message::newInstance()
                        ->setSubject('Новый отзыв на сайте gribupardot.sunweb.by')
                        ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                        ->setTo($product->getUser()->getEmail())
                        ->setBody('О Вас оставили новый отзыв на сайте gribupardot.sunweb.by. '
                                . 'Вы можете прочитать его в <a href="' . $this->generateUrl('account_targetreview', array(), true) . '">личном кабинете</a>.','text/html');

                        $this->get('mailer')->send($message);
                    }
                    
                    $manager->persist($review);
                    $manager->flush();
                    
                    //calculate rating
                    //$currentRating = $productUser->getRating();
                    
                    $productUserReviews = $manager->getRepository("DashboardCommonBundle:Review")->findBy(array("targetUser" => $product->getUser()));
                    $plusReviews = 0;
                    
                    if(count($productUserReviews) > 0)
                    {
                        foreach($productUserReviews as $productUserReview)
                        {
                            if($productUserReview->getStatus() == 1)
                            {
                                $plusReviews++;
                            }
                        }
                        
                        $userRating = ($plusReviews * 100) / count($productUserReviews);
                    }
                    else
                       $userRating = 0; 
                    
                    $productUser = $product->getUser()->getUserinfo();
                    $productUser->setRating(floor($userRating));
                    
                    $manager->persist($productUser);
                    $manager->flush();
                    
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Panākumi!</strong> Jūsu atsauksmes nosūtīta pārdevējam.') . '</div>'
                    );
                }
                else
                {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat atstāt atsauksmes par sevi.') . '</div>'
                    );
                }
            }
            else
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Atbildes nav nosūtītas. Jūs vēlaties atstāt atsauksmi par esošu reklāmu.') . '</div>'
                );
            }
               
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_myorders");
            }
            else
            {
                return $this->redirectToRoute("account_myordersLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        return $this->render('DashboardCommonBundle:User:myorders.html.twig', array("user" => $user,
                                                                                    "orders" =>$orders,
                                                                                    "orderStatuses" => $orderStatuses,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "reviewForm" => $reviewForm->createView()));
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
     * @Route("/account/chengemessagestatus/{messageId}", name="account_changemessagestatus")
     */
    public function changeMessageStatusAction($messageId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $message = $manager->getRepository("DashboardCommonBundle:Message")->findOneBy(array("id" => $messageId, "userTo" => $user->getId(), "userOwner" => $user->getId()));
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($message)
        {
            if($message->getIsNew())
            {
                $message->setIsNew(false);
            }
            else
            {
                $message->setIsNew(true);
            }
            
            $manager->persist($message);
            $manager->flush();
            
            return new Response(1);
        }
        else
            return new Response(0);
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
     * @Route("/account/userblacklist/{userId}", name="account_userblacklist", defaults={"userId" : 0} )
     * @Route("/{_locale}/account/userblacklist/{userId}", name="account_userblacklistLocale", defaults={"_locale" : "lv","userId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function userBlacklistAction($userId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($userId)
        {
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
                        return $this->redirectToRoute("account_userblacklist");
                    }
                    else
                    {
                        return $this->redirectToRoute ("account_userblacklistLocale", array("_locale" => $locale->getCode()));       
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

                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("account_userblacklist");
                }
                else
                {
                    return $this->redirectToRoute ("account_userblacklistLocale", array("_locale" => $locale->getCode()));       
                }
            }
        }
        
        $blackUsers = array();
        
        $blacklist = $manager->getRepository("DashboardCommonBundle:Blacklist")->findBy(array("userAuthor" => $user->getId()));
        
        if($blacklist)
        {
            foreach($blacklist as $item)
            {
                $itemUser = $manager->getRepository("DashboardCommonBundle:User")->find($item->getUserTo());
                
                if($itemUser)
                {
                    array_push($blackUsers, $itemUser);
                }
            }
        }
        
        return $this->render('DashboardCommonBundle:User:userblacklist.html.twig', array("blacklist" => $blackUsers,
                                                                                         "user" => $user,
                                                                                         "settings" => $settings,
                                                                                         "locale" => $locale));
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

