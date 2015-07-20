<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LogiCorpoBundle\Entity\Produit;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProduitController extends Controller
{
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('LogiCorpoBundle:Produit');
		$produits = $repository->getProductsByCategory();

		return $this->render('LogiCorpoBundle:Produit:index.html.twig', ['produits' => $produits]);
	}

	public function jsonListAction()
	{
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('LogiCorpoBundle:Produit');
		$produits = $repository->getProductsByCategory();

		$response = new JsonResponse();
		$response->setData($produits);

		return $response;
	}

	public function nouveauAction(Request $req)
	{
		$produit = new Produit();
		$form = $this->get('form.factory')->createBuilder('form', $produit)
			->add('categorie', 'choice', [
				'choices' => $this->getDoctrine()->getManager()->getRepository('LogiCorpoBundle:Produit')->getAllCategories(),
				'empty_value' => 'Choisir une catégorie'
			])
			->add('libelle', 'text',['label' => 'Libellé'])
			->add('dispo', 'checkbox', ['label' => 'Disponible'])
			->add('stock', 'number')
			->add('prixVente','money', ['label' => 'Prix de vente'])
			->add('prixAchat', 'money', ['label' => 'Prix d\'achat'])
			->add('reduction', 'checkbox', ['label' => 'Appliquer les réductions'])
			->add('supplementDisponible', 'entity', [
				'class' => 'LogiCorpoBundle:Supplement',
				'multiple' => true,
				'label' => 'Suppléments disponibles'
			])
			->add('Ajouter', 'submit')
			->getForm();

		$form->handleRequest($req);

		if($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($produit);
			$em->flush();

			$req->getSession()->getFlashBag()->add('succes', 'Le produit "'.$produit.'" a bien été ajouté');
			return $this->redirect($this->generateUrl('lc_produit_home'));
		}

		return $this->render('LogiCorpoBundle:Produit:nouveau.html.twig', ['form' => $form->createView()]);
	}
}
