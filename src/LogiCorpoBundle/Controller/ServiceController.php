<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use LogiCorpoBundle\Entity\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use LogiCorpoBundle\Form\ServiceType;

class ServiceController extends Controller
{
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$services = $em->getRepository('LogiCorpoBundle:Service')->findBy([], ['debut' => 'ASC']);
		return $this->render('LogiCorpoBundle:Service:index.html.twig', ['services' => $services]);
	}

	public function effectuerChoiceAction()
	{
		//lister tout les services d'ajd
		$em = $this->getDoctrine()->getManager();
		$serviceRep = $em->getRepository('LogiCorpoBundle:Service');

		$services = $serviceRep->getNextServices();

		return $this->render('LogiCorpoBundle:Service:effectuerChoice.html.twig', ['services' => $services]);
	}

	public function effectuerAction($id, Service $service)
	{
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery("SELECT c FROM LogiCorpoBundle:Commande c JOIN LogiCorpoBundle:ProduitsCommande p WHERE c.service = :service")
			->setParameter('service', $service);
			dump($query->getResult());
		return $this->render('LogiCorpoBundle:Service:effectuer.html.twig',[
			'service'   => $service,
			'commandes' => $query->getResult()
		]);
	}

	public function nouveauAction() {
		$service = new Service();
		$form = $this->createForm(new ServiceType($service), $service, ['submit' => 'Ajouter']);

		return $this->render('LogiCorpoBundle:Service:nouveau.html.twig', ['form' => $form]);
	}

	public function nouvelleCommandeAction() {
		$produitRep = $this->getDoctrine()->getManager()->getRepository('LogiCorpoBundle:Produit');
		$lastProducts = $produitRep->getLastOrder($this->getUser(),5,4);

		return $this->render('LogiCorpoBundle:Service:nouvelleCommande.html.twig', ['lastProductsCommande' => $lastProducts]);
	}
}
