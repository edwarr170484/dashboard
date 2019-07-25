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

use Dashboard\CommonBundle\Entity\FormMessage;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class MessageController extends Controller
{  
    /**
     * @Route("/admin/messages/{messageId}", name="admin_messages", defaults={"messageId" : "0"})
     */
    public function messagesAction($messageId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->find(1);
        
        if($request->request->get('actionForm'))
        {
            switch($request->request->get('actionForm'))
            {
                case 'save':
                    if($request->request->get('messageIds'))
                    {
                        foreach($request->request->get('messageIds') as $messageId)
                        {
                            $message = $manager->getRepository("DashboardCommonBundle:FormMessage")->find($messageId);
                            
                            if($message)
                            {
                                $message->setIsNew(0);
                                $manager->persist($message);
                            }
                        }
                        
                        $manager->flush();
                        
                        $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Успешно!</strong> Изменения сохранены.</div>')
                        );
                        
                        return $this->redirectToRoute('admin_messages');
                    }
                break;
                
                case 'delete':
                    if($request->request->get('messageIds'))
                    {
                        foreach($request->request->get('messageIds') as $messageId)
                        {
                            $message = $manager->getRepository("DashboardCommonBundle:FormMessage")->find($messageId);
                            
                            if($message)
                            {
                                $manager->remove($message);
                            }
                        }
                        
                        $manager->flush();
                        
                        $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Успешно!</strong> Сообщения удалены.</div>')
                        );
                        
                        return $this->redirectToRoute('admin_messages');
                    }
                break;
            }
        }
        
        if($request->request->get('action'))
        {
            if($request->request->get('message'))
            {
                $message = $manager->getRepository("DashboardCommonBundle:FormMessage")->find($request->request->get('message'));
                
                if($message)
                {
                    //send an email
                    $messageEmail = \Swift_Message::newInstance()
                    ->setSubject('Администратор сайта gribupardot.sunweb.by ответил на Ваше сообщение')
                    ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                    ->setTo($message->getAuthorEmail())
                    ->setBody(
                        $this->renderView(
                            'Emails/messageanswer.html.twig',
                            array("message" => $message,
                                  "answer" => $request->request->get('answer'))
                        ),
                        'text/html'
                    );

                    $this->get('mailer')->send($messageEmail);
                    
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Ответ отправлен пользователю.</div>')
                    );
                    
                    $message->setIsNew(0);
                    $message->setAnswer($request->request->get('answer'));
                    $manager->persist($message);
                    $manager->flush();
                }
            }
            
            return $this->redirectToRoute('admin_messages');
        }
        
        if($messageId)
        {
            $message = $manager->getRepository("DashboardCommonBundle:FormMessage")->find($messageId);
            
            if($message)
            {
                $manager->remove($message);
                $manager->flush();
                
                $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Сообщение удалено.</div>')
                    );
               
            }
            else {
                return $this->redirectToRoute('admin_notfound');
            }
            
            return $this->redirectToRoute('admin_messages');
        }
        
        $query = $manager->createQuery("SELECT m FROM DashboardCommonBundle:FormMessage m ORDER BY m.dateAdded DESC" );

        try{
            $messages = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $messages = 0;
        }
        
        return $this->render('DashboardAdminBundle:Default:messages.html.twig', array("messages" => $messages));
    }
}



