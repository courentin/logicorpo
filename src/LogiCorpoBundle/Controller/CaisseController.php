<?php

namespace LogiCorpoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

		$solde = $rep->getSoldes();
		dump($solde);
		return $this->render('LogiCorpoBundle:Caisse:index.html.twig', ['transactions' => $transactions, 'max' => $max, 'solde' => $solde]);
	}

}
