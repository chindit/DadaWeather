<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller{

    public function indexAction(){
        // replace this example code with whatever you need
        return $this->render('CoreBundle::index.html.twig');
    }
}
