<?php

namespace Dashboard\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Dashboard\CommonBundle\Entity\Service;
use Dashboard\AdminBundle\Form\Type\ServiceType;
use Dashboard\CommonBundle\Entity\Pack;
use Dashboard\AdminBundle\Form\Type\PackType;

class ServiceController extends Controller
{
     /**
     * @Route("/admin/service/{serviceId}", name="admin_service",defaults={"serviceId": 0})
     */
    public function serviceAction($serviceId)
    {
        $manager = $this->getDoctrine()->getManager();
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        
        if($serviceId)
        {
            $service = $manager->getRepository("DashboardCommonBundle:Service")->find($serviceId);
            
            if($service)
            {
                if(count($service->getProducts()) <= 0)
                {
                    $manager->remove($service);
                    $manager->flush();
                    
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Услуга удалена.</div>')
                    );
                }
                else
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Ошибка!</strong> Нельзя удалить услугу. К ней привязаны ' . count($service->getProducts()) . ' объявлений.</div>')
                    );
            }
            
            return $this->redirectToRoute("admin_service");
        }
        
        return $this->render('DashboardAdminBundle:Service:list.html.twig', array("services" => $services));
    }
    
     /**
     * @Route("/admin/editservice/{serviceId}", name="admin_service_edit",defaults={"serviceId": 0} )
     */
    public function editServiceAction($serviceId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalTranslations = new ArrayCollection();
        $originalPrices = new ArrayCollection();
        
        if($serviceId)
        {
            $service = $manager->getRepository("DashboardCommonBundle:Service")->find($serviceId);
            
            if(!$service)
                return $this->redirectToRoute("admin_notfound");
        }
        else
            $service = new Service();
        
        if($serviceId)
        {
            foreach ($service->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
            foreach ($service->getPrices() as $item) {
                $originalPrices->add($item);
            }
        }
        
        $serviceForm = $this->createForm(new ServiceType($manager), $service);
        
        $serviceForm->handleRequest($request);
        
        if($serviceForm->isValid())
        {
            if($originalTranslations)
            {
                foreach ($originalTranslations as $item) 
                {
                    if (false === $service->getTranslations()->contains($item)) 
                    {
                        $item->setService(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($originalPrices)
            {
                foreach ($originalPrices as $item) 
                {
                    if (false === $service->getPrices()->contains($item)) 
                    {
                        $item->setService(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($service->getTranslations())
            {
                foreach($service->getTranslations() as $item)
                {
                    $item->setService($service);
                    $manager->persist($item);
                }
            }
            
            if($service->getPrices())
            {
                foreach($service->getPrices() as $item)
                {
                    $item->setService($service);
                    $manager->persist($item);
                }
            }
            
            $manager->persist($service);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Информация об услуге сохранена.</div>')
            );
            
            return $this->redirectToRoute("admin_service_edit", array("serviceId" => $service->getId()));
        }
        return $this->render('DashboardAdminBundle:Service:edit.html.twig', array("serviceForm" => $serviceForm->createView()));
    }
    
    /**
     * @Route("/admin/pack/{packId}", name="admin_service_pack",defaults={"packId": 0})
     */
    public function packAction($packId)
    {
        $manager = $this->getDoctrine()->getManager();
        $packs = $manager->getRepository("DashboardCommonBundle:Pack")->findAll();
        
        if($packId)
        {
            $pack = $manager->getRepository("DashboardCommonBundle:Pack")->find($packId);
            
            if($pack)
            {
                if($pack->getTranslations()){
                    foreach($pack->getTranslations() as $translation){
                        $translation->setPack(null);
                        $manager->remove($translation);
                    }
                }
                if($pack->getServices()){
                    foreach($pack->getServices() as $service){
                        $service->setPack(null);
                        $manager->remove($service);
                    }
                }
                if($pack->getProduct()){
                    $product = $pack->getProduct();
                    $product->getServicePack(null);
                    $manager->persist($product);
                }
                
                $manager->remove($pack);
                $manager->flush();
                
                $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Пакет услуг удален.</div>')
                    );
            }
            
            return $this->redirectToRoute("admin_service_pack");
        }
        
        return $this->render('DashboardAdminBundle:Service:packList.html.twig', array("packs" => $packs));
    }
    
    /**
     * @Route("/admin/editpack/{packId}", name="admin_edit_service_pack",defaults={"packId": 0})
     */
    public function packEditAction($packId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalTranslations = new ArrayCollection();
        $originalServices = new ArrayCollection();
        $originalPrices = new ArrayCollection();
        
        if($packId){
            $pack = $manager->getRepository("DashboardCommonBundle:Pack")->find($packId);
            
            if(!$pack){
                return $this->createNotFoundException();
            }
        }
        else{
           $pack = new Pack(); 
        }
        
        if($pack->getTranslations()){
            foreach ($pack->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
        }
        
        if($pack->getServices()){
            foreach ($pack->getServices() as $item) {
                $originalServices->add($item);
            }
        }
        
        if($pack->getPrices()){
            foreach ($pack->getPrices() as $item) {
                $originalPrices->add($item);
            }
        }
        
        $packForm = $this->createForm(new PackType($manager), $pack);
        
        $packForm->handleRequest($request);
        
        if($packForm->isValid())
        {
            if($originalTranslations)
            {
                foreach ($originalTranslations as $item) 
                {
                    if (false === $pack->getTranslations()->contains($item)) 
                    {
                        $item->setPack(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($pack->getTranslations())
            {
                foreach($pack->getTranslations() as $item)
                {
                    $item->setPack($pack);
                    $manager->persist($item);
                }
            }
            
            if($originalServices)
            {
                foreach ($originalServices as $item) 
                {
                    if (false === $pack->getServices()->contains($item)) 
                    {
                        $item->setPack(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($pack->getServices())
            {
                foreach($pack->getServices() as $item)
                {
                    $item->setPack($pack);
                    $manager->persist($item);
                }
            }
            
            if($originalPrices)
            {
                foreach ($originalPrices as $item) 
                {
                    if (false === $pack->getPrices()->contains($item)) 
                    {
                        $item->setPack(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($pack->getPrices())
            {
                foreach($pack->getPrices() as $item)
                {
                    $item->setPack($pack);
                    $manager->persist($item);
                }
            }
            
            $manager->persist($pack);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Информация сохранена.</div>')
            );
            
            return $this->redirectToRoute("admin_edit_service_pack", array("packId" => $pack->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Service:packEdit.html.twig', array("packForm" => $packForm->createView()));
    }
}

