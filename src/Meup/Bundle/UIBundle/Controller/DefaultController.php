<?php

namespace Meup\Bundle\UIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MeupUIBundle:Default:index.html.twig', array('name' => $name));
    }
}
