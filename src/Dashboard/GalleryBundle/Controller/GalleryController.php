<?php

namespace Dashboard\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Filesystem;

use Dashboard\GalleryBundle\Entity\Gallery;
use Dashboard\GalleryBundle\Entity\GalleryItems;

use Dashboard\GalleryBundle\Form\Type\GalleryType;

class GalleryController extends Controller
{
    public function galleryAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        
        $galleries = $this->getDoctrine()->getRepository('DashboardGalleryBundle:Gallery')->findAll();
        
        return $this->render('DashboardGalleryBundle:Admin:gallery.html.twig',array("galleries" => $galleries));
    }
    
    public function editAction($galleryId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $gallery = new Gallery();
        $subtitle = $this->get('translator')->trans("Add");
        $originalItems = new ArrayCollection();
        $fm = new Filesystem();
        
        if($galleryId)
        {
            $gallery = $manager->getRepository("DashboardGalleryBundle:Gallery")->find($galleryId);
            
            foreach ($gallery->getItems() as $item) {
                $originalItems->add($item);
            }
            
            $subtitle = $this->get('translator')->trans("Edit");
        }
        
        $galleryForm = $this->createForm(new GalleryType($manager),$gallery);
        
        $galleryForm->handleRequest($request);
        
        if($galleryForm->isValid())
        {

            if($originalItems)
            {
                foreach ($originalItems as $item) 
                {
                    if (false === $gallery->getItems()->contains($item)) 
                    {
                        if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/gallery/' . $item->getImage()))
                        {
                            $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/gallery/' . $item->getImage());
                        }
                        $item->setGallery(null);
                        $manager->remove($item);
                    }
                }
            }               
            
            if(!$galleryId)
            {
                $galleryCount = $manager->getRepository("DashboardGalleryBundle:Gallery")->galleryCount();
                $gallery->setSort($galleryCount + 1);
                $gallery->setIsShow(1);
            }
            
            $manager->persist($gallery);
            
            if($gallery->getItems())
            {
                foreach($gallery->getItems() as $key => $item)
                {
                    $item->setGallery($gallery);
                    
                    $image = $galleryForm['items'][$key]['imageNew']->getData();
                    $oldImage = $galleryForm['items'][$key]['image']->getData();
                    
                    if($image)
                    {
                        if($oldImage)
                        {
                            if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/gallery/' . $oldImage ))
                            {
                                $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/gallery/' . $oldImage );
                            }
                        }
                    
                        $extention = $image->getClientOriginalExtension();
                        $localImageName = rand(1, 99999).'.'.$extention;
                        $image->move('bundles/images/gallery',$localImageName);
                        $item->setImage($localImageName);
                        $item->setThumb($localImageName);
                    }
                    $item->setIsMain(0);
                    //$item->setStatus(1);
                    
                    $manager->persist($item);
                }
            }
            
            $manager->flush();
            
            $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Успешно!</strong> Информация о галерее успешно сохранена.</div>')
                    );
            
            return $this->redirect($this->generateUrl('dashboard_gallery_admin_edit', array('galleryId' => $gallery->getId())));
        }
        
        return $this->render("DashboardGalleryBundle:Admin:edit.html.twig", array("subtitle" => $subtitle, "galleryForm" => $galleryForm->createView()));
    }
    
    public function deleteAction($galleryId)
    {
        $manager = $this->getDoctrine()->getManager();
        
        if($galleryId)
        {
            $gallery = $manager->getRepository("DashboardGalleryBundle:Gallery")->find($galleryId);
            
            if($gallery)
            {
                foreach ($gallery->getItems() as $item) 
                {
                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/gallery/' . $item->getImage()))
                    {
                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/gallery/' . $item->getImage());
                    }
                    $item->setGallery(null);
                    $manager->remove($item);
                }
                
                $manager->remove($gallery);
                $manager->flush();
                
                $this->addFlash(
                        'notice',
                        $this->get('translator')->trans('<div class="alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Успешно!</strong> Галерея удалена.</div>')
                );
            }
        }
        
        return $this->redirectToRoute("dashboard_gallery_admin_index");
    }
}
