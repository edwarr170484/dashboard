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
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isActive = 1 AND p.isConfirm = 0 AND p.isBlocked = 0 ORDER BY p.dateAdded DESC" );

        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        $totalProducts = count($products);
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isActive = 1 AND p.isConfirm = 0 AND p.isBlocked = 0 ORDER BY p.dateAdded DESC" )->setFirstResult((($page > 0) ? ($page - 1) : 0) * 10)->setMaxResults(10);

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
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 0 AND p.isActive = 1 AND p.isBlocked = 1 ORDER BY p.dateAdded DESC" );

        try{
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
            $products = 0;
        }
        
        $totalProducts = count($products);
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 0 AND p.isActive = 1 AND p.isBlocked = 1 ORDER BY p.dateAdded DESC" )->setFirstResult((($page > 0) ? ($page - 1) : 0) * 10)->setMaxResults(10);

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
        $originalServices = new ArrayCollection();
        $user = $this->get('security.context')->getToken()->getUser();
        $product = new Product();
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->find(1);
        $services = $manager->getRepository("DashboardCommonBundle:Service")->findAll();
        
        if($productId)
        {
            $product = $manager->getRepository("DashboardCommonBundle:Product")->find($productId);
            
            if($product)
            {
                if($product->getFotos()){
                    foreach ($product->getFotos() as $foto) {
                        $originalFotos->add($foto);
                    }
                }
                
                if($product->getServices()){
                    foreach ($product->getServices() as $service) {
                        $originalServices->add($service);
                    }
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
            
        if(count($errors) > 0) 
        {
            foreach($errors as $error){
                $this->addFlash(
                    'notice',
                    $this->get('translator')->trans('<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Ошибка!</strong> ' . $error->getMessage() . '.</div>')
                );
            }
                 
            return $this->redirectToRoute("admin_product_edit", array("productId" => $product->getId()));
        }
        
        if($productForm->isSubmitted() && $productForm->isValid())
        {
            if($originalServices){
                foreach ($originalServices as $service){
                    if(false === $product->getServices()->contains($service)){
                        $service->setProduct(null);
                        if($service->getBills()){
                            foreach($service->getBills() as $bill){
                                $bill->removeService($service);
                                $manager->persist($bill);
                            }
                        }
                        
                        $manager->remove($service);
                    }
                }
            }
            
            if($product->getServices()){
                foreach($product->getServices() as $service){
                    $service->setProduct($product);
                    $manager->persist($service);
                }
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
            
            if($product->getFotos()){
                foreach($product->getFotos() as $key => $item){
                    $item->setProduct($product);
                    $foto = $productForm['fotos'][$key]['fotoNew']->getData();
                        
                    if($foto ){
                        $extention = $foto->getClientOriginalExtension();
                        $localFotoName = rand(1, 99999).'.'.$extention;
                        $foto->move('bundles/images/products',$localFotoName); 
                        $item->setFoto($localFotoName);
                    }
                        
                    $manager->persist($item);
                }
            }
            
            $product->setDateEdited(new \DateTime("now"));
            
            if($productId == 0)
            {
                $product->setUser($user);
                $product->setDateAdded(new \DateTime("now"));
                $product->setDateEdited(new \DateTime("now"));
                $product->setIsActive(1);
            }
            
            $helpers = $this->get('app.helpers');
            $product->setTranslit($helpers->translit($product->getName()));
            
            $manager->persist($product);
            $manager->flush();
                      
            if($productForm['isBlocked']->getData())
            {
                $correctReason = $productForm['correctReason']->getData();
                $settings = $manager->getRepository("DashboardCommonBundle:Settings")->find(1);
                
                $product->setIsConfirm(0);
                
                if($product->getUser()->getIsAlertChangeOrderStatus()){
                    $message = \Swift_Message::newInstance()
                    ->setSubject('Ваше объявление не прошло модерацию на сайте ' . $settings->getSiteName())
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
            
            $this->addFlash(
                'notice',
                $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Выполнено!</strong> Изменения сохранены.</div>')
            );
                
            return $this->redirectToRoute("admin_product_edit", array("productId" => $product->getId()));
        }
        
        return $this->render('DashboardAdminBundle:Product:editproduct.html.twig', array("productForm" => $productForm->createView(), "services" => $services));
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

