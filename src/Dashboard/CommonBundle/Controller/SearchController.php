<?php
namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Dashboard\CommonBundle\Entity\ProductInfo;

class SearchController extends Controller{
    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $securityContext = $this->container->get('security.authorization_checker');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
            $user = $this->get('security.context')->getToken()->getUser();
        else
            $user = 0;
        
        $view = ($this->get('session')->get('viewCategory')) ? $this->get('session')->get('viewCategory') : 'table';
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.locale=" . $locale->getId() . " AND p.isUserpage = 0 AND p.route = 'search'" );

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
        
        $searchText = '';
        
        if($this->get('session')->get('sessionCity')){
            $city = $manager->getRepository("DashboardCommonBundle:City")->find($this->get('session')->get('sessionCity'));
            $sql = "SELECT p FROM DashboardCommonBundle:Product p LEFT JOIN p.user pu LEFT JOIN p.info pi WHERE p.city = " . $city->getId() . " AND pu.isActive = 1 AND p.isActive = 1 AND p.isConfirm = 1 AND p.isDraft = 0 AND p.isBlocked = 0";
        }
        else{
            $city = 0;
            $sql = "SELECT p FROM DashboardCommonBundle:Product p LEFT JOIN p.user pu LEFT JOIN p.info pi WHERE pu.isActive = 1 AND p.isActive = 1 AND p.isConfirm = 1 AND p.isDraft = 0 AND p.isBlocked = 0";
        }

        if($request->request->get('searchText'))
        {
            $sql .= " AND (p.name LIKE '%" . $request->request->get('searchText') . "%'";
            $sql .= " OR pi.description LIKE '%" . $request->request->get('searchText') . "%')";
        } 
        
        $query = $manager->createQuery($sql);
        
        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        return $this->render('DashboardCommonBundle:Default:Search/search.html.twig', array('searchText' => $searchText,
                                                                                     'products' => $products,
                                                                                     'categories' => $categories,
                                                                                     'pagination' => 0,
                                                                                     'locale' => $locale,
                                                                                     'settings' => $settings,
                                                                                     'page' => $page,
                                                                                     'view' => $view,
                                                                                     'user' => $user));
    }
    
    /**
     * @Route("/search/ajax", name="searchAjax")
     */
    public function searchAjaxAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));

        if($request->request->get('searchText'))
        {
            $sql = "SELECT p,pi FROM DashboardCommonBundle:Product p LEFT JOIN p.user pu LEFT JOIN p.info pi WHERE pu.isActive = 1 AND p.isBlocked = 0 AND p.isActive = 1 AND p.isConfirm = 1 AND p.isDraft = 0";
            
            $sql .= " AND (p.name LIKE '%" . $request->request->get('searchText') . "%'";
            $sql .= " OR pi.description LIKE '%" . $request->request->get('searchText') . "%')";
            
            $query = $manager->createQuery($sql)->setMaxResults(8);
        
            try{
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }
        }else{
            $products = 0;
        }
        
        return new \Symfony\Component\HttpFoundation\JsonResponse(array("count" => ($products) ? count($products) : 0, "view" => $this->renderView('DashboardCommonBundle:Default:Search/searchAjax.html.twig', array('products' => $products,'locale' => $locale))));
    }
    
    public function createSearchSqlAction($request, $category)
    {
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
    }
}

