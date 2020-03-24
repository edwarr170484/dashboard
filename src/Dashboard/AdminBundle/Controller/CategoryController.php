<?php

namespace Dashboard\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Collections\ArrayCollection;
use Dashboard\CommonBundle\Entity\Category;
use Dashboard\CommonBundle\Entity\Generation;
use Dashboard\CommonBundle\Entity\GenerationBoard;
use Dashboard\CommonBundle\Entity\GenerationItem;
use Dashboard\CommonBundle\Entity\Modification;
use Dashboard\CommonBundle\Entity\FilterValue;
use Dashboard\AdminBundle\Form\Type\CategoryType;
use Dashboard\AdminBundle\Form\DataTransformer\CategoryToNumberTransformer;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryController extends Controller
{
    /**
     * @Route("/admin/categories/{categoryId}", name="admin_product_category", defaults={"categoryId": 0})
     */
    public function categoryAction($categoryId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        
        if($request->request->get('category'))
        {
            foreach($request->request->get('category') as $id)
            {
                $category = $manager->getRepository("DashboardCommonBundle:Category")->find($id);

                if(count($category->getChildren()) > 0 )
                {
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Невозможно удалить категорию!</strong> Категория <strong>' . $category->getTitle() . '</strong>  явялется родительской для ' . count($category->getChildren()) . ' категорий.</div>')
                    );
                }
                elseif(count($category->getProduct()) > 0)
                {
                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Невозможно удалить категорию!</strong> В категории <strong>' . $category->getTitle() . '</strong>  ' . count($category->getProduct()) . ' товаров.</div>')
                    );
                }
                else
                {
                    if($category->getProduct())
                    {
                        foreach($category->getProduct() as $product)
                        {
                            $product->setCategory(null);
                            $manager->persist($product);
                        }
                    }

                    if($category->getTranslations())
                    {
                        foreach($category->getTranslations() as $translation)
                        {
                            $translation->setCategory(null);
                            $manager->remove($translation);
                        }
                    }

                    if($category->getDescriptions())
                    {
                        foreach($category->getDescriptions() as $translation)
                        {
                            $translation->setCategory(null);
                            $manager->remove($translation);
                        }
                    }
                    
                    if($category->getRates())
                    {
                        foreach($category->getRates() as $rate)
                        {
                            $rate->setCategory(null);
                            $manager->remove($rate);
                        }
                    }
                    
                    $manager->remove($category);
                    $manager->flush();

                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Категория удалена.</div>')
                    );
                }
            }
            
            return $this->redirectToRoute('admin_product_category');
        }
        
        if($request->request->get('sortorder'))
        {
            $actives = $request->request->get('isactive');
            
            foreach($request->request->get('sortorder') as $key => $value)
            {
                $category = $manager->getRepository("DashboardCommonBundle:Category")->find($key);
                
                if($category)
                {
                    $category->setSortorder($value);
                    if(isset($actives[$key]))
                    {
                        $category->setIsActive($actives[$key]);
                    }
                    else
                        $category->setIsActive(0);
                    
                    $manager->persist($category);
                }
                
            }
            $manager->flush();
            
            $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Успешно!</strong> Информация сохранена.</div>')
            );
            
            return $this->redirectToRoute('admin_product_category');
        }
        
        if($categoryId)
        {
            $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
            
            if(count($category->getChildren()) > 0 )
            {
                $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Невозможно удалить категорию!</strong> Категория <strong>' . $category->getTitle() . '</strong>  явялется родительской для ' . count($category->getChildren()) . ' категорий.</div>')
                );
            }
            /*elseif(count($category->getProduct()) > 0)
            {
                $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Невозможно удалить категорию!</strong> В категории <strong>' . $category->getTitle() . '</strong>  ' . count($category->getProduct()) . ' товаров.</div>')
                );
            }*/
            else
            {
                if($category->getProduct())
                {
                    foreach($category->getProduct() as $product)
                    {
                        $product->setCategory(null);
                        $manager->persist($product);
                    }
                }
                
                if($category->getTranslations())
                {
                    foreach($category->getTranslations() as $translation)
                    {
                        $translation->setCategory(null);
                        $manager->remove($translation);
                    }
                }
                
                if($category->getDescriptions())
                {
                    foreach($category->getDescriptions() as $translation)
                    {
                        $translation->setCategory(null);
                        $manager->remove($translation);
                    }
                }
                
                $manager->remove($category);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Успешно!</strong> Категория удалена.</div>')
                );
            }
            
            return $this->redirectToRoute('admin_product_category');
        }
        
        $query = $manager->createQuery('SELECT c,cc FROM Dashboard\CommonBundle\Entity\Category c LEFT JOIN c.children cc WHERE c.parent IS NULL ORDER BY c.sortorder, cc.sortorder' );
        
        try{
            $categories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $categories = 0;
        }
        
        $category = new Category();
        $builder = $this->get('form.factory')->createNamedBuilder('category', 'form', $category);
        $categoryForm = $builder
            ->add('title', TextType::class, array('required' => true, 'label' => 'Название:', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('parent', 'hidden')->addModelTransformer(new CategoryToNumberTransformer($manager)))
            ->getForm();
        
        
        return $this->render('DashboardAdminBundle:Category:categories.html.twig', array("categories" => $categories,"categoryForm" => $categoryForm->createView()));
    }
    
    /**
     * @Route("/admin/getsub/category/{categoryId}/{spaces}", name="admin_product_category_getsub")
     */
    public function getCategorySubcats($categoryId, $spaces, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $category = $manager->getRepository("DashboardCommonBundle:Category")->findOneBy(array("id" => $categoryId), array("sortorder" => "ASC"));
        
        if(!$category)
        {
            return $this->createNotFoundException();
        }
        
        return $this->render('DashboardAdminBundle:Category:categoryitem.html.twig', array("category" => $category,"spaces" => $spaces));     
    }
    
    /**
     * @Route("/admin/category/add", name="admin_product_category_add")
     */
    public function addCategoryAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $category = new Category();
        $builder = $this->get('form.factory')->createNamedBuilder('category', 'form', $category);
        $categoryForm = $builder
            ->add('title', TextType::class, array('required' => true, 'label' => 'Название:', 'attr' => array('class' => 'form-control')))
            ->add($builder->create('parent', 'hidden')->addModelTransformer(new CategoryToNumberTransformer($manager)))
            ->getForm();

        $categoryForm->handleRequest($request);      
       
        if($categoryForm->isSubmitted() && $categoryForm->isValid())
        {
            $helpers = $this->get('app.helpers');
            $category->setName($helpers->translit($category->getTitle()));
            
            $category->setIsActive(true);
            $category->setIsShowPriceFilter(true);
            
            if($categoryForm['parent']->getData()){
                $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.id = ' . $categoryForm['parent']->getData()->getId());
                
                try{
                    $categoryParent = $query->getSingleResult();
                }
                catch(\Doctrine\ORM\NoResultException $e) {
                    $categoryParent = 0;
                }
                
                if($categoryParent){
                    $sortorder = count($categoryParent->getChildren()) + 1;
                }else{
                    $sortorder = 1;
                }
                
            }else{
                $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL' );
        
                try{
                    $categories = $query->getResult();
                }
                catch(\Doctrine\ORM\NoResultException $e) {
                    $categories = 0;
                }
                
                $sortorder = count($categories) + 1;
            }
            
            $category->setSortorder($sortorder);
            
            $manager->persist($category);
            $manager->flush();
        }
        
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL' );
        
        try{
            $categories = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $categories = 0;
        }
        
        return new JsonResponse(array("message" => "Категория добавлена", "view" => $this->renderView("DashboardAdminBundle:Category:tableList.html.twig", array("categories" => $categories))));
        
    }
    
    /**
     * @Route("/admin/categoryedit/{categoryId}", name="admin_product_category_edit")
     */
    public function editCategoryAction($categoryId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $originalTranslations = new ArrayCollection();
        $originalDescriptions = new ArrayCollection();
        $originalGenerations = new ArrayCollection();
        $originalGenerationTranslations = new ArrayCollection();
        $originalGenerationModifications = new ArrayCollection();
        $originalGenerationBoards = new ArrayCollection();
        $originalGenerationItems = new ArrayCollection();
        $originalRates = new ArrayCollection();
        
        $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
        
        if($category)
        {
            foreach ($category->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
            foreach ($category->getDescriptions() as $item) {
                $originalDescriptions->add($item);
            }
            foreach($category->getGenerations() as $item){
                $originalGenerations->add($item);
                
                if($item->getTranslations()){
                    foreach($item->getTranslations() as $translation){
                        $originalGenerationTranslations->add($translation);
                    }
                }
                if($item->getModifications()){
                    foreach($item->getModifications() as $modification){
                        $originalGenerationModifications->add($modification);
                    }
                }
                if($item->getBoards()){
                    foreach($item->getBoards() as $board){
                        $originalGenerationBoards->add($board);
                    }
                }
                if($item->getItems()){
                    foreach($item->getItems() as $generationItem){
                        $originalGenerationItems->add($generationItem);
                    }
                }
            }
            foreach ($category->getRates() as $item) {
                $originalRates->add($item);
            }
        }
        
        $categoryForm = $this->createForm(new CategoryType($manager, $category), $category);

        $categoryForm->handleRequest($request);      
       
        if($categoryForm->isSubmitted() && $categoryForm->isValid())
        {
            if($originalGenerations){
                foreach($originalGenerations as $generation){
                    if (false === $category->getGenerations()->contains($generation)){                        
                        if($generation->getTranslations()){
                            foreach($generation->getTranslations() as $translation){
                                $translation->setGeneration(null);
                                $manager->remove($translation);
                            }
                        }
                        
                        if($generation->getModifications()){
                            foreach($generation->getModifications() as $modification){
                                $modification->setGeneration(null);
                                $manager->remove($modification);
                            }
                        }
                        
                        if($generation->getBoards()){
                            foreach($generation->getBoards() as $board){
                                if($board->getImage()){
                                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/generation/' . $board->getImage())){
                                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/generation/' . $board->getImage());
                                    }
                                }
                                $board->setGeneration(null);
                                $manager->remove($board);
                            }
                        }
                        
                        if($generation->getItems()){
                            foreach($generation->getItems() as $item){
                                $item->setGeneration(null);
                                $manager->remove($item);
                            }
                        }
                        
                        $generation->setCategory(null);
                        $manager->remove($generation);
                    }
                    
                    if($originalGenerationTranslations){
                        foreach($originalGenerationTranslations as $translation){
                            if(false === $generation->getTranslations()->contains($translation)){
                                $translation->setGeneration(null);
                                $manager->remove($translation);
                            }
                        }
                    }
                    
                    if($originalGenerationModifications){
                        foreach($originalGenerationModifications as $modification){
                            if(false === $generation->getModifications()->contains($modification)){
                                $modification->setGeneration(null);
                                $manager->remove($modification);
                            }
                        }
                    }
                    
                    if($originalGenerationBoards){
                        foreach($originalGenerationBoards as $board){
                            if(false === $generation->getBoards()->contains($board)){
                                if($board->getImage()){
                                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/generation/' . $board->getImage())){
                                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/generation/' . $board->getImage());
                                    }
                                }
                                $board->setGeneration(null);
                                $manager->remove($board);
                            }
                        }
                    }
                    
                    if($originalGenerationItems){
                        foreach($originalGenerationItems as $item){
                            if(false === $generation->getItems()->contains($item)){
                                $item->setGeneration(null);
                                $manager->remove($item);
                            }
                        }
                    } 
                }
            }
            if($originalTranslations)
            {
                foreach ($originalTranslations as $item) 
                {
                    if (false === $category->getTranslations()->contains($item)) 
                    {
                        $item->setCategory(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($originalDescriptions)
            {
                foreach ($originalDescriptions as $item) 
                {
                    if (false === $category->getDescriptions()->contains($item)) 
                    {
                        $item->setCategory(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($originalRates)
            {
                foreach ($originalRates as $item) 
                {
                    if (false === $category->getRates()->contains($item)) 
                    {
                        $item->setCategory(null);
                        $manager->remove($item);
                    }
                }
            }
            
            if($category->getGenerations()){
                foreach($category->getGenerations() as $key => $generation){
                    if($generation->getTranslations()){
                        foreach($generation->getTranslations() as $translation){
                            $translation->setGeneration($generation);
                            $manager->persist($translation);
                        }
                    }
                    if($generation->getModifications()){
                        foreach($generation->getModifications() as $modification){
                            $modification->setGeneration($generation);
                            $manager->persist($modification);
                        }
                    }
                    if($generation->getBoards()){
                        foreach($generation->getBoards() as $boardKey => $board){
                            
                            $image = $categoryForm['generations'][$key]['boards'][$boardKey]['imageNew']->getData();
                            $oldImage = $categoryForm['generations'][$key]['boards'][$boardKey]['image']->getData();

                            if($image)
                            {
                                if($oldImage)
                                {
                                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/generation/' . $oldImage ))
                                    {
                                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/generation/' . $oldImage );
                                    }
                                }

                                $extention = $image->getClientOriginalExtension();
                                $localImageName = rand(1, 99999) . rand(1, 99999) . rand(1, 99999) . '.' . $extention;
                                $image->move('bundles/images/category/generation',$localImageName);
                                $board->setImage($localImageName);
                            }
                            
                            $board->setGeneration($generation);
                            $manager->persist($board);
                        }
                    }
                    
                    if($generation->getItems()){
                        foreach($generation->getItems() as $item){
                            $item->setGeneration($generation);
                            $manager->persist($item);
                        }
                    }
                    
                    $generation->setCategory($category);
                    $manager->persist($generation);
                }
            }
            
            if($category->getTranslations())
            {
                foreach($category->getTranslations() as $item)
                {
                    $item->setCategory($category);
                    $manager->persist($item);
                }
            }
            
            if($category->getDescriptions())
            {
                foreach($category->getDescriptions() as $item)
                {
                    $item->setCategory($category);
                    $manager->persist($item);
                }
            }
            
            if($category->getRates())
            {
                foreach($category->getRates() as $item)
                {
                    $item->setCategory($category);
                    $manager->persist($item);
                }
            }
            
            if($categoryForm['name']->getData())
            {
                $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.id <> ' . $categoryId . ' AND c.name = \'' . $categoryForm['name']->getData() . '\'' );
                    
                try{
                    $categories = $query->getResult();
                }
                catch(\Doctrine\ORM\NoResultException $e) {
                    $categories = array();
                }
                    
                if(count($categories) > 0)
                {
                    $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-warning alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Предупреждение!</strong> Категория с таким URL (' . $categoryForm['name']->getData() . ') уже существует. '
                                    . 'URL должен быть уникальным для каждой категории. Он был переименован в ' . $categoryForm['name']->getData() . '-' .count($categories) . '.</div>')
                        );
                        
                    $category->setName($categoryForm['name']->getData() . '-' .count($categories)); 
                }
                else
                    $category->setName($categoryForm['name']->getData());
            }
            else
            {
                $helpers = $this->get('app.helpers');
                $category->setName($helpers->translit($category->getTitle()));
            }

            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Выполнено!</strong> Данные успешно сохранены.</div>')
            );

            return $this->redirectToRoute('admin_product_category_edit', array('categoryId' => $category->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Category:edit.html.twig', array("categoryForm" => $categoryForm->createView(),
                                                                                   "category" => $category));
    }
    
    /**
     * @Route("/admin/categoryimport", name="admin_category_import")
     */
    public function categoryImportAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $finder = new Finder();
        
        /*$baseCategory = $manager->getRepository("DashboardCommonBundle:Category")->find(27);
        
        if($baseCategory){
            foreach($baseCategory->getChildren() as $child){
                if(count($child->getChildren()) > 0){
                    foreach($child->getChildren() as $subChild){
                        $subChild->setParent(null);
                        $manager->remove($subChild);
                    }
                }
                $child->setParent(null);
                $manager->remove($child);
            }
            
            $manager->flush();
        }*/
        
        /*$yearFiter = $manager->getRepository("DashboardCommonBundle:Filter")->find(32);
        
        $categoryYearsFrom = $manager->createQuery('SELECT c.yearFrom FROM Dashboard\CommonBundle\Entity\Category c WHERE c.yearFrom > 0 ORDER BY c.yearFrom ASC');
        $yearsFrom = $categoryYearsFrom->getResult();
        
        $yearStart = intval($yearsFrom[0]['yearFrom']);
        $yearEnd = intval($yearsFrom[count($yearsFrom) - 1]['yearFrom']);
        
        $values = new ArrayCollection();
        
        for($i = $yearStart; $i <= $yearEnd + 1; $i++){
            $value = new FilterValue();
            $value->setFilter($yearFiter);
            $value->setValue($i);
            $values->add($value);
        }
        
        foreach($values as $val){
            $manager->persist($val);
        }
        $manager->flush($val);*/
        
        $transmissionFilter = $manager->getRepository("DashboardCommonBundle:Filter")->find(17);
        $transmissionFilterValue1 = $manager->getRepository("DashboardCommonBundle:FilterValue")->findOneBy(array("filter" => $transmissionFilter, "value" => "Trasero"));
        $transmissionFilterValue2 = $manager->getRepository("DashboardCommonBundle:FilterValue")->findOneBy(array("filter" => $transmissionFilter, "value" => "Lleno"));
        $generationItems = $manager->getRepository("DashboardCommonBundle:GenerationItem")->findAll();
        
        $newItems = new ArrayCollection();
        
        if($generationItems){
            foreach($generationItems as $item){
                $newItem1 = clone $item;
                $newItem2 = clone $item;
                $newItem1->setTransmissionType($transmissionFilterValue1);
                $newItem2->setTransmissionType($transmissionFilterValue2);
                $newItems->add($newItem1);
                $newItems->add($newItem2);
            }
        }
        
        foreach($newItems as $newItem){
            $manager->persist($newItem);
        }
        
        $manager->flush();
        
        //$finder->files()->name('equipment.txt')->in('import');
        
        /*$comfortFilter = $manager->getRepository("DashboardCommonBundle:Filter")->find(33);
        $extrasFilter = $manager->getRepository("DashboardCommonBundle:Filter")->find(34);
        $infotainmentFilter = $manager->getRepository("DashboardCommonBundle:Filter")->find(35);
        $securityFilter = $manager->getRepository("DashboardCommonBundle:Filter")->find(36);
        $filterValues = new ArrayCollection();
        
        foreach ($finder as $file) {
            $lines = file($file->getRealPath());
            foreach($lines as $line){
                $filterInfo = explode(";", $line);
                
                $filterValue = new FilterValue();
                $filterValue->setValue(trim($filterInfo[0]));
                
                switch(trim($filterInfo[1])){
                    case 'comfort':
                        $filterValue->setFilter($comfortFilter);
                    break;
                    
                    case 'extras':
                        $filterValue->setFilter($extrasFilter);
                    break;
                    
                    case 'infotainment':
                        $filterValue->setFilter($infotainmentFilter);
                    break;
                    
                    case 'security':
                        $filterValue->setFilter($securityFilter);
                    break;
                }
                
                $filterValues->add($filterValue);
            }
            
            foreach($filterValues as $value){
                $manager->persist($value);
            }
            
            $manager->flush();
        }*/
        
        /*$baseCategory = $manager->getRepository("DashboardCommonBundle:Category")->find(27);
        $finder->files()->name('export-short.txt')->in('import');
        $modifications = new ArrayCollection();
        
        $workFile = 0;
        foreach($finder as $file){
            $workFile = $file;
        }
        
        $lines = file($workFile->getRealPath());
        foreach($lines as $line){
            $modificationInfo = explode(";", $line);
            
            $parentCategory = $manager->getRepository("DashboardCommonBundle:Category")->findOneBy(array("parent" => $baseCategory, "title" => $modificationInfo[0]));
            $category = $manager->getRepository("DashboardCommonBundle:Category")->findOneBy(array("parent" => $parentCategory, "title" => $modificationInfo[1], "name" => $this->get('app.helpers')->translit($modificationInfo[1])));
                        
            if($category){
                $generation = $category->getGenerations()->first();
            
                $modification = $manager->getRepository("DashboardCommonBundle:Modification")->findBy(array("generation" => $generation, "label" => trim($modificationInfo[2]), "power" => trim($modificationInfo[5]), "size" => trim($modificationInfo[6])));

                if($modification){
                    foreach($modification as $item){
                        $item->setYearFrom(substr($modificationInfo[3],3,4));
                        $item->setYearTo(substr($modificationInfo[4],3,4));
                        $modifications->add($item);
                    }
                }
            }else{
                dump($parentCategory);
                dump($modificationInfo);
            }
        }
          
        foreach($modifications as $modification){
            $manager->persist($modification);
        }
        
        $manager->flush();*/
        
        /*$baseCategory = $manager->getRepository("DashboardCommonBundle:Category")->find(27);
        
        $finder->files()->name('export-short5.txt')->in('import');
        $categories = new ArrayCollection();
        $childrens = new ArrayCollection();
        $generationItems = new ArrayCollection();*/
        
        /*if($baseCategory){
            foreach ($finder as $file) {
                $lines = file($file->getRealPath());
                
                if(count($lines) > 0){
                    foreach($lines as $line){
                        $categoryInfo = explode(";", $line);
                        
                        $marker = 0;
                        foreach($categories as $cat){
                            if($cat->getTitle() == trim($line)){
                                $marker = 1;
                                break;
                            }
                        }
                        
                        if(!$marker){
                            $category = new Category();
                            $category->setParent($baseCategory);
                            $category->setTitle(trim($line));
                            $category->setName($this->get('app.helpers')->translit(trim($line)));
                            $category->setIsActive(1);
                            $category->setIsShowFilters(1);
                            $category->setIsShowPriceFilter(1);
                            $categories->add($category);
                        }
                    }
                    
                    foreach($categories as $key => $category){
                        $category->setSortorder($key + 1);
                        $manager->persist($category);
                    }
                    
                    $manager->flush();

                    $categories = $manager->getRepository("DashboardCommonBundle:Category")->findBy(array("parent" => $baseCategory));
                    $boardFilter = $manager->getRepository("DashboardCommonBundle:Filter")->find(19);
                    $gasFilter = $manager->getRepository("DashboardCommonBundle:Filter")->find(16);
                    $transmissionFilter = $manager->getRepository("DashboardCommonBundle:Filter")->find(17);
                    $gearFilter = $manager->getRepository("DashboardCommonBundle:Filter")->find(18);
                    
                    if($categories){
                        foreach($lines as $line){
                            $categoryInfo = explode(";", $line);

                            $parent = 0;
                            foreach($categories as $category){
                                if($category->getTitle() == trim($categoryInfo[0])){
                                    $parent = $category;
                                    break;
                                }
                            }

                            if($parent){                              
                                $marker = 0;
                                foreach($childrens as $cat){
                                    if($cat->getTitle() == trim($categoryInfo[1]) && $cat->getParent()->getId() == $parent->getId()){
                                        $marker = 1;
                                        
                                        $boardFilterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->findOneBy(array("filter" => $boardFilter, "value" => trim($categoryInfo[7])));
                                    
                                        if(count($cat->getGenerations()) > 0){
                                            foreach($cat->getGenerations() as $catGeneration){
                                                if(count($catGeneration->getBoards()) > 0){
                                                    $contains = 0;
                                                    foreach($catGeneration->getBoards() as $board){
                                                        if($board->getBoard()->getId() == $boardFilterValue->getId()){
                                                            $contains = 1;
                                                            break;
                                                        }
                                                    }
                                                    
                                                    if(!$contains){
                                                        $generationBoard = new GenerationBoard();
                                                        $generationBoard->setGeneration($catGeneration);
                                                        $generationBoard->setBoard($boardFilterValue);
                                                        $catGeneration->addBoard($generationBoard);
                                                    }
                                                }
                                                
                                                if(count($catGeneration->getModifications()) > 0){
                                                    $contain = 0;
                                                    foreach($catGeneration->getModifications() as $modification){
                                                        if(($modification->getLabel() == trim($categoryInfo[4])) && ($modification->getPower() == trim($categoryInfo[10])) && ($modification->getSize() == trim($categoryInfo[11]))){
                                                            $contain = 1;
                                                            break;
                                                        }
                                                    }
                                                    
                                                    if(!$contain){
                                                        $modification = new Modification();
                                                        $modification->setGeneration($catGeneration);
                                                        $modification->setLabel(trim($categoryInfo[4]));
                                                        $modification->setPower(trim($categoryInfo[10]));
                                                        $modification->setSize(trim($categoryInfo[11]));
                                                        $modification->setYearFrom(substr(trim($categoryInfo[5]),3,4));
                                                        $modification->setYearTo(substr(trim($categoryInfo[6]),3,4));
                                                        $catGeneration->addModification($modification);
                                                    }
                                                }
                                            }
                                        }
                                        
                                        break;
                                    }
                                }

                                if(!$marker){
                                    $child = new Category();
                                    $child->setParent($parent);
                                    $child->setTitle(trim($categoryInfo[1]));
                                    $child->setName($this->get('app.helpers')->translit(trim($categoryInfo[1])));
                                    $child->setIsActive(1);
                                    $child->setIsShowFilters(1);
                                    $child->setIsShowPriceFilter(1);
                                    $child->setYearFrom(trim($categoryInfo[2]));
                                    $child->setYearTo(trim($categoryInfo[3]));
                                    
                                    //adding generation
                                    $generation = new Generation();
                                    $generation->setCategory($child);
                                    $generation->setName("I generación");
                                    $generation->setYearFrom(trim($categoryInfo[2]));
                                    $generation->setYearTo(trim($categoryInfo[3]));
                                    $generation->setIsRightWheel(1);
                                    $generation->setIsGas(1);
                                    
                                    //adding boardType
                                    $boardFilterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->findOneBy(array("filter" => $boardFilter, "value" => trim($categoryInfo[7])));
                                    $generationBoard = new GenerationBoard();
                                    $generationBoard->setGeneration($generation);
                                    $generationBoard->setBoard($boardFilterValue);
                                    $generation->addBoard($generationBoard);
                                    
                                    //add first modification
                                    $modification = new Modification();
                                    $modification->setGeneration($generation);
                                    $modification->setLabel(trim($categoryInfo[4]));
                                    $modification->setPower(trim($categoryInfo[10]));
                                    $modification->setSize(trim($categoryInfo[11]));
                                    $modification->setYearFrom(substr(trim($categoryInfo[5]),3,4));
                                    $modification->setYearTo(substr(trim($categoryInfo[6]),3,4));
                                    $generation->addModification($modification);
                                    
                                    $child->addGeneration($generation);
                                    $childrens->add($child);
                                }
                            }
                        }

                        foreach($childrens as $key => $child){
                            $child->setSortorder(1);
                            $manager->persist($child);
                        }

                        $manager->flush();
                        
                    
                    foreach($lines as $line){
                        $categoryInfo = explode(";", $line);
                        
                        $parent = 0;
                        foreach($categories as $category){
                            if($category->getTitle() == trim($categoryInfo[0])){
                                $parent = $category;
                                break;
                            }
                        }

                        if($parent){
                            foreach($parent->getChildren() as $cat){
                                if($cat->getTitle() == trim($categoryInfo[1])){
                                    $boardValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->findOneBy(array("filter" => $boardFilter, "value" => trim($categoryInfo[7])));
                                    $gasFilterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->findOneBy(array("filter" => $gasFilter, "value" => trim($categoryInfo[8])));
                                    $gearFilterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->findOneBy(array("filter" => $gearFilter, "value" => trim($categoryInfo[9])));
                                    if(!$gearFilterValue){
                                        $gearFilterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->find(64);
                                    }
                                    $transmissionFilterValue = $manager->getRepository("DashboardCommonBundle:FilterValue")->findOneBy(array("filter" => $transmissionFilter, "value" => "Frente"));
                                      
                                    if(count($cat->getGenerations()) > 0){
                                        foreach($cat->getGenerations() as $catGeneration){
                                           
                                            $modification = $manager->getRepository("DashboardCommonBundle:Modification")->findOneBy(array("generation" => $catGeneration, "label" => trim($categoryInfo[4]), "power" => trim($categoryInfo[10]), "size" => trim($categoryInfo[11])));
                                            
                                            if(count($generationItems) > 0){
                                                $isItem = 0;
                                                foreach($generationItems as $item){
                                                    if(($item->getGeneration()->getId() == $catGeneration->getId()) &&
                                                       ($item->getBoard()->getBoard()->getId() == $boardValue->getId()) && 
                                                       ($item->getGasType()->getId() == $gasFilterValue->getId()) && 
                                                       ($item->getTransmissionType()->getId() == $transmissionFilterValue->getId()) && 
                                                       ($item->getGearType()->getId() == $gearFilterValue->getId())){
                                                            $isItem = 1;
                                                            if($modification){
                                                                if(false === $item->getItemModifications()->contains($modification)){
                                                                    $item->addItemModification($modification);
                                                                }
                                                            }
                                                    }
                                                }
                                                
                                                if(!$isItem){
                                                    $generationItem = new GenerationItem();
                                                    $generationItem->setGeneration($catGeneration);
                                                    foreach($catGeneration->getBoards() as $generationBoard){
                                                        if($generationBoard->getBoard()->getId() == $boardValue->getId()){
                                                            $generationItem->setBoard($generationBoard);
                                                        }
                                                    }
                                                    $generationItem->setGasType($gasFilterValue);
                                                    $generationItem->setGearType($gearFilterValue);
                                                    $generationItem->setTransmissionType($transmissionFilterValue);
                                                    if($modification){
                                                        $generationItem->addItemModification($modification);
                                                    }
                                                    $generationItems->add($generationItem); 
                                                }
                                                
                                            }else{
                                                $generationItem = new GenerationItem();
                                                $generationItem->setGeneration($catGeneration);
                                                foreach($catGeneration->getBoards() as $generationBoard){
                                                    if($generationBoard->getBoard()->getId() == $boardValue->getId()){
                                                        $generationItem->setBoard($generationBoard);
                                                    }
                                                }
                                                $generationItem->setGasType($gasFilterValue);
                                                $generationItem->setGearType($gearFilterValue);
                                                $generationItem->setTransmissionType($transmissionFilterValue);
                                                if($modification){
                                                    $generationItem->addItemModification($modification);
                                                }
                                                $generationItems->add($generationItem);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    foreach($generationItems as $item){
                        $manager->persist($item);
                    }
                        
                    $manager->flush();
                        
                    }
                }
            }
        
        }*/
        return new Response('OK');
    }
}

