<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use LogiCorpoBundle\Entity\Service;
use Symfony\Component\HttpFoundation\JsonResponse;

class ServiceController extends Controller
{
	public function indexAction()
	{
		return $this->render('LogiCorpoBundle:Default:index.html.twig');
	}

	public function effectuerChoiceAction()
	{
		//lister tout les services d'ajd
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('LogiCorpoBundle:Service');

		$services = $repository->atDay();

		return $this->render('LogiCorpoBundle:Service:effectuerChoice.html.twig', ['services' => $services]);
	}

	public function effectuerAction($id, Service $service)
	{

		return $this->render('LogiCorpoBundle:Service:effectuer.html.twig',['service' => $service]);
	}

	public function effectuerApiAction($id, Service $service) {
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT c, u FROM  LogiCorpoBundle:Commande c JOIN c.utilisateur u');

		$commandes = $query->getArrayResult();
		$commandes = json_encode($commandes);
		
		$response = new JsonResponse();
		$response->setData($commandes);
		return $response;
	}
}
