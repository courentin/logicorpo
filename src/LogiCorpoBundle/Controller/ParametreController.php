<?php
namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ParametreController extends Controller
{
	public function indexAction() {
		return $this->render('LogiCorpoBundle:Parametre:index.html.twig');
	}
}