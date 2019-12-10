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
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Dashboard\CommonBundle\Entity\Product;
use Dashboard\CommonBundle\Entity\ProductInfo;
use Dashboard\CommonBundle\Entity\ProductFotos;
use Dashboard\CommonBundle\Entity\ProductService;
use Dashboard\CommonBundle\Entity\Bill;
use Dashboard\CommonBundle\Entity\FilterValue;
use Dashboard\CommonBundle\Model\AdvertInfo;
use Dashboard\CommonBundle\Model\AdvertImage;
use Dashboard\CommonBundle\Model\AdvertFilter;
use Dashboard\CommonBundle\Model\SelectedService;

class AdvertController extends Controller
{
    /**
     * @Route("/account/addadvert/{step}/{data}", name="addAdvert", defaults={"step" : "0", "data" : 0})
     * @Route("/{_locale}/account/addadvert/{step}/{data}", name="addAdvertLocale", defaults={"_locale" : "es", "step" : "0", "data" : 0}, requirements={"_locale" : "es|ru"})
     */
    public function addAdvertAction($step, $data,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL' );
        $categories = $query->getResult();
        
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(), new GetSetMethodNormalizer(), new ArrayDenormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $session = new Session();
        $advertInfo = ($session->get('advertInfo')) ? $serializer->deserialize($session->get('advertInfo'), 'Dashboard\CommonBundle\Model\AdvertInfo', 'json') : new AdvertInfo();
        $advertImages = ($session->get('advertImages')) ? $serializer->deserialize($session->get('advertImages'), 'Dashboard\CommonBundle\Model\AdvertImage[]', 'json') : array();
        $advertFilters = ($session->get('advertFilters')) ? $serializer->deserialize($session->get('advertFilters'), 'Dashboard\CommonBundle\Model\AdvertFilter[]', 'json') : array();
        
        if($step){
            switch($step){
                case "step11":
                    $category = $manager->getRepository("DashboardCommonBundle:Category")->find($data);
                    if($category->getParent()->getId() != $advertInfo->getBaseCategory())
                    {
                        if(count($advertImages) > 0){
                            $tempAdvertImages = new ArrayCollection($advertImages);
                            foreach($advertImages as $image){
                                if($image->getName()){
                                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $image->getName())){
                                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $image->getName());
                                    }
                                }
                                $tempAdvertImages->removeElement($image);
                            }
                        }
                        $advertInfo = new AdvertInfo();
                        $advertImages = array();
                        $advertFilters = array();
                        $advertData = $serializer->serialize($advertInfo, 'json');
                        $session->set('advertInfo', $advertData);
                        $advertData = $serializer->serialize($advertImages, 'json');
                        $session->set('advertImages', $advertData);
                        $advertData = $serializer->serialize($advertFilters, 'json');
                        $session->set('advertFilters', $advertData);
                    }
                    if($category){
                        $advertInfo->setBaseCategory($category->getParent()->getId());
                        $advertData = $serializer->serialize($advertInfo, 'json');
                        $session->set('advertInfo', $advertData);
                    }
                    return $this->render('DashboardCommonBundle:Product:add/step11.html.twig', array("category" => $category, "settings" => $settings, "locale" => $locale));
                break;
            
                case "step12":
                    $boards = new ArrayCollection();
                    $boardGenerations = new ArrayCollection();
                    $gasTypes = new ArrayCollection();
                    $gearTypes = new ArrayCollection();
                    $transmittionTypes = new ArrayCollection();
                    $modifications = new ArrayCollection();
                    
                    $category = $manager->getRepository("DashboardCommonBundle:Category")->find($data);
                    $baseCategory = $advertInfo->getBaseCategory();
                    
                    if($category->getId() != $advertInfo->getCategory())
                    {
                        if(count($advertImages) > 0){
                            $tempAdvertImages = new ArrayCollection($advertImages);
                            foreach($advertImages as $image){
                                if($image->getName()){
                                    if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $image->getName())){
                                        $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $image->getName());
                                    }
                                }
                                $tempAdvertImages->removeElement($image);
                            }
                        }
                        $advertInfo = new AdvertInfo();
                        $advertImages = array();
                        $advertFilters = array();
                        $advertData = $serializer->serialize($advertInfo, 'json');
                        $session->set('advertInfo', $advertData);
                        $advertData = $serializer->serialize($advertImages, 'json');
                        $session->set('advertImages', $advertData);
                        $advertData = $serializer->serialize($advertFilters, 'json');
                        $session->set('advertFilters', $advertData);
                    }
                    
                    if($category){
                        $advertInfo->setCategory($category->getId());
                        $advertInfo->setBaseCategory($baseCategory);
                        $advertData = $serializer->serialize($advertInfo, 'json');
                        $session->set('advertInfo', $advertData);
                    }
                    
                    //select all parameters if they are
                    if($advertInfo->getYear()){
                        $query = $manager->createQuery('SELECT g FROM Dashboard\CommonBundle\Entity\Generation g WHERE g.yearFrom <= ' . $advertInfo->getYear() . ' AND g.yearTo >= ' . $advertInfo->getYear());
                    
                        $generations = $query->getResult();
                        
                        if($generations){
                            foreach($generations as $generation){
                                if($generation->getBoards()){
                                    foreach($generation->getBoards() as $generationBoard){
                                        if(false === $boards->contains($generationBoard->getBoard())){
                                            $boards->add($generationBoard);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if($advertInfo->getBoard()){
                        $query = $manager->createQuery('SELECT gb FROM Dashboard\CommonBundle\Entity\GenerationBoard gb WHERE gb.board = ' . $advertInfo->getBoard());
                        $boardGenerations = $query->getResult();
                    }
                    
                    if($advertInfo->getGeneration()){
                        $boardFilter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
                        $board = $manager->getRepository("DashboardCommonBundle:GenerationBoard")->findOneByBoard($boardFilter->getId());

                        $query = $manager->createQuery('SELECT gi FROM Dashboard\CommonBundle\Entity\GenerationItem gi WHERE gi.generation = ' . $advertInfo->getGeneration() . ' AND gi.board = ' . $board->getId());

                        $items = $query->getResult();

                        if($items){
                            foreach($items as $item){
                                if(false === $gasTypes->contains($item->getGasType())){
                                    $gasTypes->add($item->getGasType());
                                }
                            }
                        }
                    }
                    
                    if($advertInfo->getGasType()){
                        $boardFilter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
                        $board = $manager->getRepository("DashboardCommonBundle:GenerationBoard")->findOneByBoard($boardFilter->getId());

                        $query = $manager->createQuery('SELECT gi FROM Dashboard\CommonBundle\Entity\GenerationItem gi WHERE gi.generation = ' . $advertInfo->getGeneration() . ' AND gi.board = ' . $board->getId() . ' AND gi.gasType = ' . $advertInfo->getGasType());

                        $items = $query->getResult();
 
                        if($items){
                            foreach($items as $item){
                                if(false === $gearTypes->contains($item->getGearType())){
                                    $gearTypes->add($item->getGearType());
                                }
                            }
                        }
                    }
                    
                    if($advertInfo->getGearType()){
                        $boardFilter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
                        $board = $manager->getRepository("DashboardCommonBundle:GenerationBoard")->findOneByBoard($boardFilter->getId());

                        $query = $manager->createQuery('SELECT gi FROM Dashboard\CommonBundle\Entity\GenerationItem gi WHERE gi.generation = ' . $advertInfo->getGeneration() . ' AND gi.board = ' . $board->getId() . ' AND gi.gasType = ' . $advertInfo->getGasType() . ' AND gi.gearType = ' . $advertInfo->getGearType());

                        $items = $query->getResult();

                        if($items){
                            foreach($items as $item){
                                if(false === $transmittionTypes->contains($item->getTransmissionType())){
                                    $transmittionTypes->add($item->getTransmissionType());
                                }
                            }
                        }
                     }
                     
                    if($advertInfo->getTransmissionType()){
                        $boardFilter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
                        $board = $manager->getRepository("DashboardCommonBundle:GenerationBoard")->findOneByBoard($boardFilter->getId());

                        $query = $manager->createQuery('SELECT gi FROM Dashboard\CommonBundle\Entity\GenerationItem gi WHERE gi.generation = ' . $advertInfo->getGeneration() . ' AND gi.board = ' . $board->getId() . ' AND gi.gasType = ' . $advertInfo->getGasType() . ' AND gi.gearType = ' . $advertInfo->getGearType() . ' AND gi.transmissionType = ' . $advertInfo->getTransmissionType());

                        $items = $query->getResult();

                        if($items){
                            foreach($items as $item){
                                if($item->getItemModifications()){
                                    foreach($item->getItemModifications() as $modification){
                                        if(false === $modifications->contains($modification)){
                                            $modifications->add($modification);
                                        }
                                    }
                                }

                            }
                        }
                    }
                    
                    return $this->render('DashboardCommonBundle:Product:add/step12.html.twig', array("category" => $category, "settings" => $settings, "locale" => $locale,
                                                                                                     "advertInfo" => $advertInfo,
                                                                                                     "boards" => $boards,
                                                                                                     "boardGenerations" => $boardGenerations,
                                                                                                     "gasTypes" => $gasTypes,
                                                                                                     "gearTypes" => $gearTypes,
                                                                                                     "transmittionTypes" => $transmittionTypes,
                                                                                                     "modifications" => $modifications));
                break;
                
                case "boards":
                    $advertInfo->setYear($data); 
                    $advertInfo->setBoard(0);
                    $advertInfo->setGeneration(0);
                    $advertInfo->setGasType(0);
                    $advertInfo->setGearType(0);
                    $advertInfo->setTransmissionType(0);
                    $advertInfo->setModification(0);
                    $advertInfo->setRightWheel(0);
                    $advertInfo->setIsGas(0);
                    
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    $session->set('advertImages', $serializer->serialize(array(), 'json'));
                    
                    $query = $manager->createQuery('SELECT g FROM Dashboard\CommonBundle\Entity\Generation g WHERE g.yearFrom <= ' . $advertInfo->getYear() . ' AND g.yearTo >= ' . $advertInfo->getYear());
                    
                    $generations = $query->getResult();
                    $boards = new ArrayCollection();
                    
                    if($generations){
                        foreach($generations as $generation){
                            if($generation->getBoards()){
                                foreach($generation->getBoards() as $generationBoard){
                                    if(false === $boards->contains($generationBoard->getBoard())){
                                        $boards->add($generationBoard);
                                    }
                                }
                            }
                        }
                    }
                    
                    return $this->render('DashboardCommonBundle:Product:add/boards.html.twig', array("boards" => $boards, "locale" => $locale, "advertInfo" => $advertInfo));
                    
                break;
                
                case "generations":
                    $advertInfo->setBoard($data);
                    $advertInfo->setGeneration(0);
                    $advertInfo->setGasType(0);
                    $advertInfo->setGearType(0);
                    $advertInfo->setTransmissionType(0);
                    $advertInfo->setModification(0);
                    $advertInfo->setIsGas(0);
                    
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    $session->set('advertImages', $serializer->serialize(array(), 'json'));
                    
                    $query = $manager->createQuery('SELECT gb FROM Dashboard\CommonBundle\Entity\GenerationBoard gb WHERE gb.board = ' . $advertInfo->getBoard());
                    
                    $boards = $query->getResult();
                    
                    return $this->render('DashboardCommonBundle:Product:add/generations.html.twig', array("boards" => $boards, "locale" => $locale, "advertInfo" => $advertInfo));
                break;
            
                case "engines":
                    $advertInfo->setGeneration($data);
                    $advertInfo->setGasType(0);
                    $advertInfo->setGearType(0);
                    $advertInfo->setTransmissionType(0);
                    $advertInfo->setModification(0);
                    $advertInfo->setIsGas(0);
                    
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    $session->set('advertImages', $serializer->serialize(array(), 'json'));

                    $boardFilter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
                    $board = $manager->getRepository("DashboardCommonBundle:GenerationBoard")->findOneByBoard($boardFilter->getId());
                    
                    $query = $manager->createQuery('SELECT gi FROM Dashboard\CommonBundle\Entity\GenerationItem gi WHERE gi.generation = ' . $advertInfo->getGeneration() . ' AND gi.board = ' . $board->getId());
                    
                    $items = $query->getResult();
                    $gasTypes = new ArrayCollection();
                    
                    if($items){
                        foreach($items as $item){
                            if(false === $gasTypes->contains($item->getGasType())){
                                $gasTypes->add($item->getGasType());
                            }
                        }
                    }
                    
                    return $this->render('DashboardCommonBundle:Product:add/gasTypes.html.twig', array("gasTypes" => $gasTypes, "locale" => $locale, "advertInfo" => $advertInfo));
                    
                break;
                
                case "gears":
                    $advertInfo->setGasType($data);
                    $advertInfo->setGearType(0);
                    $advertInfo->setTransmissionType(0);
                    $advertInfo->setModification(0);
                    
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    $session->set('advertImages', $serializer->serialize(array(), 'json'));
                    
                    $boardFilter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
                    $board = $manager->getRepository("DashboardCommonBundle:GenerationBoard")->findOneByBoard($boardFilter->getId());
                    
                    $query = $manager->createQuery('SELECT gi FROM Dashboard\CommonBundle\Entity\GenerationItem gi WHERE gi.generation = ' . $advertInfo->getGeneration() . ' AND gi.board = ' . $board->getId() . ' AND gi.gasType = ' . $advertInfo->getGasType());
                    
                    $items = $query->getResult();
                    $gearTypes = new ArrayCollection();
                    
                    if($items){
                        foreach($items as $item){
                            if(false === $gearTypes->contains($item->getGearType())){
                                $gearTypes->add($item->getGearType());
                            }
                        }
                    }
                    
                    return $this->render('DashboardCommonBundle:Product:add/gearTypes.html.twig', array("gearTypes" => $gearTypes, "locale" => $locale, "advertInfo" => $advertInfo));
                    
                break;
                
                case "transmission":
                    $advertInfo->setGearType($data);
                    $advertInfo->setTransmissionType(0);
                    $advertInfo->setModification(0);
                    
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    $session->set('advertImages', $serializer->serialize(array(), 'json'));
                    
                    $boardFilter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
                    $board = $manager->getRepository("DashboardCommonBundle:GenerationBoard")->findOneByBoard($boardFilter->getId());
                    
                    $query = $manager->createQuery('SELECT gi FROM Dashboard\CommonBundle\Entity\GenerationItem gi WHERE gi.generation = ' . $advertInfo->getGeneration() . ' AND gi.board = ' . $board->getId() . ' AND gi.gasType = ' . $advertInfo->getGasType() . ' AND gi.gearType = ' . $advertInfo->getGearType());
                    
                    $items = $query->getResult();
                    $transmittionTypes = new ArrayCollection();
                    
                    if($items){
                        foreach($items as $item){
                            if(false === $transmittionTypes->contains($item->getTransmissionType())){
                                $transmittionTypes->add($item->getTransmissionType());
                            }
                        }
                    }
                    
                    return $this->render('DashboardCommonBundle:Product:add/transmittionTypes.html.twig', array("transmittionTypes" => $transmittionTypes, "locale" => $locale,"advertInfo" => $advertInfo));
                break;    
                
                case 'modification':
                    $advertInfo->setTransmissionType($data);
                    $advertInfo->setModification(0);
                    
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    $session->set('advertImages', $serializer->serialize(array(), 'json'));
                    
                    $boardFilter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
                    $board = $manager->getRepository("DashboardCommonBundle:GenerationBoard")->findOneByBoard($boardFilter->getId());
                    
                    $query = $manager->createQuery('SELECT gi FROM Dashboard\CommonBundle\Entity\GenerationItem gi WHERE gi.generation = ' . $advertInfo->getGeneration() . ' AND gi.board = ' . $board->getId() . ' AND gi.gasType = ' . $advertInfo->getGasType() . ' AND gi.gearType = ' . $advertInfo->getGearType() . ' AND gi.transmissionType = ' . $advertInfo->getTransmissionType());
                    
                    $items = $query->getResult();
                    $modifications = new ArrayCollection();
                    
                    if($items){
                        foreach($items as $item){
                            if($item->getItemModifications()){
                                foreach($item->getItemModifications() as $modification){
                                    if(false === $modifications->contains($modification)){
                                        $modifications->add($modification);
                                    }
                                }
                            }
                            
                        }
                    }
                    
                    return $this->render('DashboardCommonBundle:Product:add/modifications.html.twig', array("modifications" => $modifications, "locale" => $locale, "advertInfo" => $advertInfo));
                    
                break;    
                
                case 'setWheel':
                    $val = ($data == 'true') ? true : false;
                    $advertInfo->setRightWheel($val);
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    
                    return new Response("OK");
                break;
            
                case 'setGas':
                    $val = ($data == 'true') ? true : false;
                    $advertInfo->setIsGas($val);
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    
                    return new Response("OK");
                break;
                
                case 'setmodification':
                    $advertInfo->setModification($data);
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    
                    return new Response("OK");
                break;
                
                case "step2":
                    $categoryId = $advertInfo->getCategory();
                    $generationId = $advertInfo->getGeneration();
                    
                    $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
                    $generation = $manager->getRepository("DashboardCommonBundle:Generation")->find($generationId);
                    
                    return $this->render('DashboardCommonBundle:Product:add/step2.html.twig', array("category" => $category, "generation" => $generation, "locale" => $locale, "settings" => $settings, "advertInfo" => $advertInfo, "advertImages" => $advertImages));
                break;
            
                case 'setcolor':
                    $advertInfo->setColor($data);
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    
                    return new Response("OK");
                break;
            
                case 'deleteimage':
                    if(count($advertImages) > 0){
                        $tempAdvertImages = new ArrayCollection($advertImages);
                        foreach($advertImages as $image){
                            if($image->getName() == $data){
                                if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $image->getName())){
                                    $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $image->getName());
                                }
                                $tempAdvertImages->removeElement($image);
                            }
                        }
                        $advertImages = $tempAdvertImages->toArray();
                        $advertData = $serializer->serialize($advertImages, 'json');
                        $session->set('advertImages', $advertData);
                    }
                    return new Response("OK");
                break;    
            
                case "step3":
                    $categoryId = $advertInfo->getCategory();
                    $generationId = $advertInfo->getGeneration();
                    
                    $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
                    $generation = $manager->getRepository("DashboardCommonBundle:Generation")->find($generationId);
                    
                    $filters = array();
                    
                    foreach($advertFilters as $advertFilter){
                        $filters[$advertFilter->getId()] = 1;
                    }
                    
                    return $this->render('DashboardCommonBundle:Product:add/step3.html.twig', array("category" => $category, "generation" => $generation, "locale" => $locale, "settings" => $settings, "filters" => $filters));
                break;
            
                case "step4":
                    
                    if($request->request->get('filter')){
                        $advertFilters = array();
                        foreach($request->request->get('filter') as $filterId){
                            $filter = new AdvertFilter();
                            $filter->setId($filterId);
                            array_push($advertFilters, $filter);
                        }
                    }
                    
                    $advertData = $serializer->serialize($advertFilters, 'json');
                    $session->set('advertFilters', $advertData);
                    
                    $categoryId = $advertInfo->getCategory();
                    $generationId = $advertInfo->getGeneration();
                    
                    $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
                    $generation = $manager->getRepository("DashboardCommonBundle:Generation")->find($generationId);
                    $conditions = $manager->getRepository("DashboardCommonBundle:Shape")->findAll();
                    
                    return $this->render('DashboardCommonBundle:Product:add/step4.html.twig', array("category" => $category, "generation" => $generation, "conditions" => $conditions,"locale" => $locale, "settings" => $settings, "advertInfo" => $advertInfo));
                break;
            
                case "step5":
                    $advertInfo->setProbeg($request->request->get('probeg'));
                    $advertInfo->setCondition($request->request->get('condition'));
                    $advertInfo->setOwners($request->request->get('owners'));
                    $advertInfo->setVin($request->request->get('vin'));
                    $advertInfo->setDescription($request->request->get('description'));
                    $advertInfo->setPrice($request->request->get('price'));
                    $advertInfo->setNds(0);
                    $advertInfo->setNds($request->request->get('NDS'));
                    $advertInfo->setTorg(0);
                    $advertInfo->setTorg($request->request->get('torg'));
                    $advertInfo->setGarant(0);
                    $advertInfo->setGarant($request->request->get('garant'));
                    
                    $advertData = $serializer->serialize($advertInfo, 'json');
                    $session->set('advertInfo', $advertData);
                    
                    $baseCategory = $manager->getRepository("DashboardCommonBundle:Category")->find($advertInfo->getBaseCategory());
                    $category = $manager->getRepository("DashboardCommonBundle:Category")->find($advertInfo->getCategory());
                    $generation = $manager->getRepository("DashboardCommonBundle:Generation")->find($advertInfo->getGeneration());
                    $board = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
                    $color = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getColor());
                    $gas = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getGasType());
                    $gear = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getGearType());
                    $transmission = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getTransmissionType());
                    $modification = $manager->getRepository("DashboardCommonBundle:Modification")->find($advertInfo->getModification());
                    $shape = $manager->getRepository("DashboardCommonBundle:Shape")->find($advertInfo->getCondition());
                    
                    return $this->render('DashboardCommonBundle:Product:add/step5.html.twig', array("baseCategory" => $baseCategory,"category" => $category, "generation" => $generation, "locale" => $locale, "settings" => $settings, "advertImages" => $advertImages, "advertInfo" => $advertInfo,"shape" => $shape,"color" => $color, "board" => $board,"gas" => $gas,"gear" => $gear, "transmission" => $transmission,"modification" => $modification, "user" => $user,"role" => $user->getRoles()[0]));
                    
                break;
                
                case 'getcitycodes':
                    $query = $manager->createQuery('SELECT cc FROM Dashboard\CommonBundle\Entity\CityCode cc WHERE cc.code LIKE \'' . $data . '%\'');
                    $codes = $query->getResult();
                    
                    return $this->render('DashboardCommonBundle:Product:add/citycodes.html.twig', array("codes" => $codes, "locale" => $locale));
                break;
            
                case 'getcities':
                    $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\City c WHERE c.name LIKE \'' . $data . '%\'');
                    $cities = $query->getResult();
                    
                    return $this->render('DashboardCommonBundle:Product:add/cities.html.twig', array("cities" => $cities, "locale" => $locale));
                break;    
            }
        }
        
        return $this->render('DashboardCommonBundle:Product:add/add.html.twig', array("categories" => $categories, "settings" => $settings, "locale" => $locale));
    }
 
    /**
     * @Route("/account/ajaxloadfotos", name="ajaxloadfotos")
     */
    public function ajaxLoadFotosAction(Request $request)
    {
        $fm = new Filesystem();
        $error = 0;
        $settings = $this->getDoctrine()->getRepository("DashboardCommonBundle:Settings")->find(1);
        $extentions = array("jpg", "jpeg", "png", "gif", "JPG", "JPEG", "PNG", "GIF");
        $image = $request->files->all()["file"];
        
        $serializer = new Serializer(
            [new GetSetMethodNormalizer(), new ArrayDenormalizer()],
            [new JsonEncoder()]
        );    

        $session = new Session();
        $advertImages = ($session->get('advertImages')) ? $serializer->deserialize($session->get('advertImages'), 'Dashboard\CommonBundle\Model\AdvertImage[]', 'json') : array();

        if($image)
        {           
            $extention = $image->getClientOriginalExtension();
            $localImageName = rand(1, 99999999).'.'.$extention;
            
            if(in_array($extention, $extentions))
            {
                try
                {
                    $image->move('bundles/images/products',$localImageName);
                    
                    $simpleImage = $this->get('app.simpleimage');
                    $simpleImage->load('bundles/images/products/' . $localImageName);
                    $simpleImage->resizeToWidth(1024);
                    $simpleImage->save('bundles/images/products/' . $localImageName);
                    
                    $newImage = new AdvertImage();
                    $newImage->setName($localImageName);
                    array_push($advertImages, $newImage);
                    $advertData = $serializer->serialize($advertImages, 'json');
                    $session->set('advertImages', $advertData);
                    
                }
                catch (Symfony\Component\HttpFoundation\File\Exception\FileException $e)
                {
                    $error = $this->get('translator')->trans("Iekraušanas laikā radās kļūda. Mēģiniet vēlreiz. Derīgi paplašinājumi: jpg, jpeg, png, gif.");
                }
            }
            else 
            {
                $error = $this->get('translator')->trans("Iekraušanas laikā radās kļūda. Mēģiniet vēlreiz. Derīgi paplašinājumi: jpg, jpeg, png, gif.");
            }

            $data = $error ? array('error' => $error) : array('imageName' => $localImageName);
            
            return new Response(json_encode( $data ));
        }
    }
    
    /**
     * @Route("/account/createadvert/{isDraft}", name="createAdvert", defaults={"isDraft" : 0})
     * @Route("/{_locale}/account/createadvert/{isDraft}", name="createAdvertLocale", defaults={"_locale" : "es", "isDraft" : 0}, requirements={"_locale" : "es|ru"})
     */
    public function createAdvertAction($isDraft,Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $helper = $this->get('app.helpers');
        
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(), new GetSetMethodNormalizer(), new ArrayDenormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $session = new Session();
        $advertInfo = ($session->get('advertInfo')) ? $serializer->deserialize($session->get('advertInfo'), 'Dashboard\CommonBundle\Model\AdvertInfo', 'json') : NULL;
        $advertImages = ($session->get('advertImages')) ? $serializer->deserialize($session->get('advertImages'), 'Dashboard\CommonBundle\Model\AdvertImage[]', 'json') : array();
        $advertFilters = ($session->get('advertFilters')) ? $serializer->deserialize($session->get('advertFilters'), 'Dashboard\CommonBundle\Model\AdvertFilter[]', 'json') : array();
        
        if($advertInfo){
            $advertInfo->setContactName($request->request->get('contactName'));
            $advertInfo->setContactPhone($request->request->get('contactPhone'));
            $advertInfo->setContactEmail($request->request->get('contactEmail'));
            $advertInfo->setContactCity($request->request->get('contactCity'));
            $advertInfo->setContactCityCode($request->request->get('contactCityCode'));

            if($request->request->get('servicePack') && $request->request->get('servicePack') != 0){
                $advertInfo->setServicePack($request->request->get('servicePack'));
                $advertInfo->setServices(array());
            }elseif($request->request->get('service')){
                $advertInfo->setServicePack(0);
                $advertInfo->setServices($request->request->get('service'));
            }

            $advertData = $serializer->serialize($advertInfo, 'json');
            $session->set('advertInfo', $advertData);
            
            $category = $manager->getRepository("DashboardCommonBundle:Category")->find($advertInfo->getCategory());
            $baseCategory = $manager->getRepository("DashboardCommonBundle:Category")->find($advertInfo->getBaseCategory());
            $city = $manager->getRepository("DashboardCommonBundle:City")->findOneByName($advertInfo->getContactCity());
            $cityCode = $manager->getRepository("DashboardCommonBundle:CityCode")->find($advertInfo->getContactCityCode());
            
            //generate product name from category
            $productName = array();
            $this->generateCategoriesTree($category, $productName, $locale);
            $productTitle = implode(" ", array_reverse($productName));
            
            $product = new Product();
            $product->setUser($user);
            $product->setCategory($category);
            $product->setBaseCategory($baseCategory);
            $product->setAuthorName($advertInfo->getContactName());
            $product->setAuthorPhone($advertInfo->getContactPhone());
            $product->setAuthorEmail($advertInfo->getContactEmail());
            $product->setRegion($city->getRegion());
            $product->setCity($city);
            $product->setCityCode($cityCode);
            $product->setName($productTitle);
            $product->setTranslit($helper->translit($productTitle));
            $product->setIsActive(1);
            $product->setDateAdded(new \DateTime("now"));
            $product->setDateEdited(new \DateTime("now"));
            
            if(count($advertImages) > 0){
                foreach($advertImages as $image){
                    $productImage = new ProductFotos();
                    $productImage->setProduct($product);
                    $productImage->setFoto($image->getName());
                    $product->addFoto($productImage);
                }
            }
            
            if(count($advertFilters) > 0){
                foreach($advertFilters as $filterId){
                    $filter = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($filterId->getId());
                    if($filter){
                        $product->addFilter($filter);
                    }
                }
            }
            
            if($advertInfo->getServicePack()){
                $servicePack = $manager->getRepository("DashboardCommonBundle:Pack")->find($advertInfo->getServicePack());
                if($servicePack){
                    if($servicePack->getServices()){
                        foreach($servicePack->getServices() as $service){
                            $productService = new ProductService();
                            $productService->setProduct($product);
                            $productService->setService($service->getService());
                            $productService->setCount($service->getValue());
                            $productService->setIsActive(0);
                            $product->addService($productService);
                        }
                    }
                    
                    //add bill
                    $bill = new Bill();
                    $bill->setDateAdded(new \DateTime("now"));
                    $bill->setProduct($product);
                    $bill->setServicePack($servicePack);
                    $bill->setUser($user);
                    
                    $price = 0;
                    
                    if($servicePack->getPrices()){
                        foreach($servicePack->getPrices() as $packPrice){
                            if($packPrice->getCategory()->getId() == $baseCategory->getId()){
                                $price = $packPrice->getPrice();
                            }
                        }
                    }
                    
                    if($price == 0){
                        $price = $servicePack->getPrice();
                    }
                    
                    $bill->setPrice($price);
                }
            }       
            
            if(count($advertInfo->getServices()) > 0){
                $bill = new Bill();
                $bill->setDateAdded(new \DateTime("now"));
                $bill->setProduct($product);
                $bill->setUser($user);
                
                foreach($advertInfo->getServices() as $serviceId){
                    $service = $manager->getRepository("DashboardCommonBundle:Service")->find($serviceId);
                    if($service){
                        $productService = new ProductService();
                        $productService->setService($service);
                        $productService->setCount($service->getDays());
                        $productService->setIsActive(0);
                        $product->addService($productService);
                        $bill->addService($productService);
                        
                        $price = 0;
                    
                        if($service->getPrices()){
                            foreach($service->getPrices() as $servicePrice){
                                if($servicePrice->getCategory()->getId() == $baseCategory->getId()){
                                    $price = $servicePrice->getPrice();
                                }
                            }
                        }

                        if($price == 0){
                            $price = $service->getPrice();
                        }

                        $bill->setPrice($price);
                    }
                }
            }
            
            $info = new ProductInfo();
            $info->setProduct($product);
            $info->setDescription($advertInfo->getDescription());
            $info->setGarant($advertInfo->getGarant());
            $info->setIsGasBaloon($advertInfo->getIsGas());
            $info->setNds($advertInfo->getNds());
            $info->setOwners($advertInfo->getOwners());
            $info->setPrice($advertInfo->getPrice());
            $info->setProbeg($advertInfo->getProbeg());
            $info->setTorg($advertInfo->getTorg());
            $info->setVin($advertInfo->getVin());
            $info->setWheel($advertInfo->getRightWheel());
            $info->setYear($advertInfo->getYear());
            
            $board = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getBoard());
            $info->setBoard($board);
            
            $color = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getColor());
            $info->setColor($color);
            
            $condition = $manager->getRepository("DashboardCommonBundle:Shape")->find($advertInfo->getCondition());
            $info->setShape($condition);
            
            $gasType = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getGasType());
            $info->setGasType($gasType);
            
            $gearType = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getGearType());
            $info->setGearType($gearType);
            
            $generation = $manager->getRepository("DashboardCommonBundle:Generation")->find($advertInfo->getGeneration());
            $info->setGeneration($generation);
            
            $modification = $manager->getRepository("DashboardCommonBundle:Modification")->find($advertInfo->getModification());
            $info->setModification($modification);
            
            $transmissionType = $manager->getRepository("DashboardCommonBundle:FilterValue")->find($advertInfo->getTransmissionType());
            $info->setTransmissionType($transmissionType);
            
            $product->setInfo($info);
            
            if($isDraft){
                $product->setIsDraft(1);
            }
            
            $product->setIsConfirm(0);
            $product->setIsBlocked(0);
            
            $manager->persist($product);
            $manager->persist($bill);
            $manager->flush();
            
            //clear session
            $session->remove('advertInfo');
            $session->remove('advertImages');
            $session->remove('advertFilters');    
            
            if($isDraft){
                return $this->render('DashboardCommonBundle:Product:add/resultDraft.html.twig', array("locale" => $locale, "settings" => $settings));
            }else{
                return $this->render('DashboardCommonBundle:Product:add/result.html.twig', array("locale" => $locale, "settings" => $settings));
            }
        }
        
        return $this->createNotFoundException();
    }
    
    private function generateCategoriesTree($category, &$productName, $locale){
        
        if($category->getParent()){
            $categoryName = '';
            if(count($category->getTranslations()) > 0){
                foreach($category->getTranslations() as $translation){
                    if($translation->getLocale()->getId() == $locale->getId()){
                        $categoryName = $translation->getValue();
                    }
                }
            }else{
                $categoryName = $category->getTitle();
            }

            array_push($productName, $categoryName);
            $this->generateCategoriesTree($category->getParent(), $productName, $locale);
        }
    }
    
    /**
     * @Route("/{_locale}/account/advert/ajax/{action}/{productId}", name="advertPublicate", defaults={"_locale" : "es"}, requirements={"_locale" : "es|ru"})
     */
    public function advertPublicateAction($action, $productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $settings = $manager->getRepository("DashboardCommonBundle:Settings")->findOneBy(array("locale" => $locale));
        $product = $manager->getRepository("DashboardCommonBundle:Product")->findOneBy(array("id" => $productId, "user" => $user));
        
        $flashText = '';
        $flashTitle = '';
        
        if($product){
            switch($action){
                case 'publicate':
                    $product->setIsDraft(0);
                    if($settings->getIsModerate()){
                        $product->setIsConfirm(0);
                    }else{
                        $product->setIsConfirm(1);
                    }

                    $manager->persist($product);
                    $manager->flush();
                    
                    if($settings->getIsModerate()){
                        $flashTitle = $this->get('translator')->trans('Ваше объявление отправлено на модерацию');
                        $flashText = $this->get('translator')->trans(' Мы модерируем каждое объявление и проверим его в течении 30 минут. Потому что мы заботимся о безопасности наших пользователей и о качестве базы объявлений. Все объявления модерируются в порядке очереди. Если вы подаете объявление в ночное время, то оно будет промодерировано утром следующего дня.');
                    }else{
                        $flashTitle = $this->get('translator')->trans('Ваше объявление опубликовано');
                        $flashText = $this->get('translator')->trans('Благодарим за использование нашего сервиса. Вы можете воспользоваться платными услугами для увеличения шансов на продажу.');
                    }
                break;
                
                case 'archive':
                    $product->setIsActive(0);
                    $manager->persist($product);
                    $manager->flush();
                    
                    $flashTitle = $this->get('translator')->trans('Успешно');
                    $flashText = $this->get('translator')->trans('Объявление перемещено в архив');
                break;    
            
                case 'switchon':
                    $product->setIsActive(1);
                    $product->setDateAdded(new \DateTime("now"));
                    $product->setDateEdited(new \DateTime("now"));
                    
                    if($settings->getIsModerate()){
                        $product->setIsConfirm(0);
                    }else{
                        $product->setIsConfirm(1);
                    }
                    
                    $manager->persist($product);
                    $manager->flush();
                    
                    if($settings->getIsModerate()){
                        $flashTitle = $this->get('translator')->trans('Ваше объявление отправлено на модерацию');
                        $flashText = $this->get('translator')->trans(' Мы модерируем каждое объявление и проверим его в течении 30 минут. Потому что мы заботимся о безопасности наших пользователей и о качестве базы объявлений. Все объявления модерируются в порядке очереди. Если вы подаете объявление в ночное время, то оно будет промодерировано утром следующего дня.');
                    }else{
                        $flashTitle = $this->get('translator')->trans('Ваше объявление опубликовано');
                        $flashText = $this->get('translator')->trans('Объявление продлено и перемещено в раздел "Текущие".');
                    }
                    
                break;
                
                case 'unblocked':
                    $product->setIsBlocked(0);
                    $product->setCorrectReason(NULL);
                            
                    if($settings->getIsModerate()){
                        $product->setIsConfirm(0);
                    }else{
                        $product->setIsConfirm(1);
                    }

                    $manager->persist($product);
                    $manager->flush();
                    
                    if($settings->getIsModerate()){
                        $flashTitle = $this->get('translator')->trans('Ваше объявление отправлено на модерацию');
                        $flashText = $this->get('translator')->trans(' Мы модерируем каждое объявление и проверим его в течении 30 минут. Потому что мы заботимся о безопасности наших пользователей и о качестве базы объявлений. Все объявления модерируются в порядке очереди. Если вы подаете объявление в ночное время, то оно будет промодерировано утром следующего дня.');
                    }else{
                        $flashTitle = $this->get('translator')->trans('Ваше объявление разблокировано');
                        $flashText = $this->get('translator')->trans('Вы можете воспользоваться платными услугами для увеличения шансов на продажу.');
                    }
                break;
                
                case 'delete':
                    $this->deleteAdvert($product, $request);
                    
                    $flashTitle = $this->get('translator')->trans('Объявление удалено');
                    $flashText = $this->get('translator')->trans('Вы можете добавить другие объявления на нашу доску.');
                break;
            
                case 'addservice':
                    $session = new Session();
                    $data = explode(";", $productId);
                
                    $encoders = [new JsonEncoder()];
                    $normalizers = [new ObjectNormalizer(), new GetSetMethodNormalizer(), new ArrayDenormalizer()];
                    $serializer = new Serializer($normalizers, $encoders);
                    
                    $selectedServices = ($session->get('selectedServices')) ? $serializer->deserialize($session->get('selectedServices'), 'Dashboard\CommonBundle\Model\SelectedService[]', 'json') : array();
                    
                    $newService = new SelectedService();
                    $newService->setProduct($data[0]);
                    $newService->setService($data[1]);
                    $newService->setPrice($data[2]);
                    
                    array_push($selectedServices, $newService);
                                        
                    $servicesData = $serializer->serialize($selectedServices, 'json');
                    $session->set('selectedServices', $servicesData);
                    
                    $totalPrice = 0;
                    
                    foreach($selectedServices as $selectedService){
                        $totalPrice += $selectedService->getPrice();
                    }
                    
                    return new Response($totalPrice);
                    
                break;
                
                case 'removeservice':
                    $session = new Session();
                    $data = explode(";", $productId);
                   
                    $encoders = [new JsonEncoder()];
                    $normalizers = [new ObjectNormalizer(), new GetSetMethodNormalizer(), new ArrayDenormalizer()];
                    $serializer = new Serializer($normalizers, $encoders);
                    
                    $selectedServices = ($session->get('selectedServices')) ? $serializer->deserialize($session->get('selectedServices'), 'Dashboard\CommonBundle\Model\SelectedService[]', 'json') : array();
                    
                    $tmpSelectedServices = new ArrayCollection();
                    
                    foreach($selectedServices as $selectedService){
                        if($selectedService->getProduct() == $data[0] && $selectedService->getService() == $data[1]){
                            continue;
                        }else{
                            $tmpSelectedServices->add($selectedService);
                        }
                    }
                    
                    $totalPrice = 0;
                    
                    if(count($tmpSelectedServices) > 0){
                        $servicesData = $serializer->serialize($tmpSelectedServices->toArray(), 'json');
                        $session->set('selectedServices', $servicesData);
                        
                        foreach($tmpSelectedServices as $selectedService){
                            $totalPrice += $selectedService->getPrice();
                        }
                        
                    }else{
                        $session->remove('selectedServices');
                    }
                                        
                    return new Response($totalPrice);
                    
                break;
            }
        }
        
        
        $flashMessage = $this->renderView('DashboardCommonBundle:Flash:advert/actionResult.html.twig', array("settings" => $settings,"locale" => $locale, "text" => $flashText, "title" => $flashTitle));
        $this->addFlash('notice', $flashMessage);

        return new Response("OK");
    }
    
    private function deleteAdvert($product, $request){
        
        $manager = $this->getDoctrine()->getManager();
        $fm = new Filesystem();
        
        if($product->getMainfoto()){
            if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $product->getMainfoto()))
            {
                $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $product->getMainfoto());
            }
        }
        
        if($product->getFotos()){
            foreach($product->getFotos() as $foto){
                if($fm->exists($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto()))
                {
                    $fm->remove($request->server->get('DOCUMENT_ROOT') . '/bundles/images/products/' . $foto->getFoto());
                }
                
                $foto->setProduct(null);
                $manager->remove($foto);
            }
        }
        
        if($product->getOrders()){
            foreach($product->getOrders() as $order){
                $order->setUserReceived(null);
                $order->setUserSended(null);
                $order->setProduct(null);
                
                $manager->remove($order);
            }
        }
        
        if($product->getComplaint()){
            foreach($product->getComplaint() as $complaint){
                $complaint->setProduct(null);
                $manager->remove($complaint);
            }
        }
        
        if($product->getMessages()){
            foreach($product->getMessages() as $message){
                $message->setProduct(null);
                $manager->remove($message);
            }
        }
        
        if($product->getFilters()){
            foreach($product->getFilters() as $filter){
                $filter->removeProduct($product);
                $manager->persist($filter);
            }
        }
        
        if($product->getService()){
            foreach($product->getService() as $service){
                if($service->getBills()){
                    foreach($service->getBills() as $bill){
                        $bill->setProduct(null);
                        $bill->removeService($service);
                        $manager->remove($bill);
                    }
                }
                
                $service->setProduct(null);
                $manager->remove($service);
            }
        }
        
        $bills = $manager->getRepository("DashboardCommonBundle:Bill")->findBy(array("product" => $product));
        if($bills){
            foreach($bills as $bill){
                $bill->setProduct(null);
                $manager->remove($bill);
            }
        }
        
        $info = $product->getInfo();
        if($info){
            $info->setProduct(null);
            $manager->remove($info);
        }
        
        $manager->remove($product);
        $manager->flush();
        
        return true;
    }
    
    /**
     * @Route("/{_locale}/account/getcategoryfilters/{categoryId}", name="getcategoryfilters", defaults={"categoryId" : "0", "_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function getCategoryFiltersAction($categoryId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        if($user && $categoryId)
        {
            $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
        }
        
        return $this->render('DashboardCommonBundle:Product:filters.html.twig', array("category" => $category,"locale" => $locale));
    }
    
    private function renderFiltersView($categoryId, $filters, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        return $this->renderView("DashboardCommonBundle:Product:showfilters.html.twig", array("category" => $category, "filters" => $filters,"locale" => $locale));
    }
    
    /**
     * @Route("/{_locale}/account/getsubcategories/{categoryId}", name="getsubcategories", defaults={"categoryId" : "0", "_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function getSubcategories($categoryId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        if($user && ($categoryId == 0))
        {
            $query = $manager->createQuery('SELECT c FROM Dashboard\CommonBundle\Entity\Category c WHERE c.parent IS NULL' );
            $categories = $query->getResult();
            return $this->render("DashboardCommonBundle:User:category.html.twig", array("items" => $categories, "locale" => $locale));
        }
        
        if($user && ($categoryId != 0))
        {
            $category = $manager->getRepository("DashboardCommonBundle:Category")->find($categoryId);
            return $this->render("DashboardCommonBundle:Product:subcategories.html.twig", array("category" => $category, "locale" => $locale));
        }
        else
        {
            return new Response("error");
        }
    }
    
    /**
     * @Route("/{_locale}/getsearchfilters/{categoryName}", name="getsearchfilters", defaults={"categoryName" : "0", "_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function getSearchFiltersAction($categoryName, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        if($categoryName)
        {
            $category = $manager->getRepository("DashboardCommonBundle:Category")->findOneByName($categoryName);
        }
        
        return $this->render('DashboardCommonBundle:Product:categoryfilters.html.twig', array("category" => $category,"locale" => $locale));
    }
    
    /**
     * @Route("/account/switchonproduct/{productId}", name="account_switchonproduct", defaults={"productId" : "0"})
     * @Route("/{_locale}/account/switchonproduct/{productId}", name="account_switchonproductLocale", defaults={"productId" : "0", "_locale" : "lv"},requirements={"_locale" : "lv|ru"})
     */
    public function switchonProductAction($productId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        if($productId)
        {
            $product = $manager->getRepository("DashboardCommonBundle:Product")->findOneBy(array("id" => $productId,
                                                                                                 "user" => $user,
                                                                                                 "isActive" => 0,
                                                                                                 "isConfirm" => 1,
                                                                                                 "isBlocked" => 0));
            if($product)
            {
                $product->setDateAdded(new \DateTime("now"));
                $product->setIsActive(1);
                $manager->persist($product);
                $manager->flush();
                
                $this->addFlash(
                                'notice',
                                '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . 
                                $this->get('translator')->trans('<strong>Veiksmīgi!</strong> Jūsu reklāma ir atjaunota.') . '</div>'
                            );
            }
            else
               $this->addFlash(
                    'notice',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> ' .
                    $this->get('translator')->trans('<strong>Kļūda!</strong> Šo reklāmu nevar nodot tālāk. Varbūt tas nepastāv vai tas jums nepieder.') . '</div>'
                ); 
            if($locale->getIsDefault())
            {
                return $this->redirectToRoute('account_products_current');
            }
            else
            {
                return $this->redirectToRoute("account_products_currentLocale", array("_locale" => $locale->getCode()));
            }
            
        }
        else
        {
            return $this->createNotFoundException();
        }
    }
    
    /**
     * @Route("/check", name="check")
     */
    public function checkAction()
    {
        $manager = $this->getDoctrine()->getManager();
        
        $query = $manager->createQuery("SELECT p FROM DashboardCommonBundle:Product p WHERE p.isConfirm = 1");
        
        try
        {
            $products = $query->getResult();
        }
        catch(\Doctrine\ORM\NoResultException $e) {
                
            $products = 0;
        }

        $date = new \DateTime("now");
        
        if($products)
        {
            foreach($products as $product)
            {
                $productService = $product->getService();
                if($productService)
                {
                    $diff = $date->diff($productService->getDateEnd());
                    
                    if($diff->invert == 1)
                    {
                       $productService->setProduct(null);
                       $productService->setService(null);
                       $product->setService(null);
                       $manager->remove($productService);
                       
                       $product->setViewpremium(false);
                       $product->setViewcommon(true);
                       $product->setViewselected(false);
                    }
                }
                
                $diff = $date->diff($product->getDateAdded());
                
                if((($diff->d * 24) + $diff->h) > (30 * 24))
                {
                    $product->setIsActive(0);
                }
                
                $nowDay = (int)$date->format("d");
                $editedDay = (int)$product->getDateEdited()->format("d");
                
                if($nowDay != $editedDay)
                {
                    $product->setViewsPerDate(0);
                }
                
                /*$helpers = $this->get('app.helpers');
                $product->setTranslit($helpers->translit($product->getName()));*/
                
                $product->setDateEdited(new \DateTime("now"));
                $manager->persist($product);
            }
            
            $manager->flush();
        }
        
        return new Response(1);
    }
    /**
     * @Route("/checkrating", name="check_rating")
     */
    public function checkRating()
    {
        $manager = $this->getDoctrine()->getManager();
        
        $users = $manager->getRepository("DashboardCommonBundle:User")->findAll();
        
        if($users)
        {
            foreach($users as $user)
            {
                $plusReviews = 0;
                $reviews = $user->getTargetReviews();
                
                if(count($reviews) > 0)
                    {
                        foreach($reviews as $review)
                        {
                            if($review->getStatus() == 1)
                            {
                                $plusReviews++;
                            }
                        }
                        
                        $userRating = ($plusReviews / count($reviews)) * 100;
                    }
                    else
                       $userRating = 0; 
                    
                    $userinfo = $user->getUserinfo();
                    $userinfo->setRating(floor($userRating));
                    
                    $manager->persist($userinfo);
                    
            }
            
            $manager->flush();
        } 
        
        return new Response(2);
    }
}

