<?php

namespace Dada\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller{

    public function indexAction(){
        return $this->render('DadaCoreBundle::index.html.twig');
    }
}
