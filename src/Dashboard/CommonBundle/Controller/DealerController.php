<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\DealerInfo;
use Dashboard\CommonBundle\Entity\DealerFoto;
use Dashboard\CommonBundle\Entity\DealerSalon;
use Dashboard\CommonBundle\Form\Type\DealerRegisterType;
use Dashboard\CommonBundle\Entity\Message;
use Dashboard\CommonBundle\Entity\Conversation;
use Dashboard\CommonBundle\Entity\Review;

use Dashboard\CommonBundle\Form\Type\UserType;
use Dashboard\CommonBundle\Form\Type\UserPasswordType;
use Dashboard\CommonBundle\Form\Type\DealerEditType;
use Dashboard\CommonBundle\Form\Type\DealerSalonType;
use Dashboard\CommonBundle\Form\Type\ReviewType;

class DealerController extends Controller
{
    public function getSidebarAction($routeName, Request $request)
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
        
        return $this->render('DashboardCommonBundle:Dealer:account/sidebar.html.twig', 
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
                      "locale" => $locale,
                      "routeName" => $routeName));
    }
    /**
     * @Route("/dealer", name="dealer")
     * @Route("/{_locale}/dealer", name="dealerLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function pageAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.locale=" . $locale->getId() . " AND p.isUserpage = 0 AND p.route = 'dealer'" );
        
        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $page = 0;
        }
        
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL AND c.isActive = 1 ORDER BY c.sortorder');
        
        try{
            $categories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $categories = 0;
        }
        
        return $this->render('DashboardCommonBundle:Dealer:page.html.twig', array("page" => $page,
                                                                                  "locale" => $locale,
                                                                                  "settings" => $settings,
                                                                                  "categories" => $categories));
    }
    
    /**
     * @Route("/dealer/register", name="dealerRegister")
     * @Route("/{_locale}/dealer/register", name="dealerRegisterLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
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
            $role = $this->getDoctrine()->getRepository("DashboardCommonBundle:Role")->findOneByRole("ROLE_DEALER");
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
            $dealer->getUserinfo()->setUser($dealer);
            
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
        
        return $this->render('DashboardCommonBundle:Dealer:register.html.twig', array("locale" => $locale,
                                                                                      "settings" => $settings,
                                                                                      "registerForm" => $registerForm->createView(),
                                                                                      "success" => $success));
    }
    
    /**
     * @Route("/dealerpage/{dealerName}", name="dealerPage")
     * @Route("/{_locale}/dealerpage/{dealerName}", name="dealerPageLocale", defaults={"_locale" : "es","dealerName" : 0}, requirements={"_locale" : "es|ru"})
     */
    public function dealerPageAction($dealerName,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery('SELECT c,cc FROM Dashboard\CommonBundle\Entity\Category c LEFT JOIN c.children cc WHERE c.parent IS NULL AND c.isActive = 1 ORDER BY c.sortorder, cc.sortorder');
        
        try{
            $categories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $categories = 0;
        }
        
        return $this->render('DashboardCommonBundle:Dealer:dealer.html.twig', array("locale" => $locale,
                                                                                    "settings" => $settings,
                                                                                    "categories" => $categories));
    }
    
    /**
     * @Route("/dealers", name="dealers")
     * @Route("/{_locale}/dealers", name="dealersLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function dealersAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        return $this->render('DashboardCommonBundle:Dealer:dealers.html.twig', array("locale" => $locale,
                                                                                      "settings" => $settings));
    }
    
    /**
     * @Route("/account/dealer/editsalon/{salonId}", name="account_dealer_editsalon")
     * @Route("/{_locale}/account/dealer/editsalon/{salonId}", name="account_dealer_editsalonLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
    */
    
    public function editSalonAction($salonId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $fm = new Filesystem();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $originalPhones = new ArrayCollection();
        
        $salon = $manager->getRepository("DashboardCommonBundle:DealerSalon")->findOneBy(array("id" => $salonId, "dealerInfo" => $user->getDealerinfo()));
        
        if($salon){
            
            if($salon->getPhones()){
                foreach($salon->getPhones() as $phone){
                    $originalPhones->add($phone);
                }
            }
            
            $formDealerSalon = $this->createForm(new DealerSalonType($manager), $salon);
            $formDealerSalon->handleRequest($request);
            
            if($formDealerSalon->isValid()){
                
                if($originalPhones){
                    foreach($originalPhones as $phone){
                        if(false === $salon->getPhones()->contains($phone)){
                            $phone->setDealerSalon(null);
                            $manager->remove($phone);
                        }
                    }
                }
                
                if($salon->getPhones()){
                    foreach($salon->getPhones() as $dealerSalonPhone){
                        $dealerSalonPhone->setDealerSalon($salon);
                        $manager->persist($dealerSalonPhone);
                    }
                }

                $avatar = $formDealerSalon['logotypeNew']->getData();
                $oldAvatar = $formDealerSalon['logotype']->getData();

                if($avatar)
                {
                    if($oldAvatar)
                    {
                        if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/salons/' .$oldAvatar ))
                        {
                            $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/salons/' .$oldAvatar );
                        }
                    }
                    $extention = $avatar->getClientOriginalExtension();
                    $localAvatarName = rand(1, 99999) . rand(1, 99999) . rand(1, 99999) . '.' . $extention;
                    $avatar->move('bundles/images/dealers/salons',$localAvatarName);
                    $salon->setLogotype($localAvatarName);
                }

                $manager->persist($salon);
                $manager->flush();

                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Успешно!</strong> Информация об автосалоне сохранена.') . '</div>'
                );

                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("account_dealer_settings");
                }
                else
                {
                    return $this->redirectToRoute("account_dealer_settingsLocale", array("_locale" => $locale->getCode()));
                }
            }
            
            return $this->render('DashboardCommonBundle:Dealer:account/salonEditForm.html.twig', array("formDealerSalon" => $formDealerSalon->createView(),"locale" => $locale,"salon" => $salon));
        }else{
            return $this->createNotFoundException();
        }
    }
    
    /**
     * @Route("/{_locale}/account/dealer/deletesalon/{salonId}", name="account_dealer_deletesalonLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
    */
    
    public function deleteSalonAction($salonId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $fm = new Filesystem();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $originalPhones = new ArrayCollection();
        
        $salon = $manager->getRepository("DashboardCommonBundle:DealerSalon")->findOneBy(array("id" => $salonId, "dealerInfo" => $user->getDealerinfo()));
        
        if($salon){
            
            if($salon->getLogotype())
            {
                if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/salons/' . $salon->getLogotype()))
                {
                    $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/salons/' . $salon->getLogotype());
                }
            }
            
            if($salon->getPhones()){
                foreach($salon->getPhones() as $dealerSalonPhone){
                    $dealerSalonPhone->setDealerSalon(NULL);
                    $manager->remove($dealerSalonPhone);
                }
            }
            
            if($salon->getWorkinfo()){
                $salon->getWorkinfo()->setDealerSalon(NULL);
                $manager->remove($salon->getWorkinfo());
            }
            
            $salon->setDealerInfo(NULL);
            $manager->remove($salon);
            $manager->flush();
            
        }
        
        return new Response("DELETED");
    }
    
    /**
     * @Route("/{_locale}/account/dealer/deletesalonlogo/{salonId}", name="account_dealer_deletesalonLogoLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
    */
    
    public function deleteSalonLogoAction($salonId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $fm = new Filesystem();
        
        $salon = $manager->getRepository("DashboardCommonBundle:DealerSalon")->findOneBy(array("id" => $salonId, "dealerInfo" => $user->getDealerinfo()));
        
        if($salon){
            if($salon->getLogotype())
            {
                if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/salons/' . $salon->getLogotype()))
                {
                    $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/salons/' . $salon->getLogotype());
                }
            }
            
            $salon->setLogotype(NULL);
            $manager->persist($salon);
            $manager->flush();
        }
        
        return new Response("DELETED");
    }
    
    /**
     * @Route("/account/dealer/settings", name="account_dealer_settings")
     * @Route("/{_locale}/account/dealer/settings", name="account_dealer_settingsLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
    */
    
    public function settingsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $fm = new Filesystem();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $originalPhones = new ArrayCollection();
        
        if($user->getDealerInfo()){
            if($user->getDealerInfo()->getPhones()){
                foreach($user->getDealerInfo()->getPhones() as $dealerPhone){
                    $originalPhones->add($dealerPhone);
                }
            }
        }
        
        $formMain = $this->createForm(new UserType($this->getDoctrine()->getManager(), $user->getUserinfo(), $locale), $user);
        $formDealer = $this->createForm(new DealerEditType($this->getDoctrine()->getManager(), $locale), $user->getDealerInfo());
        $formPassword = $this->createForm(new UserPasswordType($this->getDoctrine()->getManager()), $user);
        $formAutos = $this->get('form.factory')->createNamedBuilder('autos', 'form', $user->getDealerInfo())
             ->add('autos', 'entity', array('class' => 'DashboardCommonBundle:Category',
                                              'choice_label' => function($category){
                                                    return $category->getTitle();
                                              },
                                              'required' => false, 
                                              'label' => '',
                                              'multiple' => true,
                                              'expanded' => true,
                                              'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->where('c.parent = 27');},
                                              'attr' => array('class' => 'form-control')))->getForm();
        
        $formAlert = $this->get('form.factory')->createNamedBuilder('alert', 'form', $user)
            ->add('isAlertBroadcast', CheckboxType::class, array('required' => false, 'label' => $this->get('translator')->trans('я соглашаюсь получать информационную рассылку от ') . $settings->getSiteName(), 'attr' => array('class' => 'custom-checkbox')))
            ->add('isAlertNewMessage', CheckboxType::class, array('required' => false, 'label' => $this->get('translator')->trans('получать информацию о новом сообщении'), 'attr' => array('class' => 'custom-checkbox')))
            ->add('isAlertNewOrder', CheckboxType::class, array('required' => false, 'label' => $this->get('translator')->trans('получать информацию о новой заявке'), 'attr' => array('class' => 'custom-checkbox')))
            ->getForm();
        
        $dealerSalon = new DealerSalon();
        $formDealerSalon = $this->createForm(new DealerSalonType($this->getDoctrine()->getManager()), $dealerSalon);
                
        $formMain->handleRequest($request);
        $formPassword->handleRequest($request);
        $formDealer->handleRequest($request);
        $formAutos->handleRequest($request);
        $formDealerSalon->handleRequest($request);
        
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
                
                return $this->render('DashboardCommonBundle:Dealer:account/settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
                                                                                    "formMain" => $formMain->createView(),
                                                                                    "formPassword" => $formPassword->createView(),
                                                                                    "formDealer" => $formDealer->createView(),
                                                                                    "formAutos" => $formAutos->createView(),
                                                                                    "formDealerSalon" => $formDealerSalon->createView(),
                                                                                    "user" => $user,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "dealerImages" => $user->getDealerinfo()->getFotos(),
                                                                                    "routeName" => $request->attributes->get("_route")));
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
                $user->setAvatar($localAvatarName);
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
        
        if($formDealer->isValid())
        {
            
            $user->getDealerInfo()->getWorkinfo()->setDealer($user->getDealerInfo());
            
            if($originalPhones){
                foreach($originalPhones as $dealerPhone){
                    if(false === $user->getDealerInfo()->getPhones()->contains($dealerPhone)){
                        $dealerPhone->setDealerInfo(null);
                        $manager->remove($dealerPhone);
                    }
                }
            }
            if($user->getDealerInfo()->getPhones()){
                foreach($user->getDealerInfo()->getPhones() as $dealerPhone){
                    $dealerPhone->setDealerInfo($user->getDealerInfo());
                    $manager->persist($dealerPhone);
                }
            }
            
            $avatar = $formDealer['logotypeNew']->getData();
            $oldAvatar = $formDealer['logotype']->getData();
            
            if($avatar)
            {
                if($oldAvatar)
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/logotypes/' .$oldAvatar ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/logotypes/' .$oldAvatar );
                    }
                }
                $extention = $avatar->getClientOriginalExtension();
                $localAvatarName = rand(1, 99999).'.'.$extention;
                $avatar->move('bundles/images/dealers/logotypes',$localAvatarName);
                $user->getDealerInfo()->setLogotype($localAvatarName);
            }
            
            $manager->persist($user->getDealerInfo());
            $manager->persist($user->getDealerInfo()->getWorkinfo());
            $manager->flush();
            
            $this->addFlash(
                'notice',
                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                $this->get('translator')->trans('<strong>Gatavs!</strong> Lietotāja dati tika veiksmīgi saglabāti.') . '</div>'
            );
            
            if($locale->getIsDefault()){
                return $this->redirectToRoute("account_dealer_settings");
            }
            else{
                return $this->redirectToRoute("account_dealer_settingsLocale", array("_locale" => $locale->getCode()));
            }
            
        }
        
        if($formAutos->isValid()){
            
            $manager->persist($user->getDealerInfo());            
            $manager->flush();
            
            $this->addFlash(
                'notice',
                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                $this->get('translator')->trans('<strong>Успешно!</strong> Изменения сохранены.') . '</div>'
            );
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_dealer_settings");
            }
            else
            {
                return $this->redirectToRoute("account_dealer_settingsLocale", array("_locale" => $locale->getCode()));
            }
            
        }
        
        if($formDealerSalon->isValid()){
            
            $dealerSalon->getWorkinfo()->setDealerSalon($dealerSalon);
            $dealerSalon->setDealerInfo($user->getDealerInfo());
            
            if($dealerSalon->getPhones()){
                foreach($dealerSalon->getPhones() as $dealerSalonPhone){
                    $dealerSalonPhone->setDealerSalon($dealerSalon);
                    $manager->persist($dealerSalonPhone);
                }
            }
            
            $avatar = $formDealerSalon['logotypeNew']->getData();
            $oldAvatar = $formDealerSalon['logotype']->getData();
            
            if($avatar)
            {
                if($oldAvatar)
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/salons/' .$oldAvatar ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/salons/' .$oldAvatar );
                    }
                }
                $extention = $avatar->getClientOriginalExtension();
                $localAvatarName = rand(1, 99999) . rand(1, 99999) . rand(1, 99999) . '.' . $extention;
                $avatar->move('bundles/images/dealers/salons',$localAvatarName);
                $dealerSalon->setLogotype($localAvatarName);
            }
            
            $manager->persist($dealerSalon);
            $manager->persist($dealerSalon->getWorkinfo());
            $manager->flush();
            
            $this->addFlash(
                'notice',
                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                $this->get('translator')->trans('<strong>Успешно!</strong> Информация об автосалоне сохранена.') . '</div>'
            );
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_dealer_settings");
            }
            else
            {
                return $this->redirectToRoute("account_dealer_settingsLocale", array("_locale" => $locale->getCode()));
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
                return $this->redirectToRoute("account_dealer_settings");
            }
            else
            {
                return $this->redirectToRoute("account_dealer_settingsLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        $formAlert->handleRequest($request);
        if($formAlert->isValid()){
            
            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                $this->get('translator')->trans('<strong>Успешно!</strong> Изменения сохранены.') . '</div>'
            );
            
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_dealer_settings");
            }
            else
            {
                return $this->redirectToRoute("account_dealer_settingsLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        return $this->render('DashboardCommonBundle:Dealer:account/settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
                                                                                    "formMain" => $formMain->createView(),
                                                                                    "formPassword" => $formPassword->createView(),
                                                                                    "formAlert" => $formAlert->createView(),
                                                                                    "formDealer" => $formDealer->createView(),
                                                                                    "formAutos" => $formAutos->createView(),
                                                                                    "formDealerSalon" => $formDealerSalon->createView(),
                                                                                    "user" => $user,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "dealerImages" => $user->getDealerInfo()->getFotos(),
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/ajaxloadfotos", name="ajaxDealerLoadFotos")
     */
    public function ajaxLoadFotosAction(Request $request)
    {
        $fm = new Filesystem();
        $error = 0;
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $extentions = array("jpg", "jpeg", "png", "gif", "JPG", "JPEG", "PNG", "GIF");
        $image = $request->files->all()["file"];

        if($image)
        {           
            $extention = $image->getClientOriginalExtension();
            $localImageName = rand(1, 99999999).'.'.$extention;
            
            if(in_array($extention, $extentions))
            {
                try
                {
                    $image->move('bundles/images/dealers',$localImageName);
                    
                    $simpleImage = $this->get('app.simpleimage');
                    $simpleImage->load('bundles/images/dealers/' . $localImageName);
                    $simpleImage->resizeToWidth(1024);
                    $simpleImage->save('bundles/images/dealers/' . $localImageName);
                    
                    $newImage = new DealerFoto();
                    $newImage->setImage($localImageName);
                    $newImage->setDealerInfo($user->getDealerinfo());
                    $manager->persist($newImage);
                    $manager->flush();
                }
                catch (Symfony\Component\HttpFoundation\File\Exception\FileException $e)
                {
                    $error = $this->get('translator')->trans("Доступные форматы для изображений: jpg, jpeg, png, gif.");
                }
            }
            else 
            {
                $error = $this->get('translator')->trans("Доступные форматы для изображений: jpg, jpeg, png, gif.");
            }

            $data = $error ? array('error' => $error) : array('imageName' => $localImageName);
            
            return new Response(json_encode( $data ));
        }
    }
    
    /**
     * @Route("/account/dealer/deleteimage/{data}", name="ajaxDealerDeleteFoto")
     */
    public function ajaxDeleteFotoAction($data, Request $request)
    {
        $fm = new Filesystem();
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $dealerImage = $manager->getRepository("DashboardCommonBundle:DealerFoto")->findOneBy(array("image" => $data, "dealerInfo" => $user->getDealerinfo()));
        
        if($dealerImage){
            if($dealerImage->getImage() == $data){
                if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/' . $dealerImage->getImage())){
                    $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/' . $dealerImage->getImage());
                }
                $manager->remove($dealerImage);
                $manager->flush();
            }
        }
        
        return new Response("OK");
    }
    
    /**
     * @Route("/account/dealer/deletelogo", name="ajaxDealerDeleteLogo")
     */
    public function ajaxDeleteLogotypeAction(Request $request)
    {
        $fm = new Filesystem();
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($user && $user->getDealerinfo()->getLogotype()){
            if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/logotypes/' . $user->getDealerinfo()->getLogotype())){
                $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/dealers/logotypes/' . $user->getDealerinfo()->getLogotype());
            }
            $user->getDealerinfo()->setLogotype(null);
            $manager->persist($user->getDealerinfo());
            $manager->flush();
        }
        
        return new Response("OK");
    }
    
    /**
     * @Route("/account/dealer", name="accountDealer")
     * @Route("/{_locale}/account/dealer", name="accountDealerLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function accountAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $allProducts = $manager->getRepository("Dashboard\CommonBundle\Entity\Product")->findByUser($user);
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));

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
        
        return $this->render('DashboardCommonBundle:Dealer:account/account.html.twig', array("user" => $user,
                                                                                   "countProducts" => count($allProducts),
                                                                                   "products" => $products,
                                                                                   "favProducts" => $favProducts,
                                                                                   "messages" => $messages,
                                                                                   "services" => $services,
                                                                                   "locale" => $locale,
                                                                                   "routeName" => $request->attributes->get("_route"),
                                                                                   "settings" => $settings));
    }
    
    /**
     * @Route("/account/dealer/bills", name="account_dealer_bills")
     * @Route("/{_locale}/account/dealer/bills", name="account_dealer_billsLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function accountBillsAction(Request $request){
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        
        
        return $this->render('DashboardCommonBundle:Dealer:account/bills.html.twig', array(
                                                                                        "user" => $user,
                                                                                        "settings" => $settings,
                                                                                        "locale" => $locale,
                                                                                        "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/userblacklist/{userId}", name="account_dealer_userblacklist", defaults={"userId" : 0} )
     * @Route("/{_locale}/account/dealer/userblacklist/{userId}", name="account_dealer_userblacklistLocale", defaults={"_locale" : "lv", "userId" : 0}, requirements={"_locale" : "lv|ru"})
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
                        return $this->redirectToRoute("account_dealer_userblacklist");
                    }
                    else
                    {
                        return $this->redirectToRoute ("account_dealer_userblacklistLocale", array("_locale" => $locale->getCode()));       
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
                    return $this->redirectToRoute("account_dealer_userblacklist");
                }
                else
                {
                    return $this->redirectToRoute ("account_dealer_userblacklistLocale", array("_locale" => $locale->getCode()));       
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
        
        return $this->render('DashboardCommonBundle:Dealer:account/blacklist.html.twig', array("blacklist" => $blackUsers,
                                                                                         "user" => $user,
                                                                                         "settings" => $settings,
                                                                                         "locale" => $locale,
                                                                                         "routeName" => $request->attributes->get("_route")));
    } 
    
    /**
     * @Route("/account/dealer/blacklist/delete", name="account_dealer_userblacklist_delete")
     */
    public function userBlacklistDeleteAction(Request $request){
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($request->request->get('blackListUser')){
            foreach($request->request->get('blackListUser') as $userId){
                
                $userTo = $manager->getRepository("DashboardCommonBundle:User")->find($userId);
        
                if($userTo)
                {
                    $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $user->getId(), "userTo" => $userId));

                    if($blacklistItem)
                    {
                        $manager->remove($blacklistItem);
                        $manager->flush();
                    }
                }
            }
        }
        
        $this->addFlash(
            'notice',
            '<div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
            $this->get('translator')->trans('<strong>Успешно!</strong> Выбранные пользователи удалены.') . '</div>'
        );
        
        return new Response("OK");
    }
    
    /**
     * @Route("/account/dealer/rates", name="account_dealer_rates")
     * @Route("/{_locale}/account/dealer/rates", name="account_dealer_ratesLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function accountRatesAction(Request $request){
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL AND c.isActive = 1 ORDER BY c.sortorder');
        
        try{
            $categories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $categories = 0;
        }
        
        
        return $this->render('DashboardCommonBundle:Dealer:account/rates.html.twig', array(
                                                                                        "user" => $user,
                                                                                        "settings" => $settings,
                                                                                        "locale" => $locale,
                                                                                        "categories" => $categories,
                                                                                        "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/products/{productId}", name="account_dealer_products", defaults={"productId" : 0})
     * @Route("/{_locale}/account/dealer/products/{productId}", name="account_dealer_productsLocale", defaults={"_locale" : "es","productId" : 0}, requirements={"_locale" : "es|ru"})
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
                    return $this->redirectToRoute("account_dealer_products_stopped");
                }
                else
                {
                    return $this->redirectToRoute("account_dealer_products_stoppedLocale", array("_locale" => $locale->getCode()));
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
        
        return $this->render('DashboardCommonBundle:Dealer:account/products/products.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings, 
                                                                                    "user" => $user, 
                                                                                    "title" => "Мои объявления",
                                                                                    "services" => $services,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/correctproducts", name="account_dealer_products_correct")
     * @Route("/{_locale}/account/dealer/correctproducts", name="account_dealer_products_correctLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
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
        
        return $this->render('DashboardCommonBundle:Dealer:account/products/correct.html.twig', array("products" => $products,  
                                                                                    "user" => $user, 
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/currentproducts", name="account_dealer_products_current")
     * @Route("/{_locale}/account/dealer/currentproducts", name="account_dealer_products_currentLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
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
        
        return $this->render('DashboardCommonBundle:Dealer:account/products/products.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale, 
                                                                                    "user" => $user, 
                                                                                    "services" => $services,
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/confirmproducts", name="account_dealer_products_confirm")
     * @Route("/{_locale}/account/dealer/confirmproducts", name="account_dealer_products_confirmLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
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
        
        return $this->render('DashboardCommonBundle:Dealer:account/products/confirm.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings, 
                                                                                    "user" => $user, 
                                                                                    "title" => "Объявления на модерации",
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/stoppedproducts/{productId}", name="account_dealer_products_stopped", defaults={"productId" : "0"})
     * @Route("/{_locale}/account/dealer/stoppedproducts/{productId}", name="account_dealer_products_stoppedLocale", defaults={"_locale" : "es","productId" : "0"}, requirements={"_locale" : "es|ru"})
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
        
        return $this->render('DashboardCommonBundle:Dealer:account/products/stopped.html.twig', array("products" => $products,  
                                                                                           "user" => $user, 
                                                                                           "services" => $services,
                                                                                           "settings" => $settings,
                                                                                           "locale" => $locale,
                                                                                           "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/blockedproducts/{productId}", name="account_dealer_products_blocked", defaults={"productId" : 0})
     * @Route("/{_locale}/account/dealer/blockedproducts/{productId}", name="account_dealer_products_blockedLocale", defaults={"_locale" : "lv","productId" : 0}, requirements={"_locale" : "lv|ru"})
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
        
        return $this->render('DashboardCommonBundle:Dealer:account/products/blocked.html.twig', array("products" => $products, "settings" => $settings,"user" => $user, "title" => $this->get('translator')->trans("Bloķētās reklāmas"),"settings" => $settings,"locale" => $locale,"routeName" => $request->attributes->get("_route")));
    }  
    
    /**
     * @Route("/account/dealer/favproducts/{productId}", name="account_dealer_favproducts", defaults={"productId" : 0})
     * @Route("/{_locale}/account/dealer/favproducts/{productId}", name="account_dealer_favproductsLocale", defaults={"_locale" : "es","productId" : 0}, requirements={"_locale" : "es|ru"})
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
        
        return $this->render('DashboardCommonBundle:Dealer:account/products/favorite.html.twig', array("products" => $products,
                                                                                    "user" => $user, 
                                                                                    "services" => $services,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale, 
                                                                                    "routeName" => $request->attributes->get("_route")));
        
    }
    
    /**
     * @Route("/account/dealer/messages/{messageId}", name="account_dealer_messages", defaults={"messageId" : 0})
     * @Route("/{_locale}/account/dealer/messages/{messageId}", name="account_dealer_messagesLocale", defaults={"_locale" : "es","messageId" : 0}, requirements={"_locale" : "es|ru"})
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
            
        return $this->render('DashboardCommonBundle:Dealer:account/message/conversations.html.twig', array("user" =>$user,
                                                                                    "conversations" => $conversations,
                                                                                    "pagination" => 0,
                                                                                    "locale" => $locale,
                                                                                    "settings" => $settings,
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/conversation/{conversationId}", name="account_dealer_conversation", defaults={"conversationId" : 0})
     * @Route("/{_locale}/account/dealer/conversation/{conversationId}", name="account_dealer_conversationLocale", defaults={"_locale" : "es", "conversationId" : 0}, requirements={"_locale" : "es|ru"})
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
        $query = $manager->createQuery("SELECT m FROM DashboardCommonBundle:Message m WHERE m.conversation = " . $conversationId . " AND m.userOwner = " . $user->getId() . " ORDER BY m.sentDate DESC")->setFirstResult(0)->setMaxResults($settings->getUserMessagesNumber());
               
        try{
            $messages = $query->getResult();
            $messages = array_reverse($messages);
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
                     return $this->redirectToRoute("account_dealer_messages");
                }
                else
                {
                     return $this->redirectToRoute("account_dealer_messagesLocale", array("_locale" => $locale->getCode()));
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
                if($userTo->getIsAlertNewMessage())
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
                return $this->redirectToRoute("account_dealer_conversation", array("conversationId" => $conversationId));
            }
            else
            {
                return $this->redirectToRoute("account_dealer_conversationLocale", array("_locale" => $locale->getCode(),"conversationId" => $conversationId));
            }
        }
        
        return $this->render('DashboardCommonBundle:Dealer:account/message/conversation.html.twig', array("lastmessage" => $message,
                                                                                       "user" => $user,
                                                                                       "formMessage" => $formMessage->createView(),
                                                                                       "messages" => $messages,
                                                                                       "conversation" => $conversation,
                                                                                       "settings" => $settings,
                                                                                       "locale" => $locale,
                                                                                       "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/moremessages/{conversationId}/{start}", name="account_dealer_more_messages")
     * @Route("/{_locale}/account/dealer/moremessages/{conversationId}/{start}", name="account_dealer_more_messagesLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function getMoreMessagesAction($conversationId,$start,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $conversation = $manager->getRepository("DashboardCommonBundle:Conversation")->find($conversationId);
        
        $query = $manager->createQuery("SELECT m FROM DashboardCommonBundle:Message m WHERE m.conversation = " . $conversationId . " AND m.userOwner = " . $user->getId() . " ORDER BY m.sentDate DESC")->setFirstResult( $start)->setMaxResults($settings->getUserMessagesNumber());
               
        try{
            $messages = $query->getResult();
            $messages = array_reverse($messages);
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $messages = 0;
        }
        
        return $this->render('DashboardCommonBundle:Dealer:account/message/items.html.twig', array(
                                                                                       "user" => $user,
                                                                                       "messages" => $messages,
                                                                                       "conversation" => $conversation));
        
    }
    
    /**
     * @Route("/account/dealer/deleteconversation", name="account_dealer_conversation_delete") 
     */
    public function deleteConversationAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($request->request->get('conversation')){
            foreach($request->request->get('conversation') as $key => $conversationId){
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
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .$this->get('translator')->trans('<strong>Veiksmīga!</strong> Saruna tika dzēsta.') . '</div>'
                    );
                }
            }
        }
        
        return new Response("OK");
    }
    
     /**
     * @Route("/account/dealer/changeconversation", name="account_dealer_conversation_change") 
     */
    public function changeConversationAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($request->request->get('conversation')){
            foreach($request->request->get('conversation') as $key => $conversationId){
                $conversation = $manager->getRepository("DashboardCommonBundle:Conversation")->find($conversationId);
        
                if($conversation)
                {
                    foreach($conversation->getMessages() as $message)
                    {
                        $message->setIsNew(false);
                        $manager->persist($message);
                        $manager->flush();
                    }
                }
            }
        }
        
        return new Response("OK");
    }
    
    /**
     * @Route("/account/dealer/orders", name="account_dealer_orders")
     * @Route("/{_locale}/account/dealer/orders", name="account_dealer_ordersLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function ordersAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));        
        
        $query = $manager->createQuery("SELECT os,o FROM DashboardCommonBundle:OrderStatus os LEFT JOIN os.orders o WHERE o.userReceived = " . $user->getId());
        
        try{
            $orderStatuses = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $orderStatuses = 0;
        }
        
        return $this->render('DashboardCommonBundle:Dealer:account/order/orders.html.twig', array("user" => $user,
                                                                                  "orderStatuses" => $orderStatuses,
                                                                                  "locale" => $locale,
                                                                                  "settings" => $settings,
                                                                                  "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/dealer/myorders", name="account_dealer_myorders")
     * @Route("/{_locale}/account/dealer/myorders", name="account_dealer_myordersLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function myOrdersAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery("SELECT os,o FROM DashboardCommonBundle:OrderStatus os LEFT JOIN os.orders o WHERE o.userSended = " . $user->getId());
        
        try{
            $orderStatuses = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $orderStatuses = 0;
        }
        
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
                            return $this->redirectToRoute("account_dealer_myorders");
                        }
                        else
                        {
                            return $this->redirectToRoute("account_dealer_myordersLocale", array("_locale" => $locale->getCode()));
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
                        ->setSubject('Новый отзыв на сайте ' . $settings->getSiteName())
                        ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                        ->setTo($product->getUser()->getEmail())
                        ->setBody('О Вас оставили новый отзыв на сайте ' . $settings->getSiteName() . '. '
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
                return $this->redirectToRoute("account_dealer_myorders");
            }
            else
            {
                return $this->redirectToRoute("account_dealer_myordersLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        return $this->render('DashboardCommonBundle:Dealer:account/order/myorders.html.twig', array("user" => $user,
                                                                                    "orderStatuses" => $orderStatuses,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "reviewForm" => $reviewForm->createView(),
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
}
