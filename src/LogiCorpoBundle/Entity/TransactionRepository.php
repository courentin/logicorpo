<?php
namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository {

	public function getSoldes(\DateTime $until = null) {
		if($until) $dateSelector = " AND t.date <= " . $until->format("'Y-m-d H:i:s'");
		else $dateSelector = "";

		$types = [
			'physique' => "SELECT SUM(t.montant) FROM LogiCorpoBundle:Transaction t WHERE t.moyenPaiement = 'espece'" . $dateSelector,
			'nonDispo' => "SELECT SUM(u.solde) FROM LogiCorpoBundle:Utilisateur u",
			'ventes'   => "SELECT SUM(t.montant) FROM LogiCorpoBundle:Transaction t WHERE t.type = 'achat_commande'" . $dateSelector,
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

	public function getSoldesPeriod(\DateTime $from, \DateTime $until, $step) {

	}
}