<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LogiCorpoBundle\Entity\Utilisateur;
use LogiCorpoBundle\Entity\Transaction;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CompteController extends Controller
{
	public function indexAction($limite = 25)
	{
		$transactionRep = $this->getDoctrine()->getManager()->getRepository('LogiCorpoBundle:Transaction\Transaction');
		$transactions = $transactionRep->getUserHistory($this->getUser(),$limite);

		$config = $this->get('settings_manager');

		$canUpgrade = !$this->get('security.authorization_checker')->isGranted('ROLE_MEMBRE')
					&& $this->getUser()->canPay($config->get('montantAdhesion'), $config->get('seuil'));

		return $this->render('LogiCorpoBundle:Compte:index.html.twig', [
			'transactions'    => $transactions,
			'canUpgrade'      => $canUpgrade
		]);
	}

	/**
	 * @Security("!has_role('ROLE_MEMBRE')")
	 */
	public function upgradeAction()
	{
		$montantAdhesion = $this->get('settings_manager')->get('montantAdhesion');
		$user = $this->getUser();

		if($user->canPay($montantAdhesion, $this->get('settings_manager')->get('seuil'))) {
			$em = $this->getDoctrine()->getManager();
			$membre = $em->getRepository('LogiCorpoBundle:Rang')->findOneBySlug('MEMBRE');

			$user->setRang($membre);

			$transaction = new TransactionFraisAdhesion();
			$transaction->setUtilisateur($user)
						->setMontant($montantAdhesion)
						->setMoyenPaiement('compte');
			$em->persist($transaction);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('lc_compte_home'));
	}
}
