<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LogiCorpoBundle\Entity\Utilisateur;
use LogiCorpoBundle\Entity\Transaction;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CompteController extends Controller
{
	public function indexAction()
	{
		$limite = 25;
		$transactionRep = $this->getDoctrine()->getManager()->getRepository('LogiCorpoBundle:Transaction');
		$transactions = $transactionRep->getUserHistory($this->getUser(),$limite);

		$config = $this->get('logicorpo.settings');

		$canUpgrade = !$this->get('security.authorization_checker')->isGranted('ROLE_MEMBRE')
					&& $this->getUser()->canPay($config->montantAdhesion, $config->seuil);

		return $this->render('LogiCorpoBundle:Compte:index.html.twig', [
			'transactions'    => $transactions,
			'canUpgrade'      => $canUpgrade,
			'montantAdhesion' => $config->montantAdhesion
		]);
	}

	/**
	 * @Security("!has_role('ROLE_MEMBRE')")
	 */
	public function upgradeAction()
	{
		$montantAdhesion = $this->get('logicorpo.settings')->montantAdhesion;
		$user = $this->getUser();

		if($user->canPay($montantAdhesion, $this->get('logicorpo.settings')->seuil)) {
			$em = $this->getDoctrine()->getManager();
			$membre = $em->getRepository('LogiCorpoBundle:Rang')->findOneBySlug('MEMBRE');

			$user->appendSolde(-$montantAdhesion);
			$user->setRang($membre);

			$transaction = new Transaction();
			$transaction->setType('frais_adhesion')
						->setUtilisateur($user)
						->setMontant($montantAdhesion)
						->setMoyenPaiement('compte');
			$em->persist($transaction);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('lc_compte_home'));
	}
}
