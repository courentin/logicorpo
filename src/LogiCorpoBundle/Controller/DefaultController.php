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

	public function menuAction()
	{
		$items = [
			[
				'label' => 'Passer commande',
				'route' => 'lc_compte_home'
			],
			'|',
			[
				'label' => 'akka',
				'route' => 'lc_compte_home'
			]
		];
		return $this->render('::menu.html.twig', ['items' => $items]);
	}
}
