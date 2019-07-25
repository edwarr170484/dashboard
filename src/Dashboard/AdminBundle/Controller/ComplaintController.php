<?php

namespace Dashboard\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\Common\Collections\ArrayCollection;



class ComplaintController extends Controller
{
    /**
     * @Route("/admin/complaint/{complaintId}", name="admin_complaint", defaults={"complaintId": 0} )
     */
    public function complaintAction($complaintId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($complaintId || $request->request->get('complaintStatus'))
        {            
            if($complaintId)
            {
                $complaint = $manager->getRepository("DashboardCommonBundle:Complaint")->find($complaintId);

                if($complaint)
                {
                    $manager->remove($complaint);
                    $manager->flush();

                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Успешно!</strong> Жалоба успешно удалена.</div>')
                    );
                }
            }
            
            return $this->redirectToRoute('admin_complaint');
        }
        
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Complaint c ORDER BY c.dateAdded DESC');
        $complaints = $query->getResult();
        
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->find(1);
        
        return $this->render('DashboardAdminBundle:Complaint:complaint.html.twig', array("user" => $user,
                                                                                         "complaints" => $complaints,
                                                                                         "settings" => $settings));
    }
    
    /**
     * @Route("/admin/changecomplaintstatus/{complaintId}/{complaintStatus}", name="account_changecomplaintstatus" )
     */
    public function changeComplaintAction($complaintId, $complaintStatus)
    {
        $manager = $this->getDoctrine()->getManager();
        $complaint = $manager->getRepository("DashboardCommonBundle:Complaint")->find($complaintId);
                
        if($complaint)
        {
            if($complaintStatus == 1 || $complaintStatus == 0)
            {
                $complaint->setStatus($complaintStatus);
                
                $manager->persist($complaint);
                $manager->flush();
                
                return new Response("Статус жалобы изменен");
            }
            else
               return new Response("Неверный статус жалобы"); 
        }
        else
            return new Response("Неверный идентификатор жалобы");
    }
}

