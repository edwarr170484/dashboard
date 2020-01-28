<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Dashboard\CommonBundle\Model\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class PaypalController extends Controller
{
    public function orderFormAction($billId, $paymentId,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $bill = $manager->getRepository("DashboardCommonBundle:Bill")->find($billId);    
        $payment = $manager->getRepository("DashboardCommonBundle:Payment")->find($paymentId);
        
        return $this->render('DashboardCommonBundle:Money/paypal:orderForm.html.twig', array("bill" => $bill,"payment" => $payment, "settings" => $settings, "locale" => $locale));
    }
    
    /**
     * @Route("/paypal-transaction-verify", name="paypal_transaction_verify")
     */
    public function paypalTransactionVerifycationAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $client = PayPalClient::client();
        $response = $client->execute(new OrdersGetRequest($request->request->get('orderId')));
        
        if($response->statusCode == '200'){
            if($response->result->status == 'COMPLETED'){
                $invoice = $response->result->purchase_units[0]->amount;
                $billId = $response->result->purchase_units[0]->invoice_id;
                $bill = $manager->getRepository("DashboardCommonBundle:Bill")->find($billId);
                
                $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));

                if($invoice->currency_code == $locale->getCurrency()->getCode() && $bill){
                    if($bill->getPrice() == $invoice->value){
                        $bill->setDatePayed(new \DateTime($response->result->update_time));
                        $bill->setIsPayed(1);
                        $manager->persist($bill);
                        //$manager->flush();
                        return new JsonResponse(array("error" => 0, "redirect" => "/account/payment/success/" . $billId));
                    }
                }
            }
        }
        
        return new JsonResponse($response);
    }
}

