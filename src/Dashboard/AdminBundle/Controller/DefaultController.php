<?php

namespace Dashboard\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


use Dashboard\CommonBundle\Entity\Banner;
use Dashboard\CommonBundle\Entity\Page;
use Dashboard\CommonBundle\Entity\Role;
use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\UserInfo;
use Dashboard\CommonBundle\Entity\Textblock;
use Dashboard\CommonBundle\Entity\OrderStatus;

use Dashboard\AdminBundle\Form\Type\UserInfoType;
use Dashboard\AdminBundle\Form\Type\UserRateType;
use Dashboard\AdminBundle\Form\Type\TranslationType;
use Dashboard\AdminBundle\Form\Type\PageblockType;

class DefaultController extends Controller
{
    /**
     * @Route("/admin", name="admin_main")
     */
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();
        
        $users = $manager->getRepository("DashboardCommonBundle:User")->findAll();
        $product = $manager->getRepository("DashboardCommonBundle:Product")->findAll();
        $productNewNumber = 0;
        
        if($product)
        {
            $currentDate = date("Y-m-d");
            foreach($product as $productItem)
            {
                if($productItem->getDateAdded()->format("Y-m-d") == $currentDate)
                {
                    $productNewNumber++;
                }
            }
        }
        
        return $this->render('DashboardAdminBundle:Default:index.html.twig', array("users" => count($users), "products" => count($product),"productNewNumber" => $productNewNumber));
    }
    
    /**
     * @Route("/admin/clearcache", name="admin_clear_cache")
     */
    public function clearCacheAction()
    {
        $kernel = $this->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);
        $options = array('command' => 'cache:clear',"--env" => 'prod', '--no-warmup' => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
        
        return $this->redirectToRoute("admin_main");
    }
    
	
    public function getSidebarAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $settings = $this->getDoctrine()->getRepository("DashboardCommonBundle:Settings")->find(1);
        
        return $this->render('DashboardAdminBundle:Common:sidebar.html.twig', array("user" => $user, "settings" => $settings));
    }
    
    public function getHeaderAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $settings = $this->getDoctrine()->getRepository("DashboardCommonBundle:Settings")->find(1);
        $products = $manager->getRepository("DashboardCommonBundle:Product")->findBy(array("isConfirm" => "0", "isBlocked" => "0"));
        $complaints = $manager->getRepository("DashboardCommonBundle:Complaint")->findBy(array("status" => "0"));
        $messages = $manager->getRepository("DashboardCommonBundle:FormMessage")->findBy(array("isNew" => "1"));
        $users = $manager->getRepository("DashboardCommonBundle:User")->findBy(array("isConfirm" => 0, "isActive" => 1));
        
        $newUsers = new ArrayCollection($users);
        $dealers = $newUsers->filter(function(User $user){
            return ($user->getRoles()[0]->getRole() == "ROLE_DEALER" || $user->getRoles()[0]->getRole() == "ROLE_SERVICE");
        });
        
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Conversation c WHERE c.userOne = " . $user->getId() . " OR c.userTwo = " . $user->getId() . " ORDER BY c.id DESC");

        try{
            $conversations = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $conversations = 0;
        }
        
        $techNewMessages = 0;
        if(count($conversations) > 0){
            foreach($conversations as $conversation){
                if($conversation->getMessages()){
                    foreach($conversation->getMessages() as $message){
                        if($message->getIsNew()){
                            $techNewMessages++;
                        }
                    }
                }
            }
        }
        
        return $this->render('DashboardAdminBundle:Common:header.html.twig', array("user" => $user,
                                                                                   "newConfirmations" => count($products),
                                                                                   "newComplaints" => count($complaints),
                                                                                   "newMessages" => count($messages),
                                                                                   "techNewMessages" => $techNewMessages,
                                                                                   "settings" => $settings,
                                                                                   "users" => $dealers));
    }
    
    /**
     * @Route("/admin/roles/{roleId}", name="admin_roles", defaults={"roleId": 0} )
     */
    public function roleAction($roleId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        if($roleId)
        {
            $role = $manager->getRepository("DashboardCommonBundle:Role")->find($roleId);
            
            if($role)
            {
                if(count($role->getUsers()) > 0)
                {
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>????????????!</strong> ?? ???????????? ' . $role->getName() . '  ?????????????????? ' . count($role->getUsers())  . ' ??????????????????????????. ???????????????? ????????????????????.</div>')
                    );
                }
                else
                {
                    $manager->remove($role);
                    $manager->flush();
                    
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>??????????????!</strong> ???????????????????? ?? ???????????? ??????????????.</div>')
                    );
                }
            }
            
            return $this->redirectToRoute('admin_roles');
        }
        
        if($request->request->get('role'))
        {
            foreach($request->request->get('role') as $roleId)
            {
                $role = $manager->getRepository("DashboardCommonBundle:Role")->find($roleId);
            
                if($role)
                {
                    if(count($role->getUsers()) > 0)
                    {
                        $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>????????????!</strong> ?? ???????????? ' . $role->getName() . '  ?????????????????? ' . count($role->getUsers())  . ' ??????????????????????????. ???????????????? ????????????????????.</div>')
                        );
                    }
                    else
                    {
                        $manager->remove($role);
                        $manager->flush();

                        $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>??????????????!</strong> ???????????????????? ?? ???????????? ??????????????.</div>')
                        );
                    }
                }
            }
            
            return $this->redirectToRoute('admin_roles');
        }
        
        $roles = $manager->getRepository("DashboardCommonBundle:Role")->findAll();
        
        return $this->render('DashboardAdminBundle:Settings:role.html.twig', array("roles" => $roles));
    }
    
    /**
     * @Route("/admin/role/edit/{roleId}", name="admin_role_edit", defaults={"roleId": 0})
     */
    public function editRoleAction($roleId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $role = ($roleId) ? $manager->getRepository("DashboardCommonBundle:Role")->find($roleId) : new Role();
        
        $roleForm = $this->get('form.factory')->createNamedBuilder('role', 'form', $role)
                ->add('title', TextType::class, array('required' => true, 'label' => '?????????????????? ????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????? ???????????? ??????????????????????????')))
                ->add('filterTitle', TextType::class, array('required' => false, 'label' => '???????????????? ?????? ??????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????? ?????? ??????????????')))
                ->add('name', TextType::class, array('required' => true, 'label' => '???????????????? ???????????? ??????????????????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????? ???????????? ??????????????????????????')))
                ->add('advertNumber', TextType::class, array('required' => false, 'label' => '???????????????????????? ???????????????????? ????????????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????????????? ???????????????????? ????????????????????')))
                ->add('advertFotoNumber', TextType::class, array('required' => false, 'label' => '???????????????????????? ???????????????????? ???????? ?????? ???????????? ????????????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????????????? ???????????????????? ???????? ?????? ???????????? ????????????????????')))
                ->add('invoiceText', TextareaType::class, array('required' => false, 'label' => '?????????? ?????? ????????-????????????', 'attr' => array('class' => 'form-control tinyeditor','placeholder' => '?????????? ?????? ????????-????????????')))
                ->add('services', 'entity', array('class' => 'DashboardCommonBundle:Service',
                            'choice_label' => 'title',
                            'required' => false, 
                            'multiple' => true,
                            'expanded' => true,
                            'label' => '???????????? ?????? ?????????????????????????? ????????????:', 'attr' => array('class' => 'form-control')))
                ->add('payments', 'entity', array('class' => 'DashboardCommonBundle:Payment',
                            'choice_label' => 'title',
                            'required' => false, 
                            'multiple' => true,
                            'expanded' => true,
                            'label' => '?????????????????? ?????????????? ?????? ?????????????????????????? ????????????:', 'attr' => array('class' => 'form-control')))
                ->add('servicePacks', 'entity', array('class' => 'DashboardCommonBundle:Pack',
                            'choice_label' => 'label',
                            'required' => false, 
                            'multiple' => true,
                            'expanded' => true,
                            'label' => '???????????? ?????????? ?????? ?????????????????????????? ????????????:', 'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => '??????????????????', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
        $roleForm->handleRequest($request);
        
        if($roleForm->isSubmitted() && $roleForm->isValid())
        {
            if(!$roleId)
            {
                $helpers = $this->get('app.helpers');
                $role->setRole("ROLE_" . $helpers->translit($role->getName()));
            }
            
            $manager->persist($role);
            $manager->flush();
            
            $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>??????????????!</strong> ???????????????????? ?? ???????????? ??????????????????.</div>')
                    );
            
            return $this->redirectToRoute('admin_role_edit', array("roleId" => $role->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Settings:roleedit.html.twig', array("roleForm" => $roleForm->createView()));
    }
    /**
     * @Route("/admin/deleteuser/{userId}", name="admin_delete_user", defaults={"userId": 0} )
     */
    public function deleteUserAction($userId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $sessionUser = $this->get('security.context')->getToken()->getUser();
        
        if($userId)
        {
            $user = $manager->getRepository("DashboardCommonBundle:User")->find($userId);
            
            if($user)
            {
                if($user->getId() == $sessionUser->getId())
                {
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>????????????!</strong> ?????? ?????????????? ?????????????? ???????????? ????????????????????????????, ?????????????? ???? ?????????? ???????? ??????????????.</div>')
                    );
                    
                    return $this->redirectToRoute("admin_users");
                }
                $this->deleteUser($user, $request);
                
                return $this->redirectToRoute("admin_users");
            }
            else
                $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>????????????!</strong> ???????????? ???????????????????????? ???? ????????????????????.</div>')
                    );
        }
        
        return $this->redirectToRoute("admin_users");
    }
    /**
     * @Route("/admin/users/{page}", name="admin_users", defaults={"page": 0} )
     */
    public function userAction($page, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $users =  $manager->getRepository("DashboardCommonBundle:User")->findAll();
        $pagination = 0;
        
        $user = new User();
        
        $userForm = $this->get('form.factory')->createNamedBuilder('user', 'form', $user)
                ->add('id', TextType::class, array('required' => false,'mapped' => false,'label' => 'ID ????????????????????????', 'attr' => array('class' => 'form-control','placeholder' => 'ID ????????????????????????')))
                ->add('email', TextType::class, array('required' => false,'mapped' => false,'label' => 'Email', 'attr' => array('class' => 'form-control','placeholder' => 'Email')))
                ->add('firstname', TextType::class, array('required' => false,'label' => '??????', 'mapped' => false, 'attr' => array('class' => 'form-control','placeholder' => '??????')))
                ->add('lastname', TextType::class, array('required' => false, 'mapped' => false, 'label' => '??????????????','attr' => array('class' => 'form-control','placeholder' => '??????????????')))
                ->add('save', ButtonType::class, array('label' => '??????????', 'attr' => array('class' => 'btn btn-sm btn-primary m-r-5')))->getForm();
        
        $userForm->handleRequest($request);
        
        if($userForm->isSubmitted() && $userForm->isValid())
        {
            if($userForm['id']->getData() || $userForm['email']->getData() || $userForm['firstname']->getData() || $userForm['lastname']->getData())
            {
                $sql = "SELECT u FROM DashboardCommonBundle:User u LEFT JOIN u.userinfo ui WHERE u.id > 0";
                
                if($userForm['id']->getData())
                {
                    $sql .= " AND u.id = " . $userForm['id']->getData();
                }

                if($userForm['email']->getData())
                {
                    $sql .= " AND u.email = '" . $userForm['email']->getData() . "'";
                }

                if($userForm['firstname']->getData())
                {
                    $sql .= " AND ui.firstname = '" . $userForm['firstname']->getData() . "'";
                }

                if($userForm['lastname']->getData())
                {
                    $sql .= " AND ui.lastname = '" . $userForm['lastname']->getData() . "'";
                }
                
                $query = $manager->createQuery($sql);
                
                try{
                    $users = $query->getResult();
                }
                catch(\Doctrine\ORM\NoResultException $e) {
                    $users = 0;
                }
            }
            else
            {
                return $this->redirectToRoute("admin_users");
            }
        }
        else
        {
            $query = $manager->createQuery("SELECT u FROM DashboardCommonBundle:User u" );
        
            try{
                $users = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $users = 0;
            }

            $totalUsers = count($users);

            $query = $manager->createQuery("SELECT u FROM DashboardCommonBundle:User u" )->setFirstResult((($page > 0) ? ($page - 1) : 0) * 100)->setMaxResults(100);

            try{
                $users = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $users = 0;
            }

            $helper = $this->get("app.helpers");
            $pagination = $helper->paginator(($page > 0) ? (int)$page : 1, $totalUsers, 100, "/admin/users");
        }
        
        $roles = $manager->getRepository("DashboardCommonBundle:Role")->findAll();

        return $this->render('DashboardAdminBundle:Settings:users.html.twig', array("users" => $users,
                                                                                    "roles" => $roles,
                                                                                    "pagination" => $pagination,
                                                                                    "userForm" => $userForm->createView()));
    }
    
    /**
     * @Route("/admin/user/edit/{userId}", name="admin_user_edit", defaults={"userId": 0} )
     */
    public function editUserAction($userId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $originalRates = new ArrayCollection;  
        $originalRatesItems = new ArrayCollection;
        $user = ($userId) ? $manager->getRepository("DashboardCommonBundle:User")->find($userId) : new User();

        $roles = ($userId) ? $user->getRoles() : 0;
        
        $userinfo = ($user) ? $user->getUserinfo() : new UserInfo();
        
        $originalEmail = $user->getEmail();  
        
        if($user->getRates()){
            foreach($user->getRates() as $rate){
                $originalRates->add($rate);
                if($rate->getItems()){
                    foreach($rate->getItems() as $item){
                        $originalRatesItems->add($item);
                    }
                }
            }
        }
        
        $userForm = $this->get('form.factory')->createNamedBuilder('user', 'form', $user)
            ->add('email', EmailType::class, array('required' => false, 'label' => '????. ??????????', 'attr' => array('class' => 'form-control')))
            ->add('password', TextType::class, array('required' => false, 'mapped' => false,'label' => '?????????? ????????????', 'attr' => array('class' => 'form-control', 'plceholder' => '?????????? ????????????')))
            ->add('isConfirm', CheckboxType::class, array('required' => false, 'label' => '?????????????? ??????????????????????', 'attr' => array('class' => 'form-control')))
            ->add('isActive', CheckboxType::class, array('required' => false, 'label' => '?????????????? ??????????????', 'attr' => array('class' => 'form-control')))
            ->add('advertNumber', TextType::class, array('required' => false, 'label' => '???????????????????? ???????????????????????????? ????????????????????', 'attr' => array('class' => 'form-control')))
            ->add('userinfo', new UserInfoType($manager, $userinfo), array('data_class' => 'Dashboard\CommonBundle\Entity\UserInfo'))
            ->add('rates', CollectionType::class, array('entry_type' => new UserRateType(), 'required' => false,'allow_add' => true,'allow_delete' => true, 'by_reference' => false))
            ->add('roles', 'entity', array('class' => 'DashboardCommonBundle:Role', 
                  'choice_label' => 'title', 'label' => '???????????? ??????????????????????????', 'data' => ($roles) ? $roles[0] : 0,'mapped' => false,'attr' => array('class' => 'form-control')))
            ->add('save', ButtonType::class, array('label' => '??????????????????', 'attr' => array('class' => 'btn btn-success pull-right')))->getForm();
        
        $userForm->handleRequest($request);
        
        if($userForm->isSubmitted() && $userForm->isValid())
        {
            if($originalRates){
                foreach($originalRates as $rate){
                    if(false === $user->getRates()->contains($rate)){
                        $rate->setUser(null);
                        if($rate->getItems()){
                            foreach($rate->getItems() as $item){
                                $item->setUserrate(null);
                                $manager->remove($item);
                            }
                        }
                        $manager->remove($rate);
                    }
                    
                    if($originalRatesItems){
                        foreach($originalRatesItems as $item){
                            if(false === $rate->getItems()->contains($item)){
                                $item->setUserrate(null);
                                $manager->remove($item);
                            }
                        }
                    }
                }
            }
            
            if($user->getRates()){
                foreach($user->getRates() as $rate){
                    if(!$rate->getAdvertNumber()){
                        $rate->setAdvertNumber($rate->getRate()->getAdvertNumber());
                    }
                    $rate->setUser($user);
                    if(count($rate->getItems()) > 0){
                        foreach($rate->getItems() as $item){
                            $item->setUserrate($rate);
                            $manager->persist($item);
                        }
                    }else{
                        $services = $rate->getRate()->getServices();
                        if(count($services) > 0){
                            foreach($services as $service){
                                $rateItem = new \Dashboard\CommonBundle\Entity\UserRateItem();
                                $rateItem->setService($service);
                                $rateItem->setCount($service->getValue());
                                $rateItem->setUserrate($rate);
                                $manager->persist($rateItem);
                            }
                        }
                    }
                    $manager->persist($rate);
                }
            }
            
            $avatar = $userForm['userinfo']['avatarNew']->getData();
            $oldAvatar = $userForm['userinfo']['avatar']->getData();
            
            if($originalEmail != $userForm['email']->getData())
            {
                $emailIs = $manager->getRepository("DashboardCommonBundle:User")->findByEmail($userForm['email']->getData());
            
                if($emailIs)
                {
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>????????????!</strong> ???????????????????????? ?? ?????????? email ?????? ????????????????????.</div>')
                     );

                    return $this->redirectToRoute('admin_user_edit', array("userId" => $user->getId()));
                }
            }
            
            
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
            
            if($roles)
            {
                foreach($roles as $role)
                {
                    $user->removeRole($role);
                }
            }
            
            $newRole = $this->getDoctrine()->getRepository("DashboardCommonBundle:Role")->find($userForm['roles']->getData());
            $user->addRole($newRole);
            
            if(!$userId)
            {
                $user->setIsConfirm(1);
                $user->setIsActive(1);
                $user->setAlerts(1);
            }
            
            if($userId)
            {
                $confirmation = $manager->getRepository("DashboardCommonBundle:Register")->findOneByUserId($userId);
                
                if($confirmation)
                {
                    $manager->remove($confirmation);
                }
            }
            
            $user->setUsername($user->getEmail());
            
            if($userForm['password']->getData())
            {
                $password = $this->get('security.password_encoder')->encodePassword($user, $userForm['password']->getData());
                $user->setPassword($password);
            }
            
            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>??????????????!</strong> ???????????? ?? ???????????????????????? ??????????????????.</div>')
            );
            
            return $this->redirectToRoute('admin_user_edit', array("userId" => $user->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Settings:useredit.html.twig', array("userForm" => $userForm->createView(),
                                                                                       "user" => $user));
    }
    
    /**
     * @Route("/admin/usersettings", name="admin_user_settings" )
     */
    public function userSettingsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->find(1);
        $roles = $manager->getRepository("DashboardCommonBundle:Role")->findAll();
        
        $choices = array();
        
        foreach($roles as $role)
        {
            $choices[$role->getId()] = $role->getName();
        }
        
        $settingsForm = $this->get('form.factory')->createNamedBuilder('settings', 'form', $settings)
            ->add('userAdvertLimitText', TextareaType::class, array('required' => false, 'label' => '?????????? ?????? ???????????????????? ???????????? ????????????????????', 'attr' => array('class' => 'form-control tinyeditor')))   
            ->add('userDefaultGroup', ChoiceType::class, array('choices' => $choices, 'data' => $settings->getUserDefaultGroup(), 'label' => '???????????? ?????????????????????????? ???? ??????????????????', 
                  'attr' => array('class' => 'form-control')))
            ->add('save', ButtonType::class, array('label' => '??????????????????', 'attr' => array('class' => 'btn btn-success pull-right')))->getForm();
        
        $settingsForm->handleRequest($request);
        
        if($settingsForm->isSubmitted() && $settingsForm->isValid())
        {            
            $manager->persist($settings);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>??????????????!</strong> ?????????????????? ??????????????????.</div>')
            );
            
            return $this->redirectToRoute('admin_user_settings');
        }
        
        return $this->render('DashboardAdminBundle:Settings:usersettings.html.twig', array("settingsForm" => $settingsForm->createView()));
    }
    
    /**
     * @Route("/admin/pages", name="admin_pages")
     */
    
    public function pageAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $query = $manager->createQuery('SELECT l FROM Dashboard\CommonBundle\Entity\Locale l INNER JOIN Dashboard\CommonBundle\Entity\Page p WHERE p.isUserpage = 0' );
        $locales = $query->getResult();
        
        return $this->render('DashboardAdminBundle:Default:pages.html.twig', array("locales" => $locales));
    }
    
    /**
     * @Route("/admin/userpages/{pageId}", name="admin_userpages", defaults={"pageId": 0})
     */
    public function userpageAction($pageId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        if($pageId)
        {
            $page = $manager->getRepository("DashboardCommonBundle:Page")->find($pageId);
            
            if($page)
            {
                if($page->getIsUserpage())
                {
                    $manager->remove($page);
                    $manager->flush();
                    
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>??????????????!</strong> ???????????????????? ?? ???????????????? ??????????????.</div>')
                    );
                }
                else 
                {
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>????????????!</strong> ?????? ?????????????????? ????????????????, ???? ???????????? ??????????????.</div>')
                    );
                }
            }
            
            return $this->redirectToRoute('admin_userpages');
        }
        
        if($request->request->get('page'))
        {
            foreach($request->request->get('page') as $pageId)
            {
                $page = $manager->getRepository("DashboardCommonBundle:Page")->find($pageId);
            
                if($page)
                {
                    if($page->getIsUserpage())
                    {
                        $manager->remove($page);
                        $manager->flush();
                    }
                }
            }
            
            $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>??????????????!</strong> ???????????????????? ?? ?????????????????? ??????????????.</div>')
                        );
            
            return $this->redirectToRoute('admin_userpages');
        }
        
        if($request->request->get('sortorder'))
        {
            foreach($request->request->get('sortorder') as $key => $value)
            {
                $page = $manager->getRepository("DashboardCommonBundle:Page")->find($key);
                if($page)
                {
                    $page->setSortorder($value);
                    $manager->persist($page);
                }
            }
            
            $manager->flush();
            
            $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>??????????????!</strong> ???????????????????? ?? ?????????????????? ??????????????????.</div>')
            );
        }
        
        $query = $manager->createQuery('SELECT l FROM Dashboard\CommonBundle\Entity\Locale l LEFT JOIN Dashboard\CommonBundle\Entity\Page p WHERE p.isUserpage <> 0 ORDER BY p.sortorder' );
        
        $locales = $query->getResult();
        
        return $this->render('DashboardAdminBundle:Default:userpages.html.twig', array("locales" => $locales));
    }
    
    /**
     * @Route("/admin/pageedit/{pageId}", name="admin_pages_edit", defaults={"pageId": 0})
     */
    public function editPageAction($pageId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalBlocks = new ArrayCollection();
        
        $page = ($pageId) ? $manager->getRepository("DashboardCommonBundle:Page")->find($pageId) : new Page();
        $isUserpage = ($pageId) ? $page->getIsUserpage() : 0;
        $route = ($pageId) ? $page->getRoute() : 0;
        
        if($pageId && $page)
        {
            foreach ($page->getBlocks() as $block) {
                $originalBlocks->add($block);
            }
        }
        
        $pageForm = $this->get('form.factory')->createNamedBuilder('page', 'form', $page)
                ->add('title', TextType::class, array('required' => true, 'label' => '???????????????? ????????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????? ????????????????')))
                ->add('route', TextType::class, array('required' => true, 'label' => '????????????????(SEO)', 'attr' => array('class' => 'form-control','placeholder' => '????????????????(SEO)')))
                ->add('isFooterMenu', ChoiceType::class, array('choices' => array("0" => "??????","1" =>"????"),'label' => '???????????????????? ?? ???????????? ????????','empty_value' => false,'expanded' => true))
                ->add('footerMenuSection', ChoiceType::class, array('choices' => array("0" => "??????","1" =>"????????????????????","2" => "????????????"),'label' => '???????????? ?????? ???????????????? ?? ???????????? ????????', 'attr' => array('class' => 'form-control')))
                ->add('text', TextareaType::class, array('required' => false, 'label' => '????????????????????', 'attr' => array('class' => 'form-control tinyeditor','placeholder' => '????????????????????')))
                 ->add('blocks', 'collection', array('type' => new PageblockType($manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false,
                                               'attr' => array('class' => 'page_blocks')))
                ->add('metaTagTitle', TextareaType::class, array('required' => false, 'label' => '????????-?????? Title', 'attr' => array('class' => 'form-control','placeholder' => '????????-?????? Title')))
                ->add('metaTagDescription', TextareaType::class, array('required' => false, 'label' => '????????-?????? Description', 'attr' => array('class' => 'form-control','placeholder' => '????????-?????? Description')))
                ->add('metaTagAuthor', TextareaType::class, array('required' => false, 'label' => '????????-?????? Author', 'attr' => array('class' => 'form-control','placeholder' => '????????-?????? Author')))
                ->add('metaTagRobots', TextareaType::class, array('required' => false, 'label' => '????????-?????? Robots', 'attr' => array('class' => 'form-control','placeholder' => '????????-?????? Robots')))
                ->add('metaTagKeywords', TextareaType::class, array('required' => false, 'label' => '????????-?????? Keywords', 'attr' => array('class' => 'form-control','placeholder' => '????????-?????? Keywords')))
                ->add('locale', 'entity', array('class' => 'DashboardCommonBundle:Locale',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => true, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('l')->orderBy('l.sortorder', 'ASC');},
                            'label' => '??????????????????????:', 'attr' => array('class' => 'hidden-input form-control','id' => 'region','placeholder' => '??????????????????????:')))
                ->add('save', ButtonType::class, array('label' => '??????????????????', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
        $pageForm->handleRequest($request);
        
        if($pageForm->isSubmitted() && $pageForm->isValid())
        {
            
            if(!$pageId)
            {
                if($pageForm['route']->getData())
                {
                    $page->setRoute($pageForm['route']->getData());
                }
                else {
                    $helpers = $this->get('app.helpers');
                    $page->setRoute($helpers->translit($page->getTitle()));
                }
                $page->setIsUserpage(1);
                $page->setSortorder(count($manager->getRepository("DashboardCommonBundle:Page")->findAll()) + 1);
            }
            
            if($pageId && !$isUserpage)
            {
                $page->setRoute($route);
            }
            
            if($originalBlocks)
            {
                foreach ($originalBlocks as $block) 
                {
                    if (false === $page->getBlocks()->contains($block)) 
                    {
                        $block->setPage(null);
                        $manager->remove($block);
                    }
                }
            }  
            
            if($page->getBlocks())
            {
                foreach($page->getBlocks() as $block)
                {
                    $block->setPage($page);
                    $manager->persist($block);
                }
            }  
            
            $manager->persist($page);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>??????????????!</strong> ???????????????????? ?? ???????????????? ??????????????????.</div>')
            );

            return $this->redirectToRoute('admin_pages_edit', array('pageId' => $page->getId()));
            
        }
        
        return $this->render('DashboardAdminBundle:Default:pageedit.html.twig', array("pageForm" => $pageForm->createView(),
                                                                                      "isUserpage" => $isUserpage,
                                                                                      "pageId" => $pageId));
    }
    
    /**
     * @Route("/admin/orderstatus", name="admin_settings_orderstatus")
     */
    public function orderStatusAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $statuses = $manager->getRepository("DashboardCommonBundle:OrderStatus")->findAll();
        
        if($request->request->get('statusIds'))
        {
            foreach($request->request->get('statusIds') as $id)
            {
                $status = $manager->getRepository("DashboardCommonBundle:OrderStatus")->find($id);
                if($status)
                {
                    $orders = $manager->getRepository("DashboardCommonBundle:ProductOrder")->findByStatus($id);
                    if(count($orders) > 0)
                    {
                        $this->addFlash(
                            'notice_region',
                            $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>????????????!</strong> ???????????? ?????????????? ???????? ????????????. ?? ?????? ?????????????? ' . count($orders)  . ' ??????????????.</div>')
                        );
                    }
                    else {
                        
                        if($status->getTranslations())
                        {
                            foreach($status->getTranslations() as $translation)
                            {
                                $translation->setOrderStatus(null);
                                $manager->remove($translation);
                            }
                        }
                        
                        $manager->remove($status);
                        $manager->flush();
                        
                        $this->addFlash(
                            'notice_region',
                            $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>??????????????!</strong> ???????????? ????????????.</div>')
                        );
                    }
                }
            }
            return $this->redirectToRoute("admin_settings_orderstatus");
        }

        return $this->render('DashboardAdminBundle:Settings:orderstatus.html.twig', array("statuses" => $statuses));
    }
    
    /**
     * @Route("/admin/edit/orderstatus/{statusId}", name="admin_settings_orderstatus_edit", defaults={"statusId" : 0})
     */
    public function orderStatusEditAction($statusId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalTranslations = new ArrayCollection();
        
        $status = ($statusId) ? $manager->getRepository("DashboardCommonBundle:OrderStatus")->find($statusId) : new OrderStatus();
        
        if($statusId)
        {
            foreach ($status->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
        }
        
        $statusForm = $this->get('form.factory')->createNamedBuilder('status', 'form', $status)
                ->add('name', TextType::class, array('required' => true, 'label' => '???????????? ????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????? ????????????')))
                ->add('color', TextType::class, array('required' => false, 'label' => '???????? ?????? ??????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????? ?????? ??????????????')))
                ->add('fontColor', TextType::class, array('required' => false, 'label' => '???????? ???????????? ?????? ??????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????? ???????????? ?????? ??????????????')))
                ->add('translations', 'collection', array('type' => new TranslationType($manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
                ->add('save', ButtonType::class, array('label' => '??????????????????', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
        $statusForm->handleRequest($request);
        
        if($statusForm->isSubmitted() && $statusForm->isValid())
        {
            if($originalTranslations)
            {
                foreach ($originalTranslations as $item) 
                {
                    if (false === $status->getTranslations()->contains($item)) 
                    {
                        $item->setOrderStatus(null);
                        $manager->remove($item);
                    }
                }
            }

            if($status->getTranslations())
            {
                foreach($status->getTranslations() as $item)
                {
                    $item->setOrderStatus($status);
                    $manager->persist($item);
                }
            }
                
            $manager->persist($status);
            $manager->flush();
                
            $this->addFlash(
                'notice_region',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>??????????????!</strong> ?????????? ???????????? ???????????? ????????????????.</div>')
            );
            
            return $this->redirectToRoute("admin_settings_orderstatus");
        }
        
        return $this->render('DashboardAdminBundle:Settings:orderstatusedit.html.twig', array("statusForm" => $statusForm->createView()));
    }

    /**
     * @Route("/admin/banner/{bannerId}", name="admin_settings_banners", defaults={"bannerId" : 0})
     */
    public function bannerAction($bannerId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        
        if($bannerId)
        {
            $banner = $manager->getRepository("DashboardCommonBundle:Banner")->find($bannerId);
            
            if($banner)
            {
                if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/banners/' . $banner->getImage()))
                {
                    $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/banners/' . $banner->getImage());
                }
                
                $manager->remove($banner);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>??????????????!</strong> ???????????? ?????????????? ????????????.</div>')
                );
            }
            
            return $this->redirectToRoute('admin_settings_banners');
        }
        
        if($request->request->get('banner'))
        {
            foreach($request->request->get('banner') as $bannerId)
            {
                $banner = $manager->getRepository("DashboardCommonBundle:Banner")->find($bannerId);
            
                if($banner)
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/banners/' . $banner->getImage()))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/banners/' . $banner->getImage());
                    }

                    $manager->remove($banner);
                    $manager->flush();
                }
            }
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>??????????????!</strong> ???????????????????? ?????????????? ?????????????? ??????????????.</div>')
            );
            
            return $this->redirectToRoute('admin_settings_banners');
        }
        
        $banners = $manager->getRepository("DashboardCommonBundle:Banner")->findAll();
        
        return $this->render('DashboardAdminBundle:Settings:banner.html.twig', array("banners" => $banners));
    }
    
    /**
     * @Route("/admin/banneredit/{bannerId}", name="admin_settings_banners_edit", defaults={"bannerId" : 0})
     */
    
    public function editBannerAction($bannerId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $extentions = array("jpg", "jpeg", "png", "gif", "JPG", "JPEG", "PNG", "GIF");
        
        $banner = ($bannerId) ? $manager->getRepository("DashboardCommonBundle:Banner")->find($bannerId) : new Banner();
        
        $bannerForm = $this->get('form.factory')->createNamedBuilder('banner', 'form', $banner)
                ->add('title', TextType::class, array('required' => true, 'label' => '????????????????', 'attr' => array('class' => 'form-control','placeholder' => '????????????????')))
                ->add('imageNew', FileType::class, array('required' => false, 'label' => '','label' => '??????????????????????','mapped' => false, 'attr' => array('class' => 'form-control')))
                ->add('image', HiddenType::class, array('required' => false, 'label' => ''))
                ->add('link', TextType::class, array('required' => false, 'label' => '????????????', 'attr' => array('class' => 'form-control','placeholder' => '????????????')))
                ->add('code', TextareaType::class, array('required' => false, 'label' => '?????? ???????????? ????????????????', 'attr' => array('class' => 'form-control','placeholder' => '?????? ???????????? ????????????????')))
                ->add('position', ChoiceType::class, array('choices' => 
                    array('toppage' => 'A1',
                          'rightcolumn' => 'B1',
                          'bottompage' => 'C1',
                          'centerpage' => 'C2',
                          'slider' => 'A6',
                          'defaulttop' => 'A1 ???? ??????????????????',
                          'defaultright' => 'B1 ???? ??????????????????',
                          'defaultbottom' => 'C1 ???? ??????????????????',
                          'defaultcenter' => 'C2 ???? ??????????????????',
                          'defaultslider' => 'A6 ???? ??????????????????',), 
                    'data' => $banner->getPosition(), 'label' => '?????????????? ??????????????', 'attr' => array('class' => 'form-control')))
                ->add('dateFrom', DateType::class, array('required' => false, 'label' => '???????? ???????????? ??????????????????????', 'attr' => array('class' => 'form-control')))
                ->add('dateTo', DateType::class, array('required' => false, 'label' => '???????? ?????????????????? ??????????????????????', 'attr' => array('class' => 'form-control')))
                ->add('categories', 'entity', array('class' => 'DashboardCommonBundle:Category', 
                                            'choice_label' => 'title',
                                            'multiple' => true,
                                            'expanded' => true,
                                            'label' => '?????????????????????? ?????????????????? (?????? ???????????? ???????????????????? ?????????????? Ctrl)',
                                            'required' => false,
                                            'attr' => array('class' => 'form-control')))
                ->add('pages', 'entity', array('class' => 'DashboardCommonBundle:Page', 
                                            'choice_label' => 'title',
                                            'multiple' => true,
                                            'expanded' => true,
                                            'label' => '????????????????, ???? ?????????????? ???????????????????? ????????????',
                                            'required' => false,
                                            'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => '??????????????????', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
        $bannerForm->handleRequest($request);

        if($bannerForm->isSubmitted() && $bannerForm->isValid())
        {
            $image = $bannerForm['imageNew']->getData();
            $oldImage = $bannerForm['image']->getData();

            if($image)
            {
                $extention = $image->getClientOriginalExtension();
                
                if(!in_array($extention, $extentions))
                {
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>????????????!</strong> ???????????? ???????????????????????? ?????????????? ???? ????????????????????????????. ???????????????????????????? ??????????????: jpg, jpeg, png, gif.</div>')
                    );
                    
                    return $this->render('DashboardAdminBundle:Settings:banneredit.html.twig', array("banner" => $banner,"bannerForm" => $bannerForm->createView()));
                }
                
                if($oldImage)
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/banners/' .$oldImage ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/banners/' .$oldImage );
                    }
                }
                
                $localImageName = rand(1, 99999999).'.'.$extention;
                $image->move('bundles/images/banners',$localImageName);
                $banner->setImage($localImageName);
            }
            
            $banner->setDateAdded(new \DateTime("now"));
            
            $manager->persist($banner);
            $manager->flush();

            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>??????????????????!</strong> ???????????? ?????????????? ????????????????.</div>')
            );
            
            return $this->redirectToRoute('admin_settings_banners_edit', array('bannerId' => $banner->getId()));
        }
        return $this->render('DashboardAdminBundle:Settings:banneredit.html.twig', array("banner" => $banner,"bannerForm" => $bannerForm->createView()));
    }
    
    /**
     * @Route("/admin/settings", name="admin_settings")
     */
    public function settingsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        
        $locales = $manager->getRepository("DashboardCommonBundle:Locale")->findBy(array(), array("sortorder" => "ASC"));
        $defaultLocale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("isDefault" => true));
        
        $orderStatuses = $manager->getRepository("DashboardCommonBundle:OrderStatus")->findAll();
        
        $statuses = array();
        
        if($orderStatuses)
        {
            foreach($orderStatuses as $key => $value)
            {
                $statuses[$value->getId()] = $value->getName();
            }
        }
        
        $settingFroms = array();
        foreach($locales as $locale)
        {
            $settingsForms[$locale->getCode()] = $this->get('form.factory')->createNamedBuilder('settings_' . $locale->getCode(), 'form', $locale->getSettings())
            ->add('siteName', TextType::class, array('required' => false, 'label' => '???????????????? ??????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????? ??????????')))    
            ->add('siteDescription', TextareaType::class, array('required' => false, 'label' => '???????????????? ??????????', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('adminEmail', TextType::class, array('required' => false, 'label' => 'Email ????????????????????????????', 'attr' => array('class' => 'form-control','placeholder' => 'Email ????????????????????????????')))
            ->add('isModerate', CheckboxType::class, array('required' => false, 'label' => '???????????????? ?????????????????? ????????????????????'))
            ->add('isShowCaptcha', CheckboxType::class, array('required' => false, 'label' => '???????????????? Google Recaptcha'))
            ->add('isShowType', CheckboxType::class, array('required' => false, 'label' => '???????????????????? ???????? ???????????? ???????? ?????? ???????????????????? ?????? ???????????????????????????? ????????????????????'))
            ->add('mainPageDefaultCategory', 'entity', array('class' => 'DashboardCommonBundle:Category',
                            'choice_label' => 'title',
                            'empty_data' => null,
                            'required' => false, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('c')->where('c.parent IS NULL');},
                            'label' => '?????????????????? ?????? ?????????????? ???? ??????????????????:', 'attr' => array('class' => 'form-control','placeholder' => '?????????????????? ?????? ?????????????? ???? ??????????????????:')))  
            ->add('userMessagesNumber', TextType::class, array('required' => false, 'label' => '???????????????????? ?????????????????? ?? ??????????????','attr' => array('class' => 'form-control')))
            ->add('categoryProductNumber', TextType::class, array('required' => false, 'label' => '???????????????????? ???????????????????? ???? ???????????????? ??????????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????????? ???????????????????? ???? ???????????????? ??????????????????')))
            ->add('categoryPanelItemsNumber', TextType::class, array('required' => false, 'label' => '???????????????????? ?????????????????? ???? ???????????? "???????????????? ?????? ??????????"', 'attr' => array('class' => 'form-control','placeholder' => '???????????????????? ?????????????????? ???? ???????????? "???????????????? ?????? ??????????"')))
            ->add('mainpageAdvertsNumber', TextType::class, array('required' => false, 'label' => '???????????????????? ???????????????????? ???? ???????????????? ??????????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????????? ???????????????????? ???? ?????????????? ????????????????')))
            ->add('catpagePremiumNumber', TextType::class, array('required' => false, 'label' => '???????????????????? ???????????????????? ???? ?????????? "??????????????????????????????"', 'attr' => array('class' => 'form-control','placeholder' => '???????????????????? ???????????????????? ???? ?????????? "??????????????????????????????"')))
            ->add('advertDaysShowNumber', TextType::class, array('required' => false, 'label' => '???????????????????? ???????? ?????? ???????????????????? ????????????????????', 'attr' => array('class' => 'form-control','placeholder' => '???????????????????? ???????? ?????? ???????????????????? ????????????????????')))
            ->add('premiumAdvPrice', TextType::class, array('required' => false, 'label' => '???????????? ??????', 'attr' => array('class' => 'form-control','placeholder' => '???????????? ??????')))
            ->add('siteLogoNew', FileType::class, array('required' => false, 'mapped' => false,'label' => '?????????????? ??????????', 'attr' => array('class' => 'form-control','placeholder' => '?????????????? ??????????')))
            ->add('siteLogo', HiddenType::class, array('required' => false))
            ->add('watermarkNew', FileType::class, array('required' => false, 'mapped' => false,'label' => '?????????????? ????????', 'attr' => array('class' => 'form-control','placeholder' => '?????????????? ????????')))
            ->add('watermark', HiddenType::class, array('required' => false))
            ->add('dafaultOrderStatus', 'entity', array('class' => 'DashboardCommonBundle:OrderStatus',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => false, 
                            'label' => '???????????? ?????????????? ?????? ????????????????????:', 'attr' => array('class' => 'form-control')))                        
            ->add('copyright', TextType::class, array('required' => false, 'label' => '????????????????', 'attr' => array('class' => 'form-control','placeholder' => '????????????????'))) 
            ->add('serviceTabText', TextareaType::class, array('required' => false, 'label' => '???????????????? ?????? ?????????????? ??????????', 'attr' => array('class' => 'form-control tinyeditor'))) 
            ->add('textblockHowToPrice', TextareaType::class, array('required' => false, 'label' => '???????? ?????? ???????????????? ???????????????????? ????????????', 'attr' => array('class' => 'form-control tinyeditor'))) 
            ->add('textblockUserAgreement', TextareaType::class, array('required' => false, 'label' => '???????????????????????????????? ????????????????????', 'attr' => array('class' => 'form-control tinyeditor'))) 
            ->add('userAdvertWorkRight', TextareaType::class, array('required' => false, 'label' => '?????????????? ???????????????????? ?? ?????? ?????????????????? ??????????????', 'attr' => array('class' => 'form-control tinyeditor'))) 
            ->add('googleMapsKey', TextType::class, array('required' => false, 'label' => '???????? Google Maps API', 'attr' => array('class' => 'form-control','placeholder' => '???????? Google Maps API')))
            ->add('centerLat', TextType::class, array('required' => false, 'label' => '???????????? ?????? ???????????? ??????????(Lat)', 'attr' => array('class' => 'form-control','placeholder' => '???????????? ?????? ???????????? ??????????(Lat)')))
            ->add('centerLng', TextType::class, array('required' => false, 'label' => '?????????????? ?????? ???????????? ??????????(Lat)', 'attr' => array('class' => 'form-control','placeholder' => '?????????????? ?????? ???????????? ??????????(Lat)')))
            ->add('currency', 'entity', array('class' => 'DashboardCommonBundle:Currency',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => true, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('l')->orderBy('l.sortorder', 'ASC');},
                            'label' => '????????????:', 'attr' => array('class' => 'hidden-input form-control','id' => 'region','placeholder' => '????????????:'))) 
            ->add('newReviewStatus', 'entity', array('class' => 'DashboardCommonBundle:ReviewStatus',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => false, 
                            'label' => '???????????? ?????????????????????? ??????????????:', 'attr' => array('class' => 'form-control'))) 
            ->add('publicReviewStatus', 'entity', array('class' => 'DashboardCommonBundle:ReviewStatus',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => false, 
                            'label' => '?????????????????????? ???????????? ???????????? ???? ????????????????:', 'attr' => array('class' => 'form-control')))
            ->add('orderReviewStatus', 'entity', array('class' => 'DashboardCommonBundle:OrderStatus',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => false, 
                            'label' => '?????????????????? ????????????, ???????? ?????????? ???? ????????????????:', 'attr' => array('class' => 'form-control')))
            ->add('successAddAdvertText', TextareaType::class, array('required' => false, 'label' => '?????????? ?????? ?????????????????? ???? ???????????????? ???????????????????? ????????????????????', 'attr' => array('class' => 'form-control','placeholder' => '?????????? ?????? ?????????????????? ???? ???????????????? ???????????????????? ????????????????????')))
            ->add('premiumService', 'entity', array('class' => 'DashboardCommonBundle:Service',
                            'choice_label' => 'title',
                            'empty_data' => null,
                            'required' => false, 
                            'label' => '???????????????????? ???????????????????? ?????? "??????????????" ?? ??????????????:', 'attr' => array('class' => 'form-control')))   
            ->add('specialService', 'entity', array('class' => 'DashboardCommonBundle:Service',
                            'choice_label' => 'title',
                            'empty_data' => null,
                            'required' => false, 
                            'label' => '???????????????????? ???????????????????? ?? ?????????? "??????????????????????????????" ?? ??????????????:', 'attr' => array('class' => 'form-control')))
            ->add('selectedService', 'entity', array('class' => 'DashboardCommonBundle:Service',
                            'choice_label' => 'title',
                            'empty_data' => null,
                            'required' => false, 
                            'label' => '???????????????? ???????????????????? ?? ??????????????:', 'attr' => array('class' => 'form-control')))
                                    ->getForm();
        }
        
        foreach($locales as $locale)
        {
            $settingsForms[$locale->getCode()]->handleRequest($request);
            
            if($settingsForms[$locale->getCode()]->isValid())
            {
                $siteLogo = $settingsForms[$locale->getCode()]['siteLogoNew']->getData();
                $oldsiteLogo = $settingsForms[$locale->getCode()]['siteLogo']->getData();

                if($siteLogo)
                {
                    if($oldsiteLogo)
                    {
                        if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/site/' . $oldsiteLogo ))
                        {
                            $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/site/' . $oldsiteLogo );
                        }
                    }
                    $extention = $siteLogo->getClientOriginalExtension();
                    $localImageName = rand(1, 99999999).'.'.$extention;
                    $siteLogo->move('bundles/images/site',$localImageName);
                    $locale->getSettings()->setSiteLogo($localImageName);
                }

                $watermarkNew = $settingsForms[$locale->getCode()]['watermarkNew']->getData();
                $watermark = $settingsForms[$locale->getCode()]['watermark']->getData();

                if($watermarkNew)
                {
                    if($watermark)
                    {
                        if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/site/' . $watermark ))
                        {
                            $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/site/' . $watermark );
                        }
                    }
                    $extention = $watermarkNew->getClientOriginalExtension();
                    $localImageName = rand(1, 99999999).'.'.$extention;
                    $watermarkNew->move('bundles/images/site',$localImageName);
                    $locale->getSettings()->setWatermark($localImageName);
                }

                $manager->persist($locale->getSettings());
                $manager->flush();

                $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>??????????????!</strong> ?????????????????? ?????????? ??????????????????.</div>')
                );

                $this->redirectToRoute('admin_settings');
            }
        }
        
        $settingFromsViews = array();
        
        foreach($settingsForms as $key => $value)
        {
            $settingFromsViews[$key] = $value->createView();
        }
        
        return $this->render('DashboardAdminBundle:Settings:settings.html.twig', array("locales" => $locales, "settingsForms" => $settingFromsViews));
    }
    
    /**
     * @Route("/admin/404", name="admin_notfound")
     */
    
    public function notFoundAction(Request $request)
    {
        return $this->render('DashboardAdminBundle:Common:notfound.html.twig');
    }
    
    /**
     * @Route("/admin/textblock", name="admin_textblock")
     */
    public function textblockAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $textblock = $manager->getRepository("DashboardCommonBundle:Textblock")->find(1);
        
        if(!$textblock)
            $textblock = new Textblock();
        
        $textblockForm = $this->get('form.factory')->createNamedBuilder('textblock', 'form', $textblock)
            ->add('howToSetPrice', TextareaType::class, array('required' => false, 'label' => '?????? ?????????????????? ?????????????? ????????', 'attr' => array('class' => 'form-control tinyeditor','placeholder' => '?????? ?????????????????? ?????????????? ????????')))    
            ->add('userAgreement', TextareaType::class, array('required' => false, 'label' => '???????????????????????????????? ????????????????????', 'attr' => array('class' => 'form-control tinyeditor','placeholder' => '???????????????????????????????? ????????????????????')))
            ->add('save', ButtonType::class, array('label' => '??????????????????', 'attr' => array('class' => 'btn btn-success pull-right')))->getForm();
        
        $textblockForm->handleRequest($request);
        
        if($textblockForm->isSubmitted() && $textblockForm->isValid())
        {
            $manager->persist($textblock);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>??????????????!</strong> ?????????????????? ?????????? ??????????????????.</div>')
            );
            
            $this->redirectToRoute('admin_textblock');
        }
        
        return $this->render('DashboardAdminBundle:Settings:textblock.html.twig', array("textblockForm" => $textblockForm->createView()));
    }
    
    private function deleteUser($user, $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        
        //?????????????? ????????????
        if($user->getUserinfo()->getAvatar())
        {
            if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/users/avatars/' . $user->getUserinfo()->getAvatar() ))
            {
                $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/users/avatars/' . $user->getUserinfo()->getAvatar() );
            }
        }
        
        //?????????????? ????????????????????
        $userInfo = $user->getUserinfo();
        $userInfo->setUser(null);
        $manager->remove($userInfo);
        
        //?????????????? ?????? ???????????? ?????????????????????? ?? ?????? ????????????
        if($user->getReviews())
        {
            foreach($user->getReviews() as $review)
            {
                /*$answers = $manager->getRepository("DashboardCommonBundle:Review")->findBy(array("answer" => $review));
                
                if($answers)
                {
                    foreach($answers as $answer)
                    {
                        $answer->setAnswer(null);
                        $manager->remove($answer);
                    }
                }
                
                $answers = $manager->getRepository("DashboardCommonBundle:Review")->findBy(array("answerTo" => $review));
                if($answers)
                {
                    foreach($answers as $answer)
                    {
                        $answer->setAnswerTo(null);
                        $manager->remove($answer);
                    }
                }*/
                
                /*$review->setAnswer(null);
                $review->setAnswerTo(null);*/
                $review->setUser(null);
                /*$review->setTargetUser(null);
                $review->setProduct(null);*/
                $manager->persist($review);
                $manager->flush();
            }
        }
        
        if($user->getTargetReviews())
        {
            foreach($user->getTargetReviews() as $review)
            {
                /*$answers = $manager->getRepository("DashboardCommonBundle:Review")->findBy(array("answer" => $review));
                if($answers)
                {
                    foreach($answers as $answer)
                    {
                        $answer->setAnswer(null);
                        $manager->persist($answer);
                    }
                }
                
                $answers = $manager->getRepository("DashboardCommonBundle:Review")->findBy(array("answerTo" => $review));
                if($answers)
                {
                    foreach($answers as $answer)
                    {
                        $answer->setAnswerTo(null);
                        $manager->persist($answer);
                    }
                }*/
                
                /*$review->setAnswer(null);
                $review->setAnswerTo(null);
                $review->setUser(null);*/
                $review->setTargetUser(null);
                /*$review->setProduct(null);*/
                $manager->persist($review);
                $manager->flush();
            }
        }
        
        //?????????????? ?????? ??????????????????
        if($user->getMessageInbox())
        {
            foreach($user->getMessageInbox() as $message)
            {
                if($message->getUserOwner()->getId() == $user->getId())
                {
                    $message->setUserFrom(null);
                    $message->setUserTo(null);
                    $message->setUserOwner(null);
                    $message->setProduct(null);
                    $message->setConversation(null);
                    $manager->remove($message);
                    $manager->flush();
                }
                else
                {
                    $message->setUserTo(null);
                    $manager->persist($message);
                    $manager->flush();
                }
            }
        }
        if($user->getMessageOutbox())
        {
            foreach($user->getMessageOutbox() as $message)
            {
                if($message->getUserOwner()->getId() == $user->getId())
                {
                    $message->setUserFrom(null);
                    $message->setUserTo(null);
                    $message->setUserOwner(null);
                    $message->setProduct(null);
                    $message->setConversation(null);
                    $manager->remove($message);
                    $manager->flush();
                }
                else
                {
                    $message->setUserFrom(null);
                    $manager->persist($message);
                    $manager->flush();
                }
            }
        }
        if($user->getMessageOwner())
        {
            foreach($user->getMessageOwner() as $message)
            {
                $message->setUserFrom(null);
                $message->setUserTo(null);
                $message->setUserOwner(null);
                $message->setProduct(null);
                $message->setConversation(null);
                $manager->remove($message);
                $manager->flush();
            }
        }
        
        //get all conversations
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Conversation c WHERE c.userOne = " . $user->getId() . " OR c.userTwo = " . $user->getId() . " ORDER BY c.id DESC");
                                    
        try{
            $conversations = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $conversations = 0;
        }
        
        if($conversations)
        {
            foreach($conversations as $conversation)
            {
                /*$conversation->setUserOne(null);
                $conversation->setUserTwo(null);
                $manager->remove($conversation);*/
                
                if($conversation->getUserOne()->getId() == $user->getId())
                {
                    $conversation->setUserOne(null);
                    $manager->persist($conversation);
                    $manager->flush();
                }
                else
                {
                    $conversation->setUserTwo(null);
                    $manager->persist($conversation);
                    $manager->flush();
                }
            }
        }
        
        //?????????????? ?????? ????????????????????
        
        if($user->getProducts())
        {
            foreach($user->getProducts() as $product)
            {
                $this->deleteAdvert($product->getId(), $request);
            }
        }
        
        //?????????????????? ???? ?????????????????? ????????????????????
        $favProducts = $manager->getRepository("DashboardCommonBundle:FavoriteProducts")->findBy(array("userId" => $user->getId()));
        if($favProducts)
        {
            foreach($favProducts as $product)
            {
                $manager->remove($product);
            }
        }
        //?????????????? ???????????? ????????????
        $blacklists = $manager->getRepository("DashboardCommonBundle:Blacklist")->findBy(array("userAuthor" => $user->getId()));
        
        if($blacklists)
        {
            foreach($blacklists as $blacklist)
            {
                $manager->remove($blacklist);
            }
        }
        $blacklists = $manager->getRepository("DashboardCommonBundle:Blacklist")->findBy(array("userTo" => $user->getId()));
        
        if($blacklists)
        {
            foreach($blacklists as $blacklist)
            {
                $manager->remove($blacklist);
            }
        }
        
        //?????????????? ???????????? ????????????????????????
        if($user->getComplaint())
        {
            foreach($user->getComplaint() as $complaint)
            {
                $complaint->setUser(null);
                $complaint->setProduct(null);
                $manager->remove($complaint);
            }
        }
        //?????????????? ???????????? ????????????????????????
        if($user->getReceivedOrders())
        {
            foreach($user->getReceivedOrders() as $order)
            {
                $order->setUserReceived(null);
                $order->setProduct(null);
                $manager->remove($order);
            }
        }
        if($user->getSendedOrders())
        {
            foreach($user->getSendedOrders() as $order)
            {
                $order->setUserSended(null);
                $order->setProduct(null);
                $manager->remove($order);
            }
        }
                
        //?????????????? ????????????????????????
        $manager->remove($user);
        $manager->flush();
        
        $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>??????????????!</strong> ???????????????????????? ????????????.</div>')
        );
    }
    
    private function deleteAdvert($productId, $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId);
        
        if($product)
        {
                //?????????????? ?????????????? ????????
                if($product->getMainfoto())
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $product->getMainfoto() ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $product->getMainfoto() );
                    }
                }
                
                //?????????????? ???????????????????????????? ????????
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
                
                //?????????????? ????????????, ?????????????????????? ?? ?????????? ????????????
                if($product->getReviews())
                {
                    foreach($product->getReviews() as $review)
                    {
                        $review->setProduct(null);
                        $manager->persist($review);
                        $manager->flush();
                    }
                }
                
                //?????????????? ??????????????????, ?????????????????????? ?? ?????????? ????????????
                if($product->getMessages())
                {
                    foreach($product->getMessages() as $message)
                    {
                        $message->setProduct(null);
                        $manager->persist($message);
                        $manager->flush();
                    }
                }
                
                //?????????????? ???????????? ???? ???????? ??????????
                if($product->getComplaint())
                {
                    foreach($product->getComplaint() as $complaint)
                    {
                        $complaint->setProduct(null);
                        $complaint->setUser(null);
                        $manager->remove($complaint);
                    }
                }
                
                //?????????????? ????????????, ?????????????????????? ?? ????????????
                if($product->getService())
                {
                    $product->getService()->setProduct(null);
                    $manager->remove($product->getService());
                    $product->setService(null);
                }
                
                //?????????????? ????????????, ?????????????????????? ?? ?????????? ????????????
                if($product->getOrders())
                {
                    foreach($product->getOrders() as $order)
                    {
                        $order->setProduct(null);
                        $manager->persist($order);
                    }
                }
                
                //?????????????? ???? ??????????????????
                $favProducts = $manager->getRepository("DashboardCommonBundle:FavoriteProducts")->findBy(array("productId" => $product->getId()));
                if($favProducts)
                {
                    foreach($favProducts as $favProduct)
                    {
                        $manager->remove($favProduct);
                    }
                }
                $product->setUser(null);
                
                $manager->remove($product);
                $manager->flush();
        }
    }
    
    /**
     * @Route("/admin/getregion/{regionId}", name="region_getregioncity")
     */
    public function getRegionCitiesAction($regionId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $answer = "";
        
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:City c WHERE c.region = " . $regionId . " ORDER BY c.name ASC");
        
        try{
                $cities = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $cities = 0;
            }
        
        if($cities)
        {
            foreach($cities as $city)
            {
                $answer .= '<option value="' . $city->getId() . '">' . $city->getName() . '</option>';
            }
            return new Response($answer);
        }
        else
            return new Response("0");
    }
    
    /**
     * @Route("/admin/delivery", name="admin_delivery")
     */
    public function deliveryAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $deliveryForm = $this->get('form.factory')->createNamedBuilder('friendmessage', 'form')
                ->add('deliveryText', TextareaType::class, array('required' => true, 'label' => '?????????? ????????????????', 'attr' => array('class' => 'form-control tinyeditor')))
                ->add('save', ButtonType::class, array('label' => '??????????????????', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
        $deliveryForm->handleRequest($request);
            
        if($deliveryForm->isSubmitted() && $deliveryForm->isValid())
        {
            $settings = $manager->getRepository("DashboardCommonBundle:Settings")->find(1);
            $users = $manager->getRepository("DashboardCommonBundle:User")->findBy(array("isActive" => "1"));
            
            if(count($users) > 0)
            {
                $deliveryText = $deliveryForm['deliveryText']->getData();
                
                foreach($users as $user)
                {
                    if($user->getEmail())
                    {
                        $message = \Swift_Message::newInstance()
                            ->setSubject('?????????????? ?? ?????????? gribupardot.sunweb.by')
                            ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                            ->setTo($user->getEmail())
                            ->setBody($deliveryText,'text/html');

                        $this->get('mailer')->send($message);
                    }
                }

            }
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>??????????????!</strong> ???????????? ???????????????????? ???????? ???????????????? ?????????????????????????? ??????????.</div>')
            );
            
            return $this->redirectToRoute("admin_delivery");
        }
        
        return $this->render('DashboardAdminBundle:Default:delivery.html.twig', array("deliveryForm" => $deliveryForm->createView()));
    }
    
    
}
