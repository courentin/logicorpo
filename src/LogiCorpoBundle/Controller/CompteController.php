<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LogiCorpoBundle\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CompteController extends Controller
{
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT t FROM LogiCorpoBundle:Transaction t WHERE t.utilisateur = :u');
		$query->setParameter('u',$this->getUser());
		$transactions = $query->getResult();

		$canUpgrade = false;
		if(!$this->get('security.context')->isGranted('ROLE_MEMBRE'))
			$canUpgrade = $this->getUser()->canPay();

		return $this->render('LogiCorpoBundle:Compte:index.html.twig', ['transactions' => $transactions, 'canUpgrade' => $canUpgrade]);
	}

	/**
	 * @Security("!has_role('ROLE_MEMBRE')")
	 */
	public function upgradeAction() {
		define("__PRICE__", 5);



		$user = $this->getUser();
		$u_solde = $user->getSolde();
		if($u_solde >= __PRICE__) {
			$user->setSolde($u_solde-__PRICE__);

			$em = $this->getDoctrine()->getManager();
			$repository = $em->getRepository('LogiCorpoBundle:Rang');
			$produits = $repository->findAll();

			$user->setRang( );
			$user->flush();
		}
		return $this->redirect($this->generateUrl('lc_compte_home'));
	}
}
