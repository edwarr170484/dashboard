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
use Dashboard\CommonBundle\Entity\Product;
use Dashboard\AdminBundle\Form\Type\ServiceType;

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
        
        return $this->render('DashboardAdminBundle:Default:service.html.twig', array("services" => $services));
    }
    
     /**
     * @Route("/admin/editservice/{serviceId}", name="admin_service_edit",defaults={"serviceId": 0} )
     */
    public function editServiceAction($serviceId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalTranslations = new ArrayCollection();
        
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
            
            if($service->getTranslations())
            {
                foreach($service->getTranslations() as $item)
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
        return $this->render('DashboardAdminBundle:Default:editservice.html.twig', array("serviceForm" => $serviceForm->createView()));
    }
}

