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
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\ProductOrder;
use Dashboard\CommonBundle\Entity\Payment;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class MoneyController extends Controller
{
    /**
     * @Route("/admin/payments", name="admin_payments_list")
     */
    public function paymentsListAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $payments = $manager->getRepository("DashboardCommonBundle:Payment")->findAll();        
        
        return $this->render('DashboardAdminBundle:Money:list.html.twig', array("payments" => $payments));
    }
    
    /**
     * @Route("/admin/payment/edit/{payemntId}", name="admin_payment_edit", defaults={"payemntId": 0})
     */
    public function paymentEditAction($payemntId,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        if($payemntId){
            $payment = $manager->getRepository("DashboardCommonBundle:Payment")->find($payemntId);
            if(!$payment)
                return $this->createNotFoundException ();
        }
        else{
            $payment = new Payment();
        }       
        
        $builder = $this->get('form.factory')->createNamedBuilder('payment', 'form', $payment);
        $paymentForm = $builder
            ->add('title', TextType::class, array('required' => true, 'label' => 'Название:', 'attr' => array('class' => 'form-control')))
            ->add('icon', TextareaType::class, array('required' => false, 'label' => 'Изображение(svg-код):', 'attr' => array('class' => 'form-control')))    
            ->add('tieser', TextareaType::class, array('required' => false, 'label' => 'Краткое описание:', 'attr' => array('class' => 'form-control tinyeditor'))) 
            ->add('info', TextareaType::class, array('required' => false, 'label' => 'Дополнительная информация:', 'attr' => array('class' => 'form-control tinyeditor'))) 
            ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')))
            ->getForm();
        
        $paymentForm->handleRequest($request);
        
        if($paymentForm->isValid()){
            
            $manager->persist($payment);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Изменения сохранены.</div>')
            );
            
            return $this->redirectToRoute("admin_payment_edit", array("payemntId" => $payment->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Money:edit.html.twig', array("paymentForm" => $paymentForm->createView()));
    }
    
    /**
     * @Route("/account/payments/{billId}", name="account_payments")
     */
    public function paymentsAction($billId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $bill = $manager->getRepository("DashboardCommonBundle:Bill")->find($billId);
        
        if(!$bill){
            return $this->createNotFoundException();
        }
                
        return $this->render('DashboardCommonBundle:Money:payments.html.twig', array("user" => $user,"settings" => $settings,"locale" => $locale,"routeName" => $request->attributes->get("_route"), "totalPrice" => $totalPrice, "bill" => $bill,"back" => $request->headers->get('referer')));
    }
    
    /**
     * @Route("/account/dealer/payments/{billId}", name="account_dealer_payments")
     */
    public function paymentsDealerAction($billId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $bill = $manager->getRepository("DashboardCommonBundle:RateBill")->find($billId);
        
        if(!$bill){
            return $this->createNotFoundException();
        }
        
        return $this->render('DashboardCommonBundle:Money:payments.html.twig', array("user" => $user,"settings" => $settings,"locale" => $locale,"routeName" => $request->attributes->get("_route"), "bill" => $bill,"back" => $request->headers->get('referer')));
    }
}
