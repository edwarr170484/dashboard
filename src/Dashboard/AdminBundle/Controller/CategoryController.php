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
        
        $finder->files()->name('moto.txt')->in('import');
        
        $baseCategory = $manager->getRepository("DashboardCommonBundle:Category")->find(27);
        
        $categories = new ArrayCollection();
        $childrens = new ArrayCollection();
        
        if($baseCategory){
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $lines = file($file->getRealPath());
                
                if(count($lines) > 0){
                    foreach($lines as $line){
                        $categoryInfo = explode(";", $line);
                        
                        $category = new Category();
                        $category->setParent($baseCategory);
                        $category->setTitle($categoryInfo[0]);
                        $category->setName($this->get('app.helpers')->translit($categoryInfo[0]));
                        $category->setIsActive(1);
                        $category->setIsShowFilters(1);
                        $category->setIsShowPriceFilter(1);
                        $category->setIsUseChildrensLikeModel(1);
                        
                        $marker = 0;
                        foreach($categories as $cat){
                            if($cat->getTitle() == $categoryInfo[0]){
                                $marker = 1;
                                break;
                            }
                        }
                        
                        if(!$marker){
                            $categories->add($category);
                        }
                    }
                    
                    foreach($categories as $key => $category){
                        $category->setSortorder($key + 1);
                        $manager->persist($category);
                    }
                    
                    $manager->flush();
                    
                    $categories = $manager->getRepository("DashboardCommonBundle:Category")->findBy(array("parent" => $baseCategory));
                    
                    if($categories){
                        foreach($lines as $line){
                            $categoryInfo = explode(";", $line);

                            $parent = 0;
                            foreach($categories as $category){
                                if($category->getTitle() == $categoryInfo[0]){
                                    $parent = $category;
                                    break;
                                }
                            }

                            if($parent){
                                $child = new Category();
                                $child->setParent($parent);
                                $child->setTitle($categoryInfo[1]);
                                $child->setName($this->get('app.helpers')->translit($categoryInfo[1]));
                                $child->setIsActive(1);
                                $child->setIsShowFilters(1);
                                $child->setIsShowPriceFilter(1);
                                $childrens->add($child);
                            }
                        }

                        foreach($childrens as $key => $child){
                            $child->setSortorder(count($child->getParent()->getChildren()) + 1);
                            $manager->persist($child);
                        }

                        $manager->flush();
                    }
                }
            }
        }
        
        return new Response('OK');
    }
}

