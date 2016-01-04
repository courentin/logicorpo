<?php
/**
 * Created by PhpStorm.
 * User: corentin
 * Date: 01/01/16
 * Time: 21:00
 */

namespace LogiCorpoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StatistiquesController extends Controller
{
	private $labels;
	private $series;
	private $datas;

	public function indexAction(\DateTime $start, \DateTime $end, $step = 'jour', Request $req)
	{
		$dateForm = $this->createFormBuilder()
			->add('start', 'date', ['data' => $start, 'label' => 'Du'])
			->add('end', 'date', ['data' => $end, 'label' => 'Au'])
			->add('step', 'choice', [
				'choices' => ['jour' => 'Jour', 'mois' => 'Mois', 'annee' => 'Année'],
				'data'    => $step
			])
			->add('Filtrer', 'submit')
			->getForm();
		$dateForm->handleRequest($req);
		if($dateForm->isSubmitted()) {
			if($dateForm->isValid()) {
				return $this->redirectToRoute('lc_statistiques_home', [
					'start' => $dateForm->get('start')->getData()->format('Y-m-d'),
					'end'   =>  $dateForm->get('end')->getData()->format('Y-m-d'),
					'step'  =>  $dateForm->get('step')->getNormData(),
				]);
			} else throw new NotFoundHttpException();
		}
		return $this->render('LogiCorpoBundle:Statistiques:index.html.twig', ['form' => $dateForm->createView()]);
	}

	public function commandesAction(\DateTime $start, \DateTime $end, $step = 'jour')
	{
		$em = $this->getDoctrine()->getEntityManager();
		$q = $em->createQuery('SELECT c.date, COUNT(c) as nbCommande FROM LogiCorpoBundle:Commande c INDEX BY c.date GROUP BY c.date');
		$r = $q->getArrayResult();

		foreach($r as $key => $value) {
			$this->datas[] = $value['nbCommande'];
		}

		$this->labels = array_keys($r);
		$this->series = ['Nombre de commande'];

		return $this->sendJson();
	}

	private function sendJson()
	{
		$json['labels'] = $this->labels;
		$json['series'] = $this->series;
		$json['datas'] = $this->datas;

		return new JsonResponse($json);
	}
}