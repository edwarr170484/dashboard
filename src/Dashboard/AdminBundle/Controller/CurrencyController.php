<?php

namespace Dashboard\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityRepository;

use Dashboard\CommonBundle\Entity\Currency;
use Dashboard\AdminBundle\Form\Type\CurrencyType;

class CurrencyController extends Controller
{
    /**
     * @Route("/admin/currency", name="currencyBoard")
     */
    public function currencyAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $currencies = $manager->getRepository("DashboardCommonBundle:Currency")->findBy(array(), array("sortorder" => "ASC"));
        
        $form = $this->createFormBuilder()->add('action','hidden',array('attr' => array('value' => 'save')))->getForm();
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            switch($form['action']->getData())
            {
                    case 'save':
                        if($request->request->get('currencyDefault'))
                        {
                            foreach($currencies as $currency)
                            {
                                $currency->setIsDefault(0);
                                $manager->persist($currency);
                            }
                        }

                        $manager->flush();

                        if($request->request->get('currencyDefault'))
                        {
                            foreach($request->request->get('currencyDefault') as $key => $value)
                            {
                                $currency = $manager->getRepository("DashboardCommonBundle:Currency")->find($value);
                                if($currency)
                                {
                                    $currency->setIsDefault(1);
                                    $manager->persist($currency);
                                    break;
                                }
                            }
                        }

                        if($request->request->get('sortorder'))
                        {
                            foreach($request->request->get('sortorder') as $key => $value)
                            {
                                $currency = $manager->getRepository("DashboardCommonBundle:Currency")->find($key);
                                if($currency)
                                {
                                    $currency->setSortorder($value);
                                    $manager->persist($currency);
                                }
                            }
                        }

                        $manager->flush();

                        $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Успешно!</strong> Изменения сохранены.</div>')
                        );

                    break;   
            }

            return $this->redirectToRoute("currencyBoard");
        }
        
        return $this->render('DashboardAdminBundle:Currency:list.html.twig', array("currencies" => $currencies, "form" => $form->createView()));
    }
    
    /**
     * @Route("/board/edit/currency/{currencyId}", name="currencyEditBoard", defaults={"currencyId" : "0"})
     */
    public function currencyEditAction($currencyId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $currency = ($currencyId) ? $manager->getRepository("DashboardCommonBundle:Currency")->find($currencyId) : new Currency();
        
        $currencyForm = $this->createForm(new CurrencyType(), $currency);
        
        $currencyForm->handleRequest($request);
        
        if($currencyForm->isSubmitted() && $currencyForm->isValid())
        {  
            $currency->setIsDefault(false);
            $manager->persist($currency);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Выполнено!</strong> Изменения сохранены.</div>')
            );
            
            if($currencyForm['exit']->getData())
            {
                return $this->redirectToRoute("currencyBoard");
            }
            
            return $this->redirectToRoute("currencyEditBoard", array("currencyId" => $currency->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Currency:edit.html.twig', array("currencyForm" => $currencyForm->createView()));
    }
}

