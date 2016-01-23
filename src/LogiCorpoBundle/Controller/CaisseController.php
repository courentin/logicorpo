<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use LogiCorpoBundle\Entity\Transaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
* @Security("has_role('ROLE_TRESORIER')")
*/
class CaisseController extends Controller
{
	public function indexAction($max = 25)
	{
/*
		$trans = new Transaction\TransactionRemboursement();
		$trans->setMontant(5)
		      ->setUtilisateur($this->getUser());

		$em = $this->getDoctrine()->getManager();
		$em->persist($trans);
		$em->flush();
*/

		$rep = $this->getDoctrine()->getRepository('LogiCorpoBundle:Transaction\Transaction');
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

	public function jsonSoldesPeriodAction($from, $step) {
		$rep = $this->getDoctrine()->getRepository('LogiCorpoBundle:Transaction\Transaction');
		$result = $rep->getSoldesPeriod(new \DateInterval('P' . $step), new \DateTime($from), new \DateTime());

		$response = new JsonResponse();
		$response->setData($result);

		return $response;
	}

	public function nouvelleTransactionAction(Request $req)
	{
		$transaction = new Transaction\Transaction();
		$transaction->setUtilisateur($this->getUser())
					->setMoyenPaiement('espece');
		$form = $this->createFormBuilder($transaction)
			->add('type_transaction', ChoiceType::class, [
				'choices' => [
					'mouvement_banque' => 'Mouvement banque',
					'erreur_caisse'    => 'Erreur de caisse'
				],
				'choices_as_values' => true
			])
			->add('montant',MoneyType::class)
			->add('Enregistrer', SubmitType::class)
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
			->add('type', ChoiceType::class, [
				'choices' => [
					'mouvement_banque' => 'Mouvement banque',
					'erreur_caisse'    => 'Erreur de caisse',
					'approvisionnement'=> 'Approvisionnement stock',
					'mouvement_carte'  => 'Approvisionnement compte',
					'achat_commande'   => 'Achat/Commande',
					'remboursement'    => 'Remboursement'
				],
				'choices_as_values' => true,
				'placeholder' => 'Tous les types',
				'empty_data' => null,
				'required'    => false
			])
			->add('du', DateType::class, [
				'data' => new \DateTime()
			])
			->add('au', DateType::class, [
				'data' => new \DateTime(),
				'constraints' => [
				]
			])
			->add('utilisateur', EntityType::class, [
				'class'         => 'LogiCorpoBundle:Utilisateur',
				'placeholder'   => 'Tous les utilisateurs',
				'empty_data'    => null,
				'required'      => false,
				'query_builder' => function(EntityRepository $er) {
					return $er->createQueryBuilder('u')
							  ->orderBy('u.nom');
				}
			])
			->add('ref', NumberType::class, [
				'required' => false
			])
			->add('rechercher', SubmitType::class)
			->getForm();
		$form->handleRequest($req);

		if($form->isValid()) {
			$repository = $this->getDoctrine()->getRepository('LogiCorpoBundle:Transaction\Transaction');
			$query = $repository->createQueryBuilder('t')
								->orderBy('t.date');
			
			if($ref = $form->get('ref')->getData()) {
				$query->where('t.id = :ref')
					  ->setParameter('ref',$ref);
			} else {
				$du = $form->get('du')->getData()->setTime(0,0,0);
				$au = $form->get('au')->getData()->setTime(23,59,59);

				$query->where('t.date BETWEEN :du AND :au')
					  ->setParameter('du',$du)
					  ->setParameter('au',$au);
			
				if($user = $form->get('utilisateur')->getData()) {
					$query->andWhere('t.utilisateur = :user')
						  ->setParameter('user',$user);
				}
				if($type = $form->get('type')->getData()) {
					$query->andWhere('t INSTANCE OF :type')
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

	public function corrigerAction($id, Transaction\Transaction $transaction, Request $req) {
		$form = $this->createFormBuilder($transaction)
					 ->add('utilisateur', TextType::class, [
					 	'data' => $transaction->getUtilisateur(),
					 	'disabled' => true
					 ])
					->add('type', ChoiceType::class, [
						'choices' => [
							'mouvement_banque' => 'Mouvement banque',
							'erreur_caisse'    => 'Erreur de caisse',
							'approvisionnement'=> 'Approvisionnement stock',
							'mouvement_carte'  => 'Approvisionnement compte',
							'achat_commande'   => 'Achat/Commande',
							'remboursement'    => 'Remboursement'
						],
						'choices_as_values' => true,
						'data' => $transaction->getType(),
						'disabled' => true
					])
					 ->add('montant', MoneyType::class)
					 ->add('corriger', SubmitType::class)
					 ->getForm();

		$form->handleRequest($req);
		if($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$req->getSession()->getFlashBag()->add('succes', "La transaction ($transaction) a bien été corrigée");
			return $this->redirect($this->generateUrl('lc_caisse_home'));
		}
		return $this->render('LogiCorpoBundle:Caisse:corriger.html.twig', [
			'form'        => $form->createView(),
			'transaction' => $transaction
		]);
	}
}
