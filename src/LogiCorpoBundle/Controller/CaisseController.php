<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
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
			->add('montant','money')
			->add('Enregistrer', 'submit')
			->getForm();
		$form->handleRequest($req);

		if($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($transaction);
			$em->flush();

			$req->getSession()->getFlashBag()->add('succes', "La transaction ($transaction) a bien été enregistrée");
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
					'approvisionnement'=> 'Approvisionnement stock',
					'mouvement_carte'  => 'Approvisionnement compte',
					'achat_commande'   => 'Achat/Commande',
					'remboursement'    => 'Remboursement'
				],
				'empty_value' => 'Tous les types',
				'empty_data' => null,
				'required'    => false
			])
			->add('du','date', [
				'data' => new \DateTime()
			])
			->add('au','date', [
				'data' => new \DateTime(),
				'constraints' => [
				]
			])
			->add('utilisateur','entity', [
				'class'         => 'LogiCorpoBundle:Utilisateur',
				'empty_value'   => 'Tous les utilisateurs',
				'empty_data'    => null,
				'required'      => false,
				'query_builder' => function(EntityRepository $er) {
					return $er->createQueryBuilder('u')
							  ->orderBy('u.nom');
				}
			])
			->add('ref','number', [
				'required' => false
			])
			->add('rechercher', 'submit')
			->getForm();
		$form->handleRequest($req);

		if($form->isValid()) {
			$repository = $this->getDoctrine()->getRepository('LogiCorpoBundle:Transaction');
			$query = $repository->createQueryBuilder('t')
								->orderBy('t.date');
			
			if($ref = $form->get('ref')->getData()) {
				$query->where('t.id = :ref')
					  ->setParameter('ref',$ref);
			} else {
				$query->where('t.date BETWEEN :du AND :au')
					  ->setParameter('du',$form->get('du')->getData())
					  ->setParameter('au',$form->get('au')->getData());
			
				if($user = $form->get('utilisateur')->getData()) {
					$query->andWhere('t.utilisateur = :user')
						  ->setParameter('user',$user);
				}
				if($type = $form->get('type')->getData()) {
					$query->andWhere('t.type = :type')
						  ->setParameter('type',$type);
				}
			}

			return $this->render('LogiCorpoBundle:Caisse:chercher.html.twig', [
				'form'         => $form->createView(),
				'transactions' => $query->getQuery()->getResult()
			]);
		}
		return $this->render('LogiCorpoBundle:Caisse:chercher.html.twig', ['form' => $form->createView()]);
	}

	public function corrigerAction($id, Transaction $transaction, Request $req) {
		$form = $this->createFormBuilder($transaction)
					 ->add('utilisateur', 'text', [
					 	'data' => $transaction->getUtilisateur(),
					 	'disabled' => true
					 ])
					->add('type', 'choice', [
						'choices' => [
							'mouvement_banque' => 'Mouvement banque',
							'erreur_caisse'    => 'Erreur de caisse',
							'approvisionnement'=> 'Approvisionnement stock',
							'mouvement_carte'  => 'Approvisionnement compte',
							'achat_commande'   => 'Achat/Commande',
							'remboursement'    => 'Remboursement'
						],
						'data' => $transaction->getType()
					])
					 ->add('montant', 'money')
					 ->add('corriger', 'submit')
					 ->getForm();

		$form->handleRequest($req);
		if($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->flush($transaction);

			$req->getSession()->getFlashBag()->add('succes', "La transaction ($transaction) a bien été corrigée");
			return $this->redirect($this->generateUrl('lc_caisse_home'));
		}
		return $this->render('LogiCorpoBundle:Caisse:corriger.html.twig', [
			'form'        => $form->createView(),
			'transaction' => $transaction
		]);
	}
}
