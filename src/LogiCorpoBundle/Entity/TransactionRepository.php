<?php
namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository {

	public function getSoldes() {

		$types = [
			'physique' => "SELECT SUM(t.solde) FROM LogiCorpoBundle:Transaction t WHERE t.moyenPaiement = 'espece' ",
			'nonDispo' => "SELECT SUM(u.solde) FROM LogiCorpoBundle:Utilisateur u",
			'ventes'   => "SELECT SUM(t.solde) FROM LogiCorpoBundle:Transaction t WHERE t.type = 'achat_commande'",
			'achats'   => "SELECT -SUM(t.solde) FROM LogiCorpoBundle:Transaction t WHERE t.type = 'approvisionnement'",
			'errCaisse'=> "SELECT SUM(t.solde) FROM LogiCorpoBundle:Transaction t WHERE t.type = 'erreur_caisse'"
		];

		foreach ($types as $type => $dql) {
			$query = $this->getEntityManager()->createQuery($dql);
			$soldes[$type] = floatval($query->getSingleResult()[1]);
		}

		$soldes['dispo'] = $soldes['physique'] - $soldes['nonDispo'];
		$soldes['benefices'] = $soldes['ventes'] - $soldes['achats'] - $soldes['errCaisse'];

		return $soldes;
	}

}