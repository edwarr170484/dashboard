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
use Dashboard\CommonBundle\Entity\Message;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                    ->setSubject('Администратор сайта ' . $settings->getSiteName() . ' ответил на Ваше сообщение')
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
        
        return $this->render('DashboardAdminBundle:Default:message/messages.html.twig', array("messages" => $messages));
    }
    
    /**
     * @Route("/admin/conversations", name="admin_conversations")
     */
    public function conversationsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($request->request->get('actionForm'))
        {
            switch($request->request->get('actionForm'))
            {
                case 'delete':
                    if($request->request->get('conversationIds'))
                    {
                        foreach($request->request->get('conversationIds') as $conversationId)
                        {
                            $conversation = $manager->getRepository("DashboardCommonBundle:Conversation")->find($conversationId);
                            
                            if($conversation)
                            {
                                foreach($conversation->getMessages() as $message)
                                {
                                    if($message->getImage())
                                    {
                                        if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/messages/' . $message->getImage()))
                                        {
                                            $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/messages/' . $message->getImage());
                                        }
                                    }

                                    $message->setUserOwner(null);
                                    $message->setProduct(null);
                                    $message->setUserTo(null);
                                    $message->setUserFrom(null);

                                    $manager->remove($message);
                                    $manager->flush();
                                }

                                $conversation->setUserOne(null);
                                $conversation->setUserTwo(null);
                                $manager->remove($conversation);
                                $manager->flush();
                            }
                        }
                        
                        $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Успешно!</strong> Отмеченные пункты удалены.</div>')
                        );

                        return $this->redirectToRoute('admin_conversations');
                    }
                break;
            }
        }
        //get all conversations
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:Conversation c WHERE c.userOne = " . $user->getId() . " OR c.userTwo = " . $user->getId() . " ORDER BY c.id DESC");

        try{
            $conversations = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $conversations = 0;
        }

        return $this->render('DashboardAdminBundle:Default:message/conversations.html.twig', array("user" =>$user,
                                                                                    "conversations" => $conversations,
                                                                                    "pagination" => 0,
                                                                                    "locale" => $locale,
                                                                                    "settings" => $settings,
                                                                                    "routeName" => $request->attributes->get("_route")));
    }
    
    /**
     * @Route("/admin/conversation/{conversationId}", name="admin_conversation")
     */
    public function conversationAction($conversationId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $conversation = $manager->getRepository("DashboardCommonBundle:Conversation")->find($conversationId);
        
        foreach($conversation->getMessages() as $message)
        {
            if($user->getId() == $message->getUserTo()->getId())
            {
                $message->setIsNew(0);
                $message->setReadedDate(new \DateTime("now"));
                $manager->persist($message);
                $manager->flush();
            }
        }

        $query = $manager->createQuery("SELECT m FROM DashboardCommonBundle:Message m WHERE m.conversation = " . $conversationId . " AND m.userOwner = " . $user->getId() . " ORDER BY m.sentDate DESC")->setFirstResult(0)->setMaxResults($settings->getUserMessagesNumber());

        try{
            $messages = $query->getResult();
            $messages = array_reverse($messages);
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $messages = 0;
        }
        
        $extentions = array("jpg", "jpeg", "png", "gif");

        $newMessage = new Message();
        $newMessageTwo = new Message();

        $formMessage = $this->get('form.factory')->createNamedBuilder('message', 'form', $newMessage)
                ->add('message', TextareaType::class, array('required' => true, 'label' => '', 'attr' => array('class' => 'form-control','placeholder' => 'Написать сообщение')))
                ->add('image', FileType::class, array('required' => false, 'mapped' => false, 'label' => 'Прикрепить изображение'))
                ->add('userFrom',HiddenType::class,array('mapped' => false, 'data' => $user->getId()))
                ->add('userTo',HiddenType::class,array('mapped' => false, 'data' => ($conversation->getUserOne()->getId() == $user->getId()) ? $conversation->getUserTwo()->getId() : $conversation->getUserOne()->getId()))
                ->add('conversation',HiddenType::class,array('mapped' => false, 'data' => $conversation->getId()))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Отправить'), 'attr' => array('class' => 'btn btn-success')))->getForm();
        
        $formMessage->handleRequest($request);

        if($formMessage->isValid()&& $formMessage->isSubmitted())
        {
            $image = $formMessage['image']->getData();

            if($image)
            {
                $extention = $image->getClientOriginalExtension();

                if(in_array($extention, $extentions) && ($image->getClientSize() < 2097152))
                {
                    $localImageName = rand(1, 99999).'.'.$extention;
                    $image->move('bundles/images/messages',$localImageName);
                    $newMessage->setImage($localImageName);
                }
                else
                {
                    $this->addFlash(
                            'notice',
                            '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                            $this->get('translator')->trans('<strong>Ошибка!</strong> Изображение не соответствует требованиям. Допустимые расширения: jpg, jpeg, png, gif. Максимальный размер - 2Мб. Сообщение отправлено без изображения.') . '</div>'
                    );
                }
            }

            $newMessage->setUserFrom($user);
            $newMessage->setUserOwner($user);

            $userTo = $manager->getRepository("DashboardCommonBundle:User")->find($formMessage['userTo']->getData());
            
            if($userTo)
            {
                $newMessage->setUserTo($userTo);
            }

            $newMessage->setProduct(null);

            $newMessage->setIsNew(1);
            $newMessage->setIsDeleted(0);
            $newMessage->setSentDate(new \DateTime("now"));
            $newMessage->setReadedDate(new \DateTime("now"));
            $newMessage->setConversation($conversation);

            $manager->persist($newMessage);
            $manager->flush();

            $newMessageTwo = clone $newMessage;

            $newMessageTwo->setUserOwner($userTo);

            $manager->persist($newMessageTwo);
            $manager->flush();

            //send an email
            if($userTo)
            {
                if($userTo->getIsAlertNewMessage())
                {
                    $message = \Swift_Message::newInstance()
                    ->setSubject('Вам пришло новое сообщение на сайте ' . $settings->getSiteName())
                    ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                    ->setTo($userTo->getEmail())
                    ->setBody('Вы получили новое сообщение на сайте ' . $settings->getSiteName() . '. '
                            . 'Вы можете прочитать его в <a href="' . $this->generateUrl('account_conversations', array(), true) . '">личном кабинете</a>.','text/html');

                    $this->get('mailer')->send($message);
                }
            }

            return $this->redirectToRoute("admin_conversation", array("conversationId" => $conversationId));
        }
        
        return $this->render('DashboardAdminBundle:Default:message/conversation.html.twig', array("user" =>$user,
                                                                                    "conversation" => $conversation,
                                                                                    "locale" => $locale,
                                                                                    "settings" => $settings,
                                                                                    "messages" => $messages,
                                                                                    "formMessage" => $formMessage->createView()));
    }
}



