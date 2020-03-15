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

use Dashboard\CommonBundle\Entity\UserRate;
use Dashboard\CommonBundle\Entity\UserRateItem;
use Dashboard\CommonBundle\Entity\ProductOrder;
use Dashboard\CommonBundle\Entity\ProductService;
use Dashboard\CommonBundle\Entity\Payment;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use Dashboard\CommonBundle\Model\CustomPdf;

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
            ->add('clientId', TextType::class, array('required' => false, 'label' => 'Client ID:', 'attr' => array('class' => 'form-control')))    
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
     * @Route("/account/payments/{billId}/{className}", name="account_payments")
     */
    public function paymentsAction($billId, $className, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $bill = $manager->getRepository("DashboardCommonBundle:" . $className)->find($billId);
        
        if(!$bill){
            return $this->createNotFoundException();
        }
                
        return $this->render('DashboardCommonBundle:Money:payments.html.twig', array("user" => $user,"settings" => $settings,"locale" => $locale,"routeName" => $request->attributes->get("_route"), "bill" => $bill, "back" => "/account/bills"));
    }
    
    /**
     * @Route("/account/payment/success/{billId}/{paymentId}/{className}", name="account_payment_success")
     */
    public function paymentSuccessAction($billId, $paymentId, $className, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $session = new Session();
        
        $bill = $manager->getRepository("DashboardCommonBundle:" . $className)->find($billId);
        $payment = $manager->getRepository("DashboardCommonBundle:Payment")->find($paymentId);
        
        if($bill && $bill->getIsPayed() && !$bill->getIsClosed()){
            if($className == "RateBill"){
                $userRate = new UserRate();
                $userRate->setUser($user);
                $userRate->setDateStart(new \DateTime("now"));
                $currentDate = $userRate->getDateStart();
                $userRate->setDateEnd($currentDate->add(new \DateInterval('P' . $bill->getRate()->getActiveTime() . 'Y')));
                $userRate->setRate($bill->getRate());
                $userRate->setCategory($bill->getCategory());
                $userRate->setAdvertNumber($bill->getRate()->getAdvertNumber());
                $userRate->setIsActive(1);

                if(count($bill->getRate()->getServices())){
                    foreach($bill->getRate()->getServices() as $rateService){
                        $userRateItem = new UserRateItem();
                        $userRateItem->setUserrate($userRate);
                        $userRateItem->setService($rateService);
                        $userRateItem->setCount($rateService->getValue());
                        $manager->persist($userRateItem);
                    }
                }            

                $bill->setPayment($payment);
                $bill->setIsClosed(1);
                $manager->persist($userRate);
                $manager->persist($bill);
                $manager->flush();
            }else{
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
                    $product = $bill->getProducts()->first();

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
                            $product = $productService->getProduct();

                            $advancedDays = 0;
                            foreach($bill->getServices() as $productService){
                                if($productService->getProduct()->getId() == $product->getId()){
                                    if($productService->getService()->getDays() > $advancedDays){
                                        $advancedDays = $productService->getService()->getDays();
                                    }
                                }
                            }

                            switch($serviceCountParameter){
                                case 'days':
                                    $currentDate = ($productService->getDateEnd()) ? $productService->getDateEnd() : new \DateTime("now");
                                    $productService->setDateEnd($currentDate->add(new \DateInterval('P' . $serviceCount . 'D')));

                                    $daysLeft = $productService->getProduct()->getDaysLeft();

                                    if($advancedDays == $serviceCount){
                                        if($serviceCount > $daysLeft){
                                            $diffDays = $serviceCount - $daysLeft;
                                            $endDate = $product->getDateEnd()->add(new \DateInterval('P' . $diffDays . 'D'));
                                            $product->setDateEnd($endDate);
                                            $manager->persist($product);
                                        }
                                    }
                                break;

                                case 'count':
                                    $productService->setDateEnd(new \DateTime("now"));
                                break;
                                }
                        }else{
                            $productService->setProduct($bill->getProducts()->first());
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
                $bill->setPayment($payment);
                $bill->setIsClosed(1);
                $manager->persist($bill);
                $manager->flush();
            }
            
            $pdf = new CustomPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false, $settings);
            $pdf->SetCreator($settings->getSiteName());
            $pdf->SetAuthor($settings->getSiteName());
            $pdf->SetTitle('Invoice-#' . $bill->getId());
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $settings->getSiteName(), '', array(0,64,255), array(0,64,128));
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->setFontSubsetting(true);
            $pdf->SetFont('dejavusans', '', 9);
            $pdf->AddPage();
            
            if($user->getRoles()[0]->getRole() == 'ROLE_DEALER' || $user->getRoles()[0]->getRole() == 'ROLE_SERVICE'){
                $pdf->SetXY(PDF_MARGIN_LEFT, 45);
                $html = '<dl style="color:#fff"><dt><b>' . $user->getDealerinfo()->getCompany() . ' C.I.F. / N.I.F.</b> N33333333<br/></dt><dt>' . $user->getDealerinfo()->getAddress() . ', ' . $user->getDealerinfo()->getCityCode()->getCode() . ' ' . $user->getDealerinfo()->getCity()->getName() . '</dt><dt>' . $user->getEmail() . '</dt></dl>';
                $pdf->writeHTMLCell(0, 0, '', '', $html, 'L', 1, 0, true, 'L', true);
                $html = '<dl><dt><b>' . $user->getDealerinfo()->getCompany() . ' C.I.F. / N.I.F.</b> N33333333<br/></dt><dt>' . $user->getDealerinfo()->getAddress() . ', ' . $user->getDealerinfo()->getCityCode()->getCode() . ' ' . $user->getDealerinfo()->getCity()->getName() . '</dt><dt>' . $user->getEmail() . '</dt></dl>';
                $pdf->SetXY(PDF_MARGIN_LEFT + 1, 43);
                $pdf->writeHTMLCell(0, 0, '', '', $html, '', 1, 0, true, 'L', true);
            }else{
                $pdf->SetXY(PDF_MARGIN_LEFT, 45);
                $html = '<dl style="color:#fff"><dt><b>' . $user->getUserinfo()->getFirstname() . ' ' . $user->getUserinfo()->getLastname() . ' D.N.I./N.I.E.</b> N33333333<br/></dt><dt>' . $user->getUserinfo()->getCityCode()->getCode() . ' ' . $user->getUserinfo()->getCity()->getName() . '</dt><dt>' . $user->getEmail() . '</dt></dl>';
                $pdf->writeHTMLCell(0, 0, '', '', $html, 'L', 1, 0, true, 'L', true);
                $html = '<dl><dt><b>' . $user->getUserinfo()->getFirstname() . ' ' . $user->getUserinfo()->getLastname() . ' D.N.I./N.I.E.</b> N33333333<br/></dt><dt>' . $user->getUserinfo()->getCityCode()->getCode() . ' ' . $user->getUserinfo()->getCity()->getName() . '</dt><dt>' . $user->getEmail() . '</dt></dl>';
                $pdf->SetXY(PDF_MARGIN_LEFT + 1, 43);
                $pdf->writeHTMLCell(0, 0, '', '', $html, '', 1, 0, true, 'L', true);
            }
            
            $pdf->SetXY(PDF_MARGIN_LEFT, 75);
            $html = '<table>'
                    . '<tr><td><b>№ Cliente</b></td><td><b>Número de FACTURA</b></td><td><b>Fecha</b></td><td><b>Tipo Documento</b></td></tr>'
                    . '<tr><td>' . $user->getId() . '</td><td>' . $bill->getId() . '</td><td>' . $bill->getDateAdded()->format("d/m/Y") . '</td><td>FACTURA</td></tr>'
                    . '<tr><td><br/></td><td><br/></td><td><br/></td><td><br/></td></tr>';
                  
            if($bill->getPayment()){
                $html .= '<tr><td> </td><td></td><td></td><td></td></tr>'
                        . '<tr><td><b>Modo de pago</b></td><td></td><td></td><td></td></tr>'
                        . '<tr><td>' . $bill->getPayment()->getTitle() . '</td><td></td><td></td><td></td></tr>'
                        . '<tr><td> </td><td></td><td></td><td></td></tr>';
            }
            
            if($user->getRoles()[0]->getRole() == 'ROLE_DEALER' || $user->getRoles()[0]->getRole() == 'ROLE_SERVICE'){
                $html .= '<tr><td><b>Banco Sabadell</b></td><td></td><td></td><td></td></tr>'
                        . '<tr><td colspan="3"><b>IBAN:</b> ES93 0081 0105 1300 0252 5055</td><td></td></tr>'
                        . '<tr><td colspan="3"><b>SWIFT (BIC):</b> BSABESBB</td><td></td></tr>';
            }
            
            $html .= '</table><br/>';
            $pdf->writeHTMLCell(0, 0, '', '', $html, '', 1, 0, true, 'L', true);
            
            $services = '';
            $totalServicePrice = 0;
            $totalPrice = 0;
            $totalPriceNds = 0;
            
            if($className == "RateBill"){
                if($bill->getRate()){
                    $category = $bill->getCategory();
                    $billId = 0;
                    if(count($category->getRates()) > 0){
                        foreach($category->getRates() as $categoryRate){
                            if($categoryRate->getRate()->getId() == $bill->getRate()->getId()){
                                $billId = $categoryRate->getBillId();
                                $totalServicePrice = $categoryRate->getPrice();
                            }
                        }
                    }
                    $totalPriceNds = round($totalServicePrice * $settings->getPremiumAdvPrice(), 2);
                    $totalPrice = $totalPriceNds + $totalServicePrice;
                    
                    $services .= '<tr>'
                        . '<td>' . $billId . '</td>'
                        . '<td>' . $bill->getRate()->getName() . '</td>'
                        . '<td>1</td>'
                        . '<td>' . $totalServicePrice . '</td>'
                        . '<td>' . $totalPriceNds . '</td>'
                        . '<td>' . $settings->getPremiumAdvPrice() * 100 . '%</td>'
                        . '</tr>';
                }
            }else{
                if($bill->getRates()){
                    foreach($bill->getRates() as $rate){
                        $services .= '<tr>'
                        . '<td>' . $rate->getRate()->getBillId() . '</td>'
                        . '<td>' . $rate->getRate()->getName() . '</td>'
                        . '<td>1</td>'
                        . '<td>' . $rate->getRate()->getPrice() . '</td>'
                        . '<td>' . $rate->getRate()->getPrice() * $settings->getPremiumAdvPrice() . '</td>'
                        . '<td>' . $settings->getPremiumAdvPrice() * 100 . '%</td>'
                        . '</tr>';

                        $totalServicePrice += $rate->getRate()->getPrice(); 
                    }

                    $totalPriceNds = round($totalServicePrice * $settings->getPremiumAdvPrice(), 2) ;
                    $totalPrice = $totalServicePrice + $totalPriceNds;
                }
                
                if($bill->getServicePack()){
                    $servicePack = $bill->getServicePack();
                    $product = $bill->getProducts()->first();
                    $billId = 0;
                    
                    if(count($servicePack->getPrices()) > 0){
                        foreach($servicePack->getPrices() as $packPrice){
                            if($packPrice->getCategory()->getId() == $product->getBaseCategory()->getId()){
                                $billId = $packPrice->getBillId();
                            }
                        }
                    }
                    
                    $totalServicePrice = $bill->getPrice() ;
                    $totalPriceNds = round($totalServicePrice * $settings->getPremiumAdvPrice(), 2);
                    $totalPrice = $totalPriceNds + $totalServicePrice;
                    
                    $services .= '<tr>'
                        . '<td>' . $billId . '</td>'
                        . '<td>' . $servicePack->getLabel() . '</td>'
                        . '<td>1</td>'
                        . '<td>' . $totalServicePrice . '</td>'
                        . '<td>' . $totalPriceNds . '</td>'
                        . '<td>' . $settings->getPremiumAdvPrice() * 100 . '%</td>'
                        . '</tr>';
                }
                
                if(count($bill->getServices()) > 0){
                    $totalServicePrice = $bill->getPrice();
                    $totalPriceNds = round($totalServicePrice * $settings->getPremiumAdvPrice(), 2);
                    $totalPrice = $totalPriceNds + $totalServicePrice;
                    
                    foreach($bill->getServices() as $productService){
                        $baseCategory = $productService->getProduct()->getBaseCategory();
                        $servicePriceValue = 0;
                        $billId = 0;
                        foreach($productService->getService()->getPrices() as $servicePrice){
                            if($servicePrice->getCategory()->getId() == $baseCategory->getId()){
                                $servicePriceValue = $servicePrice->getPrice();
                                $billId = $servicePrice->getBillId();
                            }
                        }
                        
                        $services .= '<tr>'
                            . '<td>' . $billId . '</td>'
                            . '<td>' . $productService->getService()->getTitle() . '</td>'
                            . '<td>1</td>'
                            . '<td>' . $servicePriceValue + round($servicePriceValue * $settings->getPremiumAdvPrice(), 2) . '</td>'
                            . '<td>' . round($servicePriceValue * $settings->getPremiumAdvPrice(), 2) . '</td>'
                            . '<td>' . $settings->getPremiumAdvPrice() * 100 . '%</td>'
                            . '</tr>';
                        
                    }
                }                
            }
            
            $html = '<table border="1" cellpadding="4">'
                        . '<tr>'
                        . '<th><b>Referencia</b></th>'
                        . '<th><b>Descripción</b></th>'
                        . '<th><b>Cantidad</b></th>'
                        . '<th><b>Precio unitario</b></th>'
                        . '<th><b>Total</b></th>'
                        . '<th><b>IVA</b></th>'
                        . '</tr>'
                        . $services
                        . '</table>'
                        . '<table cellpadding="4">'
                        . '<tr>'
                        . '<th></th>'
                        . '<th><b>Total BI.</b></th>'
                        . '<th><b>IVA</b></th>'
                        . '<th><b>Total Imp.</b></th>'
                        . '<th><b>Importe Neto</b></th>'
                        . '<th style="text-align:right">' . $totalServicePrice . '</th>'
                        . '</tr>'
                        . '<tr>'
                        . '<td></td>'
                        . '<td>' . $totalServicePrice . '</td>'
                        . '<td>' . $settings->getPremiumAdvPrice() * 100 . '%</td>'
                        . '<td>' . $totalPriceNds . '</td>'
                        . '<td>IVA</td>'
                        . '<td style="text-align:right">' . $totalPriceNds . '</td>'
                        . '</tr>'
                        . '<tr>'
                        . '<td></td>'
                        . '<td></td>'
                        . '<td></td>'
                        . '<td></td>'
                        . '<td style="border-top:1"><b>TOTAL ' . $locale->getCurrency()->getCode(). '.</b></td>'
                        . '<td style="text-align:right;border-top:1">' . $totalPrice . '</td>'
                        . '</tr>'
                        . '</table><div></div><div></div><div></div>' . $user->getRoles()[0]->getInvoiceText();
            
            $pdf->writeHTMLCell(0, 0, '', '', $html, '', 1, 0, true, 'L', true);
            
            $pdfString = $pdf->Output(null, 'S');
            $fp = fopen("docs/invoice-#" . $bill->getId() . ".pdf", "w");
            fwrite($fp, $pdfString);
            fclose($fp);
        }
        
        return $this->render('DashboardCommonBundle:Money:result.html.twig', array("user" => $user, "settings" => $settings, "locale" => $locale, "routeName" => "account_payments", "bill" => $bill,"back" => "/account/bills"));
    }
}
