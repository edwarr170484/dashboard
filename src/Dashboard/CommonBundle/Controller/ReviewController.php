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
     * @Route("/{_locale}/account/review/{reviewId}", name="account_reviewLocale", defaults={"_locale" : "lv","reviewId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    public function reviewAction($reviewId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($reviewId)
        {
            $this->deleteReview($reviewId, $user);
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_review");
            }
            else
            {
                return $this->redirectToRoute("account_reviewLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        $plusReviews = 0;
        $minusReviews = 0;
        $neitralReviews = 0;
        $myReviews = array();
        $targetReviews = array();
        
        $myReviews = array_reverse($user->getReviews()->toArray());
        $targetReviews = array_reverse($user->getTargetReviews()->toArray());
        
        if($myReviews)
        {
            foreach($myReviews as $review)
            {
                if($review->getStatus() == 1)
                {
                    $plusReviews++;
                }
                if($review->getStatus() == -1)
                {
                    $minusReviews++;
                }
                if($review->getStatus() == 0)
                {
                    $neitralReviews++;
                }
            }
        }
        
        if($targetReviews)
        {
            foreach($targetReviews as $review)
            {
                if($review->getStatus() == 1)
                {
                    $plusReviews++;
                }
                if($review->getStatus() == -1)
                {
                    $minusReviews++;
                }
                if($review->getStatus() == 0)
                {
                    $neitralReviews++;
                }
            }
        }
        
        $review = new Review();
        $reviewAnswerForm = $this->createForm(new ReviewAnswerType($manager), $review);
        
        $reviewAnswerForm->handleRequest($request);
        
        if ($reviewAnswerForm->isSubmitted() && $reviewAnswerForm->isValid())
        {
            $reviewAnswer = $manager->getRepository("DashboardCommonBundle:Review")->find($reviewAnswerForm['review']->getData());
            
            if($reviewAnswer)
            {
                $isReview = $manager->getRepository("DashboardCommonBundle:Review")->findOneBy(array("user" => $user, "answerTo" => $reviewAnswer));
                    
                    if($isReview)
                    {
                        $this->addFlash(
                            'notice',
                            '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                            $this->get('translator')->trans('<strong>Kļūda!</strong> Jūs jau esat iesniedzis atsauksmi par šo atsauksmi.') . '</div>'
                        );
                        
                        return $this->redirectToRoute("account_review");
                    }
                    
                $review->setUser($user);
                $review->setTargetUser($reviewAnswer->getUser());
                $review->setProduct($reviewAnswer->getProduct());
                $review->setDateAdded(new \DateTime("now"));
                $review->setAnswerTo($reviewAnswer);
                
                $manager->persist($review);
                $manager->flush();
                
                //calculate rating
                $productUserReviews = $manager->getRepository("DashboardCommonBundle:Review")->findBy(array("targetUser" => $reviewAnswer->getUser()));
                $plusReviews = 0;
                    
                if(count($productUserReviews) > 0)
                {
                    foreach($productUserReviews as $productUserReview)
                    {
                        if($productUserReview->getStatus() == 1)
                        {
                            $plusReviews++;
                        }
                    }
                        
                    $userRating = ($plusReviews * 100) / count($productUserReviews);
                }
                else
                    $userRating = 0; 
                
                $answerUser = $reviewAnswer->getUser()->getUserinfo();
                $answerUser->setRating(floor($userRating));
                    
                $manager->persist($answerUser);
                $manager->flush();
                
                $reviewAnswer->setAnswer($review);
                
                $manager->persist($reviewAnswer);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                    $this->get('translator')->trans('<strong>Veiksmīga!</strong> Atbilde tika nosūtīta.') . '</div>'
                );
            }
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_review");
            }
            else
            {
                return $this->redirectToRoute("account_reviewLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        return $this->render('DashboardCommonBundle:Review:reviews.html.twig', array("user" => $user,
                                                                                   "myreviews" => $myReviews,
                                                                                   "targetReviews" => $targetReviews,
                                                                                   "plusReviews" => $plusReviews,
                                                                                   "minusReviews" => $minusReviews,
                                                                                   "neitralReviews" => $neitralReviews,
                                                                                   "reviewAnswerForm" => $reviewAnswerForm->createView(),
                                                                                   "settings" => $settings,
                                                                                   "locale" => $locale));
    }
    
    
    /**
     * @Route("/account/myreview/{reviewId}", name="account_myreview", defaults={"reviewId" : 0})
     * @Route("/{_locale}/account/myreview/{reviewId}", name="account_myreviewLocale", defaults={"_locale" : "lv","reviewId" : 0}, requirements={"_locale" : "lv|ru"})
     */
    
    public function myReviewAction($reviewId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($reviewId)
        {
            $this->deleteReview($reviewId, $user);
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_myreview");
            }
            else
            {
                return $this->redirectToRoute("account_myreviewLocale", array("_locale" => $locale->getCode()));
            }
        }

        $plusReviews = 0;
        $minusReviews = 0;
        $neitralReviews = 0;
        $myReviews = array();
        
        $myReviews = array_reverse($user->getReviews()->toArray());
        
        if($myReviews)
        {
            foreach($myReviews as $review)
            {
                if($review->getStatus() == 1)
                {
                    $plusReviews++;
                }
                if($review->getStatus() == -1)
                {
                    $minusReviews++;
                }
                if($review->getStatus() == 0)
                {
                    $neitralReviews++;
                }
            }
        }
        
        
        
        return $this->render('DashboardCommonBundle:Review:myreview.html.twig', array("user" => $user, "myReviews" => $myReviews,
                                                                                      "plusReviews" => $plusReviews,
                                                                                      "minusReviews" => $minusReviews,
                                                                                      "neitralReviews" => $neitralReviews,
                                                                                      "settings" => $settings,
                                                                                      "locale" => $locale));
    }
    
    
    /**
     * @Route("/account/targetreview", name="account_targetreview")
     * @Route("/{_locale}/account/targetreview", name="account_targetreviewLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    
    public function targetReviewAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        $review = new Review();
        $reviewAnswerForm = $this->createForm(new ReviewAnswerType($manager), $review);
        
        $reviewAnswerForm->handleRequest($request);
        
        if ($reviewAnswerForm->isSubmitted() && $reviewAnswerForm->isValid())
        {
            $reviewAnswer = $manager->getRepository("DashboardCommonBundle:Review")->find($reviewAnswerForm['review']->getData());
            
            if($reviewAnswer)
            {
                $review->setUser($user);
                $review->setTargetUser($reviewAnswer->getUser());
                $review->setProduct($reviewAnswer->getProduct());
                $review->setDateAdded(new \DateTime("now"));
                $review->setAnswerTo($reviewAnswer);
                
                $manager->persist($review);
                $manager->flush();
                
                $answerUser = $reviewAnswer->getUser()->getUserinfo();
                $answerUser->setRating($answerUser->getRating() + $review->getStatus());
                    
                $manager->persist($answerUser);
                $manager->flush();
                
                $reviewAnswer->setAnswer($review);
                
                $manager->persist($reviewAnswer);
                $manager->flush();
                
                $this->addFlash(
                        'notice',
                        '<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                        $this->get('translator')->trans('<strong>Veiksmīga!</strong> Atbilde tika nosūtīta.') . '</div>'
                    );
            }
            
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("account_myreview");
            }
            else
            {
                return $this->redirectToRoute("account_myreviewLocale", array("_locale" => $locale->getCode()));
            }
        }
        
        $plusReviews = 0;
        $minusReviews = 0;
        $neitralReviews = 0;
        $myReviews = array();
        
        $myReviews = array_reverse($user->getTargetReviews()->toArray());
        
        if($myReviews)
        {
            foreach($myReviews as $review)
            {
                if($review->getStatus() == 1)
                {
                    $plusReviews++;
                }
                if($review->getStatus() == -1)
                {
                    $minusReviews++;
                }
                if($review->getStatus() == 0)
                {
                    $neitralReviews++;
                }
            }
        }
        
        return $this->render('DashboardCommonBundle:Review:targetreview.html.twig', array("user" => $user, 
                                                                                          "myReviews" => $myReviews,
                                                                                      "plusReviews" => $plusReviews,
                                                                                      "minusReviews" => $minusReviews,
                                                                                      "neitralReviews" => $neitralReviews,
                                                                                      "reviewAnswerForm" => $reviewAnswerForm->createView(),
                                                                                      "settings" => $settings,
                                                                                      "locale" => $locale));
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

