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

use Dashboard\CommonBundle\Entity\Region;
use Dashboard\CommonBundle\Entity\City;

class RegionController extends Controller
{
    /**
     * @Route("/region/{regionId}", name="region_getcity")
     * @Route("/{_locale}/region/{regionId}", name="region_getcityLocale", defaults={"_locale" : "lv"}, requirements={"_locale" : "lv|ru"})
     */
    public function getRegionCitiesAction($regionId, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $answer = "";
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        
        $query = $manager->createQuery("SELECT c FROM DashboardCommonBundle:City c WHERE c.region = " . $regionId . " ORDER BY c.name ASC");
        
        try{
                $cities = $query->getResult();
            }
            catch(\Doctrine\ORM\NoResultException $e) {
                $cities = 0;
            }
            
        //$answer .= '<div class="select-option" data-value="0">Вся Латвия</div>';
        
        if($cities)
        {
            foreach($cities as $city)
            {
                if($city->getTranslations())
                {
                    foreach($city->getTranslations() as $translation)
                    {
                        if($translation->getLocale()->getId() == $locale->getId())
                        {
                            $answer .= '<div class="select-option" data-value="' . $city->getId() . '">' . $translation->getValue() . '</div>';
                        }
                    }
                }
                else
                    $answer .= '<div class="select-option" data-value="' . $city->getId() . '">' . $city->getName() . '</div>';
            }
            return new Response($answer);
        }
        else
            return new Response($answer);
    }
}

