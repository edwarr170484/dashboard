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
use Dashboard\CommonBundle\Entity\ProductService;
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
                
        return $this->render('DashboardCommonBundle:Money:payments.html.twig', array("user" => $user,"settings" => $settings,"locale" => $locale,"routeName" => $request->attributes->get("_route"), "bill" => $bill, "back" => $request->headers->get('referer')));
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
    
    /**
     * @Route("/account/payment/success", name="account_payment_success")
     */
    public function paymentSuccessAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $session = new Session();
        
        $billId = 6;
        $bill = $manager->getRepository("DashboardCommonBundle:Bill")->find($billId);
        
        if($bill && $bill->getIsPayed() && !$bill->getIsClosed()){
            if($bill->getRates()){
                foreach($bill->getRates() as $rate){
                    if(!$rate->getDateAdded()){
                        $rate->setDateAdded(new \DateTime("now"));
                    }
                    $currentDate = ($rate->getDateEnd()) ? $rate->getDateEnd() : new \DateTime("now");
                    $rate->setDateEnd($currentDate->add(new \DateInterval('P' . $rate->getRate()->getActiveTime() . 'Y')));
                    $rate->setIsActive(1);
                    $manager->persist($rate);
                }
                
                $manager->flush();
                $session->remove('selectedSalons');
            }
            
            if($bill->getServicePack()){
                $servicePack = $bill->getServicePack();
                $product = $bill->getProducts()[0];
                
                if($servicePack->getServices()){
                    foreach($servicePack->getServices() as $packService){
                        $service = $packService->getService();
                        
                        if($packService->getValue()){
                            $serviceCount = $packService->getValue();
                        }else{
                            $serviceCount = $service->getDays();
                        }
                        
                        $productService = new ProductService();
                        $productService->setProduct($product);
                        $productService->setService($service);
                        $productService->setIsActive(1);
                        $productService->setDateAdded(new \DateTime("now"));
                        $productService->setCount($serviceCount);
                            
                        $serviceCountParameter = $service->getParameter();
                            
                        switch($serviceCountParameter){
                            case 'days':
                                $currentDate = new \DateTime("now");
                                $productService->setDateEnd($currentDate->add(new \DateInterval('P' . $serviceCount . 'D')));
                            break;
                                
                            case 'count':
                                $productService->setDateEnd(new \DateTime("now"));
                            break;
                        }
                        
                        $manager->persist($productService);
                    }
                }
                
                $manager->flush();
            }
            
            if(count($bill->getServices()) > 0){
                foreach($bill->getServices() as $productService){
                    $serviceCount = $productService->getService()->getDays();
                    $serviceCountParameter = $productService->getService()->getParameter();
                    
                    if($productService->getProduct()){
                        $productService->setIsActive(1);
                        $productService->setCount($productService->getCount() + $serviceCount);
                        
                        switch($serviceCountParameter){
                            case 'days':
                                $currentDate = ($productService->getDateEnd()) ? $productService->getDateEnd() : new \DateTime("now");
                                $productService->setDateEnd($currentDate->add(new \DateInterval('P' . $serviceCount . 'D')));
                            break;
                                
                            case 'count':
                                $productService->setDateEnd(new \DateTime("now"));
                            break;
                        }
                    }else{
                        $productService->setProduct($bill->getProducts()[0]);
                        $productService->setIsActive(1);
                        $productService->setDateAdded(new \DateTime("now"));
                        $productService->setCount($productService->getCount() + $serviceCount);
                        
                        switch($serviceCountParameter){
                            case 'days':
                                $currentDate = new \DateTime("now");
                                $productService->setDateEnd($currentDate->add(new \DateInterval('P' . $serviceCount . 'D')));
                            break;
                                
                            case 'count':
                                $productService->setDateEnd(new \DateTime("now"));
                            break;
                        }
                    }
                    
                    $manager->persist($productService);
                }
                
                $session->remove('selectedServices');
            }
            
            $bill->setIsClosed(1);
            $manager->flush();
        }
        
        return new Response("OK");
    }
}
