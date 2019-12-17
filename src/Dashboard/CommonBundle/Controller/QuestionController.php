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

use Dashboard\CommonBundle\Entity\Question;
use Dashboard\CommonBundle\Form\Type\QuestionType;

class QuestionController extends Controller
{
    /**
     * @Route("/admin/questions", name="admin_questions")
     */
    public function questionListAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $questions = $manager->getRepository("DashboardCommonBundle:Question")->findAll();
        
        if($request->request->get('questionIds'))
        {
            foreach($request->request->get('questionIds') as $questionId)
            {
                $question = $manager->getRepository("DashboardCommonBundle:Question")->find($questionId);
                
                if($question)
                {
                    if($question->getAnswers())
                    {
                        foreach($question->getAnswers() as $answer)
                        {
                            $answer->setQuestion(null);
                            $manager->remove($answer);
                        }
                    }
                    
                    $manager->remove($question);
                    $manager->flush();
                    
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Записи удалены.</div>')
                    );
                }
            }
            
            return $this->redirectToRoute("admin_questions");
        }
        
        return $this->render('DashboardCommonBundle:Question:list.html.twig', array("questions" => $questions));
    }
    
    /**
     * @Route("/admin/question/edit/{questionId}", name="admin_question_edit", defaults={"questionId": 0})
     */
    public function editFilterAction($questionId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalAnswers = new ArrayCollection();
        
        $question = ($questionId) ? $manager->getRepository("DashboardCommonBundle:Question")->find($questionId) : new Question();
        
        if($questionId && $question)
        {
            if($question->getAnswers()){
                foreach ($question->getAnswers() as $item) {
                    $originalAnswers->add($item);
                }
            }
        }
        
        $questionForm = $this->createForm(new QuestionType($manager), $question);
        
        $questionForm->handleRequest($request);
        
        if($questionForm->isValid())
        {
            if($originalAnswers)
            {
                foreach ($originalAnswers as $item) 
                {
                    if (false === $question->getAnswers()->contains($item)) 
                    {
                        $item->setQuestion(null);
                        $manager->remove($item);
                    }
                }
            }

            if($question->getAnswers())
            {
                foreach($question->getAnswers() as $item)
                {
                    $item->setQuestion($question);
                    $manager->persist($item);
                }
            } 
            
            $manager->persist($question);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Информация сохранена.</div>')
            );
            
            return $this->redirectToRoute("admin_question_edit", array("questionId" => $question->getId()));
        }
        
        return $this->render('DashboardCommonBundle:Question:edit.html.twig', array("questionForm" => $questionForm->createView()));
    }
    
    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $questions = $manager->getRepository("DashboardCommonBundle:Question")->findAll();
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Page p WHERE p.locale=" . $locale->getId() . " AND p.isUserpage = 0 AND p.route = 'faq'" );
        
        try{
            $page = $query->getSingleResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $page = 0;
        }
        
        return $this->render('DashboardCommonBundle:Question:faq.html.twig', array("questions" => $questions,
                                                                                   "page" => $page,
                                                                                   "locale" => $locale));
    }
}

