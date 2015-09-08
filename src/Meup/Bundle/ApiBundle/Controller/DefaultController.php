<?php

namespace Meup\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MeupApiBundle:Default:index.html.twig', array('name' => $name));
    }
}
