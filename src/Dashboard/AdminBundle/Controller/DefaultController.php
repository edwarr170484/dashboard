<?php

namespace Dashboard\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
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

use Dashboard\CommonBundle\Entity\Region;
use Dashboard\CommonBundle\Entity\Banner;
use Dashboard\CommonBundle\Entity\Page;
use Dashboard\CommonBundle\Entity\Role;
use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\UserInfo;
use Dashboard\CommonBundle\Entity\UserPurse;
use Dashboard\CommonBundle\Entity\Textblock;
use Dashboard\CommonBundle\Entity\OrderStatus;
use Dashboard\CommonBundle\Entity\UserPurseHistory;
use Dashboard\CommonBundle\Entity\UserActivity;

use Dashboard\AdminBundle\Form\Type\UserInfoType;
use Dashboard\CommonBundle\Form\Type\UserPurseType;
use Dashboard\AdminBundle\Form\Type\TranslationType;
use Dashboard\AdminBundle\Form\Type\RegionType;

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
        $products = $manager->getRepository("DashboardCommonBundle:Product")->findBy(array("isConfirm" => "0", "isCorrect" => "0", "isBlocked" => "0"));
        $complaints = $manager->getRepository("DashboardCommonBundle:Complaint")->findBy(array("status" => "0"));
        $messages = $manager->getRepository("DashboardCommonBundle:FormMessage")->findBy(array("isNew" => "1"));
        
        return $this->render('DashboardAdminBundle:Common:header.html.twig', array("user" => $user,
                                                                                   "newConfirmations" => count($products),
                                                                                   "newComplaints" => count($complaints),
                                                                                   "newMessages" => count($messages),
                                                                                   "settings" => $settings));
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
                                <strong>Ошибка!</strong> К группе ' . $role->getName() . '  привязано ' . count($role->getUsers())  . ' пользователей. Удаление невозможно.</div>')
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
                            <strong>Успешно!</strong> Информация о группе удалена.</div>')
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
                                    <strong>Ошибка!</strong> К группе ' . $role->getName() . '  привязано ' . count($role->getUsers())  . ' пользователей. Удаление невозможно.</div>')
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
                                <strong>Успешно!</strong> Информация о группе удалена.</div>')
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
                ->add('name', TextType::class, array('required' => true, 'label' => 'Название группы пользователей', 'attr' => array('class' => 'form-control','placeholder' => 'Название группы пользователей')))
                ->add('advertNumber', TextType::class, array('required' => false, 'label' => 'Максимальное количество объявлений', 'attr' => array('class' => 'form-control','placeholder' => 'Максимальное количество объявлений')))
                ->add('advertFotoNumber', TextType::class, array('required' => false, 'label' => 'Максимальное количество фото для одного объявления', 'attr' => array('class' => 'form-control','placeholder' => 'Максимальное количество фото для одного объявления')))
                ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
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
                                <strong>Успешно!</strong> Информация о группе сохранена.</div>')
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
                                <strong>Ошибка!</strong> Это текущая учетная запись администратора, которая не может быть удалена.</div>')
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
                                <strong>Ошибка!</strong> Такого пользователя не существует.</div>')
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
        $sessionUser = $this->get('security.context')->getToken()->getUser();
        $users =  $manager->getRepository("DashboardCommonBundle:User")->findAll();
        $pagination = 0;
        
        $user = new User();
        
        $userForm = $this->get('form.factory')->createNamedBuilder('user', 'form', $user)
                ->add('id', TextType::class, array('required' => false,'mapped' => false,'label' => 'ID пользователя', 'attr' => array('class' => 'form-control','placeholder' => 'ID пользователя')))
                ->add('email', TextType::class, array('required' => false,'mapped' => false,'label' => 'Email', 'attr' => array('class' => 'form-control','placeholder' => 'Email')))
                ->add('firstname', TextType::class, array('required' => false,'label' => 'Имя', 'mapped' => false, 'attr' => array('class' => 'form-control','placeholder' => 'Имя')))
                ->add('lastname', TextType::class, array('required' => false, 'mapped' => false, 'label' => 'Фамилия','attr' => array('class' => 'form-control','placeholder' => 'Фамилия')))
                ->add('save', ButtonType::class, array('label' => 'Поиск', 'attr' => array('class' => 'btn btn-sm btn-primary m-r-5')))->getForm();
        
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

        return $this->render('DashboardAdminBundle:Settings:users.html.twig', array("users" => $users,
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
        $userCurrentBalanse = 0;
                 
        $user = ($userId) ? $manager->getRepository("DashboardCommonBundle:User")->find($userId) : new User();

        $roles = ($userId) ? $user->getRoles() : 0;
        
        $userinfo = ($user) ? $user->getUserinfo() : new UserInfo();
        
        $userpurse = ($user) ? $user->getUserpurse() : new UserPurse();
        
        $originalEmail = $user->getEmail();
        
        if($user->getId())
            $userCurrentBalanse = $user->getUserpurse()->getBalanse();
        
        $userForm = $this->get('form.factory')->createNamedBuilder('user', 'form', $user)
            ->add('email', EmailType::class, array('required' => false, 'label' => 'эл. почта', 'attr' => array('class' => 'form-control')))
            ->add('password', TextType::class, array('required' => false, 'mapped' => false,'label' => 'новый пароль', 'attr' => array('class' => 'form-control', 'plceholder' => 'Новый пароль')))
            ->add('isConfirm', CheckboxType::class, array('required' => false, 'label' => 'Аккаунт подтвержден', 'attr' => array('class' => 'form-control')))
            ->add('isActive', CheckboxType::class, array('required' => false, 'label' => 'Аккаунт активен', 'attr' => array('class' => 'form-control')))
            ->add('advertNumber', TextType::class, array('required' => false, 'label' => 'Количество дополнительных объявлений', 'attr' => array('class' => 'form-control')))
            ->add('userinfo', new UserInfoType($manager, $userinfo), array('data_class' => 'Dashboard\CommonBundle\Entity\UserInfo'))
            ->add('userpurse', new UserPurseType($manager, $userpurse), array('data_class' => 'Dashboard\CommonBundle\Entity\UserPurse'))
            ->add('roles', 'entity', array('class' => 'DashboardCommonBundle:Role', 
                  'choice_label' => 'name', 'label' => 'Группа пользователей', 'data' => ($roles) ? $roles[0] : 0,'mapped' => false,'attr' => array('class' => 'form-control')))
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')))->getForm();
        
        $userForm->handleRequest($request);
        
        if($userForm->isSubmitted() && $userForm->isValid())
        {
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
                                        <strong>Ошибка!</strong> Пользователь с таким email уже существует.</div>')
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
                $userpurse = new UserPurse();
                $user->getUserinfo()->setUser($user);
                
                $password = $this->get('security.password_encoder')->encodePassword($user, '1');
                $user->setPassword($password);
                
                $userpurse->setUser($user);
                $userpurse->setBalanse($userForm['userpurse']['balanse']->getData());

                $userActivity = new UserActivity();
                $userActivity->setUser($user);
                $userActivity->setEnterCount(0);
                $userActivity->setLastActivity(new \DateTime("now"));
                
                $user->setIsConfirm(1);
                $user->setIsActive(1);
                $user->setAlerts(1);
                
                $manager->persist($userpurse);
                $manager->persist($userActivity);
            }
            
            if($userId)
            {
                $confirmation = $manager->getRepository("DashboardCommonBundle:Register")->findOneByUserId($userId);
                
                if($confirmation)
                {
                    $manager->remove($confirmation);
                }
                
                if($userCurrentBalanse != $userForm['userpurse']['balanse']->getData())
                {
                    $userPurseHistory = new UserPurseHistory();
                    $userPurseHistory->setActionDate(new \DateTime("now"));
                    $userPurseHistory->setAction("Администратор изменил значение текущего счета. Новое значение " . $userForm['userpurse']['balanse']->getData() . " гривен.");
                    $userPurseHistory->setCurrentBalanse($userForm['userpurse']['balanse']->getData());
                    $userPurseHistory->setUserpurse($user->getUserpurse());
                    
                    $manager->persist($userPurseHistory);
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
                                <strong>Успешно!</strong> Данные о пользователе сохранены.</div>')
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
            ->add('userAdvertLimitText', TextareaType::class, array('required' => false, 'label' => 'Текст при исчерпании лимита объявлений', 'attr' => array('class' => 'form-control tinyeditor')))   
            ->add('userDefaultGroup', ChoiceType::class, array('choices' => $choices, 'data' => $settings->getUserDefaultGroup(), 'label' => 'Группа пользователей по умолчанию', 
                  'attr' => array('class' => 'form-control')))
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')))->getForm();
        
        $settingsForm->handleRequest($request);
        
        if($settingsForm->isSubmitted() && $settingsForm->isValid())
        {            
            $manager->persist($settings);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Успешно!</strong> Настройки сохранены.</div>')
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
                                <strong>Успешно!</strong> Информация о странице удалена.</div>')
                    );
                }
                else 
                {
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Ошибка!</strong> Это служебная страница, ее нельзя удалить.</div>')
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
                                    <strong>Успешно!</strong> Информация о страницах удалена.</div>')
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
                                    <strong>Успешно!</strong> Информация о страницах сохранена.</div>')
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
        
        $page = ($pageId) ? $manager->getRepository("DashboardCommonBundle:Page")->find($pageId) : new Page();
        $isUserpage = ($pageId) ? $page->getIsUserpage() : 0;
        $route = ($pageId) ? $page->getRoute() : 0;
        
        $pageForm = $this->get('form.factory')->createNamedBuilder('page', 'form', $page)
                ->add('title', TextType::class, array('required' => true, 'label' => 'Название страницы', 'attr' => array('class' => 'form-control','placeholder' => 'Название страницы')))
                ->add('route', TextType::class, array('required' => true, 'label' => 'Транслит(SEO)', 'attr' => array('class' => 'form-control','placeholder' => 'Транслит(SEO)')))
                ->add('isFooterMenu', ChoiceType::class, array('choices' => array("0" => "Нет","1" =>"Да"),'label' => 'Отображать в нижнем меню','empty_value' => false,'expanded' => true))
                ->add('footerMenuSection', ChoiceType::class, array('choices' => array("0" => "Нет","1" =>"Информация","2" => "Услуги"),'label' => 'Секция для страницы в нижнем меню', 'attr' => array('class' => 'form-control')))
                ->add('text', TextareaType::class, array('required' => false, 'label' => 'Содержимое', 'attr' => array('class' => 'form-control tinyeditor','placeholder' => 'Содержимое')))
                ->add('metaTagTitle', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Title', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Title')))
                ->add('metaTagDescription', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Description', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Description')))
                ->add('metaTagAuthor', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Author', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Author')))
                ->add('metaTagRobots', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Robots', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Robots')))
                ->add('metaTagKeywords', TextareaType::class, array('required' => false, 'label' => 'Мета-тег Keywords', 'attr' => array('class' => 'form-control','placeholder' => 'Мета-тег Keywords')))
                ->add('locale', 'entity', array('class' => 'DashboardCommonBundle:Locale',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => true, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('l')->orderBy('l.sortorder', 'ASC');},
                            'label' => 'Локализация:', 'attr' => array('class' => 'hidden-input form-control','id' => 'region','placeholder' => 'Локализация:')))
                ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
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
            
            $manager->persist($page);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Информация о странице сохранена.</div>')
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
                            <strong>Ошибка!</strong> Нельзя удалить этот статус. С ним связаны ' . count($orders)  . ' заказов.</div>')
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
                            <strong>Успешно!</strong> Статус удален.</div>')
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
                ->add('name', TextType::class, array('required' => true, 'label' => 'Статус заказа', 'attr' => array('class' => 'form-control','placeholder' => 'Статус заказа')))
                ->add('translations', 'collection', array('type' => new TranslationType($manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
                ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
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
                <strong>Успешно!</strong> Новый статус заказа добавлен.</div>')
            );
            
            return $this->redirectToRoute("admin_settings_orderstatus");
        }
        
        return $this->render('DashboardAdminBundle:Settings:orderstatusedit.html.twig', array("statusForm" => $statusForm->createView()));
    }
    
    /**
     * @Route("/admin/region", name="admin_settings_region")
     */
    public function regionAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $regions = $manager->getRepository("DashboardCommonBundle:Region")->findBy(array(), array("sortorder" => "ASC"));

        if($request->request->get('regionIds'))
        {
            foreach($request->request->get('regionIds') as $regionId)
            {
                $region = $manager->getRepository("DashboardCommonBundle:Region")->find($regionId);

                if($region)
                {
                    if(count($region->getProduct()) > 0)
                    {
                        $this->addFlash(
                            'notice_region',
                            $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Ошибка!</strong> Нельзя удалить регион. К нему привязаны ' . count($region->getProduct()) . ' объявлений.</div>')
                        );
                    }
                    else
                    {
                        if($region->getTranslations())
                        {
                            foreach($region->getTranslations() as $translation)
                            {
                                $translation->setRegion(null);
                                $manager->remove($translation);
                            }
                        }
                        
                        if($region->getCity())
                        {
                            foreach($region->getCity() as $city)
                            {
                                if($city->getTranslations())
                                {
                                    foreach($city->getTranslations() as $translation)
                                    {
                                        $translation->setCity(null);
                                        $manager->remove($translation);
                                    }
                                }
                                $city->setRegion(null);
                                $manager->remove($city);
                            }
                        }

                        $manager->remove($region);
                        $manager->flush();
                    }
                }
            }
                            
            $this->addFlash(
                'notice_region',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Операция выполнена.</div>')
            );
            
            return $this->redirectToRoute('admin_settings_region');
        }
        
        if($request->request->get('sortorder'))
        {
            foreach($request->request->get('sortorder') as $key => $value)
            {
                $region = $manager->getRepository("DashboardCommonBundle:Region")->find($key);
                
                if($region)
                {
                    $region->setSortorder($value);
                    $manager->persist($region);
                }
            }
            $manager->flush();
            $this->addFlash(
                'notice_region',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Операция выполнена.</div>')
            );
            
            return $this->redirectToRoute('admin_settings_region');
        }
                        
        return $this->render('DashboardAdminBundle:Settings:region.html.twig', array("regions" => $regions));
    }
    
    /**
     * @Route("/admin/edit/region/{regionId}", name="admin_settings_region_edit", defaults={"regionId" : 0})
     */
    public function regionEditAction($regionId,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalCities = new ArrayCollection();
        $originalTranslations = new ArrayCollection();
        $originalCityTranslations = new ArrayCollection();
        
        $region = ($regionId) ? $manager->getRepository("DashboardCommonBundle:Region")->find($regionId) : new Region();
        
        if($regionId && $region)
        {
            foreach ($region->getCity() as $city) {
                $originalCities->add($city);
                
                if($city->getTranslations())
                {
                    foreach($city->getTranslations() as $translation)
                    {
                        $originalCityTranslations->add($translation);
                    }
                }
            }
            
            foreach ($region->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
        }
        
        $regionForm = $this->createForm(new RegionType($manager), $region);
         
        $regionForm->handleRequest($request);
        
        if($regionForm->isSubmitted() && $regionForm->isValid())
        {
            if($originalTranslations)
            {
                foreach ($originalTranslations as $item) 
                {
                    if (false === $region->getTranslations()->contains($item)) 
                    {
                        $item->setRegion(null);
                        $manager->remove($item);
                    }
                }
            }

            if($region->getTranslations())
            {
                foreach($region->getTranslations() as $item)
                {
                    $item->setRegion($region);
                    $manager->persist($item);
                }
            }
            
            if($originalCities)
            {
                foreach ($originalCities as $city) 
                {
                    if (false === $region->getCity()->contains($city)) 
                    {
                        if($city->getTranslations())
                        {
                            foreach($city->getTranslations() as $translation)
                            {
                                $translation->setCity(null);
                                $manager->remove($translation);
                            }
                        }
                        $city->setRegion(null);
                        $manager->remove($city);
                    }
                    
                    if($originalCityTranslations)
                    {
                        foreach($originalCityTranslations as $translation)
                        {
                            if (false === $city->getTranslations()->contains($translation))
                            {
                                $translation->setCity(null);
                                $manager->remove($translation);
                            }
                        }
                    }
                }
            } 
            
            if($region->getCity())
            {
                foreach($region->getCity() as $city)
                {
                    if($city->getTranslations())
                    {
                        foreach($city->getTranslations() as $translation)
                        {
                            $translation->setCity($city);
                            $manager->persist($translation);
                        }
                    }
                    $city->setRegion($region);
                    $manager->persist($city);
                }
            }
            
            $manager->persist($region);
            $manager->flush();

            $this->addFlash(
                'notice_region',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Выполнено!</strong> Информация о стране сохранена.</div>')
            );
            
            return $this->redirectToRoute("admin_settings_region_edit", array("regionId" => $region->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Settings:regionedit.html.twig', array("regionForm" => $regionForm->createView()));
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
                    <strong>Успешно!</strong> Баннер успешно удален.</div>')
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
                        <strong>Успешно!</strong> Отмеченные баннеры успешно удалены.</div>')
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
                ->add('title', TextType::class, array('required' => true, 'label' => 'Название', 'attr' => array('class' => 'form-control','placeholder' => 'Название')))
                ->add('imageNew', FileType::class, array('required' => false, 'label' => '','label' => 'Изображение','mapped' => false, 'attr' => array('class' => 'form-control')))
                ->add('image', HiddenType::class, array('required' => false, 'label' => ''))
                ->add('link', TextType::class, array('required' => false, 'label' => 'Ссылка', 'attr' => array('class' => 'form-control','placeholder' => 'Ссылка')))
                ->add('code', TextareaType::class, array('required' => false, 'label' => 'Код вместо картинки', 'attr' => array('class' => 'form-control','placeholder' => 'Код вместо картинки')))
                ->add('position', ChoiceType::class, array('choices' => 
                    array('toppage' => 'A1',
                          'rightcolumn' => 'B1',
                          'centerpage' => 'C1',
                          'defaulttop' => 'A1 по умолчанию',
                          'defaultright' => 'B1 по умолчанию',
                          'defaultcenter' => 'C1 по умолчанию'), 
                    'data' => $banner->getPosition(), 'label' => 'Позиция баннера', 'attr' => array('class' => 'form-control')))
                ->add('dateFrom', DateType::class, array('required' => false, 'label' => 'Дата начала отображения', 'attr' => array('class' => 'form-control')))
                ->add('dateTo', DateType::class, array('required' => false, 'label' => 'Дата окончания отображения', 'attr' => array('class' => 'form-control')))
                ->add('categories', 'entity', array('class' => 'DashboardCommonBundle:Category', 
                                            'choice_label' => 'title',
                                            'multiple' => true,
                                            'expanded' => true,
                                            'label' => 'Привязанные категории (для выбора нескольких зажмите Ctrl)',
                                            'required' => false,
                                            'attr' => array('class' => 'form-control')))
                ->add('pages', 'entity', array('class' => 'DashboardCommonBundle:Page', 
                                            'choice_label' => 'title',
                                            'multiple' => true,
                                            'expanded' => true,
                                            'label' => 'Страницы, на которых отображать баннер',
                                            'required' => false,
                                            'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
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
                        <strong>Ошибка!</strong> Формат загружаемого баннера не поддерживается. Поддерживаемые форматы: jpg, jpeg, png, gif.</div>')
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
                <strong>Выполнено!</strong> Баннер успешно сохранен.</div>')
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
            ->add('siteName', TextType::class, array('required' => false, 'label' => 'Название сайта', 'attr' => array('class' => 'form-control','placeholder' => 'Название сайта')))    
            ->add('siteDescription', TextareaType::class, array('required' => false, 'label' => 'Описание сайта', 'attr' => array('class' => 'form-control tinyeditor')))
            ->add('adminEmail', TextType::class, array('required' => false, 'label' => 'Email администратора', 'attr' => array('class' => 'form-control','placeholder' => 'Email администратора')))
            ->add('isModerate', CheckboxType::class, array('required' => false, 'label' => 'Включить модерацию объявлений'))
            ->add('isShowCaptcha', CheckboxType::class, array('required' => false, 'label' => 'Включить Google Recaptcha'))
            ->add('isShowType', CheckboxType::class, array('required' => false, 'label' => 'Показывать блок выбора типа при добавлении или редактировании объявления'))
            ->add('categoryProductNumber', TextType::class, array('required' => false, 'label' => 'Количество объявлений на странице категории', 'attr' => array('class' => 'form-control','placeholder' => 'Количество объявлений на странице категории')))
            ->add('topsellerBlockNumber', TextType::class, array('required' => false, 'label' => 'Количество продавцов в блоке рейтинга', 'attr' => array('class' => 'form-control','placeholder' => 'Количество продавцов в блоке рейтинга')))
            ->add('mainpageAdvertsNumber', TextType::class, array('required' => false, 'label' => 'Количество объявлений на странице категории', 'attr' => array('class' => 'form-control','placeholder' => 'Количество объявлений на главной странице')))
            ->add('catpagePremiumNumber', TextType::class, array('required' => false, 'label' => 'Количество премиум объявлений на странице категории', 'attr' => array('class' => 'form-control','placeholder' => 'Количество премиум объявлений на странице категории')))
            ->add('advertDaysShowNumber', TextType::class, array('required' => false, 'label' => 'Количество дней для размещения объявления', 'attr' => array('class' => 'form-control','placeholder' => 'Количество дней для размещения объявления')))
            ->add('premiumAdvPrice', TextType::class, array('required' => false, 'label' => 'Цена за премиум размещение', 'attr' => array('class' => 'form-control','placeholder' => 'Цена за премиум размещение')))
            ->add('upAdvPrice', TextType::class, array('required' => false, 'label' => 'Цена за поднятие объявления', 'attr' => array('class' => 'form-control','placeholder' => 'Цена за поднятие объявления')))
            ->add('selectedAdvPrice', TextType::class, array('required' => false, 'label' => 'Цена за выделенное объявление', 'attr' => array('class' => 'form-control','placeholder' => 'Цена за выделенное объявление')))  
            ->add('conversationIndex', TextType::class, array('required' => false, 'label' => 'Курс конвертации из денег в баллы', 'attr' => array('class' => 'form-control','placeholder' => 'Курс конвертации из денег в баллы')))    
            ->add('aditionalAdvertPrice', TextType::class, array('required' => false, 'label' => 'Цена за дополнительный слот', 'attr' => array('class' => 'form-control','placeholder' => 'Цена за дополнительный слот')))
            ->add('siteLogoNew', FileType::class, array('required' => false, 'mapped' => false,'label' => 'Логотип сайта', 'attr' => array('class' => 'form-control','placeholder' => 'Логотип сайта')))
            ->add('siteLogo', HiddenType::class, array('required' => false))
            ->add('watermarkNew', FileType::class, array('required' => false, 'mapped' => false,'label' => 'Водяной знак', 'attr' => array('class' => 'form-control','placeholder' => 'Водяной знак')))
            ->add('watermark', HiddenType::class, array('required' => false))
            ->add('dafaultOrderStatus', ChoiceType::class, array('choices' => $statuses, 'data' => $locale->getSettings()->getDafaultOrderStatus(),'required' => false, 'label' => 'Статус заказов при добавлении', 'attr' => array('class' => 'form-control','placeholder' => 'Статус заказов при добавлении')))
            ->add('copyright', TextType::class, array('required' => false, 'label' => 'Копирайт', 'attr' => array('class' => 'form-control','placeholder' => 'Копирайт'))) 
            ->add('textblockHowToPrice', TextareaType::class, array('required' => false, 'label' => 'Как правильно устанавливать цену', 'attr' => array('class' => 'form-control tinyeditor'))) 
            ->add('textblockUserAgreement', TextareaType::class, array('required' => false, 'label' => 'Пользовательское соглашение', 'attr' => array('class' => 'form-control tinyeditor'))) 
            ->add('userAdvertWorkRight', TextareaType::class, array('required' => false, 'label' => 'Правила размещения и как продавать быстрее', 'attr' => array('class' => 'form-control tinyeditor'))) 
                    
                    
            ->add('currency', 'entity', array('class' => 'DashboardCommonBundle:Currency',
                            'choice_label' => 'name',
                            'empty_data' => null,
                            'required' => true, 
                            'query_builder' => function(EntityRepository $er){return $er->createQueryBuilder('l')->orderBy('l.sortorder', 'ASC');},
                            'label' => 'Валюта:', 'attr' => array('class' => 'hidden-input form-control','id' => 'region','placeholder' => 'Валюта:')))  
            ->add('successAddAdvertText', TextareaType::class, array('required' => false, 'label' => 'Текст для сообщения об успешном добавлении объявления', 'attr' => array('class' => 'form-control','placeholder' => 'Текст для сообщения об успешном добавлении объявления')))->getForm();
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
                    $settings->setSiteLogo($localImageName);
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
                    $settings->setWatermark($localImageName);
                }

                $manager->persist($locale->getSettings());
                $manager->flush();

                $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Успешно!</strong> Настройки сайта сохранены.</div>')
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
            ->add('howToSetPrice', TextareaType::class, array('required' => false, 'label' => 'Как правильно указать цену', 'attr' => array('class' => 'form-control tinyeditor','placeholder' => 'Как правильно указать цену')))    
            ->add('userAgreement', TextareaType::class, array('required' => false, 'label' => 'Пользовательское соглашение', 'attr' => array('class' => 'form-control tinyeditor','placeholder' => 'Пользовательское соглашение')))
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')))->getForm();
        
        $textblockForm->handleRequest($request);
        
        if($textblockForm->isSubmitted() && $textblockForm->isValid())
        {
            $manager->persist($textblock);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Текстовые блоки сохранены.</div>')
            );
            
            $this->redirectToRoute('admin_textblock');
        }
        
        return $this->render('DashboardAdminBundle:Settings:textblock.html.twig', array("textblockForm" => $textblockForm->createView()));
    }
    
    private function deleteUser($user, $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        
        //удаляем аватар
        if($user->getUserinfo()->getAvatar())
        {
            if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/users/avatars/' . $user->getUserinfo()->getAvatar() ))
            {
                $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/users/avatars/' . $user->getUserinfo()->getAvatar() );
            }
        }
        
        //удаляем кошелек и историю
        foreach($user->getUserpurse()->getHistory() as $historyAction)
        {
            $historyAction->setUserpurse(null);
            $manager->remove($historyAction);
            $manager->flush();
        }
        
        $userPurse = $user->getUserpurse();
        $userPurse->setUser(null);
        $manager->remove($userPurse);
        
        //удаляем активность
        $userActivity = $user->getActivity();
        $userActivity->setUser(null);
        $manager->remove($userActivity);
        
        //удаляем информацию
        $userInfo = $user->getUserinfo();
        $userInfo->setUser(null);
        $manager->remove($userInfo);
        
        //удаляем все отзывы пользовтеля и все ответы
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
        
        //удаляем все сообщения
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
        
        //удаляем все объявления
        
        if($user->getProducts())
        {
            foreach($user->getProducts() as $product)
            {
                $this->deleteAdvert($product->getId(), $request);
            }
        }
        //удаляем всех друзей
        if($user->getFriends())
        {
            foreach($user->getFriends() as $friend)
            {
                $friend->setReferrer(null);
                $manager->remove($friend);
            }
        }
        $friends = $manager->getRepository("DashboardCommonBundle:Friend")->findBy(array("user" => $user));
        if($friends)
        {
            foreach($friends as $friend)
            {
                $friend->setUser(null);
                $manager->remove($friend);
            }
        }
        //удаляемся из избранных объявлений
        $favProducts = $manager->getRepository("DashboardCommonBundle:FavoriteProducts")->findBy(array("userId" => $user->getId()));
        if($favProducts)
        {
            foreach($favProducts as $product)
            {
                $manager->remove($product);
            }
        }
        //удаляем черный список
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
        
        //удаляем жалобы пользователя
        if($user->getComplaint())
        {
            foreach($user->getComplaint() as $complaint)
            {
                $complaint->setUser(null);
                $complaint->setProduct(null);
                $manager->remove($complaint);
            }
        }
        //удаляем заказы пользователя
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
        //удаляем ивайт
        if($user->getInvite())
        {
            $invite = $user->getInvite();
            $invite->setUser(null);
            $manager->remove($invite);
        }
        
        //удаляем пользователя
        $manager->remove($user);
        $manager->flush();
        
        $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Пользователь удален.</div>')
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
                ->add('deliveryText', TextareaType::class, array('required' => true, 'label' => 'Текст рассылки', 'attr' => array('class' => 'form-control tinyeditor')))
                ->add('save', ButtonType::class, array('label' => 'ОТПРАВИТЬ', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
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
                            ->setSubject('Новости с сайта gribupardot.sunweb.by')
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
                <strong>Успешно!</strong> Письмо отправлено всем активным пользователям сайта.</div>')
            );
            
            return $this->redirectToRoute("admin_delivery");
        }
        
        return $this->render('DashboardAdminBundle:Default:delivery.html.twig', array("deliveryForm" => $deliveryForm->createView()));
    }
    
    
}
