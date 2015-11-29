<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use LogiCorpoBundle\Entity\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use LogiCorpoBundle\Form\ServiceType;
use LogiCorpoBundle\Form\CommandeType;
use LogiCorpoBundle\Entity\Commande;
use LogiCorpoBundle\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Collection;

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

	public function nouvelleCommandeAction($id, Service $service, Request $req, Utilisateur $user = null) {
		if($user === null) $user = $this->getUser();
		$produitRep = $this->getDoctrine()->getManager()->getRepository('LogiCorpoBundle:Produit');
		$lastProducts = $produitRep->getLastOrder($user,5,4);

		$commande = new Commande();
		/*
		$form = $this->createForm('produits', $commande)
				->add('Commander', 'submit');
		*/
		$form = $this->get('form.factory')->createBuilder('form', $commande)
		     ->add('produits', 'collection', array(
		     	'type' => 'produits_commande',
				'allow_add'          => true,
				'allow_delete'       => true,
				'cascade_validation' => true,
				'by_reference'       => false
		     ))
			 ->add('Commander', 'submit')
			 ->getForm();

		$form->handleRequest($req);

		if($form->isSubmitted()) {
			$commande
				->setUtilisateur($user)
				->setService($service);

			if($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($commande);
				try {
					$em->flush();
					$req->getSession()->getFlashBag()->add('success', "La commande $commande a bien été enregistré.");
				} catch(\Exception $e) {
					$req->getSession()->getFlashBag()->add('err', "Erreur, la commande $commande n'a pas été enregistré.");
				}
			}
		}
		return $this->render('LogiCorpoBundle:Service:nouvelleCommande.html.twig', [
			'lastProductsCommande' => $lastProducts,
			'form' => $form->createView()
		]);
	}
}