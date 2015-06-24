<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LogiCorpoBundle\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;

class CompteController extends Controller
{
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT t FROM LogiCorpoBundle:Transaction t WHERE t.utilisateur = :u');
		$query->setParameter('u',$this->getUser());
		$transactions = $query->getResult();
		return $this->render('LogiCorpoBundle:Compte:index.html.twig', ['transactions' => $transactions]);
	}
}
