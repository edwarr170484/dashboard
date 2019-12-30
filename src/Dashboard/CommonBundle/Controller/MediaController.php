<?php
namespace Dashboard\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller{
    /**
     * @Route("/media", name="media")
     */
    public function mediaAction(Request $request)
    {
        return $this->render('DashboardCommonBundle:Media:page.html.twig');
    }
}

