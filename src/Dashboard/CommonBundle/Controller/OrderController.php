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

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\UserInfo;
use Dashboard\CommonBundle\Entity\UserPurse;
use Dashboard\CommonBundle\Entity\Product;
use Dashboard\CommonBundle\Entity\ProductFotos;
use Dashboard\CommonBundle\Entity\ProductOptions;
use Dashboard\CommonBundle\Entity\Message;

use Dashboard\CommonBundle\Form\Type\UserType;
use Dashboard\CommonBundle\Form\Type\MessageType;

use Dashboard\CommonBundle\Form\DataTransformer\ProductToNumberTransformer;

class OrderController extends Controller
{
    /**
     * @Route("/account/addorder", name="account_addorder")
     */
    public function addorderAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
    }
}


