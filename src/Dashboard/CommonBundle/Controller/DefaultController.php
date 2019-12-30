<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

use Dashboard\CommonBundle\Entity\ProductOrder;
use Dashboard\CommonBundle\Entity\Message;
use Dashboard\CommonBundle\Entity\Conversation;
use Dashboard\CommonBundle\Entity\Complaint;

use Dashboard\CommonBundle\Form\Type\OrderType;
use Dashboard\CommonBundle\Form\Type\ProductMessageType;
use Dashboard\CommonBundle\Form\Type\ProfileMessageType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Dashboard\CommonBundle\Form\Type\CityType;
use Dashboard\CommonBundle\Entity\City;
use Dashboard\CommonBundle\Entity\ProductInfo;

class DefaultController extends Controller
{ 
    public function getHeaderAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $securityContext = $this->container->get('security.authorization_checker');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
            $user = $this->get('security.context')->getToken()->getUser();
        else
            $user = 0;
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if(!$this->get('session')->has('sessionCity'))
        {
            if($this->getUser())
            {
                $user = $this->get('security.context')->getToken()->getUser();
                if($user->getUserinfo()->getCity())
                    $this->get('session')->set('sessionCity', $user->getUserinfo()->getCity()->getId());
            }
        }

        $locales = $manager->getRepository("DashboardCommonBundle:Locale")->findBy(array("isActive" => "1"));
        $sessionRegion = $manager->getRepository("DashboardCommonBundle:City")->findOneById($this->get('session')->get('sessionRegion'));
        $sessionCity = $manager->getRepository("DashboardCommonBundle:City")->findOneById($this->get('session')->get('sessionCity'));
        
        if($locale->getIsDefault())
        {
            $uri = $request->server->get("REQUEST_URI");
            ($uri == "/") ? $uri='' : 0;
        }
        else
            $uri = '/' . substr($request->server->get("REQUEST_URI"),4,strlen($request->server->get("REQUEST_URI")));
        
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL ORDER BY c.sortorder ASC');
        $categories = $query->getResult();
        
        return $this->render('DashboardCommonBundle:Common:header.html.twig', array("user" => $user,
                                                                                    "settings" => $settings,
                                                                                    "categories" => $categories,
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
        
        if($session->get('sessionCity'))
        {
            $city = $manager->getRepository("DashboardCommonBundle:City")->find($session->get('sessionCity'));
        }
        else
            $city = 0;
        
        $cityForm = $this->createForm(new CityType($locale, $city), new City());
        $cityForm->handleRequest($request);

        return $this->render('DashboardCommonBundle:Common:footer.html.twig', array("regions" => $regions, 
                                                                                    "cities" => $cities,
                                                                                    "settings" => $settings,
                                                                                    "footerPages" => $footerPages,
                                                                                    "textblock" => $textblock,
                                                                                    "locale" => $locale,
                                                                                    "cityForm" => $cityForm->createView()));
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
            if($position == 'slider')
                $banners = $manager->getRepository("DashboardCommonBundle:Banner")->findByPosition("defaultslider");
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
            if($position == 'slider')
                $banners = $manager->getRepository("DashboardCommonBundle:Banner")->findByPosition("slider");
        }
        
        return $this->render('DashboardCommonBundle:Common:banners.html.twig', array("banners" => $banners));
    }
    /**
     * @Route("/", name="main")
     */
    public function indexAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $sessionCity = $manager->getRepository("DashboardCommonBundle:City")->findOneById($this->get('session')->get('sessionCity'));
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.locale=" . $locale->getId() . " AND p.isUserpage = 0 AND p.route = 'main'" );
        
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
        
        foreach($categories as $category){
            $productsNum = 0;
            $this->getCategoryProducts($category, $productsNum);
            $category->setAllProductsNumber($productsNum);
        }
        
        $allcities = $manager->getRepository("DashboardCommonBundle:City")->findAll();
        
        return $this->render('DashboardCommonBundle:Default:index.html.twig', array("categories" => $categories,
                                                                                    "page" => $page,
                                                                                    "locale" => $locale,
                                                                                    "settings" => $settings,
                                                                                    "sessionCity" => $sessionCity));
    }
    
    private function getCategoryProducts($category, &$productsNum){
        if($category->getProduct()){
            foreach($category->getProduct() as $product){
                if($product->getIsActive() && $product->getIsConfirm()){
                    $productsNum += 1;
                }
            }
        }
        if($category->getChildren()){
            foreach($category->getChildren() as $children){
                $this->getCategoryProducts($children, $productsNum);
            }
        }
    }
    
    /**
     * @Route("/cahngeView/{view}", name="categoryChangeView", requirements={"view" : "list|grid"})
     * 
     */
    public function categoryChangeView($view,Request $request)
    {
        $this->get('session')->set('viewCategory', $view);
        return new Response($this->get('session')->get('viewCategory'));
    }
    
    /**
     * @Route("/category/{categoryId}_{categoryName}/{page}", name="category", defaults={"page":1})
     */
    public function categoryAction($categoryId, $categoryName, $page, Request $request)        
    {
        $manager = $this->getDoctrine()->getManager();
        $categories = array();
        $products = array();
        $pagination = 0;
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $view = ($this->get('session')->get('viewCategory')) ? $this->get('session')->get('viewCategory') : 'list';
        
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Category c WHERE c.id = " . $categoryId . " AND c.name = '" . $categoryName . "' AND c.isActive = 1");
        
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
        
        $categories = array_reverse($categories);
        $filters = new ArrayCollection();
        $this->getFilters($filters, $categories[0]);
        
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
        
        $productInfo = new ProductInfo();
        
        if($productInfo->getVars()){
            foreach($productInfo->getVars() as $var){
                if($request->request->get($var)){
                    foreach($request->request->get($var) as $key => $value){
                        if($value != 0)
                            $joinInstructions .= " LEFT JOIN pi." . $var . " pi" . $var;
                    }
                }
            }
        }
        
        $sql = "SELECT p FROM DashboardCommonBundle:Product p LEFT JOIN p.info pi" . $joinInstructions . " LEFT JOIN p.user pu WHERE pu.isActive = 1 AND p.isConfirm = 1 AND p.isBlocked = 0 AND p.isActive = 1";
        
        if($request->request->get('category'))
        {
            $sql .= " AND (";
            foreach($request->request->get('category') as $key => $categoryId){
                $searchCategory = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
                
                if($searchCategory){
                    if($searchCategory->getChildren()){
                        if($key == 0){
                            $sql .= "p.category = " . $searchCategory->getId();
                            foreach($searchCategory->getChildren() as $child){
                                $sql .= " OR p.category = " . $child->getId();
                                $sql .= $this->createCategorySql($child); 
                            }
                        }else{
                            $sql .= " OR p.category = " . $searchCategory->getId();
                            foreach($searchCategory->getChildren() as $child){
                                $sql .= " OR p.category = " . $child->getId();
                                $sql .= $this->createCategorySql($child); 
                            }
                        }
                    }
                    else{
                        if($key == 0){
                            $sql .= "p.category = " . $searchCategory->getId();
                        }else{
                            $sql .= " OR p.category = " . $searchCategory->getId();
                        }
                    }
                }
            }
            $sql .= ")";
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
        
        if($request->request->get('searchText'))
        {
            $sql .= " AND (p.name LIKE '%" . $request->request->get('searchText') . "%'";
            $sql .= " OR p.description LIKE '%" . $request->request->get('searchText') . "%')";
        }
        
        if($request->request->get('searchNew'))
        {
            $sql .= " AND pi.probeg <= 0";
        }
        
        if($request->request->get('searchOld'))
        {
            $sql .= " OR pi.probeg > 0";
        }
        
        if($productInfo->getVars()){
            foreach($productInfo->getVars() as $var){
                if($request->request->get($var)){
                    foreach($request->request->get($var) as $key => $value){
                        if(is_array($value)){
                            $sql .= " AND (";
                            
                            foreach($value as $key => $val){
                                if($val != 0){
                                    if($key == 0){
                                        $sql .= "pi" . $var . ".id = " . $val;
                                    }else{
                                        $sql .= " OR pi" . $var . ".id = " . $val;
                                    }
                                }
                            }
                            $sql .= ")";
                        }
                    }
                }
            }
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
                                $sql .= "pi.price >='" . $valueStart . "' AND pi.price <='" . $valueEnd . "'";
                            }
                            else
                            {
                                $sql .= "pi.price >='" . $valueStart . "'";
                            }
                            $sql .= ")";
                        }
                    }
                }
            }
        }
        
        if($request->query->get('sortorder') && $request->query->get('order'))
        {
            $sql .= " ORDER BY p." . $request->query->get('sortorder') . " " . $request->query->get('order');
        }
        else 
        {
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
        
        $link = '/category/' . $categoryId . '_' . $categoryName;
        
        if($request->query->get('sortorder') && $request->query->get('order'))
        {
            $sort = '?sortorder=' . $request->query->get('sortorder') . '&order=' . $request->query->get('order');
        }
        else {$sort = '';}
        
        if($locale->getIsDefault())
            $pagination = $this->get('app.helpers')->paginator($page, $productsTotalCount, $settings->getMainpageAdvertsNumber(), $link,'list-unstyled list-inline',$sort);
        else
            $pagination = $this->get('app.helpers')->paginator($page, $productsTotalCount, $settings->getMainpageAdvertsNumber(), $link,'list-unstyled list-inline',$sort);
        
        return $this->render('DashboardCommonBundle:Default:Category/category.html.twig', array("category" => $category,
                                                                                       "categories" => $categories,
                                                                                       "formFilters" => $filters,
                                                                                       "products" => $products,
                                                                                       "productsTotalCount" => $productsTotalCount,
                                                                                       "pagination" => $pagination,
                                                                                       "premiumProducts" => new ArrayCollection(),
                                                                                       "specialProducts" => new ArrayCollection(),
                                                                                       "upProducts" => new ArrayCollection(),
                                                                                       "dealerProducts" => new ArrayCollection(),
                                                                                       "filters" => $request->request->get('filter'),
                                                                                       "filtersRangeList" => $request->request->get('filterRangeList'),
                                                                                       "filterSelectable" => $request->request->get('filterSelectable'),
                                                                                       "filterSelectablePrice" => $request->request->get('filterSelectablePrice'),
                                                                                       "locale" => $locale,
                                                                                       "settings" => $settings,
                                                                                       "view" => $view,
                                                                                       "sql" => $sql));
    }
    
    private function getFilters(&$filters, $category){
        if($category->getFilters()){
            foreach($category->getFilters() as $filter){
                $filters->add($filter);
            }
        }
        if($category->getChildren()){
            foreach($category->getChildren() as $child){
                $this->getFilters($filters, $child);
            }
        }
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
    
    /**
     * @Route("/product/{productId}_{productName}", name="product",defaults={"productId":null,"productName":null})
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
        
        $product = $manager->getRepository("DashboardCommonBundle:Product")->findOneBy(array("id" => $productId, "isActive" => "1", "isConfirm" => "1", "isBlocked" => "0", "isDraft" => "0"));
        
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
        
        $order = new ProductOrder();
        $orderForm = $this->createForm(new OrderType($manager, $product->getUser()), $order);
        
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
                        $this->get('translator')->trans('<strong>Успешно!</strong> Ваш заказ отправлен владельцу объявления. Он свяжется с Вами в ближайшее время.') . '</div>'
                    );
                    
                    //send information about new order to sellers email
                    if($product->getUser()->getIsAlertNewOrder())
                    {
                        $message = \Swift_Message::newInstance()
                        ->setSubject('Поступил заказ на сайте ' . $settings->getSiteName())
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
                        $this->get('translator')->trans('<strong>Ошибка!</strong> Это объявление принадлежит Вам. Вы не можете заказывать сами у себя.') . '</div>'
                    );
                }
                
                return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
        }
        
        $friendMessageForm = $this->get('form.factory')->createNamedBuilder('friendmessage', 'form')
                ->add('friendemail', EmailType::class, array('required' => true, 'label' => $this->get('translator')->trans('Email друга: *'), 'attr' => array('class' => 'form-control')))
                ->add('friendname', TextType::class, array('required' => true, 'label' => $this->get('translator')->trans('Имя друга: *'), 'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Отправить'), 'attr' => array('class' => 'btn')))->getForm();
        
        $friendMessageForm->handleRequest($request);
            
        if($friendMessageForm->isSubmitted() && $friendMessageForm->isValid())
        {
                $message = \Swift_Message::newInstance()
                ->setSubject('Ссылка на объявление на сайте ' . $settings->getSiteName())
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
                        $this->get('translator')->trans('<strong>Успешно!</strong> Сообщение отправлено другу на указанный электронный адрес.') . '</div>'
                );
                
                return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
        }
        
        $complaint = new Complaint();
        $complaintMessageForm = $this->get('form.factory')->createNamedBuilder('complaint', 'form', $complaint)
                 ->add('username', TextType::class, array('required' => true, 'mapped' => false, 'label' => $this->get('translator')->trans('Ваше имя: *'), 'attr' => array('class' => 'form-control')))
                 ->add('reason', TextareaType::class, array('required' => true,'label' => $this->get('translator')->trans('Причина жалобы: *'), 'attr' => array('class' => 'form-control')))
                 ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Отправить'), 'attr' => array('class' => 'btn')))->getForm();
        
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
                    ->setSubject('Жалоба на объявление на сайте ' . $settings->getSiteName())
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
                        $this->get('translator')->trans('<strong>Успешно!</strong> Ваша жалоба зарегистрирована и будет рассмотрена в ближайшее время.') . '</div>'
                    );
                    
                    $productComplaints = $manager->getRepository("DashboardCommonBundle:Complaint")->findByProduct($product);
                    
                    if(count($productComplaints) >= 10)
                    {
                        $product->setIsActive(false);
                        $product->setIsConfirm(false);
                        $product->setIsCorrect(true);
                        $product->setCorrectReason($this->get('translator')->trans("Количество жалоб на объявление больше 10"));
                        
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
                        
                        return $this->redirectToRoute("main");
                    }
                    
                }
                else 
                {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Ошибка!</strong> Вы не можете жаловаться на свое объявление.') . '</div>'
                    );
                }
                
                return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
        }
        
        $rating = 0;
        if($product->getUser()->getTargetReviews()){
            $temp = $product->getUser()->getTargetReviews();
            foreach($temp as $review){
                if($review->getStatus()->getId() != $settings->getPublicReviewStatus()->getId()){
                    $product->getUser()->removeTargetReview($review);
                }else{
                    $rating += $review->getRating();
                }
            }
        }
        
        if(count($product->getUser()->getTargetReviews()) > 0){
            $product->getUser()->getDealerinfo()->setRating(ceil($rating / count($product->getUser()->getTargetReviews())));
        }
        
        if($this->getUser())
        {
            $message = new Message();
            $productMessageForm = $this->createForm(new ProductMessageType($manager, $sessionUser), $message);
            
            $profileMessage = new Message();
            $profileMessageForm = $this->createForm(new ProfileMessageType($manager), $profileMessage);
            
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
                                $this->get('translator')->trans('<strong>Ошибка!</strong> Этот пользователь добавил Вас в черный список.') . '</div>'
                            );

                        return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
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
                            ->setSubject('Новое сообщение на сайте ' . $settings->getSiteName())
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
                        $this->get('translator')->trans('<strong>Успешно!</strong> Выше сообщение отправлено владельцу объявления.') . '</div>'
                    );
                }
                else
                {
                    $this->addFlash(
                        'notice',
                        '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                        $this->get('translator')->trans('<strong>Ошибка!</strong> Вы не можете отправить сообщение самому себе.') . '</div>'
                    );
                }
                
                return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
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
                                $this->get('translator')->trans('<strong>Ошибка!</strong> Этот пользователь добавил Вас в черный список.') . '</div>'
                        );
                    
                    return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
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
                
                return $this->redirectToRoute("product", array("productId" => $product->getId(),"productName" => $product->getTranslit()));
            }
            
            //is favorite product for user
            $query = $manager->createQuery('SELECT p FROM Dashboard\CommonBundle\Entity\FavoriteProducts p WHERE p.productId = ' . $productId . ' AND p.userId = ' . $sessionUser->getId());

            try{
                $favoriteProductIs = $query->getSingleResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $favoriteProductIs = 0;
            }
            
            return $this->render('DashboardCommonBundle:Default:Products/product.html.twig', array("product" => $product,
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
                                                                                          "dealerProducts" => new ArrayCollection(),
                                                                                          "locale" => $locale,
                                                                                          "settings" => $settings,
                                                                                          "productFilters" => $productFilters));
        }
        else
            return $this->render('DashboardCommonBundle:Default:Products/product.html.twig', array("product" => $product,
                                                                                          "categories" => $categories,
                                                                                          "allcities" => $allcities,
                                                                                          "allcategories" => $allcategories,
                                                                                          "orderForm" => $orderForm->createView(),
                                                                                          "friendMessageForm" => $friendMessageForm->createView(),
                                                                                          "complaintMessageForm" => $complaintMessageForm->createView(),
                                                                                          "favoriteProductIs" => 0,
                                                                                          "services" => $services,
                                                                                          "sameProducts" => $sameProducts,
                                                                                          "dealerProducts" => new ArrayCollection(),
                                                                                          "categoriesBread" => array_reverse($categoriesBread),
                                                                                          "locale" => $locale,
                                                                                          "settings" => $settings,
                                                                                          "productFilters" => $productFilters));
    } 
    
    /**
     * @Route("/pages/{route}", name="pages")
     * @Route("/{_locale}/pages/{route}", name="pagesLocale", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function pagesAction($route, Request $request)        
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        if($route)
        {
            $page = $manager->getRepository("DashboardCommonBundle:Page")->findOneBy(array("route" => $route, "locale" => $locale));
            
            if($page)
                return $this->render('DashboardCommonBundle:Default:page.html.twig', array("page" => $page,"locale" => $locale)); 
            else
               throw $this->createNotFoundException(); 
        }
        else
            throw $this->createNotFoundException();
    }

    /**
     * @Route("/404", name="notfound")
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
    
}
