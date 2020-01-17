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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Dashboard\CommonBundle\Entity\Review;

use Dashboard\CommonBundle\Form\Type\ReviewType;
use Dashboard\CommonBundle\Form\Type\ReviewAnswerType;
use Dashboard\CommonBundle\Form\Type\ProfileMessageType;

use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

class ReviewController extends Controller
{
    /**
     * @Route("/account/review/{reviewId}", name="account_review", defaults={"reviewId" : 0})
     */
    public function reviewAction($reviewId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $statuses = $manager->getRepository("DashboardCommonBundle:ReviewStatus")->findAll();
        
        if($reviewId)
        {
            $this->deleteReview($reviewId, $user);
            return $this->redirectToRoute("account_review");
        }
        
        return $this->render('DashboardCommonBundle:User:account/review/reviews.html.twig', array("user" => $user,
                                                                                   "settings" => $settings,
                                                                                   "locale" => $locale,
                                                                                   "statuses" => $statuses,
                                                                                   "routeName" => $request->attributes->get("_route")));
    } 
    
    /**
     * @Route("/account/review/add/{sellerId}", name="account_add_review")
     */
    public function addReviewAction($sellerId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $seller = $manager->getRepository("DashboardCommonBundle:User")->find($sellerId);
        
        if(!$seller){
            throw $this->createNotFoundException();
        }
        
        if($seller->getRoles()[0]->getRole() != 'ROLE_DEALER'){
            throw $this->createNotFoundException();
        }
        
        $review = new Review();
        $reviewForm = $this->get('form.factory')->createNamedBuilder('review', 'form', $review)
                ->add('reviewReason', TextType::class, array('required' => true,'label' => '', 'attr' => array('class' => 'form-control','placeholder' => $this->get('translator')->trans('Будет использовано в качестве заголовка'))))
                ->add('reviewText', TextareaType::class, array('required' => true,'label' => '', 'attr' => array('class' => 'form-control')))
                ->add('rating', HiddenType::class, array('required' => false,'label' => '', 'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => $this->get('translator')->trans('Отправить отзыв'), 'attr' => array('class' => 'btn')))->getForm();
        
        $reviewForm->handleRequest($request);
        
        if($reviewForm->isSubmitted() && $reviewForm->isValid()){
            if($seller->getId() != $user->getId()){
                $review->setUser($user);
                $review->setTargetUser($seller);
                $review->setDateAdded(new \DateTime("now"));
                $review->setStatus($settings->getNewReviewStatus());
                
                $manager->persist($review);
                $manager->flush();
                                    
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Успешно!</strong> Ваш отзыв отправлен и появится на сайте после проверки модератором.') . '</div>'
                );
                
            }else{
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                    $this->get('translator')->trans('<strong>Ошибка!</strong> Вы не можете оставлять отзывы на самого себя.') . '</div>'
                );
            }
            
            return $this->redirectToRoute("account_review");
        }
        
        return $this->render('DashboardCommonBundle:User:account/review/add.html.twig', array("user" => $user,
                                                                                              "seller" => $seller,
                                                                                              "settings" => $settings,
                                                                                              "reviewForm" => $reviewForm->createView(),
                                                                                              "routeName" => $request->attributes->get("_route")));
    }
    
    
    /**
     * @Route("/account/review/answer/{reviewId}", name="account_answer_review")
     */
    public function reviewAnswerAction($reviewId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $review = $manager->getRepository("DashboardCommonBundle:Review")->find($reviewId);
        
        if($review && $user){
            if($review->getAnswer()){
                $answer = $review->getAnswer();
                $answer->setReviewText($request->request->get('reviewAnswer'));
                $manager->persist($answer);
                $manager->flush();
                
                return new Response(json_encode(array("message" => $this->renderView('DashboardCommonBundle:User:account/review/answer.html.twig', array('review' => $review)))));
                
            }else{
                $answer = new Review();
                $answer->setAnswerTo($review);
                $answer->setReviewText($request->request->get('reviewAnswer'));
                $answer->setDateAdded(new \DateTime("now"));
                $manager->persist($answer);
                
                $review->setAnswer($answer);
                $manager->persist($review);
                
                $manager->flush();
                
                return new Response(json_encode(array("message" => $this->renderView('DashboardCommonBundle:User:account/review/answer.html.twig', array('review' => $review)))));
            }
        }
    }
    
    /**
     * @Route("/account/review/status/{reviewId}/{statusId}", name="account_answer_review_status")
     */
    public function reviewStatusAnswerAction($reviewId, $statusId,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $review = $manager->getRepository("DashboardCommonBundle:Review")->find($reviewId);
        $status = $manager->getRepository("DashboardCommonBundle:ReviewStatus")->find($statusId);
        $statuses = $manager->getRepository("DashboardCommonBundle:ReviewStatus")->findAll();
        
        if($review){
            if($status){
                $review->setStatus($status);
                $manager->persist($review);
                $manager->flush();
                
                return new Response(json_encode(array("message" => $this->renderView('DashboardCommonBundle:User:account/review/status.html.twig', array('review' => $review,"statuses" => $statuses)))));
            }
        }
    }
    
    private function deleteReview($reviewId, $user)
    {
        $manager = $this->getDoctrine()->getManager();
        $review = $manager->getRepository("DashboardCommonBundle:Review")->findOneBy(array("id" => $reviewId, "user" => $user));
        
        if($review)
        {
            $answer = $manager->getRepository("DashboardCommonBundle:Review")->findOneByAnswer($reviewId);
            
            if($answer)
            {
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Šī ir jūsu atsauksme. Jūs to nevarat dzēst.') . '</div>'
                );
                
                return false;
                /*$answer->setAnswer(null);
                $manager->persist($answer);*/
            }
            
            $answerTo = $manager->getRepository("DashboardCommonBundle:Review")->findOneByAnswerTo($reviewId);
                
            if($answerTo)
            {
                $answerTo->setAnswer(null);
                $manager->persist($answerTo);
            }
            
            $manager->remove($review);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                $this->get('translator')->trans('<strong>Gatavs!</strong> Atsauksme ir dzēsta veiksmīgi.') . '</div>'
            );
        }
        else 
        {
            $this->addFlash(
                            'notice',
                            '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                            $this->get('translator')->trans('<strong>Kļūda!</strong> Nevar izdzēst atsauksmi.') . '</div>'
                    );
            
            return false;
        }
    }
    
    
    
}

