<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\Message;
use Dashboard\CommonBundle\Entity\Conversation;
use Dashboard\CommonBundle\Entity\Complaint;
use Dashboard\CommonBundle\Entity\Review;

use Dashboard\CommonBundle\Form\Type\ProfileMessageType;
use Dashboard\CommonBundle\Form\Type\DealerRegisterType;
use Dashboard\CommonBundle\Entity\DealerPhone;
use Dashboard\CommonBundle\Entity\DealerSalon;
use Dashboard\CommonBundle\Entity\DealerSalonPhone;

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
            $dealer->setIsAlertBroadcast(1);
            $dealer->setIsAlertNewMessage(1);
            $dealer->setIsAlertNewOrder(1);
            $dealer->getDealerinfo()->setUser($dealer);
            $dealer->getUserinfo()->setUser($dealer);
            $dealer->getUserinfo()->setCity($dealer->getDealerinfo()->getCity());
            $dealer->getUserinfo()->setCityCode($dealer->getDealerinfo()->getCityCode());
            
            $dealerPhone = new DealerPhone();
            $dealerPhone->setDealerInfo($dealer->getDealerinfo());
            $dealerPhone->setPhone($dealer->getUserinfo()->getPhone());
            
            //create new service point
            $dealerSalon = new DealerSalon();
            $dealerSalon->setDealerInfo($dealer->getDealerinfo());
            $dealerSalon->setName($dealer->getDealerinfo()->getCompany());
            
            $dealerSalonPhone = new DealerSalonPhone();
            $dealerSalonPhone->setDealerSalon($dealerSalon);
            $dealerSalonPhone->setPhone($dealer->getUserinfo()->getPhone());
            
            $manager->persist($dealer);
            $manager->persist($dealerPhone);
            $manager->persist($dealerSalon);
            $manager->persist($dealerSalonPhone);
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
        
        $sql = "SELECT u,r FROM DashboardCommonBundle:User u LEFT JOIN u.roles r LEFT JOIN u.dealerinfo ud LEFT JOIN ud.autos ua LEFT JOIN ud.salons uds LEFT JOIN uds.jobs udsj WHERE u.isActive = 1 AND r.role='ROLE_SERVICE'";
        
        if($request->request->get('serviceAutoId')){
            $sql .= ' AND ua.id = ' . $request->request->get('serviceAutoId');
        }
        
        if($request->request->get('jobCategory')){
            $sql .= ' AND (';
            foreach($request->request->get('jobCategory') as $key => $jobCategoryId){
                if($key == 0){
                    $sql .= 'udsj.category = ' . $jobCategoryId;
                }else{
                    $sql .= ' OR udsj.category = ' . $jobCategoryId;
                }
            }
            $sql .= ')';
        }elseif($request->request->get('job')){
            $sql .= ' AND (';
            foreach($request->request->get('job') as $key => $jobId){
                if($key == 0){
                    $sql .= 'udsj.id = ' . $jobId;
                }else{
                    $sql .= ' OR udsj.id = ' . $jobId;
                }
            }
            $sql .= ')';
        }
        
        $query = $manager->createQuery($sql);
        
        try{
            $services = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $services = 0;
        }
        
        $autos = new ArrayCollection();
        $coordinates = new ArrayCollection();
        
        if($services){
            foreach($services as $dealer){
                if($dealer->getDealerInfo()){
                    if($dealer->getDealerInfo()->getAutos()){
                        foreach($dealer->getDealerInfo()->getAutos() as $auto){
                            if(!$autos->get($auto->getId())){
                                $autos->set($auto->getId(), $auto);
                            }
                        }
                    }
                    
                    if($dealer->getDealerinfo()->getSalons()){
                        foreach($dealer->getDealerinfo()->getSalons() as $salon){
                            if($salon->getIsActive()){
                                $address = $salon->getAddress();
                                $address = str_replace(" ", "+", $address);
                                $coords = $this->get('app.maps')->getCoordinatesByAddress($address,$settings->getGoogleMapsKey());
                                if($coords['status'] == "OK"){
                                    $coordinates->set($dealer->getId(), $coords['results'][0]['geometry']['location']);
                                }
                                
                                $rating = 0;
                                if($salon->getReviews()){
                                    $temp = $salon->getReviews();
                                    foreach($temp as $review){
                                        if($review->getStatus()->getId() != $settings->getPublicReviewStatus()->getId()){
                                            $salon->removeReview($review);
                                        }else{
                                            $rating += $review->getRating();
                                        }
                                    }
                                }
                                
                                $salon->setRating(0);
                                if(count($salon->getReviews()) > 0){
                                    $salon->setRating(ceil($rating / count($salon->getReviews())));
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $this->render('DashboardCommonBundle:Office:services.html.twig', array("locale" => $locale,
                                                                                      "settings" => $settings,
                                                                                      "categories" => $categories,
                                                                                      "jobsPerList" => $jobsPerList,
                                                                                      "allCategories" => $allCategories,
                                                                                      "services" => $services,
                                                                                      "autos" => $autos,
                                                                                      "coordinates" => $coordinates));
    }
    
    /**
     * @Route("/servicepage/{serviceId}_{serviceName}", name="servicePage", defaults={"serviceId" : 0, "serviceName" : 0})
     */
    public function servicePageAction($serviceId, $serviceName, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $sessionUser = $this->get('security.context')->getToken()->getUser();
        
        $service = $manager->getRepository("DashboardCommonBundle:DealerSalon")->findOneBy(array("id" => $serviceId, "name" => $serviceName));
        
        if(!$service){
            return $this->createNotFoundException();
        }
        
        $jobCategories = new ArrayCollection();
        
        if($service->getJobs()){
            foreach($service->getJobs() as $job){
                if(!$jobCategories->get($job->getCategory()->getId())){
                    $jobCategories->set($job->getCategory()->getId(), $job->getCategory());
                }
            }
        }
        
        if($jobCategories){
            foreach($jobCategories as $category){
                $temp = $category->getJobs();
                if($temp){
                    foreach($temp as $job){
                        if(false === $service->getJobs()->contains($job)){
                            $category->removeJob($job);
                        }
                    }
                }
            }
        }
        
        $rating = 0;
        if($service->getReviews()){
            $temp = $service->getReviews();
            foreach($temp as $review){
                if($review->getStatus()->getId() != $settings->getPublicReviewStatus()->getId()){
                    $service->removeReview($review);
                }else{
                    $rating += $review->getRating();
                }
            }
        }
                                
        $service->setRating(0);
        if(count($service->getReviews()) > 0){
            $service->setRating(ceil($rating / count($service->getReviews())));
        }
        
        $profileMessageForm = null;
        
        if($this->getUser()){
            
            $profileMessage = new Message();
            $profileMessageForm = $this->createForm(new ProfileMessageType($manager), $profileMessage);
            
            $profileMessageForm->handleRequest($request);

            if ($profileMessageForm->isSubmitted() && $profileMessageForm->isValid())
            {
                $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $profileMessageForm['userTo']->getData(), "userTo" => $profileMessageForm['userFrom']->getData()));
                
                if($blacklistItem){
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                            $this->get('translator')->trans('<strong>Ошибка!</strong> Этот пользователь добавил Вас в черный список.') . '</div>'
                    );
                    
                    return $this->redirectToRoute("servicePage", array("serviceId" => $service->getId(),"serviceName" => $service->getName()));
                }
                
                if($service->getDealerInfo()->getUser()->getId() != $sessionUser->getId())
                {
                    //check if conversation exists
                    $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Conversation c WHERE (c.userOne = " . $profileMessageForm['userFrom']->getData()->getId()  . " AND c.userTwo = " . $profileMessageForm['userTo']->getData()->getId() . " ) "
                            . "OR (c.userOne = " . $profileMessageForm['userTo']->getData()->getId() . " AND c.userTwo = " . $profileMessageForm['userFrom']->getData()->getId()  . ")");
                    
                    try{
                        $conversation = $query->getSingleResult();
                    }
                    catch(\Doctrine\ORM\NoResultException $e) {
                        $conversation = new Conversation();
                        $conversation->setUserOne($profileMessageForm['userTo']->getData());
                        $conversation->setUserTwo($profileMessageForm['userFrom']->getData());
                        $conversation->setUserDeleted(null);
                        $manager->persist($conversation);
                        $manager->flush();
                    }

                    $profileMessage->setUserOwner($profileMessageForm['userFrom']->getData());
                    $profileMessage->setIsNew(1);
                    $profileMessage->setIsDeleted(0);
                    $profileMessage->setSentDate(new \DateTime("now"));
                    $profileMessage->setReadedDate(new \DateTime("now"));
                    $profileMessage->setProduct(null);
                    $profileMessage->setConversation($conversation);
                    
                    $manager->persist($profileMessage);
                    $manager->flush();
                    
                    $messageTwo = new Message();
                    $messageTwo = clone $profileMessage;
                    $messageTwo->setUserOwner($profileMessageForm['userTo']->getData());
                    
                    $manager->persist($messageTwo);
                    $manager->flush();
                    
                    if($service->getDealerInfo()->getUser()->getIsAlertNewMessage())
                    {
                        $messageSent = \Swift_Message::newInstance()
                            ->setSubject('Новое сообщение на сайте ' . $settings->getSiteName())
                            ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                            ->setTo($service->getDealerInfo()->getUser()->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'Emails/productmessage.html.twig',
                                    array('message' => $profileMessage->getMessage(),
                                          'user' => $sessionUser)
                                ),
                                'text/html'
                            );

                            $this->get('mailer')->send($messageSent);
                    }
                    
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Успешно!</strong> Ваше сообщение отправлено.') . '</div>'
                    );
                    
                }
                else {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Ошибка!</strong> Вы не можете писать сообщения себе.') . '</div>'
                    );
                }
                
                return $this->redirectToRoute("servicePage", array("serviceId" => $service->getId(),"serviceName" => $service->getName()));
            }
        }
        
        $complaint = new Complaint();
        $complaintMessageForm = $this->get('form.factory')->createNamedBuilder('complaint', 'form', $complaint)
                 ->add('reason', TextareaType::class, array('required' => true,'label' => '', 'attr' => array('class' => 'form-control','placeholder' => $this->get('translator')->trans('Причина жалобы'))))
                 ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Отправить'), 'attr' => array('class' => 'btn')))->getForm();
        
        $complaintMessageForm->handleRequest($request);
        
        if($complaintMessageForm->isSubmitted() && $complaintMessageForm->isValid()){
            if($service->getDealerinfo()->getUser()->getId() != $sessionUser->getId()){   
                $complaint->setUser($sessionUser);
                $complaint->setSalon($service);
                $complaint->setDateAdded(new \DateTime("now"));
                $complaint->setStatus(0);
                    
                $manager->persist($complaint);
                $manager->flush();
                    
                $message = \Swift_Message::newInstance()
                    ->setSubject('Жалоба на сайте ' . $settings->getSiteName())
                    ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                    ->setTo($settings->getAdminEmail())
                    ->setBody(
                        $this->renderView(
                            'Emails/complaintsalon.html.twig',
                            array('service' => $service,
                                  'user' => $sessionUser,
                                  'settings' => $settings)
                        ),
                        'text/html'
                );

                $this->get('mailer')->send($message);
                    
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Успешно!</strong> Ваша жалоба зарегистрирована и будет рассмотрена в ближайшее время.') . '</div>'
                );
                    
            }
            else {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Вы не можете жаловаться на себя.') . '</div>'
                );
            }
            
            return $this->redirectToRoute("servicePage", array("serviceId" => $service->getId(),"serviceName" => $service->getName()));
        }
        
        $review = new Review();
        $reviewForm = $this->get('form.factory')->createNamedBuilder('review', 'form', $review)
                ->add('reviewReason', TextType::class, array('required' => true,'label' => '', 'attr' => array('class' => 'form-control')))
                ->add('reviewText', TextareaType::class, array('required' => true,'label' => '', 'attr' => array('class' => 'form-control')))
                ->add('rating', HiddenType::class, array('required' => false,'label' => '', 'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Отправить отзыв'), 'attr' => array('class' => 'btn')))->getForm();
        
        $reviewForm->handleRequest($request);
        
        if($reviewForm->isSubmitted() && $reviewForm->isValid()){
            if($service->getDealerinfo()->getUser()->getId() != $sessionUser->getId()){
                $review->setUser($sessionUser);
                $review->setTargetUser($service->getDealerinfo()->getUser());
                $review->setSalons($service);
                $review->setDateAdded(new \DateTime("now"));
                $review->setStatus($settings->getNewReviewStatus());
                
                $manager->persist($review);
                $manager->flush();
                                    
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Успешно!</strong> Ваш отзыв отправлен и появится на сайте после проверки модератором.') . '</div>'
                );
                
            }else{
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Вы не можете оставлять отзывы на свои объекты.') . '</div>'
                );
            }
            
            return $this->redirectToRoute("servicePage", array("serviceId" => $service->getId(),"serviceName" => $service->getName()));
        }
        
        $coordinates = new ArrayCollection();
        
        $address = str_replace(" ", "+", $service->getAddress());
        $coords = $this->get('app.maps')->getCoordinatesByAddress($address, $settings->getGoogleMapsKey());
        if($coords['status'] == "OK"){
            $coordinates->set($service->getId(), $coords['results'][0]['geometry']['location']);
        }
        
        if($service->getReviews()){
            $temp = $service->getReviews();
            foreach($temp as $review){
                if(!$review->getStatus()){
                    $service->removeReview($review);
                }
            }
        }
        
        $rating = 0;
        if(count($service->getReviews()) > 0){
            foreach($service->getReviews() as $review){
                $rating += $review->getRating();
            }
            
            $rating = ceil($rating / count($service->getReviews()));
        }
        
        $service->setRating($rating);
        
        return $this->render('DashboardCommonBundle:Office:service.html.twig', array("locale" => $locale,
                                                                                     "settings" => $settings,
                                                                                     "service" => $service,
                                                                                     "coordinates" => $coordinates,
                                                                                     "jobCategories" => $jobCategories,
                                                                                     "reviewForm" => $reviewForm->createView(),
                                                                                     "profileMessageForm" => $profileMessageForm->createView(),
                                                                                     "complaintMessageForm" => $complaintMessageForm->createView()));
    }
}