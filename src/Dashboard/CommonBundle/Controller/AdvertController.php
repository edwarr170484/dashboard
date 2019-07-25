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

use Dashboard\CommonBundle\Entity\Product;
use Dashboard\CommonBundle\Entity\FavoriteProducts;
use Dashboard\CommonBundle\Entity\ProductFotos;
use Dashboard\CommonBundle\Entity\ProductOptions;
use Dashboard\CommonBundle\Entity\ProductService;
use Dashboard\CommonBundle\Entity\FilterValue;
use Dashboard\CommonBundle\Entity\UserPurseHistory;
use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\UserInfo;

use Dashboard\CommonBundle\Form\Type\UserAddAdvertType;
use Dashboard\CommonBundle\Form\Type\EditProductType;

class AdvertController extends Controller
{
    /**
     * @Route("/account/addproduct", name="addproduct")
     * @Route("/{_locale}/account/addproduct", name="addproductLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function addProductAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $textblock = $manager->getRepository("DashboardCommonBundle:Textblock")->find(1);
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $errors = 0;
        $allservices = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.isUserpage = 0 AND p.locale=" . $locale->getId() . " AND p.route = 'addproduct'" );

        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $page = 0;
        }
        $roles = $user->getRoles();
        //проверить превышено ли количество разрешенных для этого пользователя объявлений
        if($user->getProducts()->count() == ($user->getAdvertNumber() + $roles[0]->getAdvertNumber()))
        {
            return $this->render('DashboardCommonBundle:User:outlimitadvert.html.twig', array("settings" => $settings));
        }
        
        $product = new Product();
        
        $addAdvertForm = $this->createForm(new UserAddAdvertType($manager, $user->getUserinfo(), $allservices,$this->get('translator'),$locale), $product);
        
        //get all categories from database  
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL' );
        $categories = $query->getResult();
        
        $addAdvertForm->handleRequest($request);
        
        $filters = ($request->request->get('filter')) ? $this->renderFiltersView($addAdvertForm['category']->getData(), $request->request->get('filter'), $request) : 0;
        
        $validator = $this->get('validator');
        $errorsValid = $validator->validate($product);
            
        if (count($errorsValid) > 0) 
        {
            
            foreach($errorsValid as $error)
                $this->addFlash(
                    'notice',
                    '<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                    <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true">x</span></button>' . 
                    $this->get('translator')->trans('<strong>Kļūda!</strong>%message%.', array("%message%" => $error->getMessage())) . '</div>'
                );
                 
            return $this->render('DashboardCommonBundle:Product:addproduct.html.twig', array("addAdvertForm" => $addAdvertForm->createView(),
                                                                                         "categories" => $categories,
                                                                                         "textblock" => $textblock,
                                                                                         "page" => $page,
                                                                                         "filters" => $filters,
                                                                                         "role" => $roles[0],
                                                                                         "locale" => $locale,
                                                                                         "settings" => $settings));
        }

        if($addAdvertForm->isValid())
        {            
            if(!$addAdvertForm['authorName']->getData() || !$addAdvertForm['authorEmail']->getData() || !$addAdvertForm['authorPhone']->getData() || !$addAdvertForm['region']->getData() || !$addAdvertForm['city']->getData() || !$addAdvertForm['name']->getData() || !$addAdvertForm['term']->getData() || !$addAdvertForm['description']->getData() || !$addAdvertForm['price']->getData())
            {
                $this->addFlash(
                    'notice_errors',
                    '<div class = "alert alert-danger alert-dismissible fade in">
                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true">x</span></button>' .
                     $this->get('translator')->trans('<strong>Kļūda!</strong> Aizpildiet visus laukus, kas atzīmēti ar *.') . '</div>'
                );
                
                $errors = 1;
            }
            
            if(strlen(strip_tags($addAdvertForm['description']->getData())) > 3000)
            {
                $this->addFlash(
                    'notice_errors',
                    '<div class = "alert alert-danger alert-dismissible fade in">
                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true">x</span></button>' .
                     $this->get('translator')->trans('<strong>Kļūda!</strong> Apraksts nedrīkst pārsniegt 3000 rakstzīmes.') . '</div>'
                );
                
                $errors = 1;
            }
            
            if(!$addAdvertForm['category']->getData())
            {
                $this->addFlash(
                    'notice_errors',
                    '<div class = "alert alert-danger alert-dismissible fade in">
                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true">x</span></button>' .
                     $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs neesat izvēlējies savu reklāmu kategoriju.') . '</div>'
                );
                
                $errors = 1;
            }
            
            if(!$addAdvertForm['termsAccept']->getData())
            {
                $this->addFlash(
                    'notice_errors',
                    '<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>' .
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Pakalpojumam ir jāpieņem <a href="" data-toggle="modal" data-target="#userAgreementModal"> pakalpojumu sniegšanas noteikumi</a>.') .'</div>'
                );
                
                $errors = 1;
            }
            
            if(preg_match("/[^0-9]/",$addAdvertForm['authorPhone']->getData()) || strlen($addAdvertForm['authorPhone']->getData()) != 8)
            {
                $this->addFlash(
                    'notice_errors',
                    '<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>' . 
                     $this->get('translator')->trans('<strong>Kļūda!</strong> Tālruņa numuram ir jābūt tikai 8 cipariem.') . '</div>'
                );
                
                $errors = 1;
            }

            if(!$addAdvertForm['viewcommon']->getData() && !$addAdvertForm['viewpremium']->getData() && !$addAdvertForm['viewselected']->getData())
            {
                $this->addFlash(
                    'notice_errors',
                    '<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>' .
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Jums ir jāizvēlas reklāmas izvietojuma veids (Normāls, Premium, Dedicated).') .'</div>'
                );
                
                $errors = 1;
            }
            
            if($errors)
            {
                
                return $this->render('DashboardCommonBundle:Product:addproduct.html.twig', array("addAdvertForm" => $addAdvertForm->createView(),
                                                                                                 "categories" => $categories,
                                                                                                 "textblock" => $textblock,
                                                                                                 "page" => $page,
                                                                                                 "filters" => $filters,
                                                                                                 "role" => $roles[0],
                                                                                                 "locale" => $locale,
                                                                                                 "settings" => $settings));
            }
            
            $product->setUser($user);
            
            $i = 0;
            $fotos = $product->getFotos();
            foreach($fotos as $foto)
            {
                $foto->setProduct($product);
                $foto->setTitle($product->getName());
                $foto->setSortorder($i + 1);
                $i++;
            }
            
            $product->setDateAdded(new \DateTime("now"));
            $product->setDateEdited(new \DateTime("now"));
            $product->setMetaTagTitle($product->getName());
            if($settings->getIsModerate())
            {
                $product->setIsActive(0);
                $product->setIsConfirm(0);
            }
            else
            {
                $product->setIsActive(1);
                $product->setIsConfirm(1);
            }
            $product->setIsCorrect(0);
            $product->setIsBlocked(0);
            
            //set filters values
            if($request->request->get('filter'))
            {
                foreach($request->request->get('filter') as $key => $value)
                {
                    if(is_array($value))
                    {
                        foreach($value as $key => $val)
                        {
                            $filterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($val);
                    
                            if($filterValue)
                                $product->addFilter($filterValue);
                        }
                    }
                    else
                    {
                        $filter = $manager->getRepository("DashboardCommonBundle:Filter")->find($key);
                        
                        if($filter)
                        {
                            if($filter->getType() == 'selectable' || $filter->getType() == 'input')
                            {
                                $filterValue = new FilterValue();
                                $filterValue->setFilter($filter);
                                $filterValue->setValue($value);
                                $manager->persist($filterValue);
                                $product->addFilter($filterValue);
                            }
                            else
                            {
                                $filterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($value);

                                if($filterValue)
                                    $product->addFilter($filterValue);
                            }
                        }    
                    }
                }
            }
            
            $helpers = $this->get('app.helpers');
            $product->setTranslit($helpers->translit($product->getName()));
            
            $manager->persist($product);
            $manager->flush();
            
            if($addAdvertForm['viewpremium']->getData())
            {
                $service = $manager->getRepository("DashboardCommonBundle:Service")->find(1);
                
                if($service)
                {
                    if($user->getUserpurse()->getBalanse() >= $service->getPrice())
                    {
                        $premiumService = new ProductService();
                        $premiumService->setProduct($product);
                        $premiumService->setService($service);
                        
                        $date = new \DateTime("now");
                        $premiumService->setDateAdded($date);
                        $dateEnd = clone $date;
                        $dateEnd->add(new \DateInterval('P' . $service->getDays() . 'D'));
                        $premiumService->setDateEnd($dateEnd);
                        
                        $premiumService->setIsActive(true);

                        $manager->persist($premiumService);
                        
                        $product->setViewpremium(true);
                        $product->setViewcommon(false);
                        $product->setViewselected(false);
                        
                        $userPurseHistory = new UserPurseHistory();
                        $userPurseHistory->setActionDate(new \DateTime("now"));
                        $userPurseHistory->setAction($this->get('translator')->trans("Maksa par pakalpojuma aktivizēšanu") ." " . $service->getTitle() . ". " . $this->get('translator')->trans("Izrakstīts") . " " . $service->getPrice() . $settings->getCurrency()->getName() . ".");
                        $userPurseHistory->setCurrentBalanse($user->getUserpurse()->getBalanse() - $service->getPrice());
                        $userPurseHistory->setUserpurse($user->getUserpurse());
                        
                        $user->getUserpurse()->setBalanse($user->getUserpurse()->getBalanse() - $service->getPrice());
                        $manager->persist($user);
                    }
                    else
                    {
                        $this->addFlash(
                            'notice',
                            '<div class = "alert alert-warning alert-dismissible fade in" role = "alert">
                             <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true">x</span></button>' . 
                             $this->get('translator')->trans('<strong>Informācija:</strong> jūsu kontam nav pietiekami daudz naudas prēmiju izvietošanai. Reklāmu pievienoja kā parasti.') . '</div>'
                        );
                        
                        $product->setViewpremium(false);
                        $product->setViewcommon(true);
                        $product->setViewselected(false);
                    }
                }
            }
            elseif($addAdvertForm['viewselected']->getData())
            {
                $service = $manager->getRepository("DashboardCommonBundle:Service")->find(2);
                
                if($service)
                {
                    if($user->getUserpurse()->getBalanse() >= $service->getPrice())
                    {
                        $selectedService = new ProductService();
                        $selectedService->setProduct($product);
                        $selectedService->setService($service);
                        $date = new \DateTime("now");
                        $selectedService->setDateAdded($date);
                        $dateEnd = clone $date;
                        $dateEnd->add(new \DateInterval('P' . $service->getDays() . 'D'));
                        $selectedService->setDateEnd($dateEnd);
                        
                        $selectedService->setIsActive(true);

                        $manager->persist($selectedService);
                        
                        $product->setViewpremium(false);
                        $product->setViewcommon(false);
                        $product->setViewselected(true);
                        
                        $userPurseHistory = new UserPurseHistory();
                        $userPurseHistory->setActionDate(new \DateTime("now"));
                        $userPurseHistory->setAction($this->get('translator')->trans("Maksa par pakalpojuma aktivizēšanu") ." " . $service->getTitle() . ". " . $this->get('translator')->trans("Izrakstīts") . " " . $service->getPrice() . $settings->getCurrency()->getName() . ".");
                        $userPurseHistory->setCurrentBalanse($user->getUserpurse()->getBalanse() - $service->getPrice());
                        $userPurseHistory->setUserpurse($user->getUserpurse());
                        
                        $user->getUserpurse()->setBalanse($user->getUserpurse()->getBalanse() - $service->getPrice());
                        $manager->persist($user);
                    }
                    else
                    {
                        $this->addFlash(
                            'notice',
                            '<div class = "alert alert-warning alert-dismissible fade in" role = "alert">
                             <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true">x</span></button>' . 
                             $this->get('translator')->trans('<strong>Informācija:</strong> jūsu kontam nav pietiekami daudz naudas prēmiju izvietošanai. Reklāmu pievienoja kā parasti.').'</div>'
                        );
                        
                        $product->setViewpremium(false);
                        $product->setViewcommon(true);
                        $product->setViewselected(false);
                    }
                }
            }
            else
            {
                $product->setViewpremium(false);
                $product->setViewcommon(true);
                $product->setViewselected(false);
            }
            
            $manager->persist($product);
            $manager->flush();
            
            if($settings->getIsModerate())
            {
                //send an email
                $message = \Swift_Message::newInstance()
                ->setSubject('Добавлено новое объявление на модерацию на сайте gribupardot.sunweb.by')
                ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                ->setTo($settings->getAdminEmail())
                ->setBody(
                    $this->renderView(
                        'Emails/newproduct.html.twig',
                        array("product" => $product)
                    ),
                    'text/html'
                );

                $this->get('mailer')->send($message);
            }
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                ' . $settings->getSuccessAddAdvertText() . '</div>')
            );
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("addproductSuccess");
            }
            else
            {
                return $this->redirectToRoute("addproductSuccessLocale", array("_locale" => $locale->getCode()));
            }
            
        }
        
        return $this->render('DashboardCommonBundle:Product:addproduct.html.twig', array("addAdvertForm" => $addAdvertForm->createView(),
                                                                                         "categories" => $categories,
                                                                                         "textblock" => $textblock,
                                                                                         "page" => $page,
                                                                                         "filters" => $filters,
                                                                                         "role" => $roles[0],
                                                                                         "locale" => $locale,
                                                                                         "settings" => $settings));
    }
    
    /**
     * @Route("/account/success/addproduct", name="addproductSuccess")
     * @Route("/{_locale}/account/success/addproduct", name="addproductSuccessLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function addProductSuccessAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.locale=" . $locale->getId() . " AND p.isUserpage = 0 AND p.route = 'addproductSuccess'" );
        
        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            throw $this->createNotFoundException(); 
        }
        
        return $this->render('DashboardCommonBundle:Product:success.html.twig', array("page" => $page,"locale" => $locale,"settings" => $settings));
    }
    
    /**
     * @Route("/account/product/edit/{productId}", name="editproduct")
     * @Route("/{_locale}/account/product/edit/{productId}", name="editproductLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function editProductAction($productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $textblock = $manager->getRepository("DashboardCommonBundle:Textblock")->find(1);
        $user = $this->get('security.context')->getToken()->getUser();
        $originalFotos = new ArrayCollection();
        $originalFilters = new ArrayCollection();
        $receivedFilters = new ArrayCollection();
        $originalMainFoto = 0;
        $roles = $user->getRoles();
        $allservices = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId);
        
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL' );
        $categories = $query->getResult();
        
        if($product)
        {
            $productCurrentService = $product->getService();
            
            if($product->getUser()->getId() != $user->getId())
            {
                $this->addFlash(
                    'notice',
                    '<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true">x</span></button>' .
                     $this->get('translator')->trans('<strong>Kļūda!</strong> Šī nav jūsu reklāma un jūs to nevarat rediģēt.') . '</div>'
                );
                
                return $this->redirectToRoute("account_products");
            }
            
            $originalMainFoto = $product->getMainfoto();
            
            foreach ($product->getFotos() as $foto) {
                $originalFotos->add($foto);
            }
            
            foreach ($product->getFilters() as $filter) {
                $originalFilters->add($filter);
            }
            
            $productForm = $this->createForm(new EditProductType($manager, $user->getUserinfo(), $allservices, $this->get('translator'), $locale), $product);
            
            $fitersTmp = array();
            $filters = '';
            
            $i = 0;
            if($product->getFilters())
            {
                foreach($product->getFilters() as $filter)
                {
                    if($filter->getFilter()->getType() == 'checkbox')
                    {
                        $fitersTmp[$filter->getFilter()->getId()] = array();
                    }
                }
                
                foreach($product->getFilters() as $filter)
                {
                    if($filter->getFilter()->getType() == 'checkbox')
                    {
                        array_push($fitersTmp[$filter->getFilter()->getId()], $filter->getId());
                    }
                    else
                        $fitersTmp[$filter->getFilter()->getId()] = $filter->getId();
                }
                
                $filters = $this->renderFiltersView($product->getCategory(), $fitersTmp, $request);
            }

            $productForm->handleRequest($request);
            
            $validator = $this->get('validator');
            $errorsValid = $validator->validate($product);

            if (count($errorsValid) > 0) 
            {
                foreach($errorsValid as $error)
                        $this->addFlash(
                               'notice',
                               $this->get('translator')->trans('<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                            <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>
                            <strong>Kļūda!</strong>%message%. </div>', array("%message%" => $error->getMessage()))
                       );

                return $this->render('DashboardCommonBundle:Product:editproduct.html.twig', array("productForm" => $productForm->createView(),
                                                                                                  "categories" => $categories,
                                                                                                  "product" => $product,
                                                                                                  "textblock" => $textblock,
                                                                                                  "filters" => $filters,
                                                                                                  "isBlocked" => $product->getIsBlocked(),
                                                                                                  "isCorrect" => $product->getIsCorrect(),
                                                                                                  "role" => $roles[0],
                                                                                                  "productId" => $product->getId(),
                                                                                                  "locale" => $locale,
                                                                                                  "settings" => $settings));
            }
            
            if($productForm->isValid()&& $productForm->isSubmitted())       
            {
                $errors = 0;
                if(preg_match("/[^0-9]/",$productForm['authorPhone']->getData()) || strlen($productForm['authorPhone']->getData()) != 8)
                {
                    $this->addFlash(
                        'notice_errors',
                        '<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                         <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>' . 
                         $this->get('translator')->trans('<strong>Kļūda!</strong> Tālruņa numuram ir jābūt tikai 8 cipariem.') . '</div>'
                    );

                    $errors = 1;
                }
                
                if(!$productForm['authorName']->getData() || !$productForm['authorEmail']->getData() || !$productForm['authorPhone']->getData() || !$productForm['region']->getData() || !$productForm['city']->getData() || !$productForm['name']->getData() || !$productForm['term']->getData() || !$productForm['description']->getData() || !$productForm['price']->getData())
                {
                    $this->addFlash(
                        'notice_errors',
                        '<div class = "alert alert-danger alert-dismissible fade in">
                         <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true">x</span></button>' .
                         $this->get('translator')->trans('<strong>Kļūda!</strong> Aizpildiet visus laukus, kas atzīmēti ar *.') . '</div>'
                    );

                    $errors = 1;
                }
                
                if(strlen(strip_tags($addAdvertForm['description']->getData())) > 3000)
                {
                    $this->addFlash(
                        'notice_errors',
                        '<div class = "alert alert-danger alert-dismissible fade in">
                         <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true">x</span></button>' .
                         $this->get('translator')->trans('<strong>Kļūda!</strong> Apraksts nedrīkst pārsniegt 3000 rakstzīmes.') . '</div>'
                    );

                    $errors = 1;
                }

                if(!$productForm['category']->getData())
                {
                    $this->addFlash(
                        'notice_errors',
                        '<div class = "alert alert-danger alert-dismissible fade in">
                         <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true">x</span></button>' .
                         $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs neesat izvēlējies savu reklāmu kategoriju.') . '</div>'
                    );

                    $errors = 1;
                }
                
                if($errors)
                {                    
                    return $this->render('DashboardCommonBundle:Product:editproduct.html.twig', array("productForm" => $productForm->createView(),
                                                                                          "categories" => $categories,
                                                                                            "product" => $product,
                                                                                       "textblock" => $textblock,
                                                                                       "filters" => $filters,
                                                                                       "isBlocked" => $product->getIsBlocked(),
                                                                                       "isCorrect" => $product->getIsCorrect(),
                                                                                       "role" => $roles[0],
                                                                                       "productId" => $product->getId(),
                                                                                       "locale" => $locale,
                                                                                       "settings" => $settings));
                }
                
                $category = $manager->getRepository("DashboardCommonBundle:Category")->findOneById($productForm['category']->getData());
            
                if($category)
                {
                    $product->setCategory($category);
                }
                
                if($originalMainFoto != $product->getMainfoto())
                {
                    if($originalMainFoto)
                    {
                        if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $originalMainFoto))
                        {
                            $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $originalMainFoto);
                        }
                    }
                }
                
                if($originalFotos)
                {
                    foreach ($originalFotos as $foto) 
                    {       
                        if (false === $product->getFotos()->contains($foto)) 
                        {
                            if($foto->getFoto())
                            {
                                if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto()))
                                {
                                    $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto());
                                }
                            }
                            $foto->setProduct(null);
                            $manager->remove($foto);
                        }
                    }
                } 

                if($product->getFotos())
                {
                    foreach($product->getFotos() as $key => $item)
                    {
                        $item->setProduct($product);
                        $item->setTitle($product->getName());
                        
                        $manager->persist($item);
                    }
                }
                
                if($originalFilters)
                {
                    foreach($originalFilters as $filter)
                        $product->removeFilter($filter);
                }
                
                if($request->request->get('filter'))
                { 
                    foreach($request->request->get('filter') as $key => $value)
                    {
                        if($value)
                        {
                            if(is_array($value))
                            {
                                foreach($value as $key => $val)
                                {
                                    $filter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($val);
                                    $receivedFilters->add($filter);
                                }
                            }
                            else {
                                
                                $filter = $manager->getRepository("DashboardCommonBundle:Filter")->find($key);
                        
                                if($filter)
                                {
                                    if($filter->getType() == 'selectable' || $filter->getType() == 'input')
                                    {
                                        if($product->getFilters())
                                        {
                                            $marker = 0;
                                            foreach($product->getFilters() as $productFilter)
                                            {
                                                if($filter->getId() == $productFilter->getFilter()->getId())
                                                {
                                                    $productFilter->setValue($value);
                                                    $manager->persist($productFilter);
                                                    $receivedFilters->add($productFilter);
                                                    $marker == 1;
                                                    break;
                                                }
                                            }
                                            if(!$marker)
                                            {
                                                $filterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->findOneByValue($value);
                                                
                                                if(!$filterValue)
                                                {
                                                    $filterValue = new FilterValue();
                                                    $filterValue->setFilter($filter);
                                                    $filterValue->setValue($value);
                                                    $manager->persist($filterValue);
                                                }
                                                
                                                $receivedFilters->add($filterValue);
                                            }
                                        }   
                                    }
                                    else
                                    {
                                        $filterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($value);
                                        
                                        if($filterValue)
                                            $receivedFilters->add($filterValue);
                                    }
                                }
                            }
                        }
                    }
                }
                
                if($receivedFilters)
                {
                    foreach($receivedFilters as $filter)
                        $product->addFilter($filter);
                }
                
                $helpers = $this->get('app.helpers');
                $product->setTranslit($helpers->translit($product->getName()));
                
                $product->setDateEdited(new \DateTime("now"));
                
                $manager->persist($product);
                $manager->flush();
                
                if($productForm['viewpremium']->getData())
                {
                    $service = $manager->getRepository("DashboardCommonBundle:Service")->find(1);
                    
                    if($service && ($product->getIsBlocked() == 0))
                    {
                        if($productCurrentService)
                        {
                            if($productCurrentService->getService()->getId() != $service->getId())
                            {
                                $this->addFlash(
                                    'notice',
                                    '<div class = "alert alert-warning alert-dismissible fade in" role = "alert">
                                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt">
                                     <span aria-hidden = "true"> x </span></button>' .
                                     $this->get('translator')->trans('<strong>Informācija!</strong> Pakalpojums %mess1% jau ir saistīts ar šo reklāmu,%mess2% derīguma termiņš. Jaunu pakalpojumu var aktivizēt tikai pēc pašreizējā pakalpojuma beigām.', array("mess1" => $productCurrentService->getService()->getTitle(), "mess2" => $productCurrentService->getDateEnd()->format("Y-m-d H:i:s"))) . '</div>'
                                );
                                
                                if($productCurrentService->getService()->getId() == 1)
                                {
                                    $product->setViewpremium(true);
                                    $product->setViewcommon(false);
                                    $product->setViewselected(false);
                                }
                                if($productCurrentService->getService()->getId() == 2)
                                {
                                    $product->setViewpremium(false);
                                    $product->setViewcommon(false);
                                    $product->setViewselected(true);
                                }
                                
                            }
                        }
                        else
                        {
                            if($user->getUserpurse()->getBalanse() >= $service->getPrice())
                            {
                                    $premiumService = new ProductService();
                                    $premiumService->setProduct($product);
                                    $premiumService->setService($service);
                                    $date = new \DateTime("now");
                                    $premiumService->setDateAdded($date);
                                    $dateEnd = clone $date;
                                    $dateEnd->add(new \DateInterval('P' . $service->getDays() . 'D'));
                                    $premiumService->setDateEnd($dateEnd);
                                    $premiumService->setIsActive(true);

                                    $manager->persist($premiumService);

                                    $product->setViewpremium(true);
                                    $product->setViewcommon(false);
                                    $product->setViewselected(false);

                                    $userPurseHistory = new UserPurseHistory();
                                    $userPurseHistory->setActionDate(new \DateTime("now"));
                                    $userPurseHistory->setAction($this->get('translator')->trans("Maksa par pakalpojuma aktivizēšanu") ." " . $service->getTitle() . ". " . $this->get('translator')->trans("Izrakstīts") . " " . $service->getPrice() . $settings->getCurrency()->getName() . ".");
                                    $userPurseHistory->setCurrentBalanse($user->getUserpurse()->getBalanse() - $service->getPrice());
                                    $userPurseHistory->setUserpurse($user->getUserpurse());

                                    $user->getUserpurse()->setBalanse($user->getUserpurse()->getBalanse() - $service->getPrice());
                                    $manager->persist($user);
                            }
                            else
                            {
                                $this->addFlash(
                                    'notice',
                                    '<div class = "alert alert-warning alert-dismissible fade in" role = "alert">
                             <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true"> x </span> </button>' . 
                             $this->get('translator')->trans('<strong>Informācija:</strong> jūsu kontam nav pietiekami daudz naudas prēmiju izvietošanai. Reklāmu pievienoja kā parasti.') . '</div>'
                                );
                                
                                $product->setViewpremium(false);
                                $product->setViewcommon(true);
                                $product->setViewselected(false);
                            }
                        }
                    }
                }
                elseif($productForm['viewselected']->getData())
                {
                    $service = $manager->getRepository("DashboardCommonBundle:Service")->find(2);

                    if($service && ($product->getIsBlocked() == 0))
                    {
                        if($productCurrentService)
                        {
                            if($productCurrentService->getService()->getId() != $service->getId())
                            {
                                $this->addFlash(
                                    'notice',
                                    '<div class = "alert alert-warning alert-dismissible fade in" role = "alert">
                                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"><span aria-hidden = "true"> x </span> </button>' . 
                                     $this->get('translator')->trans('<strong>Informācija!</strong> Pakalpojums %mess1% jau ir saistīts ar šo reklāmu,%mess2% derīguma termiņš. Jaunu pakalpojumu var aktivizēt tikai pēc pašreizējā pakalpojuma beigām.', array("mess1" => $productCurrentService->getService()->getTitle(), "mess2" => $productCurrentService->getDateEnd()->format("Y-m-d H:i:s"))) . '</div>'
                                );
                                
                                if($productCurrentService->getService()->getId() == 1)
                                {
                                    $product->setViewpremium(true);
                                    $product->setViewcommon(false);
                                    $product->setViewselected(false);
                                }
                                if($productCurrentService->getService()->getId() == 2)
                                {
                                    $product->setViewpremium(false);
                                    $product->setViewcommon(false);
                                    $product->setViewselected(true);
                                }
                            }
                        }
                        else
                        {
                            if($user->getUserpurse()->getBalanse() >= $service->getPrice())
                            {
                                $selectedService = new ProductService();

                                $selectedService->setProduct($product);
                                $selectedService->setService($service);
                                $date = new \DateTime("now");
                                $selectedService->setDateAdded($date);
                                $dateEnd = clone $date;
                                $dateEnd->add(new \DateInterval('P' . $service->getDays() . 'D'));
                                $selectedService->setDateEnd($dateEnd);
                                $selectedService->setIsActive(true);

                                $manager->persist($selectedService);

                                $product->setViewpremium(false);
                                $product->setViewcommon(false);
                                $product->setViewselected(true);

                                $userPurseHistory = new UserPurseHistory();
                                $userPurseHistory->setActionDate(new \DateTime("now"));
                                $userPurseHistory->setAction($this->get('translator')->trans("Maksa par pakalpojuma aktivizēšanu") ." " . $service->getTitle() . ". " . $this->get('translator')->trans("Izrakstīts") . " " . $service->getPrice() . $settings->getCurrency()->getName() . ".");
                                $userPurseHistory->setCurrentBalanse($user->getUserpurse()->getBalanse() - $service->getPrice());
                                $userPurseHistory->setUserpurse($user->getUserpurse());

                                $user->getUserpurse()->setBalanse($user->getUserpurse()->getBalanse() - $service->getPrice());
                                $manager->persist($user);
                            }
                            else
                            {
                                $this->addFlash(
                                    'notice',
                                    '<div class = "alert alert-warning alert-dismissible fade in" role = "alert">
                             <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>' .
                             $this->get('translator')->trans('<strong>Informācija:</strong> jūsu kontam nav pietiekami daudz naudas prēmiju izvietošanai. Reklāmu pievienoja kā parasti.') . '</div>'
                                );
                                
                                $product->setViewpremium(false);
                                $product->setViewcommon(true);
                                $product->setViewselected(false);
                            }
                        }
                    }
                }
                else
                {
                    if($productCurrentService)
                    {
                        if($productCurrentService->getService()->getId() == 1 || $productCurrentService->getService()->getId() == 2)
                        {
                            $this->addFlash(
                                    'notice',
                                    '<div class = "alert alert-warning alert-dismissible fade in" role = "alert">
                                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>' . 
                                     $this->get('translator')->trans('<strong>Informācija!</strong> Pakalpojums %mess1% jau ir saistīts ar šo reklāmu,%mess2% derīguma termiņš. Jaunu pakalpojumu var aktivizēt tikai pēc pašreizējā pakalpojuma beigām.', array("mess1" => $productCurrentService->getService()->getTitle(), "mess2" => $productCurrentService->getDateEnd()->format("Y-m-d H:i:s"))) . '</div>'
                                );
                            
                            if($productCurrentService->getService()->getId() == 1)
                            {
                                $product->setViewpremium(true);
                                $product->setViewcommon(false);
                                $product->setViewselected(false);
                            }
                            if($productCurrentService->getService()->getId() == 2)
                            {
                                $product->setViewpremium(false);
                                $product->setViewcommon(false);
                                $product->setViewselected(true);
                            }
                            
                        }
                        else
                        {
                            $product->setViewpremium(false);
                            $product->setViewcommon(true);
                            $product->setViewselected(false);

                            if($productCurrentService->getService()->getId() != 3)
                            {
                                $isService->setProduct(null);
                                $isService->setService(null);
                                $product->setService(null);
                                $manager->remove($isService);
                            }
                        }
                    }
                    else
                    {
                        $product->setViewpremium(false);
                        $product->setViewcommon(true);
                        $product->setViewselected(false);

                        $isService = $manager->getRepository("DashboardCommonBundle:ProductService")->findOneBy(array("product" => $product));

                        if($isService)
                        {
                            if($isService->getService()->getId() != 3)
                            {
                                $isService->setProduct(null);
                                $isService->setService(null);
                                $product->setService(null);
                                $manager->remove($isService);
                            }
                        }
                    }
                }
                
                if($product->getIsBlocked())
                {
                    $product->setIsBlocked(0);
                    $product->setIsConfirm(0);
                    $product->setIsActive(0);
                    $product->setIsCorrect(0);
                }
                if($product->getIsCorrect())
                {
                    $product->setIsCorrect(0);
                    $product->setIsConfirm(0);
                    $product->setIsActive(0);
                    $product->setIsBlocked(0);
                }
                
                $manager->persist($product);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                   '<div class = "alert alert-success alert-dismissible fade in" role="alert">
                     <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>' .
                      $this->get('translator')->trans('<strong>Gatavs!</strong> Izmaiņas ir saglabātas.') . '</div>'
                );
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("editproduct", array("productId" => $product->getId()));
                }
                else
                {
                    return $this->redirectToRoute("editproductLocale", array("_locale" => $locale->getCode(),"productId" => $product->getId()));
                }
                
            }
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
        
        return $this->render('DashboardCommonBundle:Product:editproduct.html.twig', array("productForm" => $productForm->createView(),
                                                                                          "categories" => $categories,
                                                                                       "product" => $product,
                                                                                       "textblock" => $textblock,
                                                                                       "filters" => $filters,
                                                                                       "isBlocked" => $product->getIsBlocked(),
                                                                                       "isCorrect" => $product->getIsCorrect(),
                                                                                       "role" => $roles[0],
                                                                                       "productId" => $product->getId(),
                                                                                       "locale" => $locale,
                                                                                       "settings" => $settings));
        
    }
 
    /**
     * @Route("/account/ajaxloadfotos", name="ajaxloadfotos")
     */
    public function ajaxLoadFotosAction(Request $request)
    {
        $fm = new Filesystem();
        $error = 0;
        $settings = $this->getDoctrine()->getRepository("DashboardCommonBundle:Settings")->find(1);
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
                    $image->move('bundles/images/products',$localImageName);

                    if($settings->getWatermark())
                    {
                        $watermark = imagecreatefrompng('bundles/images/site/' . $settings->getWatermark());

                        $marge_right = 10;
                        $marge_bottom = 10;
                        $sx = imagesx($watermark);
                        $sy = imagesy($watermark);

                        switch($extention)
                        {
                            case 'png':
                                $markImage = imagecreatefrompng('bundles/images/products/' . $localImageName);
                                imagecopy($markImage, $watermark, imagesx($markImage) - $sx - $marge_right, imagesy($markImage) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));
                                imagepng($markImage, 'bundles/images/products/' . $localImageName);
                                imagedestroy($markImage);
                            break;

                            case 'jpg':
                                $markImage = imagecreatefromjpeg('bundles/images/products/' . $localImageName);
                                imagecopy($markImage, $watermark, imagesx($markImage) - $sx - $marge_right, imagesy($markImage) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));
                                imagejpeg($markImage, 'bundles/images/products/' . $localImageName);
                                imagedestroy($markImage);
                            break;

                            case 'jpeg':
                                $markImage = imagecreatefromjpeg('bundles/images/products/' . $localImageName);
                                imagecopy($markImage, $watermark, imagesx($markImage) - $sx - $marge_right, imagesy($markImage) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));
                                imagejpeg($markImage, 'bundles/images/products/' . $localImageName);
                                imagedestroy($markImage);
                            break;

                            case 'gif':
                                $markImage = imagecreatefromgif('bundles/images/products/' . $localImageName);
                                imagecopy($markImage, $watermark, imagesx($markImage) - $sx - $marge_right, imagesy($markImage) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));
                                imagegif($markImage, 'bundles/images/products/' . $localImageName);
                                imagedestroy($markImage);
                            break;
                        }
                    }
                    
                    $simpleImage = $this->get('app.simpleimage');
                    $simpleImage->load('bundles/images/products/' . $localImageName);
                    $simpleImage->resizeToWidth(1024);
                    $simpleImage->save('bundles/images/products/' . $localImageName);
                    
                }
                catch (Symfony\Component\HttpFoundation\File\Exception\FileException $e)
                {
                    $error = $this->get('translator')->trans("Iekraušanas laikā radās kļūda. Mēģiniet vēlreiz. Derīgi paplašinājumi: jpg, jpeg, png, gif.");
                }
            }
            else 
            {
                $error = $this->get('translator')->trans("Iekraušanas laikā radās kļūda. Mēģiniet vēlreiz. Derīgi paplašinājumi: jpg, jpeg, png, gif.");
            }

            $data = $error ? array('error' => $error) : array('imageName' => $localImageName );
            
            return new Response(json_encode( $data ));
        }
    }
    
    /**
     * @Route("/{_locale}/account/getcategoryfilters/{categoryId}", name="getcategoryfilters", defaults={"categoryId" : "0", "_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function getCategoryFiltersAction($categoryId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        if($user && $categoryId)
        {
            $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
        }
        
        return $this->render('DashboardCommonBundle:Product:filters.html.twig', array("category" => $category,"locale" => $locale));
    }
    
    private function renderFiltersView($categoryId, $filters, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        return $this->renderView("DashboardCommonBundle:Product:showfilters.html.twig", array("category" => $category, "filters" => $filters,"locale" => $locale));
    }
    
    /**
     * @Route("/{_locale}/account/getsubcategories/{categoryId}", name="getsubcategories", defaults={"categoryId" : "0", "_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function getSubcategories($categoryId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        if($user && ($categoryId == 0))
        {
            $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL' );
            $categories = $query->getResult();
            return $this->render("DashboardCommonBundle:User:category.html.twig", array("items" => $categories, "locale" => $locale));
        }
        
        if($user && ($categoryId != 0))
        {
            $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
            return $this->render("DashboardCommonBundle:Product:subcategories.html.twig", array("category" => $category, "locale" => $locale));
        }
        else
        {
            return new Response("error");
        }
    }
    
    /**
     * @Route("/{_locale}/getsearchfilters/{categoryName}", name="getsearchfilters", defaults={"categoryName" : "0", "_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function getSearchFiltersAction($categoryName, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        if($categoryName)
        {
            $category = $manager->getRepository("DashboardCommonBundle:Category")->findOneByName($categoryName);
        }
        
        return $this->render('DashboardCommonBundle:Product:categoryfilters.html.twig', array("category" => $category,"locale" => $locale));
    }
    
    /**
     * @Route("/account/switchonproduct/{productId}", name="account_switchonproduct", defaults={"productId" : "0"})
     * @Route("/{_locale}/account/switchonproduct/{productId}", name="account_switchonproductLocale", defaults={"productId" : "0", "_locale" : "lv"},requirements={"_locale" : "lv|ru"})
     */
    public function switchonProductAction($productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        if($productId)
        {
            $product = $manager->getRepository("DashboardCommonBundle:Product")->findOneBy(array("id" => $productId,
                                                                                                 "user" => $user,
                                                                                                 "isActive" => 0,
                                                                                                 "isConfirm" => 1,
                                                                                                 "isBlocked" => 0));
            if($product)
            {
                $product->setDateAdded(new \DateTime("now"));
                $product->setIsActive(1);
                $manager->persist($product);
                $manager->flush();
                
                $this->addFlash(
                                'notice',
                                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                                $this->get('translator')->trans('<strong>Veiksmīgi!</strong> Jūsu reklāma ir atjaunota.') . '</div>'
                            );
            }
            else
               $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> ' .
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Šo reklāmu nevar nodot tālāk. Varbūt tas nepastāv vai tas jums nepieder.') . '</div>'
                ); 
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute('account_products_current');
            }
            else
            {
                return $this->redirectToRoute("account_products_currentLocale", array("_locale" => $locale->getCode()));
            }
            
        }
        else
        {
            return $this->createNotFoundException();
        }
    }
    
    /**
     * @Route("/check", name="check")
     */
    public function checkAction()
    {
        $manager = $this->getDoctrine()->getManager();
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 1");
        
        try
        {
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
                
            $products = 0;
        }

        $date = new \DateTime("now");
        
        if($products)
        {
            foreach($products as $product)
            {
                $productService = $product->getService();
                if($productService)
                {
                    $diff = $date->diff($productService->getDateEnd());
                    
                    if($diff->invert == 1)
                    {
                       $productService->setProduct(null);
                       $productService->setService(null);
                       $product->setService(null);
                       $manager->remove($productService);
                       
                       $product->setViewpremium(false);
                       $product->setViewcommon(true);
                       $product->setViewselected(false);
                    }
                }
                
                $diff = $date->diff($product->getDateAdded());
                
                if((($diff->d * 24) + $diff->h) > (30 * 24))
                {
                    $product->setIsActive(0);
                }
                
                $nowDay = (int)$date->format("d");
                $editedDay = (int)$product->getDateEdited()->format("d");
                
                if($nowDay != $editedDay)
                {
                    $product->setViewsPerDate(0);
                }
                
                /*$helpers = $this->get('app.helpers');
                $product->setTranslit($helpers->translit($product->getName()));*/
                
                $product->setDateEdited(new \DateTime("now"));
                $manager->persist($product);
            }
            
            $manager->flush();
        }
        
        return new Response(1);
    }
    /**
     * @Route("/checkrating", name="check_rating")
     */
    public function checkRating()
    {
        $manager = $this->getDoctrine()->getManager();
        
        $users = $manager->getRepository("DashboardCommonBundle:User")->findAll();
        
        if($users)
        {
            foreach($users as $user)
            {
                $plusReviews = 0;
                $reviews = $user->getTargetReviews();
                
                if(count($reviews) > 0)
                    {
                        foreach($reviews as $review)
                        {
                            if($review->getStatus() == 1)
                            {
                                $plusReviews++;
                            }
                        }
                        
                        $userRating = ($plusReviews / count($reviews)) * 100;
                    }
                    else
                       $userRating = 0; 
                    
                    $userinfo = $user->getUserinfo();
                    $userinfo->setRating(floor($userRating));
                    
                    $manager->persist($userinfo);
                    
            }
            
            $manager->flush();
        } 
        
        return new Response(2);
    }
}

