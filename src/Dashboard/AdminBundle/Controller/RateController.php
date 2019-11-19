<?php

namespace Dashboard\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Dashboard\CommonBundle\Entity\Rate;
use Dashboard\AdminBundle\Form\Type\RateType;

class RateController extends Controller
{
    /**
     * @Route("/admin/rate", name="admin_rate")
     */
    public function adminListAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $rates = $manager->getRepository("DashboardCommonBundle:Rate")->findAll();
        
        if($request->request->get('rate'))
        {
            foreach($request->request->get('rate') as $key => $id)
            {
                $rate = $manager->getRepository("DashboardCommonBundle:Rate")->find($id);
                 
                if($rate){
                    if($rate->getTranslations()){
                        foreach($rate->getTranslations() as $translation){
                            $translation->setRate(null);
                            $manager->remove($translation);
                        }
                    }
                    
                    if($rate->getServices()){
                        foreach($rate->getServices() as $service){
                            $service->setRate(null);
                            $manager->remove($service);
                        }
                    }
                    
                    $categories = $manager->getRepository("DashboardCommonBundle:CategoryRate")->findBy(array("rate" => $rate));
                    
                    if($categories){
                        foreach($categories as $category){
                            $category->setRate(null);
                            $manager->remove($category);
                        }
                    }
                    
                    $manager->remove($rate);
                    $manager->flush();
                }
            }
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Отмеченные пункты удалены.</div>')
            );
            
            return $this->redirectToRoute("admin_rate");
        }

        return $this->render('DashboardAdminBundle:Rate:list.html.twig', array("rates" => $rates));
    }
    
    /**
     * @Route("/admin/rate/edit/{rateId}", name="admin_rate_edit", defaults={"rateId" : 0})
     */
    public function adminEditAction($rateId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalServices = new ArrayCollection();
        $originalTranslations = new ArrayCollection();
        
        if($rateId){
            $rate = $manager->getRepository("DashboardCommonBundle:Rate")->find($rateId);
            if(!$rate)
                return $this->createNotFoundException ();
            
            foreach ($rate->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
            foreach ($rate->getServices() as $item) {
                $originalServices->add($item);
            }
        }
        else{
            $rate = new Rate();
        }

        $rateForm = $this->createForm(new RateType($manager), $rate);
        $rateForm->handleRequest($request);
        
        if($rateForm->isValid()){
            
            if($originalTranslations)
            {
                foreach ($originalTranslations as $item) 
                {
                    if (false === $rate->getTranslations()->contains($item)) 
                    {
                        $item->setRate(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($rate->getTranslations())
            {
                foreach($rate->getTranslations() as $item)
                {
                    $item->setRate($rate);
                    $manager->persist($item);
                }
            }
            
            if($originalServices)
            {
                foreach ($originalServices as $item) 
                {
                    if (false === $rate->getServices()->contains($item)) 
                    {
                        $item->setRate(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($rate->getServices())
            {
                foreach($rate->getServices() as $item)
                {
                    $item->setRate($rate);
                    $manager->persist($item);
                }
            }
            
            $manager->persist($rate);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Информация о тарифе сохранена.</div>')
            );
            
            return $this->redirectToRoute("admin_rate_edit", array("rateId" => $rate->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Rate:edit.html.twig', array("rateForm" => $rateForm->createView()));
    }
}

