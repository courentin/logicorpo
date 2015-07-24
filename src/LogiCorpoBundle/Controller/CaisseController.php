<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LogiCorpoBundle\Entity\Transaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
* @Security("has_role('ROLE_TRESORIER')")
*/
class CaisseController extends Controller
{
	public function indexAction($max = 25)
	{
		$rep = $this->getDoctrine()->getRepository('LogiCorpoBundle:Transaction');
		$query = $rep->createQueryBuilder('t')
					 ->orderBy('t.date','DESC')
					 ->setMaxResults($max)
					 ->getQuery();
		$transactions = $query->getResult();

		return $this->render('LogiCorpoBundle:Caisse:index.html.twig', [
			'transactions' => $transactions,
			'max' => sizeof($transactions),
			'solde' => $rep->getSoldes()
		]);
	}

	public function nouvelleTransactionAction(Request $req)
	{
		$transaction = new Transaction();
		$transaction->setUtilisateur($this->getUser())
					->setMoyenPaiement('espece');
		$form = $this->get('form.factory')->createBuilder('form', $transaction)
			->add('type', 'choice', [
				'choices' => [
					'mouvement_banque' => 'Mouvement banque',
					'erreur_caisse'    => 'Erreur de caisse'
				]
			])
			->add('solde','money',[
				'label' => 'Montant'
			])
			->add('Enregistrer', 'submit')
			->getForm();
		$form->handleRequest($req);

		if($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($transaction);
			$em->flush();

			$req->getSession()->getFlashBag()->add('succes', 'La transaction (#'.$transaction->getId().' - '.$transaction->getSolde().' €) a bien été enregistrée');
			return $this->redirect($this->generateUrl('lc_caisse_home'));
		}

		return $this->render('LogiCorpoBundle:Caisse:nouvelleTransaction.html.twig', ['form' => $form->createView()]);
	}

	public function chercherAction(Request $req) {
		$data = ['utilisateur' => null];
		$form = $this->createFormBuilder($data)
			->add('type', 'choice', [
				'choices' => [
					'mouvement_banque' => 'Mouvement banque',
					'erreur_caisse'    => 'Erreur de caisse',
					'approvisionnement'=> 'Approvisionnement',
					'mouvement_carte'  => 'Approvisionnement compte',
					'achat_commande'   => 'Achat/Commande',
					'remboursement'    => 'Remboursement'
				],
				'empty_value' => 'Tous les types',
				'empty_data' => null,
				'data' => null,
				'required'    => false
			])
			->add('du','date', [
				'data' => new \DateTime()
			])
			->add('au','date', [
				'data' => new \DateTime(),
				'constraints' => [
					'moreThanOrEqual' => '18/15/65',
					'lessThanOrEqual' => new \DateTime()
				]
			])
			->add('utilisateur','entity', [
				'class'       => 'LogiCorpoBundle:Utilisateur',
				'empty_value' => 'Tous les utilisateurs',
				'empty_data'  => null,
				'required'    => false
			])
			->add('ref','number', [
				'required' => false
			])
			->add('rechercher', 'submit')
			->getForm();

		$form->handleRequest($req);

		if($form->isValid()) {
			
		}
		return $this->render('LogiCorpoBundle:Caisse:chercher.html.twig', ['form' => $form->createView()]);
	}

	public function corrigerAction() {
		
	}
}
