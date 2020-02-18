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

use Dashboard\CommonBundle\Entity\RateBill;

class OrderController extends Controller
{
    /**
     * @Route("/account/order/delaerrate/{rateId}", name="account_order_dealerrate")
     */
    public function orderDealerRateAction($rateId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($user->getRoles()[0]->getRole() == 'ROLE_DEALER'){
            $rate = $manager->getRepository("DashboardCommonBundle:CategoryRate")->find($rateId);
            
            if(!$rate){
                throw $this->createNotFoundException();
            }
            
            $bill = new RateBill();
            $bill->setDateAdded(new \DateTime("now"));
            if($rate->getPrice()){
                $bill->setPrice($rate->getPrice());
            }else{
                $bill->setPrice($rate->getRate()->getPrice());
            }
            $bill->setUser($user);
            $bill->setRate($rate->getRate());
            $bill->setCategory($rate->getCategory());
            $bill->setBillId($rate->getBillId());
            $bill->setIsPayed(0);
            
            $manager->persist($bill);
            $manager->flush();
            
            return $this->redirectToRoute('account_payments', array('billId' => $bill->getId(), 'className' => 'RateBill'));
        }
    }
}


