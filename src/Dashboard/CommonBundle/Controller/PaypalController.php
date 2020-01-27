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
    }
}

