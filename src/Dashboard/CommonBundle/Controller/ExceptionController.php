<?php

namespace Dashboard\CommonBundle\Controller;

use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    public function exceptionAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null, $format = 'html', $embedded = false)
    {
        $manager = $this->getDoctrine()->getManager();
        $locale = $manager->getRepository("DashboardCommonBundle:Locale")->findOneBy(array("code" => $request->getLocale()));
        $page = $manager->getRepository("DashboardCommonBundle:Page")->findOneBy(array("route" => "notfound", "locale" => $locale));
        
        return $this->render('DashboardCommonBundle:Common:notfound.html.twig', array("page" => $page));
    }

}

