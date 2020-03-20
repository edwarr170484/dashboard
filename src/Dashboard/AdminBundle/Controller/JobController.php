<?php

namespace Dashboard\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;

use Dashboard\CommonBundle\Entity\JobCategory;
use Dashboard\AdminBundle\Form\Type\JobCategoryType;

class JobController extends Controller
{
    /**
     * @Route("/admin/jobs/categories", name="admin_jobs_categories")
     */
    public function categoryAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $categories = $manager->getRepository("DashboardCommonBundle:JobCategory")->findAll();
        
        if($request->request->get('category'))
        {
            foreach($request->request->get('category') as $key => $id)
            {
                $category = $manager->getRepository("DashboardCommonBundle:JobCategory")->find($id);
                
                if($category){
                    
                    if($category->getImage())
                    {
                        if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/jobs/' . $category->getImage()))
                        {
                            $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/jobs/' . $category->getImage());
                        }
                    }
                    
                    if($category->getJobs())
                    {
                        foreach($category->getJobs() as $job)
                        {
                            $job->setCategory(null);
                            $manager->remove($job);
                        }
                    }
                    
                    $manager->remove($category);
                    $manager->flush();
                }
            }
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Отмеченные пункты удалены.</div>')
            );
            
            return $this->redirectToRoute("admin_jobs_categories");
        }
        
        return $this->render('DashboardAdminBundle:Job:list.html.twig', array("categories" => $categories));
    }
    
    /**
     * @Route("/admin/jobs/category/edit/{categoryId}", name="admin_jobs_category_edit", defaults={"categoryId" : 0})
     */
    public function categoryEditAction($categoryId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalJobs = new ArrayCollection();
        $fm = new Filesystem();
        
        if($categoryId){
            $category = $manager->getRepository("DashboardCommonBundle:JobCategory")->find($categoryId);
            if(!$category)
                return $this->createNotFoundException ();
            foreach ($category->getJobs() as $item) {
                $originalJobs->add($item);
            }
        }
        else{
            $category = new JobCategory();
        }
        
        $categoryForm = $this->createForm(new JobCategoryType($manager), $category);
        $categoryForm->handleRequest($request);
        
        if($categoryForm->isValid()){
            
            $image = $categoryForm['imageNew']->getData();
            $oldImage = $categoryForm['image']->getData();

            if($image)
            {
                if($oldImage)
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/jobs/' . $oldImage ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/jobs/' . $oldImage );
                    }
                }

                $extention = $image->getClientOriginalExtension();
                $localImageName = rand(1, 99999) . rand(1, 99999) . rand(1, 99999) . '.' . $extention;
                $image->move('bundles/images/jobs',$localImageName);
                $category->setImage($localImageName);
            }
            
            if($originalJobs)
            {
                foreach ($originalJobs as $item) 
                {
                    if (false === $category->getJobs()->contains($item)) 
                    {
                        $item->setCategory(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($category->getJobs())
            {
                foreach($category->getJobs() as $item)
                {
                    $item->setCategory($category);
                    $manager->persist($item);
                }
            }
            
            $categories = $manager->getRepository("DashboardCommonBundle:JobCategory")->findAll();
            
            if(!$categoryForm['sortorder']->getData()){
                $category->setSortorder(count($categories) + 1);
            }
            
            $manager->persist($category);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Информация о категории сохранена.</div>')
            );
            
            return $this->redirectToRoute("admin_jobs_category_edit", array("categoryId" => $category->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Job:edit.html.twig', array("categoryForm" => $categoryForm->createView()));
    }
}

