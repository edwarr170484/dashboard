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

use Dashboard\CommonBundle\Entity\Liqpay;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MoneyController extends Controller
{  
    /**
     * @Route("/admin/payment/liqpay", name="admin_payment_liqpay")
     */
    public function liqpayAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $liqpay = $manager->getRepository("DashboardCommonBundle:Liqpay")->find(1);
        
        if(!$liqpay)
            $liqpay = new Liqpay();
        
        $liqpayForm = $this->get('form.factory')->createNamedBuilder('liqpay', 'form', $liqpay)
                ->add('publicKey', TextType::class, array('required' => true, 'label' => 'Public key: *', 'attr' => array('class' => 'form-control')))
                ->add('privateKey', TextType::class, array('required' => true, 'label' => 'Private key: *', 'attr' => array('class' => 'form-control')))
                ->add('currency', ChoiceType::class, array('choices' => array("USD" => "USD", "RUB" => "RUB", "UAH" => "UAH","EUR" => "EUR"), 'required' => true, 'label' => 'Валюта: *', 'attr' => array('class' => 'form-control')))
                ->add('sandbox', CheckboxType::class, array('required' => false, 'label' => 'Тестовый режим: *'))
                ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')))->getForm();
        
        $liqpayForm->handleRequest($request);
        
        if ($liqpayForm->isSubmitted() && $liqpayForm->isValid()) 
        {
            $manager->persist($liqpay);
            $manager->flush();
            
            $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Успешно!</strong> Данные сохранены.</div>')
            );
            
            return $this->redirectToRoute('admin_payment_liqpay');
        }
        
        return $this->render('DashboardAdminBundle:Money:liqpay.html.twig', array("liqpayForm" => $liqpayForm->createView()));
    }
}


