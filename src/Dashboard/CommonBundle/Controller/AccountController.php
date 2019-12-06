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

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\CommonBundle\Entity\Message;
use Dashboard\CommonBundle\Entity\Conversation;
use Dashboard\CommonBundle\Entity\Review;

use Dashboard\CommonBundle\Form\Type\UserType;
use Dashboard\CommonBundle\Form\Type\UserPasswordType;
use Dashboard\CommonBundle\Form\Type\ReviewType;

class AccountController extends Controller
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
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isConfirm = 1 AND p.isActive = 1 AND p.isBlocked = 0 AND p.isDraft = 0');
        $currentProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isDraft = 1');
        $draftProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isConfirm = 0 AND p.isBlocked = 0 AND p.isDraft = 0');
        $confirmProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isConfirm = 1 AND p.isActive = 0 AND p.isBlocked = 0 AND p.isDraft = 0');
        $stoppedProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isActive = 1 AND p.isBlocked = 1 AND p.isDraft = 0');
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
        
        return $this->render('DashboardCommonBundle:User:account/sidebar.html.twig', 
                array("allProducts" => $allProducts,
                      "currentProducts" => $currentProducts,
                      "confirmProducts" => $confirmProducts,
                      "stoppedProducts" => $stoppedProducts,
                      "blockedProducts" => $blockedProducts,
                      "draftProducts"   => $draftProducts,
                      "newMessages" => count($messages),
                      "messagesInbox" => $messagesInbox,
                      "messagesSent" => $messagesSent,
                      "messagesTrash" => $messagesTrash,
                      "orderReceived" => $orderReceived,
                      "orderBanned" => $orderBanned,
                      "settings" => $settings,
                      "locale" => $locale,
                      "routeName" => $routeName));
    }
    
    /**
     * @Route("/account", name="account")
     * @Route("/{_locale}/account", name="accountLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
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
        
        return $this->render('DashboardCommonBundle:User:account/account.html.twig', array("user" => $user,
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
            
        return $this->render('DashboardCommonBundle:User:account/message/conversations.html.twig', array("user" =>$user,
                                                                                    "conversations" => $conversations,
                                                                                    "pagination" => 0,
                                                                                    "locale" => $locale,
                                                                                    "settings" => $settings,
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/moremessages/{conversationId}/{start}", name="account_more_messages")
     * @Route("/{_locale}/account/moremessages/{conversationId}/{start}", name="account_more_messagesLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
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
        
        return $this->render('DashboardCommonBundle:User:account/message/items.html.twig', array(
                                                                                       "user" => $user,
                                                                                       "messages" => $messages,
                                                                                       "conversation" => $conversation));
        
    }
    
    /**
     * @Route("/account/conversation/{conversationId}", name="account_conversation", defaults={"conversationId" : 0})
     * @Route("/{_locale}/account/conversation/{conversationId}", name="account_conversationLocale", defaults={"_locale" : "es", "conversationId" : 0}, requirements={"_locale" : "es|ru"})
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
                return $this->redirectToRoute("account_conversation", array("conversationId" => $conversationId));
            }
            else
            {
                return $this->redirectToRoute("account_conversationLocale", array("_locale" => $locale->getCode(),"conversationId" => $conversationId));
            }
        }
        
        return $this->render('DashboardCommonBundle:User:account/message/conversation.html.twig', array("lastmessage" => $message,
                                                                                       "user" => $user,
                                                                                       "formMessage" => $formMessage->createView(),
                                                                                       "messages" => $messages,
                                                                                       "conversation" => $conversation,
                                                                                       "settings" => $settings,
                                                                                       "locale" => $locale,
                                                                                       "routeName" => $request->attributes->get("_route")));
    }
    
     /**
     * @Route("/account/deleteconversation", name="account_conversation_delete") 
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
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .                         $this->get('translator')->trans('<strong>Veiksmīga!</strong> Saruna tika dzēsta.') . '</div>'
                    );
                }
            }
        }
        
        return new Response("OK");
    }
    
     /**
     * @Route("/account/changeconversation", name="account_conversation_change") 
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
        
        $formAlert = $this->get('form.factory')->createNamedBuilder('alert', 'form', $user)
            ->add('isAlertBroadcast', CheckboxType::class, array('required' => false, 'label' => $this->get('translator')->trans('я соглашаюсь получать информационную рассылку от ') . $settings->getSiteName(), 'attr' => array('class' => 'custom-checkbox')))
            ->add('isAlertNewMessage', CheckboxType::class, array('required' => false, 'label' => $this->get('translator')->trans('получать информацию о новом сообщении'), 'attr' => array('class' => 'custom-checkbox')))
            ->add('isAlertNewOrder', CheckboxType::class, array('required' => false, 'label' => $this->get('translator')->trans('получать информацию о новой заявке'), 'attr' => array('class' => 'custom-checkbox')))
            ->add('isAlertChangeOrderStatus', CheckboxType::class, array('required' => false, 'label' => $this->get('translator')->trans('получать информацию о смене статуса заказа'), 'attr' => array('class' => 'custom-checkbox')))
            ->getForm();
        
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
                
                return $this->render('DashboardCommonBundle:User:account/settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
                                                                                    "formMain" => $formMain->createView(),
                                                                                    "formPassword" => $formPassword->createView(),
                                                                                    "user" => $user,
                                                                                    "socialAccounts" => $socialAccounts,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
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
                return $this->redirectToRoute("account_settings");
            }
            else
            {
                return $this->redirectToRoute("account_settingsLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        return $this->render('DashboardCommonBundle:User:account/settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
                                                                                    "formMain" => $formMain->createView(),
                                                                                    "formPassword" => $formPassword->createView(),
                                                                                    "formAlert" => $formAlert->createView(),
                                                                                    "user" => $user,
                                                                                    "socialAccounts" => $socialAccounts,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "routeName" => $request->attributes->get("_route")));
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
        
        return $this->render('DashboardCommonBundle:User:account/products/products.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings, 
                                                                                    "user" => $user, 
                                                                                    "title" => "Мои объявления",
                                                                                    "services" => $services,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "routeName" => $request->attributes->get("_route")));
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
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isConfirm = 1 AND p.isActive = 1 AND p.isBlocked = 0');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        //вычисляем сколько осталось дней
        
        return $this->render('DashboardCommonBundle:User:account/products/products.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale, 
                                                                                    "user" => $user, 
                                                                                    "services" => $services,
                                                                                    "routeName" => $request->attributes->get("_route")));
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
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isConfirm = 0 AND p.isBlocked = 0');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        //вычисляем сколько осталось дней
        
        return $this->render('DashboardCommonBundle:User:account/products/confirm.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings, 
                                                                                    "user" => $user, 
                                                                                    "title" => "Объявления на модерации",
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/drafts", name="account_products_drafts")
     * @Route("/{_locale}/account/drafts", name="account_products_draftsLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function productsDraftAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isDraft = 1');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        //вычисляем сколько осталось дней
        
        return $this->render('DashboardCommonBundle:User:account/products/drafts.html.twig', array("products" => $products, 
                                                                                    "settings" => $settings, 
                                                                                    "user" => $user, 
                                                                                    "title" => "Черновики",
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "routeName" => $request->attributes->get("_route")));
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
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isConfirm = 1 AND p.isActive = 0 AND p.isBlocked = 0');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        return $this->render('DashboardCommonBundle:User:account/products/stopped.html.twig', array("products" => $products,  
                                                                                           "user" => $user, 
                                                                                           "services" => $services,
                                                                                           "settings" => $settings,
                                                                                           "locale" => $locale,
                                                                                           "routeName" => $request->attributes->get("_route")));
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
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isActive = 1 AND p.isBlocked = 1');

        try
            {
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        
        return $this->render('DashboardCommonBundle:User:account/products/blocked.html.twig', array("products" => $products, "settings" => $settings,"user" => $user, "title" => $this->get('translator')->trans("Bloķētās reklāmas"),"settings" => $settings,"locale" => $locale,"routeName" => $request->attributes->get("_route")));
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
        
        return $this->render('DashboardCommonBundle:User:account/products/favorite.html.twig', array("products" => $products,
                                                                                    "user" => $user, 
                                                                                    "services" => $services,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale, 
                                                                                    "routeName" => $request->attributes->get("_route")));
        
    }
    
    /**
     * @Route("/account/orders", name="account_orders")
     * @Route("/{_locale}/account/orders", name="account_ordersLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
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
        
        return $this->render('DashboardCommonBundle:User:account/order/orders.html.twig', array("user" => $user,
                                                                                  "orderStatuses" => $orderStatuses,
                                                                                  "locale" => $locale,
                                                                                  "settings" => $settings,
                                                                                  "routeName" => $request->attributes->get("_route")));
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
                return $this->redirectToRoute("account_myorders");
            }
            else
            {
                return $this->redirectToRoute("account_myordersLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        return $this->render('DashboardCommonBundle:User:account/order/myorders.html.twig', array("user" => $user,
                                                                                    "orderStatuses" => $orderStatuses,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "reviewForm" => $reviewForm->createView(),
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
        
     /**
     * @Route("/account/userblacklist/{userId}", name="account_userblacklist", defaults={"userId" : 0} )
     * @Route("/{_locale}/account/userblacklist/{userId}", name="account_userblacklistLocale", defaults={"_locale" : "lv", "userId" : 0}, requirements={"_locale" : "lv|ru"})
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
        
        return $this->render('DashboardCommonBundle:User:account/blacklist.html.twig', array("blacklist" => $blackUsers,
                                                                                         "user" => $user,
                                                                                         "settings" => $settings,
                                                                                         "locale" => $locale,
                                                                                         "routeName" => $request->attributes->get("_route")));
    } 
    
    /**
     * @Route("/account/blacklist/delete", name="account_userblacklist_delete")
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
     * @Route("/account/bills", name="account_bills")
     * @Route("/{_locale}/account/bills", name="account_billsLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function accountBillsAction(Request $request){
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        
        
        return $this->render('DashboardCommonBundle:User:account/bills.html.twig', array(
                                                                                        "user" => $user,
                                                                                        "settings" => $settings,
                                                                                        "locale" => $locale,
                                                                                        "routeName" => $request->attributes->get("_route")));
    }
}

