<?php

namespace Mallapp\GeobitsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MallappGeobitsBundle:Default:index.html.twig');
    }
}
