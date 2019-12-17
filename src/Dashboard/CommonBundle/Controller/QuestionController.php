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

class QuestionController extends Controller
{
    /**
     * @Route("/admin/questions", name="admin_questions")
     */
    public function questionListAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $questions = $manager->getRepository("DashboardCommonBundle:Question")->findAll();
        
        if($request->request->get('filterIds'))
        {
            foreach($request->request->get('filterIds') as $filterId)
            {
                $filter = $manager->getRepository("DashboardCommonBundle:Filter")->find($filterId);
                
                if($filter)
                {
                    if($filter->getTranslations())
                    {
                        foreach($filter->getTranslations() as $translation)
                        {
                            $translation->setFilter(null);
                            $manager->remove($translation);
                        }
                    }
                    
                    if($filter->getCategories())
                    {
                        foreach($filter->getCategories() as $category)
                        {
                            $category->removeFilter($filter);
                            $manager->persist($category);
                        }
                    }
                    
                    if($filter->getValues())
                    {
                        foreach($filter->getValues() as $value)
                        {
                            $value->setFilter(null);
                            
                            if($value->getTranslations())
                            {
                                foreach($value->getTranslations() as $translation)
                                {
                                    $translation->setFilter(null);
                                    $manager->remove($translation);
                                }
                            }
                            
                            if($value->getProducts())
                            {
                                foreach($value->getProducts() as $product)
                                {
                                    $product->removeFilter($value);
                                    $manager->persist($product);
                                }
                            }
                            
                            $manager->remove($value);
                        }
                    }
                    
                    $manager->remove($filter);
                    $manager->flush();
                    
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Записи удалены.</div>')
                    );
                }
            }
            
            return $this->redirectToRoute("admin_filters");
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
                    $item->setQuestion($filter);
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
        
        return $this->render('DashboardCommonBundle:Question:edit.html.twig', array());
    }
}

