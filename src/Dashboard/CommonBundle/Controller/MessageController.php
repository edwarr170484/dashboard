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

use Dashboard\CommonBundle\Entity\FormMessage;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class MessageController extends Controller
{   
    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.isUserpage = 0 AND p.locale=" . $locale->getId() . " AND p.route = '" . $request->attributes->get('_route') . "'" );

        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $page = 0;
        }
        
        $message = new FormMessage();
        $messageForm = $this->get('form.factory')->createNamedBuilder('message', 'form', $message)
                ->add('authorName', TextType::class, array('required' => true, 'label' => '', 'attr' => array('class' => 'user','placeholder' => 'John Doe')))
                ->add('authorEmail', EmailType::class, array('required' => true, 'label' => '', 'attr' => array('class' => 'email', 'placeholder' => 'E-mail')))
                ->add('messageSubject', TextType::class, array('required' => false, 'label' => '', 'attr' => array('class' => 'phone masked-phone', 'placeholder' => '+34')))
                ->add('messageText', TextareaType::class, array('required' => true, 'label' => '', 'attr' => array('class' => 'form-control', 'placeholder' => 'Текст сообщения')))
                ->add('save', ButtonType::class, array('label' => 'Отправить сообщение', 'attr' => array('class' => 'send-tab-form')))->getForm();
        
        $messageForm->handleRequest($request);
        
        if ($messageForm->isSubmitted() && $messageForm->isValid()) 
        {
            if($settings->getIsShowCaptcha())
                $checkCaptcha = $this->get('app.helpers')->checkCaptcha($request->request->get('g-recaptcha-response'));
            else {
                $checkCaptcha = true;
            }
            
            if($checkCaptcha)
            {
                $message->setDateAdded(new \DateTime("now"));
                $message->setIsNew(1);

                $manager->persist($message);
                $manager->flush();

                $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                        $this->get('translator')->trans('<strong>Успешно!</strong> Ваше сообщение отправлено администрации сайта и будет рассмотрено в ближайшее время.') . '</div>'
                );

                //send an email
                $message = \Swift_Message::newInstance()
                ->setSubject('Вам пришло новое сообщение на сайте ' . $settings->getSiteName())
                ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                ->setTo($settings->getAdminEmail())
                ->setBody('Пользователь сайта ' . $settings->getSiteName() . ' отправил для Вас сообщение из формы обратной связи.','text/html');

                $this->get('mailer')->send($message);

                return $this->redirectToRoute("contact");
            }
            else
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Неверные данные формы. Возможно вы неверно указали капчу.') . '</div>'
                );
        }
        
        return $this->render('DashboardCommonBundle:Default:contact.html.twig', array("page" => $page, "settings" => $settings,"messageForm" => $messageForm->createView()));
    }
}


