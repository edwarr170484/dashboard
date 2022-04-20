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
     * @Route("/{_locale}/contact", name="contactLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
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
                ->add('authorName', TextType::class, array('required' => true, 'label' => $this->get('translator')->trans('Jūsu vārds: *'), 'attr' => array('class' => 'form-control')))
                ->add('authorEmail', EmailType::class, array('required' => true, 'label' => $this->get('translator')->trans('Jūsu e-pasts: *'), 'attr' => array('class' => 'form-control')))
                ->add('messageSubject', TextType::class, array('required' => true, 'label' => $this->get('translator')->trans('Ziņojuma priekšmets: *'), 'attr' => array('class' => 'form-control')))
                ->add('messageText', TextareaType::class, array('required' => true, 'label' => $this->get('translator')->trans('Sludinājuma teksts: *'), 'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => 'ОТПРАВИТЬ', 'attr' => array('class' => 'send-tab-form')))->getForm();
        
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
                        $this->get('translator')->trans('<strong>Veiksmīgi!</strong> Jūsu ziņa ir nosūtīta vietnes administrācijai un drīz tiks pārskatīta.') . '</div>'
                );

                //send an email
                $message = \Swift_Message::newInstance()
                ->setSubject('Вам пришло новое сообщение на сайте gribupardot.sunweb.by')
                ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                ->setTo($settings->getAdminEmail())
                ->setBody('Пользователь сайта gribupardot.sunweb.by отправил для Вас сообщение из формы обратной связи.','text/html');

                $this->get('mailer')->send($message);

                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("contact");
                }
                else
                {
                    return $this->redirectToRoute("contactLocale", array("_locale" => $locale->getCode()));
                }
            }
            else
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Nederīgi formas dati. Iespējams, ka jūs nepareizi norādījāt captcha.') . '</div>'
                );
        }
        
        return $this->render('DashboardCommonBundle:Default:contact.html.twig', array("page" => $page, "settings" => $settings,"messageForm" => $messageForm->createView()));
    }
}


