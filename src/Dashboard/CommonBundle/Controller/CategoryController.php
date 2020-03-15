<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

use Dashboard\CommonBundle\Form\Type\CityType;
use Dashboard\CommonBundle\Entity\City;
use Dashboard\CommonBundle\Entity\ProductInfo;

class CategoryController extends Controller
{ 
    /**
     * @Route("/category/{categoryId}_{categoryName}/{page}", name="category", defaults={"page":1})
     */
    public function categoryAction($categoryId, $categoryName, $page, Request $request)        
    {
        $manager = $this->getDoctrine()->getManager();
        
        $securityContext = $this->container->get('security.authorization_checker');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
            $user = $this->get('security.context')->getToken()->getUser();
        else
            $user = 0;
        
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
        
        $categoryModels = new ArrayCollection();
        $categoryMarks = new ArrayCollection();
        
        if($request->request->get('categoryFilter')){
            $categoryFilters = $request->request->get('categoryFilter');
            if(isset($categoryFilters['type'])){
                $categoryType = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryFilters['type']);
                if($categoryType){
                    $categoryMarks = $categoryType->getChildren();
                }
            }
            
            if(isset($categoryFilters['mark'])){
                $categoryMark = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryFilters['mark']);
                if($categoryMark){
                    $categoryModels = $categoryMark->getChildren();
                }
            }
        }else{
            if($categories[0]->getIsUseChildrensLikeType()){
                if(isset($categories[1])){
                    $categoryMarks = $categories[1]->getChildren();
                }
                if(isset($categories[2])){
                    $categoryModels = $categories[2]->getChildren();
                }
            }else{
                if(isset($categories[1])){
                    $categoryModels = $categories[1]->getChildren();
                }
            }
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
        
        $productInfo = new ProductInfo();
        
        if($productInfo->getVars()){
            foreach($productInfo->getVars() as $var){
                if($request->request->get($var)){
                    if($var != 'filter'){
                        foreach($request->request->get($var) as $key => $value){
                            if($value != 0){
                                $joinInstructions .= " LEFT JOIN pi." . $var . " pi" . $var;
                            }
                        }
                    }
                }
            }
        }
        
        $sql = "SELECT p FROM DashboardCommonBundle:Product p LEFT JOIN p.info pi" . $joinInstructions . " LEFT JOIN p.user pu LEFT JOIN pu.roles pur WHERE pu.isActive = 1 AND p.isConfirm = 1 AND p.isBlocked = 0 AND p.isActive = 1";
        
        if($this->get('session')->has('sessionCity')){
            $sql .= " AND p.city = " . $this->get('session')->get('sessionCity');
        }
        
        if($request->request->get('userFilter') && $request->request->get('userFilter') != "-1"){
            $sql .= " AND pur.id = " . intval($request->request->get('userFilter'));
        }
        
        if($request->request->get('categoryFilter')){
            $clearCategory = 1;
            if(isset($categoryFilters['model']) && $categoryFilters['model'] != 0){
                $sql .= " AND (";
                foreach($categoryFilters['model'] as $key => $categoryId){
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
                $clearCategory = 0;
            }elseif(isset($categoryFilters['mark']) && $categoryFilters['mark'] != 0){
                $searchCategory = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryFilters['mark']);
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
                else{
                    $sql .= " AND p.category = " . $searchCategory->getId();
                }
                $clearCategory = 0;
            }elseif(isset($categoryFilters['type']) && $categoryFilters['type'] != 0){
                $searchCategory = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryFilters['type']);
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
                else{
                    $sql .= " AND p.category = " . $searchCategory->getId();
                }
                $clearCategory = 0;
            }
            if($clearCategory){
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
                else{
                    $sql .= " AND p.category = " . $category->getId();
                }
            }
        }else{
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
            else{
                $sql .= " AND p.category = " . $category->getId();
            }
        }
        
        if($request->request->get('searchText'))
        {
            $sql .= " AND (p.name LIKE '%" . $request->request->get('searchText') . "%'";
            $sql .= " OR p.description LIKE '%" . $request->request->get('searchText') . "%')";
        }
        
        if($request->request->get('searchNew') && $request->request->get('searchOld')){
            $sql .= " AND (pi.probeg <= 0 or pi.probeg > 0)";
        }elseif($request->request->get('searchNew')){
            $sql .= " AND pi.probeg <= 0";
        }elseif($request->request->get('searchOld')){
            $sql .= " AND pi.probeg > 0";
        }elseif($request->query->get('searchNew')){
            $sql .= " AND pi.probeg <= 0";
        }elseif($request->query->get('searchOld')){
            $sql .= " AND pi.probeg > 0";
        }
        
        if($productInfo->getVars()){
            foreach($productInfo->getVars() as $var){
                if($request->request->get($var)){
                    if($var != 'filter'){
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
                    }else{
                        foreach($request->request->get($var) as $key => $value){
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
                }
                
                if($request->request->get($var . "RangeList")){
                    if($var != 'filter'){
                       foreach($request->request->get($var . 'RangeList') as $key => $value){
                            if((isset($value[0]) && $value[0] != 0) || (isset($value[1]) && $value[1] != 0)){
                                if(isset($value[0]) && $value[0] != 0){
                                    $parameter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($value[0]);
                                    $sql .= " AND pi." . $var ." >= " . $parameter->getValue();
                                }
                                if(isset($value[1]) && $value[1] != 0){
                                    $parameter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($value[1]);
                                    $sql .= " AND pi." . $var ." <= " . $parameter->getValue();
                                }
                            }
                       } 
                    }else{
                        foreach($request->request->get($var . 'RangeList') as $key => $value)
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
                }
                
                if($request->request->get($var . "Selectable")){
                    if($var != 'filter'){
                        if((isset($value[0]) && $value[0] != 0) || (isset($value[1]) && $value[1] != 0)){
                            if(isset($value[0]) && $value[0] != 0){
                                $sql .= " AND pi." . $var ." >= " . $value[0];
                            }
                            if(isset($value[1]) && $value[1] != 0){
                                $sql .= " AND pi." . $var ." <= " . $value[1];
                            }
                        }
                    }else{
                        if(count($request->request->get($var . 'Selectable')) > 0)
                        {
                            foreach($request->request->get($var . 'Selectable') as $key => $value)
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
        
        $premiumProducts = new ArrayCollection();
        $specialProducts = new ArrayCollection();
        $regularProducts = new ArrayCollection();
        
        if($products){
            foreach($products as $product){
                $product->setIsFavorite(0);
                if($user){    
                    $query = $manager->createQuery('SELECT fp FROM Dashboard\CommonBundle\Entity\FavoriteProducts fp WHERE fp.userId = ' . $user->getId() . ' AND fp.productId=' . $product->getId());

                    try{
                        $favorite = $query->getResult();
                    }
                    catch(\Doctrine\ORM\NoResultException $e) {
                        $favorite = 0;
                    }

                    (count($favorite) > 0) ? $product->setIsFavorite(1) : $product->setIsFavorite(0);
                }
                
                if($product->getServices()){
                    $isService = 0;
                    foreach($product->getServices() as $service){
                        if($service->getIsActive()){
                            if(($service->getService()->getId() == $settings->getPremiumService()->getId())){
                                $product->setServiceName('payed');
                                $premiumProducts->add($product);
                                $isService = 1;
                            }
                            if(($service->getService()->getId() == $settings->getSpecialService()->getId())){
                                $specialProducts->add($product);
                                $isService = 1;
                            }
                            if(($service->getService()->getId() == $settings->getSelectedService()->getId())){
                                $product->setServiceName('payed');
                            }
                        }
                    }
                    
                    if(!$isService){
                        $regularProducts->add($product);
                    }
                }
            }
        }
        
        $premiumProduct = (count($premiumProducts) > 0) ? $premiumProducts[rand(1, count($premiumProducts)) - 1] : NULL;
        
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
        
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL AND c.isActive = 1 ORDER BY c.sortorder');
        
        try{
            $baseCategories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $baseCategories = 0;
        }
        
        $query = $manager->createQuery("SELECT r FROM DashboardCommonBundle:Role r WHERE r.role <> 'ROLE_ADMIN' AND r.role <> 'ROLE_SERVICE'");
        $roles = $query->getResult();
        
        return $this->render('DashboardCommonBundle:Default:Category/category.html.twig', array("category" => $category,
                                                                                       "categories" => $categories,
                                                                                       "baseCategories" => $baseCategories,
                                                                                       "categoryMarks" => $categoryMarks,
                                                                                       "categoryModels" => $categoryModels,
                                                                                       "formFilters" => $filters,
                                                                                       "products" => $regularProducts,
                                                                                       "productsTotalCount" => $productsTotalCount,
                                                                                       "pagination" => $pagination,
                                                                                       "premiumProduct" => $premiumProduct,
                                                                                       "specialProducts" => $specialProducts,
                                                                                       "upProducts" => new ArrayCollection(),
                                                                                       "dealerProducts" => new ArrayCollection(),
                                                                                       "filters" => $request->request->get('filter'),
                                                                                       "filtersRangeList" => $request->request->get('filterRangeList'),
                                                                                       "filterSelectable" => $request->request->get('filterSelectable'),
                                                                                       "filterSelectablePrice" => $request->request->get('filterSelectablePrice'),
                                                                                       "locale" => $locale,
                                                                                       "settings" => $settings,
                                                                                       "view" => $view,
                                                                                       "user" => $user,
                                                                                       "roles" => $roles));
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
     * @Route("/category/subcategories/{type}/{categoryId}", name="category_subcategories")
     */
    public function subcategoriesAction($type, $categoryId, Request $request)        
    {
        $manager = $this->getDoctrine()->getManager();
        $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
        $baseCategory = null;
        $tmpCategory = $category;
        
        if($category){
            while($tmpCategory->getParent()){
                $baseCategory = $tmpCategory->getParent();
                $tmpCategory = $tmpCategory->getParent();
            }
        }
        
        return $this->render('DashboardCommonBundle:Default:Category/filterItems.html.twig', array("category" => $category, "baseCategory" => $baseCategory, "type" => $type));
    }
}

