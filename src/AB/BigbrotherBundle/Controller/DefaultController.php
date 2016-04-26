<?php

namespace AB\BigbrotherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ABBigbrotherBundle:Default:index.html.twig');
    }
}
