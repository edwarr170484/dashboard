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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

use Dashboard\CommonBundle\Entity\Filter;
use Dashboard\CommonBundle\Entity\FilterType;
use Dashboard\AdminBundle\Form\Type\FiltersType;

class FilterController extends Controller
{
    /**
     * @Route("/admin/filter/types", name="admin_filter_types")
     */
    public function filterTypeListAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $types = $manager->getRepository("DashboardCommonBundle:FilterType")->findAll();
        
        return $this->render('DashboardAdminBundle:Filter:type.html.twig', array("types" => $types));
    }
    
    /**
     * @Route("/admin/filter/type/edit/{typeId}", name="admin_filter_type_edit", defaults={"typeId" : "0"})
     */
    public function filterTypeEditAction($typeId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $type = ($typeId) ? $manager->getRepository("DashboardCommonBundle:FilterType")->find($typeId) : new FilterType();
        
        $typeForm = $this->get('form.factory')->createNamedBuilder('filterType', 'form', $type)
                ->add('title', TextType::class, array('required' => true, 'label' => 'Название типа:', 'attr' => array('class' => 'form-control')))
                ->add('name', TextType::class, array('required' => true, 'label' => 'Имя параметра типа:', 'attr' => array('class' => 'form-control')))
                ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success pull-right')))->getForm();
        
        $typeForm->handleRequest($request);
        
        if($typeForm->isValid())
        {
            $manager->persist($type);
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Информация сохранена.</div>')
            );
            
            return $this->redirectToRoute("admin_filter_type_edit", array("typeId" => $type->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Filter:typeEdit.html.twig', array("typeForm" => $typeForm->createView()));
    }
    
    /**
     * @Route("/admin/filters/{filterId}", name="admin_filters", defaults={"filterId" : "0"})
     */
    public function filtersAction($filterId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $filters = $manager->getRepository("DashboardCommonBundle:Filter")->findAll();
        
        if($filterId)
        {
            $filter = $manager->getRepository("DashboardCommonBundle:Filter")->find($filterId);
            
            if($filter)
            {
                foreach($filter->getValues() as $value)
                {
                    $value->setFilter(null);
                    $manager->remove($value);
                }
                
                if($filter->getTranslations())
                {
                    foreach($filter->getTranslations() as $translation)
                    {
                        $translation->setFilter(null);
                        $manager->remove($translation);
                    }
                }
                
                $manager->remove($filter);
                $manager->flush();

                $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Успешно!</strong> Информация о фильтре уделена.</div>')
                );
            }
            
            return $this->redirectToRoute("admin_filters");
        }
        
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
        
        return $this->render('DashboardAdminBundle:Filter:list.html.twig', array("filters" => $filters));
    }
    
    /**
     * @Route("/admin/filter/edit/{filterId}", name="admin_filters_edit", defaults={"filterId": 0})
     */
    public function editFilterAction($filterId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $values = new ArrayCollection();
        $originalTranslations = new ArrayCollection();
        $originalValueTranslations = new ArrayCollection();
        
        $filter = ($filterId) ? $manager->getRepository("DashboardCommonBundle:Filter")->find($filterId) : new Filter();
        
        //получаем все категории
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL');
        
        try{
            $categories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $categories = 0;
        }
        
        if($filterId && $filter)
        {
            foreach ($filter->getValues() as $value) {
                $values->add($value);
                if($value->getTranslations())
                {
                    foreach($value->getTranslations() as $translation)
                    {
                        $originalValueTranslations->add($translation);
                    }
                }
            }
            
            foreach ($filter->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
        }
        
        $filterForm = $this->createForm(new FiltersType($manager), $filter);
        
        $filterForm->handleRequest($request);
        
        if($filterForm->isValid())
        {
            if($originalTranslations)
            {
                foreach ($originalTranslations as $item) 
                {
                    if (false === $filter->getTranslations()->contains($item)) 
                    {
                        $item->setFilter(null);
                        $manager->remove($item);
                    }
                }
            }

            if($filter->getTranslations())
            {
                foreach($filter->getTranslations() as $item)
                {
                    $item->setFilter($filter);
                    $manager->persist($item);
                }
            } 
            
            if($values)
            {
                foreach ($values as $value) 
                {
                    if (false === $filter->getValues()->contains($value)) 
                    {
                        if($value->getTranslations())
                        {
                            foreach($value->getTranslations() as $translation)
                            {
                                $translation->setFilterValue(null);
                                $manager->remove($translation);
                            }
                        }
                        $value->setFilter(null);
                        $manager->remove($value);
                    }
                    
                    if($originalValueTranslations)
                    {
                        foreach($originalValueTranslations as $translation)
                        {
                            if (false === $value->getTranslations()->contains($translation))
                            {
                                $translation->setFilterValue(null);
                                $manager->remove($translation);
                            }
                        }
                    }
                    
                }
            }               
            
            if(!$filterId)
            {
                $filterCount = $manager->getRepository("DashboardCommonBundle:Filter")->findAll();
                $filter->setSortorder(count($filterCount) + 1);
                $filter->setIsShow(1);
            }
            
            $manager->persist($filter);
            
            if($filter->getValues())
            {
                foreach($filter->getValues() as $key => $value)
                {
                    if($value->getTranslations())
                    {
                        foreach($value->getTranslations() as $translation)
                        {
                            $translation->setFilterValue($value);
                            $manager->persist($translation);
                        }
                    }
                    
                    $value->setFilter($filter);
                    $manager->persist($value);
                }
            }
            
            $manager->flush();
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Информация о фильтре сохранена.</div>')
            );
            
            return $this->redirectToRoute("admin_filters_edit", array("filterId" => $filter->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Filter:edit.html.twig', array("filterForm" => $filterForm->createView(),
                                                                                         "categories" => $categories,"filter" => $filter));
    }
}

