<?php
namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository {

	/**
	* Calcul les différents soldes de la caisse
	* @param until : date jusqu'à laquelle doivent être calculés les soldes
	* @return tableau des différents soldes
	*/
	public function getSoldes(\DateTime $until = null)
	{
		if($until) $dateSelector = " AND t.date <= " . $until->format("'Y-m-d H:i:s'");
		else $dateSelector = "";

		$types = [
			'physique' => "SELECT SUM(t.montant) FROM LogiCorpoBundle:Transaction t WHERE t.moyenPaiement = 'espece'" . $dateSelector,
			'nonDispo' => "SELECT SUM(u.solde) FROM LogiCorpoBundle:Utilisateur u",
			'ventes'   => "SELECT SUM(t.montant) FROM LogiCorpoBundle:Transaction t WHERE t.type IN ('achat_commande', 'frais_adhesion') " . $dateSelector,
			'achats'   => "SELECT -SUM(t.montant) FROM LogiCorpoBundle:Transaction t WHERE t.type = 'approvisionnement'" . $dateSelector,
			'errCaisse'=> "SELECT SUM(t.montant) FROM LogiCorpoBundle:Transaction t WHERE t.type = 'erreur_caisse'" . $dateSelector
		];

		foreach ($types as $type => $dql) {
			$query = $this->getEntityManager()->createQuery($dql);
			$soldes[$type] = floatval($query->getSingleScalarResult());
		}

		$soldes['dispo'] = $soldes['physique'] - $soldes['nonDispo'];
		$soldes['benefices'] = $soldes['ventes'] - $soldes['achats'] - $soldes['errCaisse'];

		return $soldes;
	}

	/**
	* Calcul les différents soldes de la caisse sur une période
	* @param $step : le pas de la période
	* @param $from : date de début de période
	* @param $until: date de fin de période
	* @return tableau de getSoldes
	*/
	public function getSoldesPeriod(\DateInterval $step, \DateTime $from, \DateTime $until = null )
	{

		for ($current = $from; $current <= $until; $current->add($step)) {
			$result[$current->format("Y-m-d H:i:s")] = $this->getSoldes($current);
		}
		return $result;
	}

	public function getUserHistory(Utilisateur $u, $limit)
	{
		$query = $this->createQueryBuilder('t')
				      ->where('t.utilisateur = :utilisateur')
				      ->setParameter('utilisateur',$u)
				      ->andWhere("t.type NOT IN ('mouvement_banque', 'approvisionnement', 'erreur_caisse')")
				      ->orderBy('t.date', 'DESC')
				      ->setMaxResults($limit)
				      ->getQuery();
		return $query->getResult();
	}
}