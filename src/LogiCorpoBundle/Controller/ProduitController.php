<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LogiCorpoBundle\Entity\Produit;
use LogiCorpoBundle\Entity\Categorie;
use LogiCorpoBundle\Form\CategorieType;
use LogiCorpoBundle\Form\ProduitType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProduitController extends Controller
{
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$categorieRep = $em->getRepository('LogiCorpoBundle:Categorie');
		$categories = $categorieRep->findBy([], ["ordre" => "ASC"]);

		$supplementRep = $em->getRepository('LogiCorpoBundle:Supplement');
		$supplements   = $supplementRep->findAll();

		return $this->render('LogiCorpoBundle:Produit:index.html.twig', [
			'categories'    => $categories,
			'supplements' => $supplements
		]);
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

	/**
	* @Security("has_role('ROLE_SECRETAIRE')")
	*/
	public function nouveauAction(Request $req) {
		$produit = new Produit();

		$form = $this->createForm('produit', $produit)
		             ->add('Ajouter', 'submit');

		$form->handleRequest($req);

		if($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($produit);
			$em->flush();

			$req->getSession()->getFlashBag()->add('succes', "Le produit $produit a bien été ajouté");
			return $this->redirectToRoute('lc_produit_home');
		}

		return $this->render('LogiCorpoBundle:Produit:nouveau.html.twig', ['form' => $form->createView()]);
	}

	/**
	* @Security("has_role('ROLE_SECRETAIRE')")
	*/
	public function editAction(Request $req, $id, Produit $produit) {
		$form = $this->createForm('produit', $produit)
		             ->add('Modifier', 'submit');

		$form->handleRequest($req);

		if($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$req->getSession()->getFlashBag()->add('succes', "Le produit $produit a bien été modifié");
			return $this->redirectToRoute('lc_produit_home');
		}

		return $this->render('LogiCorpoBundle:Produit:editer.html.twig', ['form' => $form->createView()]);
	}

	/**
	* @Security("has_role('ROLE_SECRETAIRE')")
	*/
	public function nouvelleCategorieAction(Request $req) {
		$categorie = new Categorie();
		$form = $this->createForm('produit_categorie', $categorie)
		             ->add('Ajouter', 'submit');
		
		$form->handleRequest($req);

		if($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($categorie);
			$em->flush();

			$req->getSession()->getFlashBag()->add('succes', "La catégorie $categorie a bien été ajouté");
			return $this->redirectToRoute('lc_produit_home');
		}
		return $this->render('LogiCorpoBundle:Produit:nouvelleCategorie.html.twig', ['form' => $form->createView()]);

	}

	/**
	* @Security("has_role('ROLE_SECRETAIRE')")
	*/
	public function editerCategorieAction(Request $req, Categorie $categorie, $id) {

		$form = $this->createForm('produit_categorie', $categorie)
		             ->add('Modifier', 'submit');

		$form->handleRequest($req);

		if($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$req->getSession()->getFlashBag()->add('succes', "La catégorie $categorie a bien été modifié");
			return $this->redirectToRoute('lc_produit_home');
		}
		return $this->render('LogiCorpoBundle:Produit:editerCategorie.html.twig', ['form' => $form->createView()]);
	}
}
