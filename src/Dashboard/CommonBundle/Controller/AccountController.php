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

use Dashboard\CommonBundle\Form\Type\UserType;
use Dashboard\CommonBundle\Form\Type\UserPasswordType;


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
        
        return $this->render('DashboardCommonBundle:User:account/sidebar.html.twig', 
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
        
        return $this->render('DashboardCommonBundle:User:account/account.html.twig', array("user" => $user,
                                                                                   "countProducts" => count($allProducts),
                                                                                   "products" => $products,
                                                                                   "favProducts" => $favProducts,
                                                                                   "messages" => $messages,
                                                                                   "services" => $services,
                                                                                   "locale" => $locale,
                                                                                   "settings" => $settings));
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
        
        return $this->render('DashboardCommonBundle:User:account/settings.html.twig', array("avatar" => $user->getUserinfo()->getAvatar(),
                                                                                    "formMain" => $formMain->createView(),
                                                                                    "formPassword" => $formPassword->createView(),
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
        
        return $this->render('DashboardCommonBundle:User:account/products/correct.html.twig', array("products" => $products,  
                                                                                    "user" => $user, 
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
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 0 AND p.isConfirm = 1 AND p.isActive = 1 AND p.isBlocked = 0');

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
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isCorrect = 0 AND p.isConfirm = 0 AND p.isActive = 0 AND p.isBlocked = 0');

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
        
        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Product p WHERE p.user = ' . $user->getId() . ' AND p.isBlocked = 1');

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
}

