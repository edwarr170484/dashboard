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
use Symfony\Component\Finder\Finder;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\Region;
use Dashboard\CommonBundle\Entity\City;
use Dashboard\CommonBundle\Entity\CityCode;
use Dashboard\AdminBundle\Form\Type\RegionType;
use Dashboard\CommonBundle\Form\Type\UserType;
use Dashboard\CommonBundle\Form\Type\DealerEditType;
use Dashboard\CommonBundle\Form\Type\DealerRegisterType;
class RegionController extends Controller
{
    /**
     * @Route("/admin/region/{page}", name="admin_settings_region", defaults={"page" : 0})
     */
    public function regionAction($page, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

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
        
        $query = $manager->createQuery("SELECT r FROM DashboardCommonBundle:Region r" );

        try{
            $regions = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $regions = 0;
        }

        $totalRegions = count($regions);
        
        $query = $manager->createQuery("SELECT r FROM DashboardCommonBundle:Region r ORDER BY r.name ASC" )->setFirstResult((($page > 0) ? ($page - 1) : 0) * 30)->setMaxResults(30);

        try{
            $regions = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $regions = 0;
        }
        
        $helper = $this->get("app.helpers");
        $pagination = $helper->paginator(($page > 0) ? (int)$page : 1, $totalRegions, 30, "/admin/region", '', '');
                        
        return $this->render('DashboardAdminBundle:Settings:region.html.twig', array("regions" => $regions, "pagination" => $pagination,));
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
     * @Route("/admin/cityimport", name="admin_city_import")
     */
    public function cityImportAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $finder = new Finder();
        
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->find(1);
        $regions = $manager->getRepository("DashboardCommonBundle:Region")->findAll();
        
        $finder->files()->name('city.txt')->in('import');
        $items = new ArrayCollection();
        
        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            
            $lines = file($absoluteFilePath);
            if(count($lines) > 0){
                foreach($lines as $cityInfo){
                    $info = explode(';', $cityInfo);
                    
                    $baseRegion = false;
                    foreach($regions as $region){
                        if($region->getName() == trim($info[0])){
                            $baseRegion = $region;
                            break;
                        }
                    }
                    
                    if($baseRegion){
                        $city = $manager->getRepository("DashboardCommonBundle:City")->findOneByName(trim($info[1]));
                        if($city){
                            $city->setRegion($baseRegion);
                            $manager->persist($city);
                        }
                    }
                    
                    /*$marker = 0;
                    foreach($items as $item){
                        if($item->getName() == $info[0]){
                            $marker = 1;
                            break;
                        }
                    }
                    
                    if(!$marker){
                        $city = new City();
                        $city->setName($info[0]);
                        $city->setRegion($region);
                        $items->add($city);
                    }*/
                    
                    /*$region = $manager->getRepository("DashboardCommonBundle:Region")->findOneByName(trim($cityInfo));
                    
                    if(!$region){
                        $region = new Region();
                        $region->setName(trim($cityInfo));
                        $region->setLocale($locale);
                        $items->add($region);
                    }*/
                }
            }
        }
        
        /*foreach($items as $item){
            $manager->persist($item);
        }*/
        
        $manager->flush();
        
        return new Response('OK');
    }
    
    /**
     * @Route("/region/getuserinfo", name="region_getuserinfo")
     */
    public function getUserinfoAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $user = $this->get('security.context')->getToken()->getUser();
        
        if(!is_string($user)){
            
            $formMain = $this->createForm(new UserType($this->getDoctrine()->getManager(), $user->getUserinfo(), $locale), $user);
            $formMain->handleRequest($request);

            if($formMain['userinfo']['cityCode']->getData() != ""){
                $query = $manager->createQuery("SELECT cc FROM Dashboard\CommonBundle\Entity\CityCode cc WHERE cc.code LIKE '" . $formMain['userinfo']['cityCode']->getData() . "%'");
                $codes = $query->getResult();

                if(strlen($formMain['userinfo']['cityCode']->getData()) >=5){
                    $code = $manager->getRepository("DashboardCommonBundle:CityCode")->findOneByCode($formMain['userinfo']['cityCode']->getData());
                    $user->getUserinfo()->setCity($code->getCity());
                    $user->getUserinfo()->setRegion($code->getCity()->getRegion());
                    $user->getUserinfo()->setCityCode($code);
                    $formMain = $this->createForm(new UserType($this->getDoctrine()->getManager(), $user->getUserinfo(), $locale), $user);
                }
            }else{
                $codes = new ArrayCollection();
            }

            $formDealer = NULL;
            if($user->getDealerinfo()){
                $formDealer = $this->createForm(new DealerEditType($this->getDoctrine()->getManager(), $locale, $user), $user->getDealerInfo());
                $formDealer->handleRequest($request);

                if($formDealer['cityCode']->getData() != ""){
                    $query = $manager->createQuery("SELECT cc FROM Dashboard\CommonBundle\Entity\CityCode cc WHERE cc.code LIKE '" . $formDealer['cityCode']->getData() . "%'");
                    $dealerCodes = $query->getResult();

                    if(strlen($formDealer['cityCode']->getData()) >=5){
                        $code = $manager->getRepository("DashboardCommonBundle:CityCode")->findOneByCode($formDealer['cityCode']->getData());
                        $user->getDealerinfo()->setCity($code->getCity());
                        $user->getDealerinfo()->setRegion($code->getCity()->getRegion());
                        $user->getDealerinfo()->setCityCode($code);
                        $formDealer = $this->createForm(new DealerEditType($this->getDoctrine()->getManager(), $locale, $user), $user->getDealerInfo());
                    }
                }else{
                    $dealerCodes = new ArrayCollection();
                }
            }

            $formDealerView = NULL;
            if($formDealer){
                $formDealerView = $formDealer->createView();
            }

            return $this->render('DashboardCommonBundle:Region:userinfo.html.twig', array("user" => $user, "codes" => $codes, "formMain" => $formMain->createView(), "formDealer" => $formDealerView));
        }else{
            $dealer = new User();
            $registerForm = $this->createForm(new DealerRegisterType($manager, NULL, $locale), $dealer);
            $registerForm->handleRequest($request);
            
            $code = null;
            if($registerForm['dealerinfo']['cityCode']->getData() != ""){
                if(strlen($registerForm['dealerinfo']['cityCode']->getData()) >=5){
                    $code = $manager->getRepository("DashboardCommonBundle:CityCode")->findOneByCode($registerForm['dealerinfo']['cityCode']->getData());
                    $dealer->getDealerinfo()->setCity($code->getCity());
                    $dealer->getDealerinfo()->setRegion($code->getCity()->getRegion());
                    $dealer->getDealerinfo()->setCityCode($code);
                    $registerForm = $this->createForm(new DealerRegisterType($manager, NULL, $locale), $dealer);
                }
            }
            
            return $this->render('DashboardCommonBundle:Region:registerinfo.html.twig', array("registerForm" => $registerForm->createView(), "code" => $code));
        }
    }
    
    /**
     * @Route("/region/getadvertregion", name="region_getadvert")
     */
    public function getAdvertRegionAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $user = $this->get('security.context')->getToken()->getUser();
        
        $regions = $manager->getRepository("DashboardCommonBundle:Region")->findBy(array(), array("name" => "ASC"));
        $selectedRegion = null;
        $selectedCity = null;
        
        if($request->request->get('contactRegion')){
            $selectedRegion = $manager->getRepository("DashboardCommonBundle:Region")->find($request->request->get('contactRegion'));
        }
        
        if($request->request->get('contactCityCode')){
            $code = $manager->getRepository("DashboardCommonBundle:CityCode")->findOneByCode($request->request->get('contactCityCode'));
            if($code){
                $selectedRegion = $code->getCity()->getRegion();
                $selectedCity = $code->getCity();
            }
        }
        
        return $this->render('DashboardCommonBundle:Region:advert.html.twig', array("regions" => $regions,"selectedRegion" => $selectedRegion, "selectedCity" => $selectedCity));
    }
    
    /**
     * @Route("/region/getcodes/{data}", name="region_getcodes")
     */
    public function getCodesAction($data, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $query = $manager->createQuery("SELECT cc FROM Dashboard\CommonBundle\Entity\CityCode cc WHERE cc.code LIKE '" . $data . "%'");
        $codes = $query->getResult();
            
        return $this->render('DashboardCommonBundle:Region:codes.html.twig', array("codes" => $codes));
    }
    
    /**
     * @Route("/region/getcodesadvert/{data}", name="region_getcodesadvert")
     */
    public function getCodesAdvertAction($data, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $query = $manager->createQuery("SELECT cc FROM Dashboard\CommonBundle\Entity\CityCode cc WHERE cc.code LIKE '" . $data . "%'");
        $codes = $query->getResult();
            
        return $this->render('DashboardCommonBundle:Region:codesadvert.html.twig', array("codes" => $codes));
    }
}

