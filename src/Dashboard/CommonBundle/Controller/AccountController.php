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
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Dashboard\CommonBundle\Entity\Message;
use Dashboard\CommonBundle\Entity\DealerInfo;
use Dashboard\CommonBundle\Entity\DealerFoto;
use Dashboard\CommonBundle\Entity\DealerSalon;
use Dashboard\CommonBundle\Form\Type\DealerRegisterType;
use Dashboard\CommonBundle\Entity\Conversation;
use Dashboard\CommonBundle\Entity\Review;

use Dashboard\CommonBundle\Form\Type\UserType;
use Dashboard\CommonBundle\Form\Type\UserPasswordType;
use Dashboard\CommonBundle\Form\Type\ReviewType;
use Dashboard\CommonBundle\Form\Type\DealerEditType;
use Dashboard\CommonBundle\Form\Type\DealerSalonType;

class AccountController extends Controller
{
    public function getSidebarAction($routeName, $productType = '', Request $request)
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
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isDraft = 1 AND p.isActive = 1 AND p.isBlocked = 0');
        $draftProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isConfirm = 0 AND p.isActive = 1 AND p.isBlocked = 0 AND p.isDraft = 0');
        $confirmProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isConfirm = 1 AND p.isActive = 0 AND p.isBlocked = 0 AND p.isDraft = 0');
        $stoppedProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isActive = 1 AND p.isBlocked = 1 AND p.isDraft = 0');
        $blockedProducts = $query->getResult();
        
        $query = $manager->createQuery('SELECT m FROM Dashboard\CommonBundle\Entity\Message m WHERE m.isDeleted <> 1 AND m.userTo = ' . $user->getId() . ' AND m.isNew = 1 AND m.userOwner = ' . $user->getId());
            
        $messages = $query->getResult();
        
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
                      "orderReceived" => $orderReceived,
                      "orderBanned" => $orderBanned,
                      "settings" => $settings,
                      "locale" => $locale,
                      "routeName" => $routeName,
                      "productType" => $productType,
                      "user" => $user,
                      "userRole" => $user->getRoles()[0]));
    }
    
    /**
     * @Route("/account", name="account")
     */
    public function accountAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $allProducts = $manager->getRepository("Dashboard\CommonBundle\Entity\Product")->findByUser($user);
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));

        //current products
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' ORDER BY p.isActive DESC')->setMaxResults(4);

        try
        {
            $products = $query->getResult();
        }
            catch(\Doctrine\ORM\NoResultException $e) {
                
            $products = 0;
        }
        
        //favorite products
        $productsId = $manager->getRepository("DashboardCommonBundle:FavoriteProducts")->findByUserId($user->getId());
        
        $favProducts = new ArrayCollection();
        
        if($productsId)
        {
            foreach($productsId as $productId)
            {
                $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId->getProductId());
                if($product){
                    $favProducts->add($product);
                }
            }
        }
        
        return $this->render('DashboardCommonBundle:User:account/account.html.twig', array("user" => $user,
                                                                                   "countProducts" => count($allProducts),
                                                                                   "products" => $products,
                                                                                   "favProducts" => $favProducts,
                                                                                   "locale" => $locale,
                                                                                   "routeName" => $request->attributes->get("_route"),
                                                                                   "settings" => $settings));
    }
    
    /**
     * @Route("/account/products/{productType}", name="account_products", defaults = {"productType" : ""})
     */
    public function productsAction($productType, Request $request)
    {   
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        
        
        if($productType){
            switch($productType){
                case 'confirm':
                    $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isActive = 1 AND p.isConfirm = 0 AND p.isBlocked = 0 AND p.isDraft = 0');
                    $title = $this->get('translator')->trans('Объявления на модерации'); 
                break;

                case 'current':
                    $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isActive = 1 AND p.isConfirm = 1 AND p.isBlocked = 0 AND p.isDraft = 0');
                    $title = $this->get('translator')->trans('Текущие объявления');
                break;

                case 'drafts':
                    $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isActive = 1 AND p.isDraft = 1');
                    $title = $this->get('translator')->trans('Черновики'); 
                break;

                case 'stopped':
                    $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isActive = 0 AND p.isConfirm = 1 AND p.isBlocked = 0');
                    $title = $this->get('translator')->trans('Архив'); 
                break;

                case 'blocked':
                    $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isActive = 1 AND p.isBlocked = 1');
                    $title = $this->get('translator')->trans('Заблокированные'); 
                break;
            }
        }else{
            $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' ORDER BY p.isActive DESC');
            $title = $this->get('translator')->trans('Мои объявления'); 
        }
        
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
                                                                                    "title" => $title,
                                                                                    "productType" => $productType,
                                                                                    "services" => $services,
                                                                                    "locale" => $locale,
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/favorite", name="account_favorite_products", defaults={"productId" : 0})
     */
    public function favoriteProductsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
                
        $productsId = $manager->getRepository("DashboardCommonBundle:FavoriteProducts")->findByUserId($user->getId());
        
        $favProducts = new ArrayCollection();
        
        if($productsId)
        {
            foreach($productsId as $productId)
            {
                $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId->getProductId());
                if($product){
                    $favProducts->add($product);
                }
            }
        }
        
        return $this->render('DashboardCommonBundle:User:account/products/favorite.html.twig', array("products" => $favProducts,
                                                                                    "user" => $user, 
                                                                                    "services" => $services,
                                                                                    "settings" => $settings,
                                                                                    "title" => $this->get('translator')->trans('Избранные объявления'),
                                                                                    "locale" => $locale, 
                                                                                    "routeName" => $request->attributes->get("_route")));
        
    }
    
    /**
     * @Route("/account/conversations", name="account_conversations")
     */
    public function conversationsAction(Request $request)
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
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Отправить'), 'attr' => array('class' => 'message-button-answer')))->getForm();
         
        
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

                return $this->redirectToRoute("account_messages");
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

            return $this->redirectToRoute("account_conversation", array("conversationId" => $conversationId));
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
     * @Route("/account/moremessages/{conversationId}/{start}", name="account_more_messages")
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
     * @Route("/account/orders", name="account_orders")
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

                        return $this->redirectToRoute("account_myorders");
                    }
                    
                    $review->setUser($user);
                    $review->setTargetUser($product->getUser());
                    $review->setDateAdded(new \DateTime("now"));
                    
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
                        $this->get('translator')->trans('<strong>Успешно!</strong> Ваш отзыв был отправлен продавцу.') . '</div>'
                    );
                }
                else
                {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Ошибка!</strong> Вы не можете оставлять отзывы на самого себя.') . '</div>'
                    );
                }
            }
            else
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Отзыв не отправлен. Вы хотите оставить отзыв на несуществующее объявление.') . '</div>'
                );
            }
               
            return $this->redirectToRoute("account_myorders");
        }
        
        return $this->render('DashboardCommonBundle:User:account/order/myorders.html.twig', array("user" => $user,
                                                                                    "orderStatuses" => $orderStatuses,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "reviewForm" => $reviewForm->createView(),
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/account/rates", name="account_rates")
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
        
        
        return $this->render('DashboardCommonBundle:User:account/rates.html.twig', array(
                                                                                        "user" => $user,
                                                                                        "settings" => $settings,
                                                                                        "locale" => $locale,
                                                                                        "categories" => $categories,
                                                                                        "routeName" => $request->attributes->get("_route")));
    }
    
     /**
     * @Route("/account/bills", name="account_bills")
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
    
     /**
     * @Route("/account/userblacklist/{userId}", name="account_userblacklist", defaults={"userId" : 0} )
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
                        $this->get('translator')->trans('<strong>Успешно!</strong> Пользователь удален из Вашего черного списка.') . '</div>'
                    );

                    return $this->redirectToRoute("account_userblacklist");
                }
            }
            else
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Такого пользователя нет в базе.') . '</div>'
                );

                return $this->redirectToRoute("account_userblacklist");
            }
        }
        
        $blackUsers = new ArrayCollection();
        
        $blacklist = $manager->getRepository("DashboardCommonBundle:Blacklist")->findBy(array("userAuthor" => $user->getId()));
        
        if($blacklist)
        {
            foreach($blacklist as $item)
            {
                $itemUser = $manager->getRepository("DashboardCommonBundle:User")->find($item->getUserTo());
                
                if($itemUser){
                    $blackUsers->add($itemUser);
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
     * @Route("/account/settings", name="account_settings")
    */
    public function settingsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($user->getRoles()[0]->getRole() == "ROLE_ADMIN"){
            $response = $this->forward('Dashboard\CommonBundle\Controller\AccountController::settingsIndividualAction', array("request" => $request));
        }
        if($user->getRoles()[0]->getRole() == "ROLE_DEALER"){
            $response = $this->forward('Dashboard\CommonBundle\Controller\AccountController::settingsDealerAction', array("request" => $request));
        }
        if($user->getRoles()[0]->getRole() == "ROLE_SERVICE"){
            $response = $this->forward('Dashboard\CommonBundle\Controller\AccountController::settingsServiceAction', array("request" => $request));
        }
        
        return $response;
    }
    
    public function settingsIndividualAction(Request $request)
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
            
            
            return $this->redirectToRoute("account_settings");
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
            
            return $this->redirectToRoute("account_settings");
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
            
            return $this->redirectToRoute("account_settings");
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

    
    public function settingsDealerAction(Request $request)
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
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Email %mess% принадлежит другому пользователю.', array("%mess%" => $formMain['email']->getData())) . '</div>'
                );
                
                return $this->render('DashboardCommonBundle:User:account/dealer/settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
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
                $this->get('translator')->trans('<strong>Выполнено!</strong> Данные пользователя успешно сохранены.') . '</div>'
            );

            return $this->redirectToRoute("account_settings");
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
                $this->get('translator')->trans('<strong>Выполнено!</strong> Данные пользователя успешно сохранены.') . '</div>'
            );
            
            return $this->redirectToRoute("account_settings");
            
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
            
            return $this->redirectToRoute("account_settings");
            
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
            
            return $this->redirectToRoute("account_settings");
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
                    $this->get('translator')->trans('<strong>Выполнено!</strong> Пароль успешно обновлен.') . '</div>'
                );
            }
            else
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Не выполнено!</strong> Не удалось обновить пароль.') . '</div>'
                );
            }
            
            return $this->redirectToRoute("account_settings");
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
            
            
            return $this->redirectToRoute("account_settings");
        }
        
        return $this->render('DashboardCommonBundle:User:account/dealer/settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
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
    
    public function settingsServiceAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $fm = new Filesystem();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $jobCategories = $manager->getRepository("DashboardCommonBundle:JobCategory")->findAll();
        
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
            ->getForm();
        
        $dealerSalon = new DealerSalon();
        $formDealerSalon = $this->createForm(new DealerSalonType($this->getDoctrine()->getManager(), true), $dealerSalon);
                
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
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Email %mess% принадлежит другому пользователю.', array("%mess%" => $formMain['email']->getData())) . '</div>'
                );
                
                return $this->render('DashboardCommonBundle:User:account/service/settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
                                                                                    "formMain" => $formMain->createView(),
                                                                                    "formPassword" => $formPassword->createView(),
                                                                                    "formDealer" => $formDealer->createView(),
                                                                                    "formAutos" => $formAutos->createView(),
                                                                                    "formDealerSalon" => $formDealerSalon->createView(),
                                                                                    "jobCategories" => $jobCategories,
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
                $this->get('translator')->trans('<strong>Выполнено!</strong> Данные пользователя успешно сохранены.') . '</div>'
            );

            return $this->redirectToRoute("account_settings");
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
                $this->get('translator')->trans('<strong>Выполнено!</strong> Данные пользователя успешно сохранены.') . '</div>'
            );
            
            return $this->redirectToRoute("account_settings");
            
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
            
            return $this->redirectToRoute("account_settings");
            
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
            
            return $this->redirectToRoute("account_settings");
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
                    $this->get('translator')->trans('<strong>Выполнено!</strong> Пароль успешно обновлен.') . '</div>'
                );
            }
            else
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Не выполнено!</strong> Не удалось обновить пароль.') . '</div>'
                );
            }
            
            return $this->redirectToRoute("account_settings");
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
            
            
            return $this->redirectToRoute("account_settings");
        }
        
        return $this->render('DashboardCommonBundle:User:account/service/settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
                                                                                    "formMain" => $formMain->createView(),
                                                                                    "formPassword" => $formPassword->createView(),
                                                                                    "formAlert" => $formAlert->createView(),
                                                                                    "formDealer" => $formDealer->createView(),
                                                                                    "formAutos" => $formAutos->createView(),
                                                                                    "formDealerSalon" => $formDealerSalon->createView(),
                                                                                    "jobCategories" => $jobCategories,
                                                                                    "user" => $user,
                                                                                    "settings" => $settings,
                                                                                    "locale" => $locale,
                                                                                    "dealerImages" => $user->getDealerInfo()->getFotos(),
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    
    /**
     * @Route("/account/ajaxloadfotos", name="ajaxDealerLoadFotos")
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
     * @Route("/account/deleteimage/{data}", name="ajaxDealerDeleteFoto")
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
     * @Route("/account/deletelogo", name="ajaxDealerDeleteLogo")
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
     * @Route("/account/editsalon/{salonId}", name="account_editsalon")
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

                return $this->redirectToRoute("account_settings");
            }
            
            return $this->render('DashboardCommonBundle:User:account/dealer/salonEditForm.html.twig', array("formDealerSalon" => $formDealerSalon->createView(),"locale" => $locale,"salon" => $salon));
        }else{
            return $this->createNotFoundException();
        }
    }
    
    /**
     * @Route("/account/editservice/{salonId}", name="account_editservice")
    */
    
    public function editServiceAction($salonId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $jobCategories = $manager->getRepository("DashboardCommonBundle:JobCategory")->findAll();
        $fm = new Filesystem();
        $originalPhones = new ArrayCollection();
        
        $salon = $manager->getRepository("DashboardCommonBundle:DealerSalon")->findOneBy(array("id" => $salonId, "dealerInfo" => $user->getDealerinfo()));
        
        if($salon){
            
            if($salon->getPhones()){
                foreach($salon->getPhones() as $phone){
                    $originalPhones->add($phone);
                }
            }
            
            $formDealerSalon = $this->createForm(new DealerSalonType($manager, true), $salon);
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
                    $this->get('translator')->trans('<strong>Успешно!</strong> Информация об автосервисе сохранена.') . '</div>'
                );

                return $this->redirectToRoute("account_settings");
            }
            
            return $this->render('DashboardCommonBundle:User:account/service/salonEditForm.html.twig', array("formDealerSalon" => $formDealerSalon->createView(),"locale" => $locale,"salon" => $salon,"jobCategories" => $jobCategories));
        }else{
            return $this->createNotFoundException();
        }
    }
    
    
    /**
     * @Route("/account/deletesalon/{salonId}", name="account_deletesalon")
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
     * @Route("/account/deletesalonlogo/{salonId}", name="account_deletesalonLogo")
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
}

