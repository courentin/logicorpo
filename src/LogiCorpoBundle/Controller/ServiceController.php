<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LogiCorpoBundle\Entity\Service;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use LogiCorpoBundle\Form\ProduitsCommandeType;
use Symfony\Component\HttpFoundation\JsonResponse;
use LogiCorpoBundle\Form\ServiceType;
use LogiCorpoBundle\Entity\Commande;
use LogiCorpoBundle\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
		return $this->render('LogiCorpoBundle:Service:effectuer.html.twig',[
			'service'   => $service,
			'commandes' => $query->getResult()
		]);
	}

	/**
	 * @ParamConverter("service", class="LogiCorpoBundle:Service", options={"id" = "idService"})
	 */
	public function commandeJsonAction(Service $service, $idCommande)
	{
		$em = $this->getDoctrine()->getManager();
		$commandeRep = $em->getRepository('LogiCorpoBundle:Commande');

		if($idCommande == 0) {
			$results = $commandeRep
			->findBy(
				['service' => $service],
				['date'    => 'ASC']
			);
		} else {
			$results = $commandeRep->find($idCommande);
			if(!$results) {
				throw $this->createNotFoundException(
					"Pas de commande pour l'id $idCommande"
				);
			}
		}
/*
		$serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
		JsonEncoder()));
		$json = $serializer->serialize($results, 'json');
*/
		$response = new JsonResponse();
		$response->setData([
			'succes' => true,
			'datas'  => $results
		]);
		return $response;
	}

	public function nouveauAction() {
		$service = new Service();

		$form = $this->createForm(ServiceType::class, $service)
			         ->add('Ajouter', SubmitType::class);


		return $this->render('LogiCorpoBundle:Service:nouveau.html.twig', ['form' => $form->createView()]);
	}

	/**
	 * @ParamConverter("service", class="LogiCorpoBundle:Service", options={"id" = "idService"})
	 */
	public function nouvelleCommandeAction(Service $service, Request $req, Utilisateur $user = null) {
		if($user === null) $user = $this->getUser();
		$produitRep = $this->getDoctrine()->getManager()->getRepository('LogiCorpoBundle:Produit');
		$lastProducts = $produitRep->getLastOrder($user,5,4);

		$commande = new Commande();
		$form = $this->createFormBuilder($commande)
		     ->add('produits', CollectionType::class, [
		    	'entry_type' => ProduitsCommandeType::class,
				'allow_add'          => true,
				'allow_delete'       => true,
				'by_reference'       => false,
				'label'              => ''
		     ])
			 ->add('Commander', SubmitType::class)
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