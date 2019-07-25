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

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\ProductOrder;
use Dashboard\CommonBundle\Entity\Review;
use Dashboard\CommonBundle\Entity\Message;
use Dashboard\CommonBundle\Entity\Conversation;
use Dashboard\CommonBundle\Entity\Complaint;

use Dashboard\CommonBundle\Form\Type\OrderType;
use Dashboard\CommonBundle\Form\Type\ReviewType;
use Dashboard\CommonBundle\Form\Type\ProductMessageType;
use Dashboard\CommonBundle\Form\Type\ProfileMessageType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Dashboard\CommonBundle\Form\Type\RegionType;
use Dashboard\CommonBundle\Form\Type\RegionFilterType;
use Dashboard\CommonBundle\Entity\Region;
use Dashboard\CommonBundle\Entity\Product;

class DefaultController extends Controller
{ 
    public function getHeaderAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        if($this->get('security.context')->getToken())
            $user = $this->get('security.context')->getToken()->getUser();
        else
            $user = 0;
        $messagesNew = 0;
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($this->getUser())
        {
            $query = $manager->createQuery('SELECT m FROM Dashboard\CommonBundle\Entity\Message m WHERE m.isDeleted <> 1 AND m.userTo = ' . $user->getId() . ' AND m.isNew = 1 AND m.userOwner = ' . $user->getId());
            $messagesNew = $query->getResult();
            
            if(!$user->getIsConfirm())
            {
                $this->addFlash(
                    'notice_header',
                    '<div class="alert alert-warning alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Informācija!</strong> Jūsu konts nav verificēts. Pārejiet uz saiti e-pasta ziņojumā, kas jums tika nosūtīts reģistrācijas citā kontātiks dzēsta 2 dienu laikā. Ja jūs neesat saņēmis vēstuli, lūdzu, rakstiet mums pa pastu <a href="mailto:%mess%">% mess% </a>.',array("%mess%" => $settings->getAdminEmail())) . '</div>'
                );
            }
            
            if(!$user->getUserinfo()->getFirstname() || !$user->getEmail())
            {
                if($locale->getIsDefault())
                {
                    $this->addFlash(
                        'notice_header',
                        '<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                         <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>' . 
                         $this->get('translator')->trans('<strong>Kļūda!</strong> Pilnībā aizpildiet savu profilu <a href="/account/settings"> personīgā konta iestatījumos</a>.') . '</div>'
                    );
                }
                else
                {
                    $this->addFlash(
                        'notice_header',
                        '<div class = "alert alert-danger alert-dismissible fade in" role="alert">
                         <button type = "button" class = "close" data-dismiss = "alert" aria-label = "Aizvērt"> <span aria-hidden = "true"> x </span> </button>' . 
                         $this->get('translator')->trans('<strong>Kļūda!</strong> Pilnībā aizpildiet savu profilu <a href="/%locale%/account/settings"> personīgā konta iestatījumos</a>.', array("%locale%" => $locale->getCode())) . '</div>'
                    );
                }
            }
        
            $user->setFavoriteProducts($manager->createQuery("SELECT p FROM DashboardCommonBundle:FavoriteProducts p WHERE p.userId = " . $user->getId())->getResult());
        }
        
        
        /*if(!$this->get('session')->has('sessionRegion'))
        {
            if($this->getUser())
            {
                $user = $this->get('security.context')->getToken()->getUser();
                if($user->getUserinfo()->getRegion())
                    $this->get('session')->set('sessionRegion', $user->getUserinfo()->getRegion()->getId());
            }
        }
        
        if(!$this->get('session')->has('sessionCity'))
        {
            if($this->getUser())
            {
                $user = $this->get('security.context')->getToken()->getUser();
                if($user->getUserinfo()->getCity())
                    $this->get('session')->set('sessionCity', $user->getUserinfo()->getCity()->getId());
            }
        }*/

        $locales = $manager->getRepository("DashboardCommonBundle:Locale")->findAll();
        $sessionRegion = $manager->getRepository("DashboardCommonBundle:City")->findOneById($this->get('session')->get('sessionRegion'));
        $sessionCity = $manager->getRepository("DashboardCommonBundle:City")->findOneById($this->get('session')->get('sessionCity'));
        
        if($locale->getIsDefault())
        {
            $uri = $request->server->get("REQUEST_URI");
            ($uri == "/") ? $uri='' : 0;
        }
        else
            $uri = '/' . substr($request->server->get("REQUEST_URI"),4,strlen($request->server->get("REQUEST_URI")));
        
        
        
        return $this->render('DashboardCommonBundle:Common:header.html.twig', array("user" => $user,
                                                                                    "messagesNew" => $messagesNew,
                                                                                    "settings" => $settings,
                                                                                    "sessionRegion" => $sessionRegion,
                                                                                    "sessionCity" => $sessionCity,
                                                                                    "locales" => $locales,
                                                                                    "locale" => $locale,
                                                                                    "uri" => $uri));
    }
    
    public function getFooterAction($session, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $regions = $manager->getRepository("DashboardCommonBundle:Region")->findAll();
        $cities = $manager->getRepository("DashboardCommonBundle:City")->findAll();
        $textblock = $manager->getRepository("DashboardCommonBundle:Textblock")->find(1);

        $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\Page p WHERE p.isFooterMenu = 1 AND p.locale=' . $locale->getId() . ' ORDER BY p.sortorder');
        
        try{
            $footerPages = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $footerPages = 0;
        }
        if($session->get('sessionRegion'))
        {
            $region = $manager->getRepository("DashboardCommonBundle:Region")->find($session->get('sessionRegion'));
        }
        else
            $region = 0;
        
        if($session->get('sessionCity'))
        {
            $city = $manager->getRepository("DashboardCommonBundle:City")->find($session->get('sessionCity'));
        }
        else
            $city = 0;
        
        $regionForm = $this->createForm(new RegionType($locale, $region, $city), new Product());
        $regionForm->handleRequest($request);

        return $this->render('DashboardCommonBundle:Common:footer.html.twig', array("regions" => $regions, 
                                                                                    "cities" => $cities,
                                                                                    "settings" => $settings,
                                                                                    "footerPages" => $footerPages,
                                                                                    "textblock" => $textblock,
                                                                                    "locale" => $locale,
                                                                                    "regionForm" => $regionForm->createView()));
    }
    
    public function getBannersAction($page = null, $category = null, $position = null)
    {
        $manager = $this->getDoctrine()->getManager();

        if(!$page && !$category)
        {
            if($position == 'centerpage')
                $banners = $manager->getRepository("DashboardCommonBundle:Banner")->findByPosition("defaultcenter");
            if($position == 'rightcolumn')
                $banners = $manager->getRepository("DashboardCommonBundle:Banner")->findByPosition("defaultright");
            if($position == 'toppage')
                $banners = $manager->getRepository("DashboardCommonBundle:Banner")->findByPosition("defaulttop");
        }
        
        if($page)
        {
            $banners = new ArrayCollection();
            if($page->getBanners())
            {
                foreach($page->getBanners() as $banner)
                {
                    if($banner->getPosition() == $position)
                    {
                        $banners->add($banner);
                    }
                }
            }
            
        }
        
        if($category)
        {
            $banners = new ArrayCollection();
            if($category->getBanners())
            {
                foreach($category->getBanners() as $banner)
                {
                    if($banner->getPosition() == $position)
                    {
                        $banners->add($banner);
                    }
                }
            }
        }
        
        if(count($banners) > 0)
        {
            foreach($banners as $banner)
            {
                if($banner->getDateTo() && $banner->getDateFrom())
                {
                    $today = new \DateTime(date('Y-m-d'));
                    $bannerDateEnd = new \DateTime($banner->getDateTo()->format('Y-m-d'));
                    $bannerDateStart = new \DateTime($banner->getDateFrom()->format('Y-m-d'));
                    
                    if($today > $banner->getDateTo() && $today < $banner->getDateFrom())
                    {
                        $banners->remove($banner);
                    }
                }
            }
        }
        else
        {
            if($position == 'centerpage')
                $banners = $manager->getRepository("DashboardCommonBundle:Banner")->findByPosition("defaultcenter");
            if($position == 'rightcolumn')
                $banners = $manager->getRepository("DashboardCommonBundle:Banner")->findByPosition("defaultright");
            if($position == 'toppage')
                $banners = $manager->getRepository("DashboardCommonBundle:Banner")->findByPosition("defaulttop");
        }
        
        return $this->render('DashboardCommonBundle:Common:banners.html.twig', array("banners" => $banners));
    }
    /**
     * @Route("/", name="main")
     * @Route("/{_locale}", name="mainLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function indexAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.locale=" . $locale->getId() . " AND p.isUserpage = 0 AND p.route = 'main'" );
        
        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $page = 0;
        }
        
        $query = $manager->createQuery("SELECT g,gi FROM DashboardGalleryBundle:Gallery g JOIN g.items gi WHERE g.translit = 'mainslider' AND g.isShow = 1 AND gi.status = 1 AND g.locale=" . $locale->getId() . " ORDER BY gi.sort" );
        
        try{
            $slider = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $slider = 0;
        }
        
        $query = $manager->createQuery('SELECT c,cc FROM Dashboard\CommonBundle\Entity\Category c LEFT JOIN c.children cc WHERE c.parent IS NULL AND c.isActive = 1 ORDER BY c.sortorder, cc.sortorder');
        
        try{
            $categories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $categories = 0;
        }
        
        $selltypes = $manager->getRepository("DashboardCommonBundle:Selltype")->findAll();
        
        //query premium products
        $sql = "SELECT p,ps FROM DashboardCommonBundle:Product p LEFT JOIN p.service ps LEFT JOIN p.user pu WHERE pu.isActive = 1 AND ps.service = 1";
        
        if($this->get('session')->has('sessionCity') && $this->get('session')->get('sessionCity') != "")
        {
            $sql .= " AND p.city = " . $this->get('session')->get('sessionCity');
        }
        
        $sql .= " AND p.isConfirm = 1 AND p.isActive = 1 AND p.isBlocked = 0 ORDER BY ps.dateAdded DESC";
        
        $query = $manager->createQuery($sql)->setMaxResults($settings->getMainpageAdvertsNumber());
        
        try{
            $prmiumProducts = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $prmiumProducts = 0;
        }
        
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Category c WHERE c.parent IS NULL" );
        
        try{
            $allcategories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $allcategories = 0;
        }
        
        $allcities = $manager->getRepository("DashboardCommonBundle:City")->findAll();
        
        return $this->render('DashboardCommonBundle:Default:index.html.twig', array("slider" => $slider,
                                                                                    "categories" => $categories,
                                                                                    "allcategories" => $allcategories,
                                                                                    "prmiumProducts" => $prmiumProducts,
                                                                                    "allcities" => $allcities,
                                                                                    "page" => $page,
                                                                                    "selltypes" => $selltypes,
                                                                                    "locale" => $locale,
                                                                                    "settings" => $settings));
    }
    
    /**
     * @Route("/cahngeView/{view}", name="categoryChangeView", requirements={"view" : "list|table"})
     * 
     */
    public function categoryChangeView($view,Request $request)
    {
        $this->get('session')->set('viewCategory', $view);
        return new Response($this->get('session')->get('viewCategory'));
    }
    
    /**
     * @Route("/category/{categoryName}/{page}", name="category", defaults={"categoryName":null,"page":1})
     * @Route("/{_locale}/category/{categoryName}/{page}", name="categoryLocale", defaults={"_locale" : "lv","categoryName":null,"page":1}, requirements={"_locale" : "lv|ru"})
     */
    public function categoryAction($categoryName, $page, Request $request)        
    {
        $manager = $this->getDoctrine()->getManager();
        $categories = array();
        $products = array();
        $pagination = 0;
        $premiumCategoryProducts = 0;
        $selltypes = $manager->getRepository("DashboardCommonBundle:Selltype")->findAll();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $sessionCity = $this->get('session')->get('sessionCity');
        $view = ($this->get('session')->get('viewCategory')) ? $this->get('session')->get('viewCategory') : 'list';
        
        if($this->get('session')->get('sessionRegion'))
        {
            $region = $manager->getRepository("DashboardCommonBundle:Region")->find($this->get('session')->get('sessionRegion'));
        }
        else
            $region = 0;
        
        if($this->get('session')->get('sessionCity'))
        {
            $city = $manager->getRepository("DashboardCommonBundle:City")->find($this->get('session')->get('sessionCity'));
        }
        else
            $city = 0;
        
        $regionFilterForm = $this->createForm(new RegionFilterType($locale, $region, $city), new Product());
        $regionFilterForm->handleRequest($request);
        
        $query = $manager->createQuery("SELECT c,f FROM DashboardCommonBundle:Category c LEFT JOIN c.filters f WHERE c.name = '" . $categoryName . "' AND c.isActive = 1 ORDER BY f.type DESC");
        
        try{
            $category = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            
            throw $this->createNotFoundException();
        }
        
        array_push($categories, $category);
        $parent = $category->getParent();
        
        //строим хлебную крошку
        while($parent)
        {
            array_push($categories, $parent);
            $parent = $parent->getParent();
        }  
        
        $joinInstructions = '';
        
        if($request->request->get('filter'))
        {
            foreach($request->request->get('filter') as $key => $value)
            {
                if(is_array($value))
                {
                    foreach($value as $key => $val)
                    {
                        if($val != 0)
                            $joinInstructions .= " LEFT JOIN p.filters pf" . $key . " ";
                    }
                }
                else
                {
                    if($value != 0)
                        $joinInstructions .= " LEFT JOIN p.filters pf" . $key . " ";
                }
            }
        }
        if($request->request->get('filterRangeList'))
        {
            foreach($request->request->get('filterRangeList') as $key => $value)
            {
                if($value[0] != 0 || $value[1] != 0)
                {
                    $joinInstructions .= " LEFT JOIN p.filters pf" . $key . " ";
                }
            }
        }
        if($request->request->get('filterSelectable'))
        {
            foreach($request->request->get('filterSelectable') as $key => $value)
            {
                if(count($request->request->get('filterSelectable')) > 0)
                {
                    $joinInstructions .= " LEFT JOIN p.filters pf" . $key . " ";
                }
            }
        }
        
        $sql = "SELECT p,ps FROM DashboardCommonBundle:Product p LEFT JOIN p.service ps " . $joinInstructions . " LEFT JOIN p.user pu WHERE pu.isActive = 1 AND p.isConfirm = 1 AND p.isBlocked = 0 AND p.isActive = 1";
        
        if($request->request->get('searchCategory'))
        {
            $searchCategory = $manager->getRepository("DashboardCommonBundle:Category")->findOneByName($request->request->get('searchCategory'));
            
            if($searchCategory)
            {
                if($searchCategory->getChildren())
                {
                    $sql .= " AND (p.category = " . $searchCategory->getId();
                    foreach($searchCategory->getChildren() as $child)
                    {
                        $sql .= " OR p.category = " . $child->getId();
                        $sql .= $this->createCategorySql($child); 

                    }
                    $sql .= ")";
                }
                else
                   $sql .= " AND p.category = " . $searchCategory->getId(); 
            }
        } 
        else
        {
            if($category->getChildren())
            {
                $sql .= " AND (p.category = " . $category->getId();
                foreach($category->getChildren() as $child)
                {
                    $sql .= " OR p.category = " . $child->getId();
                    $sql .= $this->createCategorySql($child); 
                    
                }
                $sql .= ")";
            }
            else
                $sql .= " AND p.category = " . $category->getId();
        }
        
        if(!$request->isXmlHttpRequest())
        {
            if($regionFilterForm['regionFilter']->getData())
            {
                $sql .= " AND p.region = " . $regionFilterForm['regionFilter']->getData()->getId();
            }
            else
            {
                if($this->get('session')->has('sessionRegion') && $this->get('session')->get('sessionRegion') != "")
                {
                    $sql .= " AND p.region = " . $this->get('session')->get('sessionRegion');
                }
            }

            if($regionFilterForm['cityFilter']->getData())
            {
                $sql .= " AND p.city = " . $regionFilterForm['cityFilter']->getData()->getId();
            }
            else
            {
                if($this->get('session')->has('sessionCity') && $this->get('session')->get('sessionCity') != "")
                {
                    $sql .= " AND p.city = " . $this->get('session')->get('sessionCity');
                }
            }
        }
        
        if($request->request->get('searchText'))
        {
            $sql .= " AND (p.name LIKE '%" . $request->request->get('searchText') . "%'";
            $sql .= " OR p.description LIKE '%" . $request->request->get('searchText') . "%')";
        }
        
        if($request->request->get('searchWithFoto'))
        {
            $sql .= " AND p.mainfoto IS NOT NULL";
        }
        
        if($request->request->get('searchIsBu'))
        {
            $sql .= " AND p.typebu = 1";
        }
        
        if($request->request->get('filter'))
        {
            foreach($request->request->get('filter') as $key => $value)
            {
                $filterId = $key;
                if(is_array($value))
                {
                    foreach($value as $key => $val)
                    {
                        if($val != 0)
                            $sql .= " AND pf" . $key . ".id = " . $val;
                    }
                }
                else
                {
                    if($value != 0)
                        $sql .= " AND pf" . $filterId . ".id = " . $value;
                }
            }
        }
        
        if($request->request->get('filterRangeList'))
        {
            foreach($request->request->get('filterRangeList') as $key => $value)
            {
                $filterId = $key;
                if($value[0] != 0 || $value[1] != 0)
                {
                    $valueStart = ($manager->getRepository("DashboardCommonBundle:FilterValue")->find($value[0])) ? $manager->getRepository("DashboardCommonBundle:FilterValue")->find($value[0])->getValue() : 0;
                    $valueEnd = ($manager->getRepository("DashboardCommonBundle:FilterValue")->find($value[1])) ? $manager->getRepository("DashboardCommonBundle:FilterValue")->find($value[1])->getValue() : 0;

                    if($valueStart < $valueEnd)
                    {
                        $filterValues = $manager->createQuery("SELECT fv,f FROM DashboardCommonBundle:FilterValue fv LEFT JOIN fv.filter f WHERE f.id = " . $key . " AND (fv.value >= " . (int)$valueStart . " AND fv.value <= " . (int)$valueEnd . ")")->getResult();
                    }
                    else
                    {
                        $filterValues = $manager->createQuery("SELECT fv,f FROM DashboardCommonBundle:FilterValue fv LEFT JOIN fv.filter f WHERE f.id = " . $key . " AND fv.value >= " . (int)$valueStart)->getResult();
                    }

                    if($filterValues)
                    {
                        $sql .= " AND (";

                        if(count($filterValues) > 1)
                        {
                            $marker = count($filterValues);
                            foreach($filterValues as $filterValue)
                            {
                                $sql .= "pf" . $filterId . ".id = " . $filterValue->getId();
                                if($marker != 1)
                                {
                                    $sql .= " OR ";
                                }
                                $marker--;
                            }
                        }
                        else
                        {
                            foreach($filterValues as $filterValue)
                            {
                                $sql .= "pf" . $filterId . ".id = " . $filterValue->getId();
                            }
                        }
                        $sql .= ")";
                    }
                }
            }
        }
        
        if(null !== $request->request->get('filterSelectable'))
        {
            if(count($request->request->get('filterSelectable')) > 0)
            {
                foreach($request->request->get('filterSelectable') as $key => $value)
                {
                    $filterId = $key;
                    if(count($value) > 0)
                    {
                        $valueStart = (isset($value[0])) ? $value[0] : 0;
                        $valueEnd = (isset($value[1])) ? $value[1] : 0;
                        
                        if($valueStart != 0 || $valueEnd != 0)
                        {
                            if($valueStart < $valueEnd)
                            {
                                $filterValues = $manager->createQuery("SELECT fv,f FROM DashboardCommonBundle:FilterValue fv LEFT JOIN fv.filter f WHERE f.id = " . $key . " AND (fv.value >= " . (int)$valueStart . " AND fv.value <= " . (int)$valueEnd . ")")->getResult();
                            }
                            else
                            {
                                $filterValues = $manager->createQuery("SELECT fv,f FROM DashboardCommonBundle:FilterValue fv LEFT JOIN fv.filter f WHERE f.id = " . $key . " AND fv.value >= " . (int)$valueStart)->getResult();
                            }

                            if($filterValues)
                            {
                                $sql .= " AND (";

                                if(count($filterValues) > 1)
                                {
                                    $marker = count($filterValues);
                                    foreach($filterValues as $filterValue)
                                    {
                                        $sql .= "pf" . $filterId . ".id = " . $filterValue->getId();
                                        if($marker != 1)
                                        {
                                            $sql .= " OR ";
                                        }
                                        $marker--;
                                    }
                                }
                                else
                                {
                                    foreach($filterValues as $filterValue)
                                    {
                                        $sql .= "pf" . $filterId . ".id = " . $filterValue->getId();
                                    }
                                }
                                $sql .= ")";
                            }
                        }

                    }
                }
            }
        }

        if(null !== $request->request->get('filterSelectablePrice'))
        {
            if(count($request->request->get('filterSelectablePrice')) > 0)
            {
                foreach($request->request->get('filterSelectablePrice') as $key => $value)
                {
                    if(count($value) > 0)
                    {
                        $valueStart = (isset($value[0])) ? $value[0] : '0';
                        $valueEnd = (isset($value[1])) ? $value[1] : '0';
                        
                        if($valueStart != 0 || $valueEnd != 0)
                        {
                            $sql .= " AND (";
                                                
                            if($valueStart < $valueEnd)
                            {
                                $sql .= "p.price >='" . $valueStart . "' AND p.price <='" . $valueEnd . "'";
                            }
                            else
                            {
                                $sql .= "p.price >='" . $valueStart . "'";
                            }
                            $sql .= ")";
                        }
                    }
                }
            }
        }
        
        if($request->query->get('sortorder') && $request->query->get('order'))
        {
            /*$sqlPremium = $sql . " AND ps.service = 1 ORDER BY p." . $request->query->get('sortorder') . " " . $request->query->get('order');
        
            $sqlSelected = $sql . " AND ps.service = 2 ORDER BY p." . $request->query->get('sortorder') . " " . $request->query->get('order');
        
            $sqlUp = $sql . " AND ps.service = 3 ORDER BY p." . $request->query->get('sortorder') . " " . $request->query->get('order');*/
        
            $sql .= " ORDER BY p." . $request->query->get('sortorder') . " " . $request->query->get('order');
        }
        else 
        {
            /*$sqlPremium = $sql . " AND ps.service = 1 ORDER BY ps.dateAdded DESC";
        
            $sqlSelected = $sql . " AND ps.service = 2 ORDER BY ps.dateAdded DESC";
        
            $sqlUp = $sql . " AND ps.service = 3 ORDER BY ps.dateAdded DESC";*/
        
            $sql .= " ORDER BY p.dateAdded DESC";
        }
                
        $query = $manager->createQuery($sql);
        
        try{
            $productsTotalCount = count($query->getResult());
            
            $products = ($page) ? $query->setFirstResult(($page - 1) * $settings->getMainpageAdvertsNumber())->setMaxResults($settings->getMainpageAdvertsNumber())->getResult() : $query->getResult();
            
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $productsTotalCount = 0;
            $products = 0;
        }
        
        /*if($category->getChildren())
            $query = $manager->createQuery($sqlPremium)->setMaxResults($settings->getCatpagePremiumNumber());
        else
            $query = $manager->createQuery($sqlPremium);
        
        try{
            $premiumProducts = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $premiumProducts = 0;
        }*/

        $allcities = $manager->getRepository("DashboardCommonBundle:City")->findAll();
        
        $link = ($locale->getIsDefault()) ? '/category/' . $categoryName : '/' . $locale->getCode() . '/category/' . $categoryName;
        
        if($request->query->get('sortorder') && $request->query->get('order'))
        {
            $sort = '?sortorder=' . $request->query->get('sortorder') . '&order=' . $request->query->get('order');
        }
        else {$sort = '';}
        
        if($locale->getIsDefault())
            $pagination = $this->get('app.helpers')->paginator($page, $productsTotalCount, $settings->getMainpageAdvertsNumber(), $link,'list-unstyled list-inline',$sort);
        else
            $pagination = $this->get('app.helpers')->paginator($page, $productsTotalCount, $settings->getMainpageAdvertsNumber(), $link,'list-unstyled list-inline',$sort);
        
        return $this->render('DashboardCommonBundle:Default:category.html.twig', array("category" => $category,
                                                                                       "categories" => array_reverse($categories),
                                                                                       "products" => $products,
                                                                                       "pagination" => $pagination,
                                                                                       "premiumProducts" => 0/*$premiumProducts*/,
                                                                                       "selectedProducts" => 0/*$selectedProducts*/,
                                                                                       "upProducts" => 0/*$upProducts*/,
                                                                                       "allcities" => $allcities,
                                                                                       "selltypes" => $selltypes,
                                                                                       "filters" => $request->request->get('filter'),
                                                                                       "filtersRangeList" => $request->request->get('filterRangeList'),
                                                                                       "filterSelectable" => $request->request->get('filterSelectable'),
                                                                                       "filterSelectablePrice" => $request->request->get('filterSelectablePrice'),
                                                                                       "locale" => $locale,
                                                                                       "settings" => $settings,
                                                                                       "view" => $view,
                                                                                       "sql" => $sql,
                                                                                       "regionFilterForm" => $regionFilterForm->createView()));
    }
    
    private function createCategorySql($category)
    {
        $sql = '';
        
        if($category->getChildren())
        {
            foreach($category->getChildren() as $child)
            {
                $sql .= " OR p.category = " . $child->getId();
                $sql .= $this->createCategorySql($child);
            }
        }
        
        return $sql;
    }
    
    public function getTopsellersAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->find(1);
        
        $query = $manager->createQuery("SELECT u FROM DashboardCommonBundle:User u JOIN u.userinfo ui WHERE u.isActive = 1 AND u.isConfirm = 1 ORDER BY ui.rating DESC" )->setMaxResults($settings->getTopsellerBlockNumber());
        $topUsers = $query->getResult();
        
        return $this->render('DashboardCommonBundle:Common:topseller.html.twig', array("topUsers" => $topUsers));
    }
    
    /**
     * @Route("/product/{productId}_{productName}", name="product",defaults={"productId":null,"productName":null})
     * @Route("/{_locale}/product/{productId}_{productName}", name="productLocale", defaults={"_locale" : "lv","productId":null,"productName":null}, requirements={"_locale" : "lv|ru"})
     */
    public function productAction($productId, $productName, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $sessionUser = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $allcities = $manager->getRepository("DashboardCommonBundle:City")->findAll();
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Category c WHERE c.parent IS NULL" );
        
        try{
            $allcategories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $allcategories = 0;
        }
        
        $services = 0/*$manager->getRepository("DashboardCommonBundle:Service")->findAll()*/;
        
        $categoriesBread = array();
        
        $categories = array();
        
        $product = $manager->getRepository("DashboardCommonBundle:Product")->findOneBy(array("id" => $productId, "isActive" => "1", "isConfirm" => "1", "isBlocked" => "0"));
        
        if(!$product)
            throw $this->createNotFoundException();
        if($product->getUser()->getIsActive() == 0)
            throw $this->createNotFoundException();
        
        //crate product filters massive
        $productFilters = array();
        
        
        if($product->getFilters())
        {
            foreach($product->getFilters() as $filter)
            {
                if(!array_key_exists($filter->getFilter()->getId(),$productFilters))
                {
                    $productFilters[$filter->getFilter()->getId()] = array("filter" => $filter->getFilter(),"values" => array());
                }
            }
            
            foreach($product->getFilters() as $filter)
            {
                array_push($productFilters[$filter->getFilter()->getId()]["values"], $filter);
            }
        }

        //change statistics
        $sessionToken = unserialize(base64_decode($this->get('session')->get('sessionToken')));
        
        if(!in_array($product->getId(), $sessionToken['products']))
        {
            array_push($sessionToken['products'], $product->getId());
            $this->get('session')->set('sessionToken', base64_encode(serialize(array("products" => $sessionToken['products']))));
            $product->setViews($product->getViews() + 1);
            $product->setViewsPerDate($product->getViewsPerDate() + 1);
            $manager->persist($product);
            $manager->flush();
        }
        
        $category = $manager->getRepository("DashboardCommonBundle:Category")->find($product->getCategory()->getId());
        
        array_push($categoriesBread, $category);
        $parent = $category->getParent();
        
        //строим хлебную крошку
        while($parent)
        {
            array_push($categoriesBread, $parent);
            $parent = $parent->getParent();
        }
        
        $categories[0] = $category;
        $i = 1;
        while($category->getParent())
        {
            $categories[$i] = $category->getParent();
            $category = $category->getParent();
            $i++;
        }
        
        $categories = array_reverse($categories);
        
        $phoneNumber = $product->getUser()->getUserinfo()->getPhone();

        $product->getUser()->getUserinfo()->setPhone($phoneNumber);
        
        //get same products
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p LEFT JOIN p.user pu WHERE pu.isActive = 1 AND p.id <> " . $product->getId() . " AND p.category = " . $product->getCategory()->getId() . " AND p.isActive = 1 AND p.isConfirm = 1 AND p.isBlocked = 0");
        
        try
        {
            $allproducts = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $allproducts = 0;
        }
        
        $sameProducts = array();
        
        if(count($allproducts) > 4)
        {
            for($i = 0; $i < 4; $i++)
            {
                $rand = rand(0, count($allproducts) - 1);
                
                while(in_array($allproducts[$rand],$sameProducts))
                {
                    $rand = rand(0, count($allproducts) - 1);
                }
                
                array_push($sameProducts, $allproducts[$rand]);
            }
        }
        else
            $sameProducts = $allproducts;
        
        if($this->getUser())
        {
            $order = new ProductOrder();
            $orderForm = $this->createForm(new OrderType($manager, $sessionUser->getUserInfo(), $product->getUser()), $order);
            
            $message = new Message();
            $productMessageForm = $this->createForm(new ProductMessageType($manager, $sessionUser), $message);
            
            $profileMessage = new Message();
            $profileMessageForm = $this->createForm(new ProfileMessageType($manager), $profileMessage);
            
            $friendMessageForm = $this->get('form.factory')->createNamedBuilder('friendmessage', 'form')
                ->add('friendemail', EmailType::class, array('required' => true, 'label' => $this->get('translator')->trans('Drauga e-pasts: *'), 'attr' => array('class' => 'form-control')))
                ->add('friendname', TextType::class, array('required' => true, 'label' => $this->get('translator')->trans('Drauga vārds: *'), 'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('SŪTĪT'), 'attr' => array('class' => 'send-tab-form')))->getForm();
            
            $complaint = new Complaint();
            $complaintMessageForm = $this->get('form.factory')->createNamedBuilder('complaint', 'form', $complaint)
                 ->add('username', TextType::class, array('required' => true, 'mapped' => false, 'data' => $sessionUser->getUserinfo()->getFirstname() . " " . $sessionUser->getUserinfo()->getLastname(),'label' => $this->get('translator')->trans('Jūsu vārds: *'), 'attr' => array('class' => 'form-control')))
                 ->add('reason', TextareaType::class, array('required' => true,'label' => $this->get('translator')->trans('Pamatojums: *'), 'attr' => array('class' => 'form-control')))
                 ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('SŪTĪT'), 'attr' => array('class' => 'send-tab-form')))->getForm();
                    
                    
            $orderForm->handleRequest($request);

            if ($orderForm->isSubmitted() && $orderForm->isValid())
            {
                if($product->getUser()->getId() != $sessionUser->getId())
                {
                    $order->setDateAdded(new \DateTime("now"));
                    $order->setUserReceived($product->getUser());
                    $order->setUserSended($sessionUser);
                    $order->setProduct($product);
                    $order->setIsNew(1);
                    $order->setStatus($settings->getDafaultOrderStatus());

                    $manager->persist($order);
                    $manager->flush();

                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Panākumi!</strong> Jūsu pasūtījums ir nosūtīts reklāmas īpašniekam. Viņš sazināsies ar tevi cik drīz vien iespējams.') . '</div>'
                    );
                    
                    //send information about new order to sellers email
                    if($product->getUser()->getAlerts())
                    {
                        $message = \Swift_Message::newInstance()
                        ->setSubject('Поступил заказ на сайте gribupardot.sunweb.by')
                        ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                        ->setTo($product->getUser()->getEmail())
                        ->setBody(
                            $this->renderView(
                                'Emails/sellerneworder.html.twig',
                                array('product' => $product, "order" => $order)
                            ),
                            'text/html'
                        );

                        $this->get('mailer')->send($message);
                    }
                }
                else
                {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Kļūda! </strong> Šī reklāma pieder jums. Jūs nevarat pasūtīt sevi.') . '</div>'
                    );
                }
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
                else
                {
                    return $this->redirectToRoute("productLocale", array("_locale" => $locale->getCode(),"productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
                
                
            }

            $productMessageForm->handleRequest($request);
            
            if($productMessageForm->isSubmitted() && $productMessageForm->isValid())
            {
                if($product->getUser()->getId() != $sessionUser->getId())
                {
                    $blacklistItem = $manager->getRepository("DashboardCommonBundle:Blacklist")->findOneBy(array("userAuthor" => $product->getUser()->getId(), "userTo" => $sessionUser->getId()));
                
                    if($blacklistItem)
                    {
                        $this->addFlash(
                                'notice',
                                '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                                $this->get('translator')->trans('<strong>Kļūda!</strong> Šis lietotājs ir pievienojis jūs melnajam sarakstam.') . '</div>'
                            );

                        if($locale->getIsDefault())
                        {
                            return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
                        }
                        else
                        {
                            return $this->redirectToRoute("productLocale", array("_locale" => $locale->getCode(),"productId" => $product->getId(),"productName" => $product->getTranslit()));
                        }
                    }
                    
                    //check if conversation exists
                    $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Conversation c WHERE (c.userOne = " . $sessionUser->getId()  . " AND c.userTwo = " . $product->getUser()->getId() . " ) "
                            . "OR (c.userOne = " . $product->getUser()->getId() . " AND c.userTwo = " . $sessionUser->getId()  . ")");
                    
                    try{
                        $conversation = $query->getSingleResult();
                    }
                    catch(\Doctrine\ORM\NoResultException $e) {
                        $conversation = new Conversation();
                        $conversation->setUserOne($product->getUser());
                        $conversation->setUserTwo($sessionUser);
                        $conversation->setUserDeleted(null);
                        $manager->persist($conversation);
                        $manager->flush();
                    }
                    
                    $message->setUserFrom($sessionUser);
                    $message->setUserTo($product->getUser());
                    $message->setUserOwner($sessionUser);
                    $message->setIsNew(1);
                    $message->setIsDeleted(0);
                    $message->setSentDate(new \DateTime("now"));
                    $message->setReadedDate(new \DateTime("now"));
                    $message->setProduct($product);
                    $message->setConversation($conversation);
                    
                    $manager->persist($message);
                    $manager->flush();
                    
                    $messageTwo = new Message();
                    $messageTwo = clone $message;
                    $messageTwo->setUserOwner($product->getUser());
                    
                    $manager->persist($messageTwo);
                    $manager->flush();
                    
                    if($productMessageForm['copytoemail']->getData())
                    {
                        $messageSent = \Swift_Message::newInstance()
                        ->setSubject('Копия сообщения для владельца объявления')
                        ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                        ->setTo($productMessageForm['userEmail']->getData())
                        ->setBody(
                            $this->renderView(
                                'Emails/productmessagecopy.html.twig',
                                array('message' => $message->getMessage(),
                                      'product' => $product)
                            ),
                            'text/html'
                        );

                        $this->get('mailer')->send($messageSent);
                    }
                    
                    if($product->getUser()->getAlerts())
                    {
                        $messageSent = \Swift_Message::newInstance()
                            ->setSubject('Новое сообщение на сайте gribupardot.sunweb.by')
                            ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                            ->setTo($product->getUser()->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'Emails/productmessage.html.twig',
                                    array('message' => $message->getMessage(),
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
                        $this->get('translator')->trans('<strong>Veiksmīga!</strong> Augstāk minēto ziņojumu nosūtīja reklāmas īpašniekam.') . '</div>'
                    );
                }
                else
                {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                        $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat nosūtīt ziņu sev.') . '</div>'
                    );
                }
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
                else
                {
                    return $this->redirectToRoute("productLocale", array("_locale" => $locale->getCode(),"productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
            }
            
            $friendMessageForm->handleRequest($request);
            
            if($friendMessageForm->isSubmitted() && $friendMessageForm->isValid())
            {
                $message = \Swift_Message::newInstance()
                ->setSubject('Ссылка на объявление на сайте gribupardot.sunweb.by')
                ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                ->setTo(array($friendMessageForm['friendemail']->getData() => $friendMessageForm['friendname']->getData()))
                ->setBody(
                    $this->renderView(
                        'Emails/friendmessage.html.twig',
                        array('product' => $product,
                              'user' => $sessionUser)
                    ),
                    'text/html'
                );

                $this->get('mailer')->send($message);
                
                $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                        $this->get('translator')->trans('<strong>Veiksmīga!</strong> Ziņojums tika nosūtīts draugam norādītajā e-pasta adresē.') . '</div>'
                );
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
                else
                {
                    return $this->redirectToRoute("productLocale", array("_locale" => $locale->getCode(),"productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
            }
            
            $complaintMessageForm->handleRequest($request);
            
            if($complaintMessageForm->isSubmitted() && $complaintMessageForm->isValid())
            {
                if($product->getUser()->getId() != $sessionUser->getId())
                {   
                    $complaint->setUser($sessionUser);
                    $complaint->setProduct($product);
                    $complaint->setDateAdded(new \DateTime("now"));
                    $complaint->setStatus(0);
                    
                    $manager->persist($complaint);
                    $manager->flush();
                    
                    $message = \Swift_Message::newInstance()
                    ->setSubject('Жалоба на объявление на сайте gribupardot.sunweb.by')
                    ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                    ->setTo($settings->getAdminEmail())
                    ->setBody(
                        $this->renderView(
                            'Emails/complaint.html.twig',
                            array('product' => $product,
                                  'user' => $sessionUser)
                        ),
                        'text/html'
                    );

                    $this->get('mailer')->send($message);
                    
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Veiksmīgi!</strong> Jūsu sūdzība ir reģistrēta un drīz tiks pārskatīta.') . '</div>'
                    );
                    
                    $productComplaints = $manager->getRepository("DashboardCommonBundle:Complaint")->findByProduct($product);
                    
                    if(count($productComplaints) >= 10)
                    {
                        $product->setIsActive(false);
                        $product->setIsConfirm(false);
                        $product->setIsCorrect(true);
                        $product->setCorrectReason($this->get('translator')->trans("Sūdzību skaits par reklāmu ir vairāk nekā 10"));
                        
                        $manager->persist($product);
                        $manager->flush();
                        
                        if($product->getUser()->getAlerts())
                        {
                            $messageCorrect = \Swift_Message::newInstance()
                            ->setSubject('Ваше объявление отправлено на коррекцию.')
                            ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                            ->setTo($product->getUser()->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'Emails/correct.html.twig',
                                    array('product' => $product,
                                          'reason' => 'На Ваше объявление подано более 10 жалоб, поэтому его действие было приостановлено.')
                                ),
                                'text/html'
                            );

                            $this->get('mailer')->send($messageCorrect);
                        }
                        
                        if($locale->getIsDefault())
                        {
                            return $this->redirectToRoute("main");
                        }
                        else
                        {
                            return $this->redirectToRoute("mainLocale", array("_locale" => $locale->getCode()));
                        }
                    }
                    
                }
                else 
                {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat sūdzēties par savu reklāmu.') . '</div>'
                    );
                }
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
                else
                {
                    return $this->redirectToRoute("productLocale", array("_locale" => $locale->getCode(),"productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
            }
            
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
                                $this->get('translator')->trans('<strong>Kļūda!</strong> Šis lietotājs ir pievienojis jūs melnajam sarakstam.') . '</div>'
                        );
                    
                    if($locale->getIsDefault())
                    {
                        return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
                    }
                    else
                    {
                        return $this->redirectToRoute("productLocale", array("_locale" => $locale->getCode(),"productId" => $product->getId(),"productName" => $product->getTranslit()));
                    }
                }
                
                if($product->getUser()->getId() != $sessionUser->getId())
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
                    
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Veiksmīga!</strong> Jūsu ziņa ir nosūtīta.') . '</div>'
                    );
                    
                }
                else {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs nevarat ziņot sev.') . '</div>'
                    );
                }
                
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
                else
                {
                    return $this->redirectToRoute("productLocale", array("_locale" => $locale->getCode(),"productId" => $product->getId(),"productName" => $product->getTranslit()));
                }
            }
            
            //is favorite product for user
            $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\FavoriteProducts p WHERE p.productId = ' . $productId . ' AND p.userId = ' . $sessionUser->getId());

            try{
                $favoriteProductIs = $query->getSingleResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $favoriteProductIs = 0;
            }
            
            return $this->render('DashboardCommonBundle:Default:product.html.twig', array("product" => $product,
                                                                                          "categories" => $categories,
                                                                                          "allcities" => $allcities,
                                                                                          "allcategories" => $allcategories,
                                                                                          "orderForm" => $orderForm->createView(),
                                                                                          "user" => $sessionUser,
                                                                                          "favoriteProductIs" => $favoriteProductIs,
                                                                                          "productMessageForm" => $productMessageForm->createView(),
                                                                                          "friendMessageForm" => $friendMessageForm->createView(),
                                                                                          "complaintMessageForm" => $complaintMessageForm->createView(),
                                                                                          "profileMessageForm" => $profileMessageForm->createView(),
                                                                                          "categoriesBread" => array_reverse($categoriesBread),
                                                                                          "services" => $services,
                                                                                          "sameProducts" => $sameProducts,
                                                                                          "locale" => $locale,
                                                                                          "settings" => $settings,
                                                                                          "productFilters" => $productFilters));
        }
        else
            return $this->render('DashboardCommonBundle:Default:product.html.twig', array("product" => $product,
                                                                                          "categories" => $categories,
                                                                                          "allcities" => $allcities,
                                                                                          "allcategories" => $allcategories,
                                                                                          "favoriteProductIs" => 0,
                                                                                          "services" => $services,
                                                                                          "sameProducts" => $sameProducts,
                                                                                          "categoriesBread" => array_reverse($categoriesBread),
                                                                                          "locale" => $locale,
                                                                                          "settings" => $settings,
                                                                                          "productFilters" => $productFilters));
    } 
    
    /**
     * @Route("/getsellerphone/{productId}", name="getsellerphone",defaults={"productId":"0"})
     */
    public function getSellerPhoneAction($productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $sessionUser = $this->get('security.context')->getToken()->getUser();
        
        if($this->getUser())
        {
            $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId);
            
            if($product)
            {
                return ($product->getUser()->getUserinfo()->getPhone()) ? new Response($product->getUser()->getUserinfo()->getPhone()) : new Response('XXX-XXX-XXX');
            }
            else
                return new Response("XXX-XXX-XXX");
        }
        else
            return new Response("XXX-XXX-XXX");
    }
    
    /**
     * @Route("/getuserphone/{userId}", name="getuserphone",defaults={"userId":"0"})
     */
    public function getUserPhoneAction($userId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $sessionUser = $this->get('security.context')->getToken()->getUser();
        
        if($this->getUser())
        {
            $user = $manager->getRepository("DashboardCommonBundle:User")->find($userId);
            
            if($user)
            {
                return ($user->getUserinfo()->getPhone()) ? new Response($user->getUserinfo()->getPhone()) : new Response('XXX-XXX-XXX');
            }
            else
                return new Response("XXX-XXX-XXX");
        }
        else
            return new Response("XXX-XXX-XXX");
    }
    
    /**
     * @Route("/pages/{route}", name="pages")
     * @Route("/{_locale}/pages/{route}", name="pagesLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function pagesAction($route, Request $request)        
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

    /**
     * @Route("/404", name="notfound")
     * @Route("/{_locale}/404", name="notfoundLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function notfoundAction(Request $request)        
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.isUserpage = 0 AND p.locale=" . $locale->getId() . " AND p.route = '" . $request->attributes->get('_route') . "'" );

        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $page = 0;
        }
                
        return $this->render('DashboardCommonBundle:Common:notfound.html.twig', array("page" => $page));
    } 
    
    /**
     * @Route("/search", name="search")
     * @Route("/{_locale}/search", name="searchLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function searchAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $selltypes = $manager->getRepository("DashboardCommonBundle:Selltype")->findAll();
        
        if($this->get('session')->get('sessionRegion'))
        {
            $region = $manager->getRepository("DashboardCommonBundle:Region")->find($this->get('session')->get('sessionRegion'));
        }
        else
            $region = 0;
        
        if($this->get('session')->get('sessionCity'))
        {
            $city = $manager->getRepository("DashboardCommonBundle:City")->find($this->get('session')->get('sessionCity'));
        }
        else
            $city = 0;
        $view = ($this->get('session')->get('viewCategory')) ? $this->get('session')->get('viewCategory') : 'table';
        $regionFilterForm = $this->createForm(new RegionFilterType($locale, $region, $city), new Product());
        $regionFilterForm->handleRequest($request);
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.locale=" . $locale->getId() . " AND p.isUserpage = 0 AND p.route = 'search'" );

        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $page = 0;
        }
        
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Category c WHERE c.parent IS NULL");
        
        try{
            $allcategories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $allcategories = 0;
        }
        
        $allcities = $manager->getRepository("DashboardCommonBundle:City")->findAll();
        $searchText = '';
        
        $sql = "SELECT p,ps,pf FROM DashboardCommonBundle:Product p LEFT JOIN p.service ps LEFT JOIN p.filters pf LEFT JOIN p.user pu WHERE pu.isActive = 1 AND p.isBlocked = 0 AND p.isActive = 1 AND p.isConfirm = 1";
        

        if($request->request->get('searchText'))
        {
            $sql .= " AND (p.name LIKE '%" . $request->request->get('searchText') . "%'";
            $sql .= " OR p.description LIKE '%" . $request->request->get('searchText') . "%')";
        }
        
        if($request->request->get('searchCategory'))
        {
            $searchCategory = $manager->getRepository("DashboardCommonBundle:Category")->findOneByName($request->request->get('searchCategory'));
            
            if($searchCategory)
            {
                if($searchCategory->getChildren())
                {
                    $sql .= " AND (p.category = " . $searchCategory->getId();
                    foreach($searchCategory->getChildren() as $child)
                    {
                        $sql .= " OR p.category = " . $child->getId();
                        $sql .= $this->createCategorySql($child); 

                    }
                    $sql .= ")";
                }
                else
                   $sql .= " AND p.category = " . $searchCategory->getId(); 
            }
        } 
        
        if(!$request->isXmlHttpRequest())
        {
            if($regionFilterForm['regionFilter']->getData())
            {
                $sql .= " AND p.region = " . $regionFilterForm['regionFilter']->getData()->getId();
            }
            else
            {
                if($this->get('session')->has('sessionRegion') && $this->get('session')->get('sessionRegion') != "")
                {
                    $sql .= " AND p.region = " . $this->get('session')->get('sessionRegion');
                }
            }

            if($regionFilterForm['cityFilter']->getData())
            {
                $sql .= " AND p.city = " . $regionFilterForm['cityFilter']->getData()->getId();
            }
            else
            {
                if($this->get('session')->has('sessionCity') && $this->get('session')->get('sessionCity') != "")
                {
                    $sql .= " AND p.city = " . $this->get('session')->get('sessionCity');
                }
            }
        }

        if($request->request->get('searchWithFoto'))
        {
            $sql .= " AND p.mainfoto IS NOT NULL";
        }
        
        if($request->request->get('searchIsBu'))
        {
            $sql .= " AND p.typebu = 1";
        }
        
        if($request->request->get('filter'))
        {
            foreach($request->request->get('filter') as $key => $value)
            {
                if(is_array($value))
                {
                    foreach($value as $key => $val)
                    {
                        if($val != 0)
                            $sql .= " AND pf.id = " . $val;
                    }
                }
                else
                {
                   if($value != 0)
                    $sql .= " AND pf.id = " . $value;
                }
            }
        }
        
        if($request->query->get('sortorder') && $request->query->get('order'))
        {
            $sqlPremium = $sql . " AND ps.service = 1 ORDER BY p." . $request->query->get('sortorder') . " " . $request->query->get('order');
        
            $sqlSelected = $sql . " AND ps.service = 2 ORDER BY p." . $request->query->get('sortorder') . " " . $request->query->get('order');
        
            $sqlUp = $sql . " AND ps.service = 3 ORDER BY p." . $request->query->get('sortorder') . " " . $request->query->get('order');
        
            $sql .= " AND p.viewcommon = 1 ORDER BY p." . $request->query->get('sortorder') . " " . $request->query->get('order');
        }
        else 
        {
            $sqlPremium = $sql . " AND ps.service = 1 ORDER BY ps.dateAdded DESC";
        
            $sqlSelected = $sql . " AND ps.service = 2 ORDER BY ps.dateAdded DESC";
        
            $sqlUp = $sql . " AND ps.service = 3 ORDER BY ps.dateAdded DESC";
        
            $sql .= " AND p.viewcommon = 1 ORDER BY p.dateAdded DESC";
        }
        
        $query = $manager->createQuery($sql);
        
        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        $query = $manager->createQuery($sqlPremium);
        
        try{
            $premiumProducts = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $premiumProducts = 0;
        }
        
        $query = $manager->createQuery($sqlSelected);
        
        try{
            $selectedProducts = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $selectedProducts = 0;
        }
        
        $query = $manager->createQuery($sqlUp);
        
        try{
            $upProducts = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $upProducts = 0;
        }
        
        return $this->render('DashboardCommonBundle:Default:search.html.twig', array('allcategories' => $allcategories,
                                                                                     'allcities' => $allcities,
                                                                                     'searchText' => $searchText,
                                                                                     'products' => $products,
                                                                                     'premiumProducts' => $premiumProducts,
                                                                                     'selectedProducts' => $selectedProducts,
                                                                                     'upProducts' => $upProducts,
                                                                                     'selltypes' => $selltypes,
                                                                                     'pagination' => 0,
                                                                                     'locale' => $locale,
                                                                                     'settings' => $settings,
                                                                                     'page' => $page,
                                                                                     'view' => $view,
                                                                                     'regionFilterForm' => $regionFilterForm->createView()));
    }
}
