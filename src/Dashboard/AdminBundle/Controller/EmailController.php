<?php

namespace Dashboard\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use Dashboard\CommonBundle\Entity\Email;

class EmailController extends Controller
{
    /**
     * @Route("/admin/emails", name="admin_emails")
     */
    public function listAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $emails = $manager->getRepository("DashboardCommonBundle:Email")->findAll();
        
        return $this->render('DashboardAdminBundle:Email:list.html.twig', array("emails" => $emails));
    }
    
    /**
     * @Route("/admin/emails/edit/{emailId}", name="admin_email_edit")
     */
    public function editAction($emailId, Request $request){
        $manager = $this->getDoctrine()->getManager();
        
        $email = $manager->getRepository("DashboardCommonBundle:Email")->find($emailId);
        
        if(!$email){
            return $this->createNotFoundException("Email template not found");
        }
        
        if (file_exists('../app/Resources/views/Emails/' . $email->getTemplate() . '.html.twig')){
            $emailFile = file_get_contents('../app/Resources/views/Emails/' . $email->getTemplate() . '.html.twig');
        }     
        
        $emailForm = $this->get('form.factory')->createNamedBuilder('email', 'form', $email)
                ->add('title', TextType::class, array('required' => true, 'label' => 'Название:', 'attr' => array('class' => 'form-control')))
                ->add('locale', 'entity', array('class' => 'DashboardCommonBundle:Locale',
                            'choice_label' => 'name',
                            'required' => true, 
                            'label' => 'Локализация:', 'attr' => array('class' => 'form-control')))
                ->add('text', TextareaType::class, array('required' => true, 'data' => $emailFile, 'label' => 'Текст письма:', 'attr' => array('class' => 'form-control tinyeditor', 'rows' => '40')))
                ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')))
                ->getForm();
        
        $emailForm->handleRequest($request);
        
        if($emailForm->isSubmitted() && $emailForm->isValid()){
            
            //create translation file template
            file_put_contents('../app/Resources/views/Emails/' . $email->getTemplate(). '.html.twig', $emailForm['text']->getData());
                        
            $manager->persist($email);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Информация сохранена.</div>')
            );
            
            return $this->redirectToRoute("admin_email_edit", array("emailId" => $email->getId()));
            
        }
        
        return $this->render('DashboardAdminBundle:Email:edit.html.twig', array("emailForm" => $emailForm->createView()));
        
    }
}

