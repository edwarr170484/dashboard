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

use Dashboard\CommonBundle\Entity\Product;
use Dashboard\CommonBundle\Entity\ProductService;
use Dashboard\CommonBundle\Entity\UserPurseHistory;

use Dashboard\CommonBundle\Form\Type\ProductServiceType;

class ServiceController extends Controller
{
    /**
     * @Route("/account/service/{serviceId}/{productId}", name="account_service", defaults={"serviceId" : "0", "productId" : "0"})
     * @Route("/{_locale}/account/service/{serviceId}/{productId}", name="account_serviceLocale", defaults={"_locale" : "lv","serviceId" : "0","productId" : "0"}, requirements={"_locale" : "lv|ru"})
     */
    public function showServiceAction($serviceId, $productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        
        if($serviceId)
        {
            $service = $manager->getRepository("DashboardCommonBundle:Service")->find($serviceId);
            
            if($service)
            {
                $product = ($productId) ? $manager->getRepository("DashboardCommonBundle:Product")->findOneBy(array("user" => $user,
                                                                                                                    "id" => $productId,
                                                                                                                    "isActive" => 1,
                                                                                                                    "isBlocked" => 0,
                                                                                                                    "isConfirm" => 1,
                                                                                                                    "isCorrect" => 0)) : 0;
                
                if(!$product)
                    return $this->redirectToRoute("pages", array("route"=> "Platnye-uslugi-doski-obyavleniy"));
                
                if($product->getService())
                    $currentService = clone $product->getService();
                
                $productService = ($product->getService()) ? $product->getService() : new ProductService();
                $serviceForm = $this->createForm(new ProductServiceType($manager), $productService);
                
                $serviceForm->handleRequest($request);
                
                if($serviceForm->isValid()&& $serviceForm->isSubmitted())       
                {
                    if($user->getUserpurse()->getBalanse() < $service->getPrice())
                    {
                        $this->addFlash(
                            'notice',
                            '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                            $this->get('translator')->trans('<strong>Kļūda!</strong> Jūsu kontā nav pietiekami daudz līdzekļu, lai aktivizētu pakalpojumu.') . '</div>'
                        );
                    }
                    else
                    {
                        if($product->getService())
                        {
                            if($currentService->getService()->getId() != $service->getId())
                            {
                                $this->addFlash(
                                    'notice',
                                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                                    $this->get('translator')->trans('<strong>Kļūda!</strong> Pakalpojuma %message% jau ir saistīts ar šo reklāmu. Jūs varat pagarināt pašreizējo pakalpojumu vai piemērot citu pēc pašreizējās darbības beigām.', array("%message%" => $currentService->getService()->getTitle())) . '</div>'
                                );
                                
                                return $this->redirectToRoute("account_service", array("serviceId" => $serviceId, "productId" => $productId));
                            }
                            else
                            {
                                $productService = $product->getService();
                                $currentBalanse = $user->getUserpurse()->getBalanse();
                                $user->getUserpurse()->setBalanse($currentBalanse - $service->getPrice());

                                $manager->persist($user);

                                $userPurseHistory = new UserPurseHistory();
                                $userPurseHistory->setActionDate(new \DateTime("now"));
                                $userPurseHistory->setAction($this->get('translator')->trans("Maksa par pakalpojuma aktivizēšanu") ." " . $service->getTitle() . ". " . $this->get('translator')->trans("Izrakstīts") . " " . $service->getPrice() . $settings->getCurrency()->getName() . ".");
                                $userPurseHistory->setUserpurse($user->getUserpurse());
                                $userPurseHistory->setCurrentBalanse($currentBalanse - $service->getPrice());
                                $manager->persist($userPurseHistory);
                                
                                $dateEnd = $product->getService()->getDateEnd();
                                $dateNewEnd = clone $dateEnd;
                                $dateNewEnd->add(new \DateInterval('P' . $service->getDays() . 'D'));
                                $productService->setDateEnd($dateNewEnd);
                                $productService->setIsActive(1);
                                $manager->persist($productService);
                                $manager->flush();
                                
                                $this->addFlash(
                                    'notice',
                                    '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                                    $this->get('translator')->trans('<strong>Gatavs!</strong> Pakalpojums ir veiksmīgi paplašināts.') . '</div>'
                                );
                                
                            }  
                        }
                        else
                        {
                            $currentBalanse = $user->getUserpurse()->getBalanse();
                            $user->getUserpurse()->setBalanse($currentBalanse - $service->getPrice());

                            $manager->persist($user);

                            $userPurseHistory = new UserPurseHistory();
                            $userPurseHistory->setActionDate(new \DateTime("now"));
                            $userPurseHistory->setAction($this->get('translator')->trans("Maksa par pakalpojuma aktivizēšanu") ." " . $service->getTitle() . ". " . $this->get('translator')->trans("Izrakstīts") . " " . $service->getPrice() . $settings->getCurrency()->getName() . ".");
                            $userPurseHistory->setUserpurse($user->getUserpurse());
                            $userPurseHistory->setCurrentBalanse($currentBalanse - $service->getPrice());
                            $manager->persist($userPurseHistory);

                            $date = new \DateTime("now");
                            $productService->setDateAdded($date);
                            $dateEnd = clone $date;
                            $dateEnd->add(new \DateInterval('P' . $service->getDays() . 'D'));
                            $productService->setDateEnd($dateEnd);
                            $productService->setIsActive(1);
                            $manager->persist($productService);

                            if($service->getId() == 1)
                            {
                                $product->setViewpremium(true);
                                $product->setViewcommon(false);
                                $product->setViewselected(false);
                            }
                            if($service->getId() == 2)
                            {
                                $product->setViewpremium(false);
                                $product->setViewcommon(false);
                                $product->setViewselected(true);
                            }
                            if($service->getId() == 3)
                            {
                                $product->setViewpremium(false);
                                $product->setViewcommon(true);
                                $product->setViewselected(false);
                            }

                            $manager->flush();

                            $this->addFlash(
                                'notice',
                                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                                $this->get('translator')->trans('<strong>Gatavs!</strong> Pakalpojums ir veiksmīgi aktivizēts.') . '</div>'
                            );
                        }
                    }
                    
                    if($locale->getIsDefault())
                    {
                        return $this->redirectToRoute("account_service", array("serviceId" => $service->getId(),
                                                                           "productId" => $product->getId(),
                                                                           "locale" => $locale,"settings" => $settings));
                    }
                    else
                    {
                        return $this->redirectToRoute("account_serviceLocale", array("serviceId" => $service->getId(),
                                                                           "productId" => $product->getId(),
                                                                           "locale" => $locale,"settings" => $settings,"_locale" => $locale->getCode()));
                    }
                    
                }
                
                
                return $this->render('DashboardCommonBundle:Product:service.html.twig', array("product" => $product, 
                                                                                              "user" => $user,
                                                                                              "service" => $service,
                                                                                              "serviceForm" => $serviceForm->createView(),
                                                                                              "locale" => $locale,"settings" => $settings));
            }
            else
                if($locale->getIsDefault())
                {
                    return $this->redirectToRoute("notfound");
                }
                else
                {
                    return $this->redirectToRoute("notfoundLocale", array("_locale" => $locale->getCode()));
                }
        }
        else
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute("notfound");
            }
            else
            {
                return $this->redirectToRoute("notfoundLocale", array("_locale" => $locale->getCode()));
            }

    }
}

