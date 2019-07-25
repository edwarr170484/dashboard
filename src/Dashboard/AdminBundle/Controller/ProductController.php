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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Dashboard\CommonBundle\Entity\Category;
use Dashboard\CommonBundle\Entity\Region;
use Dashboard\CommonBundle\Entity\City;
use Dashboard\CommonBundle\Entity\Selltype;
use Dashboard\CommonBundle\Entity\Mark;
use Dashboard\CommonBundle\Entity\Banner;
use Dashboard\CommonBundle\Entity\Page;
use Dashboard\CommonBundle\Entity\Role;
use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\Settings;
use Dashboard\CommonBundle\Entity\OrderStatus;
use Dashboard\CommonBundle\Entity\Product;
use Dashboard\CommonBundle\Entity\ProductFotos;
use Dashboard\CommonBundle\Entity\ProductOptions;
use Dashboard\CommonBundle\Entity\ProductService;
use Dashboard\CommonBundle\Entity\UserInfo;

use Dashboard\AdminBundle\Form\Type\CategoryType;
use Dashboard\CommonBundle\Form\Type\UserInfoType;
use Dashboard\AdminBundle\Form\Type\EditProductType;
use Dashboard\AdminBundle\Form\Type\TranslationType;

class ProductController extends Controller
{
    /**
     * @Route("/admin/category/{categoryId}", name="admin_product_category", defaults={"categoryId": 0})
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
                    if($category->getImage())
                    {
                        if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/' . $category->getImage() ))
                        {
                            $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/' . $category->getImage());
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
                
                if($category->getImage())
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/' . $category->getImage() ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/' . $category->getImage());
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
        
        
        return $this->render('DashboardAdminBundle:Product:category.html.twig', array("categories" => $categories));
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
        
        return $this->render('DashboardAdminBundle:Product:categoryitem.html.twig', array("category" => $category,"spaces" => $spaces));     
    }
    
    /**
     * @Route("/admin/categoryedit/{categoryId}", name="admin_product_category_edit",  defaults={"categoryId": 0})
     */
    public function editCategoryAction($categoryId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $originalTranslations = new ArrayCollection();
        $originalDescriptions = new ArrayCollection();
        
        $category = ($categoryId) ? $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId) : new Category();
               
        if($category->getParent())
            $parent = $category->getParent()->getId();
        else
            $parent = 0;
        
        if($categoryId)
        {
            foreach ($category->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
            foreach ($category->getDescriptions() as $item) {
                $originalDescriptions->add($item);
            }
        }
        
        $categoryForm = $this->createForm(new CategoryType($manager, $parent), $category);
        
        $categoryForm->handleRequest($request);
       
        if($categoryForm->isSubmitted() && $categoryForm->isValid())
        {
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
            
                if($categoryForm['name']->getData())
                {
                    $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.id <> ' . $categoryId . ' AND c.name = \'' . $categoryForm['name']->getData() . '\'' );
                    
                    try{
                        $categories = $query->getResult();
                    }
                    catch(\Doctrine\ORM\NoResultException $e) {
                        $categories = 0;
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
                
                if(!$categoryId)
                {
                    $category->setSortorder(count($manager->getRepository("DashboardCommonBundle:Category")->findAll()) + 1);
                    $category->setIsActive(1);
                }
                
                $image = $categoryForm['imageNew']->getData();
                $oldImage = $categoryForm['image']->getData();

                if($image)
                {
                    if($oldImage)
                    {
                        if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/' . $oldImage ))
                        {
                            $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/category/' . $oldImage );
                        }
                    }

                    $extention = $image->getClientOriginalExtension();
                    $localImageName = rand(1, 99999).'.'.$extention;
                    $image->move('bundles/images/category',$localImageName);
                    $category->setImage($localImageName);
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
        
        return $this->render('DashboardAdminBundle:Product:categoryedit.html.twig', array("categoryForm" => $categoryForm->createView(),
                                                                                          "category" => $category));
    }
    
    /**
     * @Route("/admin/selltype", name="admin_settings_selltype")
     */
    public function selltypeAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $selltypes = $manager->getRepository("DashboardCommonBundle:Selltype")->findAll();
        
        if($request->request->get('selltypeId'))
        {
            foreach($request->request->get('selltypeId') as $id)
            {
                $selltype = $manager->getRepository("DashboardCommonBundle:Selltype")->find($id);
            
                if($selltype)
                {
                    if(count($selltype->getProduct()) > 0)
                    {
                        $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Ошибка!</strong> Нельзя удалить тип объявлений ' . $selltype->getTitle() . '. К нему привязано ' . count($selltype->getProduct()) . ' объявлений. </div>')
                        );
                    }
                    else
                    {
                        if($selltype->getTranslations())
                        {
                            foreach($selltype->getTranslations() as $translation)
                            {
                                $translation->setSelltype(null);
                                $manager->remove($translation);
                            }
                        }
                        
                        $manager->remove($selltype);
                        $manager->flush();

                        $this->addFlash(
                            'notice',
                            $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Успешно!</strong> Тип объявлений успешно удален. </div>')
                        );
                    }
                }
            }
            
            return $this->redirectToRoute('admin_settings_selltype');
        }

        return $this->render('DashboardAdminBundle:Settings:selltype.html.twig', array("selltypes" => $selltypes));
    }
    
    /**
     * @Route("/admin/edit/selltype/{selltypeId}", name="admin_settings_selltype_edit", defaults={"selltypeId" : 0})
     */
    public function selltypeEditAction($selltypeId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalTranslations = new ArrayCollection();
        
        $selltype = ($selltypeId) ? $manager->getRepository("DashboardCommonBundle:Selltype")->find($selltypeId) : new Selltype();
        
        if($selltypeId)
        {
            foreach ($selltype->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
        }
        
        $selltypeForm = $this->get('form.factory')->createNamedBuilder('selltype', 'form', $selltype)
                ->add('title', TextType::class, array('required' => true, 'label' => 'Название типа объявления', 'attr' => array('class' => 'form-control','placeholder' => 'Название типа объявления')))
                ->add('translations', 'collection', array('type' => new TranslationType($manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
                ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
        $selltypeForm->handleRequest($request);
        
        if($selltypeForm->isSubmitted() && $selltypeForm->isValid())
        {
            if($originalTranslations)
                {
                    foreach ($originalTranslations as $item) 
                    {
                        if (false === $selltype->getTranslations()->contains($item)) 
                        {
                            $item->setSelltype(null);
                            $manager->remove($item);
                        }
                    }
                }

                if($selltype->getTranslations())
                {
                    foreach($selltype->getTranslations() as $item)
                    {
                        $item->setSelltype($selltype);
                        $manager->persist($item);
                    }
                }
                
                $manager->persist($selltype);
                $manager->flush();
                
                $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Успешно!</strong> Тип объявлений добавлен. </div>')
                );
            
        }
        
        return $this->render('DashboardAdminBundle:Settings:selltypeedit.html.twig', array("selltypeForm" => $selltypeForm->createView()));
        
    }

    /**
     * @Route("/admin/product/{page}", name="admin_product", defaults={"page" : 0})
     */
    public function productAction($page, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $pagination = 0;
        
        $product = new Product();
        
        $productForm = $this->get('form.factory')->createNamedBuilder('product', 'form', $product)
                ->add('id', TextType::class, array('required' => false,'mapped' => false,'label' => 'ID объявления', 'attr' => array('class' => 'form-control','placeholder' => 'ID объявления')))
                ->add('name', TextType::class, array('required' => false,'mapped' => false,'label' => 'Название', 'attr' => array('class' => 'form-control','placeholder' => 'Название')))
                ->add('save', ButtonType::class, array('label' => 'Поиск', 'attr' => array('class' => 'btn btn-sm btn-primary m-r-5')))->getForm();
        
        $productForm->handleRequest($request);
        
        if($productForm->isSubmitted() && $productForm->isValid())
        {
            if($productForm['id']->getData() || $productForm['name']->getData())
            {
                $sql = "SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 1";
                
                if($productForm['id']->getData())
                {
                    $sql .= " AND p.id = " . $productForm['id']->getData();
                }

                if($productForm['name']->getData())
                {
                    $sql .= " AND p.name LIKE '%" . $productForm['name']->getData() . "%'";
                }
                
                $query = $manager->createQuery($sql);
                
                try{
                    $products = $query->getResult();
                }
                catch(\Doctrine\ORM\NoResultException $e) {
                    $products = 0;
                }
                
            }
            else 
            {
                return $this->redirectToRoute("admin_product");
            }
        }
        else
        {
            $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 1 ORDER BY p.dateAdded DESC" );

            try{
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }

            $totalProducts = count($products);

            $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 1 ORDER BY p.dateAdded DESC" )->setFirstResult((($page > 0) ? ($page - 1) : 0) * 10)->setMaxResults(10);

            try{
                $products = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $products = 0;
            }

            $helper = $this->get("app.helpers");
            $pagination = $helper->paginator(($page > 0) ? (int)$page : 1, $totalProducts, 10, "/admin/product");
        }

        return $this->render('DashboardAdminBundle:Product:product.html.twig', array("products" => $products,
                                                                                     "pagination" => $pagination,
                                                                                     "productForm" => $productForm->createView()));
    }
    
    /**
     * @Route("/admin/confirmproduct/{page}", name="admin_product_confirm", defaults={"page" : 0})
     */
    public function productConfirmAction($page, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        if($request->request->get('action'))
        {
            switch($request->request->get('action'))
            {
                case 'delete':
                    if($request->request->get('productIds'))
                    {
                        foreach($request->request->get('productIds') as $productId)
                        {
                            $this->deleteAdvert($productId, $request);
                        }
                    }
                break; 
            }  
            
            return $this->redirectToRoute('admin_product_confirm');
        }
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 0 AND p.isCorrect = 0 AND p.isActive = 0 AND p.isBlocked = 0 ORDER BY p.dateAdded DESC" );

        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        $totalProducts = count($products);
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 0 AND p.isCorrect = 0 AND p.isActive = 0 AND p.isBlocked = 0 ORDER BY p.dateAdded DESC" )->setFirstResult((($page > 0) ? ($page - 1) : 0) * 10)->setMaxResults(10);

        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        $helper = $this->get("app.helpers");
        $pagination = $helper->paginator(($page > 0) ? (int)$page : 1, $totalProducts, 10, "/admin/confirmproduct");
        
        return $this->render('DashboardAdminBundle:Product:productconfirm.html.twig', array("products" => $products,
                                                                                            "pagination" => $pagination));
    }
    
    /**
     * @Route("/admin/correctproduct/{page}", name="admin_product_correct", defaults={"page" : 0})
     */
    public function productCorrectAction($page, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        if($request->request->get('action'))
        {
            switch($request->request->get('action'))
            {
                case 'delete':
                    if($request->request->get('productIds'))
                    {
                        foreach($request->request->get('productIds') as $productId)
                        {
                            $this->deleteAdvert($productId, $request);
                        }
                    }
                break; 
            }  
            
            return $this->redirectToRoute('admin_product_correct');
        }
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 0 AND p.isCorrect = 1 AND p.isActive = 0 AND p.isBlocked = 0 ORDER BY p.dateAdded DESC" );

        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        $totalProducts = count($products);
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 0 AND p.isCorrect = 1 AND p.isActive = 0 AND p.isBlocked = 0 ORDER BY p.dateAdded DESC" )->setFirstResult((($page > 0) ? ($page - 1) : 0) * 10)->setMaxResults(10);

        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        $helper = $this->get("app.helpers");
        $pagination = $helper->paginator(($page > 0) ? (int)$page : 1, $totalProducts, 10, "/admin/correctproduct");
        
        return $this->render('DashboardAdminBundle:Product:productcorrect.html.twig', array("products" => $products,
                                                                                            "pagination" => $pagination));
    }
    
    /**
     * @Route("/admin/blockedproduct/{page}", name="admin_product_blocked", defaults={"page" : 0})
     */
    public function productBlockedAction($page, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        if($request->request->get('action'))
        {
            switch($request->request->get('action'))
            {
                case 'delete':
                    if($request->request->get('productIds'))
                    {
                        foreach($request->request->get('productIds') as $productId)
                        {
                            $this->deleteAdvert($productId, $request);
                        }
                    }
                break; 
            }  
            
            return $this->redirectToRoute('admin_product_blocked');
        }
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 0 AND p.isCorrect = 0 AND p.isActive = 0 AND p.isBlocked = 1 ORDER BY p.dateAdded DESC" );

        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        $totalProducts = count($products);
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 0 AND p.isCorrect = 0 AND p.isActive = 0 AND p.isBlocked = 1 ORDER BY p.dateAdded DESC" )->setFirstResult((($page > 0) ? ($page - 1) : 0) * 10)->setMaxResults(10);

        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        $helper = $this->get("app.helpers");
        $pagination = $helper->paginator(($page > 0) ? (int)$page : 1, $totalProducts, 10, "/admin/blockedproduct");
        
        return $this->render('DashboardAdminBundle:Product:productblocked.html.twig', array("products" => $products,
                                                                                            "pagination" => $pagination));
    }
    
    /**
     * @Route("/admin/deleteproduct/{productId}", name="admin_product_delete", defaults={"productId" : 0})
     */
    public function productDeleteAction($productId, Request $request)
    {
        if($productId)
        {
            $this->deleteAdvert($productId, $request);
        }
        return $this->redirect($request->server->get("HTTP_REFERER"));
    }
    
    /**
     * @Route("/admin/editproduct/{productId}/{confirm}", name="admin_product_edit", defaults={"productId" : 0, "confirm" : 0})
     */
    
    public function editProductAction($productId, $confirm, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $originalFotos = new ArrayCollection();
        $user = $this->get('security.context')->getToken()->getUser();
        $product = new Product();
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->find(1);
        
        //get all categories from database  
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL' );
        $categories = $query->getResult();
        
        if($productId)
        {
            $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId);
            
            if($product)
            {
                foreach ($product->getFotos() as $foto) {
                    $originalFotos->add($foto);
                }
                $productForm = $this->createForm(new EditProductType($manager, $product->getUser()->getUserinfo()), $product);
            }
            else
                return $this->redirectToRoute("admin_notfound");
        }
        elseif($productId == 0)
        {
            $productForm = $this->createForm(new EditProductType($manager, $user->getUserinfo()), $product);
        }
        else
            return $this->redirectToRoute("admin_notfound");
        
        $productForm->handleRequest($request);
        
        $validator = $this->get('validator');
        $errors = $validator->validate($product);
            
        if (count($errors) > 0) 
        {
            foreach($errors as $error)
                    $this->addFlash(
                           'notice',
                           $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                           <strong>Ошибка!</strong> ' . $error->getMessage() . '.</div>')
                   );
                 
            return $this->redirectToRoute("admin_product_edit", array("productId" => $product->getId()));
        }
        
        if($productForm->isSubmitted() && $productForm->isValid())
        {
            
            $category = $manager->getRepository("DashboardCommonBundle:Category")->findOneById($productForm['category']->getData());
            
            if($category)
            {
                $product->setCategory($category);
            }
            
            $image = $productForm['mainfotoNew']->getData();
            $oldImage = $productForm['mainfoto']->getData();
                    
            if($image)
            {
                if($oldImage)
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $oldImage ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $oldImage );
                    }
                }
                    
                $extention = $image->getClientOriginalExtension();
                $localImageName = rand(1, 99999).'.'.$extention;
                $image->move('bundles/images/products',$localImageName);
                $product->setMainfoto($localImageName);
            }
            
            if($originalFotos)
            {
                foreach ($originalFotos as $foto) 
                {
                    if (false === $product->getFotos()->contains($foto)) 
                    {
                        if($foto->getFoto())
                        {
                            if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto()))
                            {
                                $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto());
                            }
                        }
                        $foto->setProduct(null);
                        $manager->remove($foto);
                    }
                }
            } 
            
            if($product->getFotos())
            {
                foreach($product->getFotos() as $key => $item)
                {
                    $item->setProduct($product);
                    $foto = $productForm['fotos'][$key]['fotoNew']->getData();
                        
                    if($foto )
                    {
                        $extention = $foto->getClientOriginalExtension();
                        $localFotoName = rand(1, 99999).'.'.$extention;
                        $foto->move('bundles/images/products',$localFotoName);
                            
                        $item->setFoto($localFotoName);
                        /*$item->setAlt($product->getName());
                        $item->setTitle($product->getName());*/
                    }
                        
                    $manager->persist($item);
                }
            }
            
            if($productId)
            {
                if($product->getIsBlocked())
                {
                    $product->setIsActive (0);
                }
            }
            
            $product->setDateEdited(new \DateTime("now"));
            
            if($productId == 0)
            {
                $product->setUser($user);
                $product->setDateAdded(new \DateTime("now"));
                $product->setDateEdited(new \DateTime("now"));
                $product->setShowTime($settings->getAdvertDaysShowNumber());
                $product->setIsActive(1);
                $product->setIsBlocked(0);
            }
            
            $helpers = $this->get('app.helpers');
            $product->setTranslit($helpers->translit($product->getName()));
            
            $product->setDateEdited(new \DateTime("now"));
            
            $manager->persist($product);
            $manager->flush();
            
            if($productForm['viewpremium']->getData())
                {
                    $service = $manager->getRepository("DashboardCommonBundle:Service")->find(1);

                    if($service && ($product->getIsBlocked() == 0))
                    {
                            $premiumService = $manager->getRepository("DashboardCommonBundle:ProductService")->findOneBy(array("product" => $product));
                            
                            if(!$premiumService)
                                $premiumService = new ProductService();
                            $date = new \DateTime("now");
                            $premiumService->setProduct($product);
                            $premiumService->setService($service);
                            $premiumService->setDateAdded($date);
                            $dateEnd = clone $date;
                            $dateEnd->add(new \DateInterval('P' . $service->getDays() . 'D'));
                            $premiumService->setDateEnd($dateEnd);
                            $premiumService->setIsActive(true);

                            $manager->persist($premiumService);

                            $product->setViewpremium(true);
                            $product->setViewcommon(false);
                            $product->setViewselected(false);
                    }
                }
                elseif($productForm['viewselected']->getData())
                {
                    $service = $manager->getRepository("DashboardCommonBundle:Service")->find(2);

                    if($service && ($product->getIsBlocked() == 0))
                    {
                            $selectedService = $manager->getRepository("DashboardCommonBundle:ProductService")->findOneBy(array("product" => $product));
                            
                            if(!$selectedService)
                                $selectedService = new ProductService();
                            $date = new \DateTime("now");
                            $selectedService->setProduct($product);
                            $selectedService->setService($service);
                            $selectedService->setDateAdded($date);
                            $dateEnd = clone $date;
                            $dateEnd->add(new \DateInterval('P' . $service->getDays() . 'D'));
                            $selectedService->setDateEnd($dateEnd);
                            $selectedService->setIsActive(true);

                            $manager->persist($selectedService);

                            $product->setViewpremium(false);
                            $product->setViewcommon(false);
                            $product->setViewselected(true);
                    }
                }
                else
                {
                    $product->setViewpremium(false);
                    $product->setViewcommon(true);
                    $product->setViewselected(false);

                    $isService = $manager->getRepository("DashboardCommonBundle:ProductService")->findOneBy(array("product" => $product));
                    
                    if($isService)
                    {
                        if($isService->getService()->getId() != 3)
                        {
                            $isService->setProduct(null);
                            $isService->setService(null);
                            $product->setService(null);
                            $manager->remove($isService);
                        }
                    }
                    
                }
            
            if($productForm['isCorrect']->getData())
            {
                $correctReason = $productForm['correctReason']->getData();
                $settings = $manager->getRepository("DashboardCommonBundle:Settings")->find(1);
                
                $product->setIsActive(false);
                $product->setIsConfirm(false);
                
                //send confirmation link to email
                if($product->getUser()->getAlerts())
                {
                    $message = \Swift_Message::newInstance()
                    ->setSubject('Ваше объявление не прошло модерацию на сайте gribupardot.sunweb.by')
                    ->setFrom(array($settings->getAdminEmail() => $settings->getSiteName()))
                    ->setTo($product->getUser()->getEmail())
                    ->setBody(
                        $this->renderView(
                            'Emails/correct.html.twig',
                            array('product' => $product,
                                  'reason' => $correctReason)
                        ),
                        'text/html'
                    );

                    $this->get('mailer')->send($message);
                }
            }
                
            $manager->persist($product);
            $manager->flush();
            
            if($productForm['isCorrect']->getData())
                $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Выполнено!</strong> Объявление отправлено на корректировку.</div>')
                );
            else
                $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Выполнено!</strong> Изменения сохранены.</div>')
                );
            
            if($confirm)
                return $this->redirectToRoute("admin_product_confirm");
            else
                return $this->redirectToRoute("admin_product_edit", array("productId" => $product->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Product:editproduct.html.twig', array("productForm" => $productForm->createView(),
                                                                                         "categories" => $categories));
    }
    
    /**
     * @Route("/admin/mark", name="admin_settings_mark")
     */
    public function markAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $marks = $manager->getRepository("DashboardCommonBundle:Mark")->findAll();
        
        if($request->request->get('markId'))
        {
            foreach($request->request->get('markId') as $id)
            {
                $mark = $manager->getRepository("DashboardCommonBundle:Mark")->find($id);
            
                if($mark)
                {
                    if($mark->getTranslations())
                    {
                        foreach($mark->getTranslations() as $translation)
                        {
                            $translation->setMark(null);
                            $manager->remove($translation);
                        }
                    }
                    $manager->remove($mark);
                    $manager->flush();

                    $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Оценка удалена. </div>')
                    );
                }
            }
            
            return $this->redirectToRoute('admin_settings_mark');
        }
        
        return $this->render('DashboardAdminBundle:Settings:mark.html.twig', array("marks" => $marks));
    }
    
    /**
     * @Route("/admin/edit/mark/{markId}", name="admin_settings_mark_edit", defaults={"markId" : 0})
     */
    public function markEditAction($markId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $originalTranslations = new ArrayCollection();
        
        $mark = ($markId) ? $manager->getRepository("DashboardCommonBundle:Mark")->find($markId) : new Mark();
        
        if($markId)
        {
            foreach ($mark->getTranslations() as $item) {
                $originalTranslations->add($item);
            }
        }

        $markForm = $this->get('form.factory')->createNamedBuilder('mark', 'form', $mark)
                ->add('title', TextType::class, array('required' => true, 'label' => 'Название оценки товара', 'attr' => array('class' => 'form-control','placeholder' => 'Название оценки товара')))
                ->add('translations', 'collection', array('type' => new TranslationType($manager), 'label' => ' ','allow_add'    => true, 'allow_delete' => true, 'by_reference' => false))
                ->add('save', ButtonType::class, array('label' => 'Сохранить', 'attr' => array('class' => 'btn btn-success')))->getForm();
        
        $markForm->handleRequest($request);
        
        if($markForm->isSubmitted() && $markForm->isValid())
        {
            if($originalTranslations)
            {
                foreach ($originalTranslations as $item) 
                {
                    if (false === $mark->getTranslations()->contains($item)) 
                    {
                        $item->setMark(null);
                        $manager->remove($item);
                    }
                }
            }

            if($mark->getTranslations())
            {
                foreach($mark->getTranslations() as $item)
                {
                    $item->setMark($mark);
                    $manager->persist($item);
                }
            } 
                
            $manager->persist($mark);
            $manager->flush();
                
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Успешно!</strong> Оценка товара добавлена. </div>')
            );
            
            return $this->redirectToRoute('admin_settings_mark');
        }
        
        return $this->render('DashboardAdminBundle:Settings:markedit.html.twig', array("markForm" => $markForm->createView()));
    }
    
    private function deleteAdvert($productId, $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId);
        
        if($product)
        {
                //удаляем главное фото
                if($product->getMainfoto())
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $product->getMainfoto() ))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $product->getMainfoto() );
                    }
                }
                
                //удаляем дополнительные фото
                if($product->getFotos())
                {
                    foreach($product->getFotos() as $foto)
                    {
                        if($foto->getFoto())
                        {
                            if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto() ))
                            {
                                $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto() );
                            }
                        }
                        
                        $foto->setProduct(null);
                        $manager->remove($foto);
                    }
                }
                
                //удаляем отзывы, привязанные к этому товару
                if($product->getReviews())
                {
                    foreach($product->getReviews() as $review)
                    {
                        $review->setProduct(null);
                        $manager->persist($review);
                        $manager->flush();
                    }
                }
                
                //удаляем сообщения, привязанные к этому товару
                if($product->getMessages())
                {
                    foreach($product->getMessages() as $message)
                    {
                        $message->setProduct(null);
                        $manager->persist($message);
                        $manager->flush();
                    }
                }
                
                //удаляем жалобы на этот товар
                if($product->getComplaint())
                {
                    foreach($product->getComplaint() as $complaint)
                    {
                        $complaint->setProduct(null);
                        $complaint->setUser(null);
                        $manager->remove($complaint);
                    }
                }
                
                //удаляем услуги, привязанные к товару
                if($product->getService())
                {
                    $product->getService()->setProduct(null);
                    $manager->remove($product->getService());
                    $product->setService(null);
                }
                
                //удаляем заказы, привязанные к этому товару
                if($product->getOrders())
                {
                    foreach($product->getOrders() as $order)
                    {
                        $order->setProduct(null);
                        $manager->persist($order);
                    }
                }
                
                //удаляем из избранных
                $favProducts = $manager->getRepository("DashboardCommonBundle:FavoriteProducts")->findBy(array("productId" => $product->getId()));
                if($favProducts)
                {
                    foreach($favProducts as $favProduct)
                    {
                        $manager->remove($favProduct);
                    }
                }
                
                $manager->remove($product);
                $manager->flush();
                
                $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Успешно!</strong> Объявление было удалено.</div>')
                    );
        }
        else
            $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Ошибка!</strong> Такого объявления не существует.</div>')
                    );
        
    }
}

