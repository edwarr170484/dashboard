<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\Register;
use Dashboard\CommonBundle\Form\Type\DealerRegisterType;
use Dashboard\CommonBundle\Entity\Message;
use Dashboard\CommonBundle\Entity\Conversation;

use Dashboard\CommonBundle\Form\Type\ProfileMessageType;

class DealerController extends Controller
{
    /**
     * @Route("/dealer", name="dealer")
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
                        $this->get('translator')->trans('<strong>????????????!</strong> ???? ?????????????? ???????????????? Captcha.') . '</div>'
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
                    $this->get('translator')->trans('<strong>????????????!</strong> ?????????? ?????????????????????? ?????????? %message% ?????? ???????????????????? ?? ??????????????.',array("%message%" => $registerForm['email']->getData())) . '</div>'
                );
                
                return $this->render('DashboardCommonBundle:Dealer:register.html.twig', array('registerForm' => $registerForm->createView(),
                                                                                              'success' => $success,"settings" => $settings, "locale" => $locale));
            }
            
            $mailPassword = $dealer->getPassword();
            $role = $this->getDoctrine()->getRepository("DashboardCommonBundle:Role")->findOneByRole("ROLE_DEALER");
            $password = $this->get('security.password_encoder')->encodePassword($dealer, $dealer->getPassword());
            
            $dealer->setUsername($dealer->getEmail()); 
            $dealer->setIsActive(1);
            $dealer->setIsConfirm(0);
            $dealer->addRole($role);
            $role->addUser($dealer);
            $dealer->setAdvertNumber(0);
            $dealer->setPassword($password);
            $dealer->setIsAlertBroadcast(1);
            $dealer->setIsAlertNewMessage(1);
            $dealer->setIsAlertNewOrder(1);
            $dealer->getDealerinfo()->setUser($dealer);
            $cityCode = $manager->getRepository("DashboardCommonBundle:CityCode")->findOneByCode($registerForm['dealerinfo']['cityCode']->getData());
            if($cityCode){
                $dealer->getDealerinfo()->setCityCode($cityCode);
            }
            $dealer->getUserinfo()->setUser($dealer);
            $dealer->getUserinfo()->setCity($dealer->getDealerinfo()->getCity());
            $dealer->getUserinfo()->setCityCode($dealer->getDealerinfo()->getCityCode());
            
            $manager->persist($dealer);
            $manager->persist($role);
            $manager->flush();
            
            $register = new Register();
            $key = md5(md5(md5($password . rand(1, 99999999)) . rand(1, 99999)) . $dealer->getEmail());
            $register->setConfirmKey($key);
            $register->setDate(new \DateTime("now"));
            
            $query = $manager->createQuery('SELECT u FROM Dashboard\CommonBundle\Entity\User u ORDER BY u.id ASC');
            $users = $query->getResult();
            $register->setUserId($users[count($users) - 1]->getId());
            $manager->persist($register);
            $manager->flush();
            
            //send confirmation link to email
            $message = \Swift_Message::newInstance()
                ->setSubject($this->get('translator')->trans('?????????????????????? ???? ??????????') . $settings->getSiteName())
                ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                ->setTo($registerForm['email']->getData())
                ->setBody(
                    $this->renderView(
                        'Emails/userregistration.html.twig',
                        array('user' => $dealer, "password" => $mailPassword, "settings" => $settings, "key" => $key,"register" => $register)
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
     * @Route("/dealerpage/{dealerName}", name="dealerPage", defaults={"dealerName" : 0})
     */
    public function dealerPageAction($dealerName, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $sessionUser = $this->get('security.context')->getToken()->getUser();
        
        $query = $manager->createQuery("SELECT u,r FROM DashboardCommonBundle:User u LEFT JOIN u.roles r LEFT JOIN u.dealerinfo ud WHERE u.isActive = 1 AND r.role='ROLE_DEALER' AND ud.company = '" . $dealerName . "'");
        
        try{
            $dealer = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $dealer = 0;
        }
        
        if(!$dealer){
            return $this->createNotFoundException();
        }
        
        $profileMessageForm = null;
        
        if($this->getUser()){
            
            $profileMessage = new Message();
            $profileMessageForm = $this->createForm(new ProfileMessageType($manager), $profileMessage);
            
            $profileMessageForm->handleRequest($request);

            if ($profileMessageForm->isSubmitted() && $profileMessageForm->isValid())
            {
                $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $profileMessageForm['userTo']->getData(), "userTo" => $profileMessageForm['userFrom']->getData()));
                
                if($blacklistItem)
                {
                    $this->addFlash(
                            'notice',
                            '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                                $this->get('translator')->trans('<strong>????????????!</strong> ???????? ???????????????????????? ?????????????? ?????? ?? ???????????? ????????????.') . '</div>'
                        );
                    
                    return $this->redirectToRoute("dealerPage", array("dealerName" => $dealer->getDealerinfo()->getCompany()));
                }
                
                if($dealer->getId() != $sessionUser->getId())
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
                    
                    if($dealer->getIsAlertNewMessage())
                    {
                        $messageSent = \Swift_Message::newInstance()
                            ->setSubject('?????????? ?????????????????? ???? ?????????? ' . $settings->getSiteName())
                            ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                            ->setTo($dealer->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'Emails/productmessage.html.twig',
                                    array('message' => $profileMessage->getMessage(),
                                          'user' => $sessionUser, "settings" => $settings)
                                ),
                                'text/html'
                            );

                            $this->get('mailer')->send($messageSent);
                    }
                    
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>??????????????!</strong> ???????? ?????????????????? ????????????????????.') . '</div>'
                    );
                    
                }
                else {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>????????????!</strong> ???? ???? ???????????? ???????????? ?????????????????? ????????.') . '</div>'
                    );
                }
                
                return $this->redirectToRoute("dealerPage", array("dealerName" => $dealer->getDealerinfo()->getCompany()));
            }
        }
        
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL AND c.isActive = 1 ORDER BY c.sortorder');
        
        try{
            $categories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $categories = 0;
        }
        
        foreach($categories as $category){
            $productsNum = 0;
            $this->getCategoryProducts($category, $productsNum);
            $category->setAllProductsNumber($productsNum);
        }
        
        $coordinates = new ArrayCollection();
        
        if($dealer->getDealerinfo()->getSalons()){
            foreach($dealer->getDealerinfo()->getSalons() as $salon){
                $address = str_replace(" ", "+", $salon->getAddress());
                $coords = $this->get('app.maps')->getCoordinatesByAddress($address, $settings->getGoogleMapsKey());
                if($coords['status'] == "OK"){
                    $coordinates->set($salon->getId(), $coords['results'][0]['geometry']['location']);
                }
            }
        }
        
        if($dealer->getTargetReviews()){
            $temp = $dealer->getTargetReviews();
            foreach($temp as $review){
                if($review->getStatus()->getId() != $settings->getPublicReviewStatus()->getId()){
                    $dealer->removeTargetReview($review);
                }
            }
        }
        
        return $this->render('DashboardCommonBundle:Dealer:dealer.html.twig', array("locale" => $locale,
                                                                                    "settings" => $settings,
                                                                                    "categories" => $categories,
                                                                                    "dealer" => $dealer,
                                                                                    "pagination" => 0,
                                                                                    "coordinates" => $coordinates,
                                                                                    "profileMessageForm" => ($profileMessageForm) ? $profileMessageForm->createView() : null));
    }
    
    private function getCategoryProducts($category, &$productsNum){
        $productsNum += count($category->getProduct());
        
        if($category->getChildren()){
            foreach($category->getChildren() as $children){
                $this->getCategoryProducts($children, $productsNum);
            }
        }
    }
    
    /**
     * @Route("/dealers", name="dealers")
     */
    public function dealersAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $sql = "SELECT u,r FROM DashboardCommonBundle:User u LEFT JOIN u.roles r LEFT JOIN u.dealerinfo ud LEFT JOIN ud.autos ua WHERE u.isActive = 1 AND r.role='ROLE_DEALER'";
        
        if($request->server->get("REQUEST_METHOD") == "POST"){
            if($request->request->get("dealerAutoType")){
                switch($request->request->get("dealerAutoType")){
                    case 'new':
                        $sql .= ' AND ud.isNewAuto = 1';
                    break;

                    case 'old':
                        $sql .= ' AND ud.isOldAuto = 1';
                    break; 
                }
            }
            
            if($request->request->get("dealerName")){
                $sql .= ' AND ud.company LIKE \'%' . $request->request->get("dealerName") . '%\'';
            }
            
            if($request->request->get("dealerAutoId")){
                $auto = $manager->getRepository("DashboardCommonBundle:Category")->find($request->request->get("dealerAutoId"));
                if($auto){
                    $sql .= ' AND ua.id = ' . $auto->getId();
                }
            }
        }
        
        $query = $manager->createQuery($sql);
        
        try{
            $dealers = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $dealers = 0;
        }
        
        $autos = new ArrayCollection();
        $coordinates = new ArrayCollection();
        
        if($dealers){
            foreach($dealers as $dealer){
                if($dealer->getDealerInfo()){
                    if($dealer->getDealerInfo()->getAutos()){
                        foreach($dealer->getDealerInfo()->getAutos() as $auto){
                            if(!$autos->get($auto->getId())){
                                $autos->set($auto->getId(), $auto);
                            }
                        }
                    }
                    
                    $address = $dealer->getDealerInfo()->getCity()->getName() . "," . $dealer->getDealerInfo()->getAddress();
                    $address = str_replace(" ", "+", $address);
                    $coords = $this->get('app.maps')->getCoordinatesByAddress($address,$settings->getGoogleMapsKey());
                    if($coords['status'] == "OK"){
                        $coordinates->set($dealer->getId(), $coords['results'][0]['geometry']['location']);
                    }
                }
            }
        }
        
        if($dealers){
            foreach($dealers as $dealer){
                $rating = 0;
                if($dealer->getTargetReviews()){
                    $temp = $dealer->getTargetReviews();
                    foreach($temp as $review){
                        if($review->getStatus()->getId() != $settings->getPublicReviewStatus()->getId()){
                            $dealer->removeTargetReview($review);
                        }else{
                            $rating += $review->getRating();
                        }
                    }
                }
                
                $dealer->getDealerinfo()->setRating(0);
                if(count($dealer->getTargetReviews()) > 0){
                    $dealer->getDealerinfo()->setRating(ceil($rating / count($dealer->getTargetReviews())));
                }
            }
        }
        
        return $this->render('DashboardCommonBundle:Dealer:dealers.html.twig', array("locale" => $locale,
                                                                                     "settings" => $settings,
                                                                                     "dealers" => $dealers,
                                                                                     "autos" => $autos,
                                                                                     "coordinates" => $coordinates));
    }
    
    /**
     * @Route("/dealer/getworkinfo/{dealerId}/{day}/{time}", name="dealerWorkinfo")
     */
    public function getWorkinfoAction($dealerId, $day, $time, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $dealer = $manager->getRepository("DashboardCommonBundle:User")->find($dealerId);
        $weekDays = array("sunday","monday","tuesday","wednesday","thursday","friday","saturday");
        
        if($dealer){
            if($dealer->getDealerinfo()){
                if($dealer->getDealerinfo()->getWorkinfo()){
                    $workInfo = $dealer->getDealerinfo()->getWorkinfo();
                    $function = 'get' . $weekDays[$day];
                    
                    if($workInfo->$function()){
                        if($workInfo->getFullDay()){
                            return new \Symfony\Component\HttpFoundation\JsonResponse(array("error" => 0, "message" => $this->get('translator')->trans("?????????????? ??????????????????????????")));
                        }else{
                            $workStart = $workInfo->getWorkStart();
                            $workEnd = $workInfo->getWorkStop();
                            
                            if($time < $workStart->format("G") || $time > $workEnd->format("G")){
                                return new \Symfony\Component\HttpFoundation\JsonResponse(array("error" => 0, "message" => $this->get('translator')->trans("?????????????? ???? %time%", array("%time%" => $workInfo->getWorkStart()->format("H:i")))));
                            }
                            if($time >= $workStart->format("G") && $time <= $workEnd->format("G")){
                                return new \Symfony\Component\HttpFoundation\JsonResponse(array("error" => 0, "message" => $this->get('translator')->trans("?????????????? ???? %time%", array("%time%" => $workInfo->getWorkStop()->format("H:i")))));
                            }
                        }
                    }else{
                         return new \Symfony\Component\HttpFoundation\JsonResponse(array("error" => 0, "message" => $this->get('translator')->trans("??????????????")));
                    }
                }
            }
        }
    }
    
    /**
     * @Route("/dealer/getsalonworkinfo/{salonId}/{day}/{time}", name="dealerSalonWorkinfo")
     */
    public function getSalonWorkinfoAction($salonId, $day, $time, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $salon = $manager->getRepository("DashboardCommonBundle:DealerSalon")->find($salonId);
        $weekDays = array("sunday","monday","tuesday","wednesday","thursday","friday","saturday");
        
        if($salon){
            if($salon->getWorkinfo()){
                $workInfo = $salon->getWorkinfo();
                $function = 'get' . $weekDays[$day];
                    
                if($workInfo->$function()){
                    if($workInfo->getFullDay()){
                        return new \Symfony\Component\HttpFoundation\JsonResponse(array("error" => 0, "message" => $this->get('translator')->trans("?????????????? ??????????????????????????")));
                    }else{
                        $workStart = $workInfo->getWorkStart();
                        $workEnd = $workInfo->getWorkStop();
                            
                        if($time < $workStart->format("G") || $time > $workEnd->format("G")){
                            return new \Symfony\Component\HttpFoundation\JsonResponse(array("error" => 0, "message" => $this->get('translator')->trans("?????????????? ???? %time%", array("%time%" => $workInfo->getWorkStart()->format("H:i")))));
                        }
                        if($time >= $workStart->format("G") && $time <= $workEnd->format("G")){
                            return new \Symfony\Component\HttpFoundation\JsonResponse(array("error" => 0, "message" => $this->get('translator')->trans("?????????????? ???? %time%", array("%time%" => $workInfo->getWorkStop()->format("H:i")))));
                        }
                    }
                }else{
                    return new \Symfony\Component\HttpFoundation\JsonResponse(array("error" => 0, "message" => $this->get('translator')->trans("??????????????")));
                }
            }
        }
    }
    
    /**
     * @Route("/dealer/getlist", name="dealerList")
     */
    public function getListAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $joinInstructions = '';
        
        if($request->request->get('dealerAutoId')){
            $joinInstructions .= " LEFT JOIN ud.autos uda";
        }
        
        $sql = "SELECT u,r FROM DashboardCommonBundle:User u LEFT JOIN u.roles r LEFT JOIN u.dealerinfo ud" . $joinInstructions . " WHERE u.isActive = 1 AND r.role='ROLE_DEALER'";
        
        if($request->request->get('dealerAutoType')){
            switch($request->request->get('dealerAutoType')){ 
                case 'new':
                    $sql .= ' AND ud.isNewAuto = 1';
                break;

                case 'old':
                    $sql .= ' AND ud.isOldAuto = 1';
                break;
            }
        }
        
        if($request->request->get('dealerAutoId')){
            $sql .= ' AND uda.id = ' . (int)$request->request->get('dealerAutoId');
        }
        
        $query = $manager->createQuery($sql);
        
        try{
            $dealers = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $dealers = 0;
        }
        
        if($dealers){
            foreach($dealers as $dealer){
                $rating = 0;
                if($dealer->getTargetReviews()){
                    $temp = $dealer->getTargetReviews();
                    foreach($temp as $review){
                        if($review->getStatus()->getId() != $settings->getPublicReviewStatus()->getId()){
                            $dealer->removeTargetReview($review);
                        }else{
                            $rating += $review->getRating();
                        }
                    }
                }
                
                $dealer->getDealerinfo()->setRating(0);
                if(count($dealer->getTargetReviews()) > 0){
                    $dealer->getDealerinfo()->setRating(ceil($rating / count($dealer->getTargetReviews())));
                }
            }
        }
        
        return $this->render('DashboardCommonBundle:Dealer:list.html.twig', array("dealers" => $dealers));
        
    }
    
}
