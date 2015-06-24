<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LogiCorpoBundle:Default:index.html.twig');
    }
}
