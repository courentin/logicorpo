<?php
namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;

class ProduitRepository extends EntityRepository {

	/**
	 * Recupère le nombre de commande par produit
	 * @param $produit : Produit concercé, si null calcul pour tout les produits
	 */
	public function orderBySold(\LogiCorpoBundle\Entity\Produit $produit = null) {
		$query = $this->getEntityManager()->createQuery('SELECT p, SUM(pc.quantite) as quantite FROM LogiCorpoBundle:Produit p
			                                             JOIN LogiCorpoBundle:ProduitsCommande pc WITH pc.produit=p
			                                             GROUP BY p.id ORDER BY quantite DESC');
		return $query->getResult();
	}

	/**
	 * Retourne la frequence de commande en unite/jour
	 * @param $produit : Produit concerné, si null calcul pour tout les produits
	 * @return double
	 */
	public function getFrequence(\LogiCorpoBundle\Entity\Produit $produit = null) {

		$days = $this->getDayWithOrder();
		$nbTotal = sizeof($days);
		$sales = $this->orderBySold($produit);
		
		for($i = 0; $i<sizeof($sales); $i++) {
			$sales[$i]['frequence'] = round($sales[$i]['quantite']/$nbTotal,3);
		}
		return $sales;
	}

	/**
	 * Recupère et classe les jours avec des commandes
	 * @param $from : Date de début
	 * @param $to : Date de fin
	 */
	public function getDayWithOrder(\Date $from = null, \Date $to = null) {
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('day', 'day')
		    ->addScalarResult('nbcommandes', 'nbCommandes');
		
		$sql = "SELECT DISTINCT date_trunc('day', date_transaction) as day, COUNT(*) as nbcommandes
				FROM transactionn
				WHERE type_transaction='achat_commande' ";

		$order = " GROUP BY day ORDER BY day DESC";

		
		if($from !== null) {
			$to = ($to == null) ? $to = new \Date() : $to;
			$sql += "AND date_transaction BETWEEN (:from AND :to)";
			$query = $this->getEntityManager()->createNativeQuery($sql.$order, $rsm)
						  ->setParameter('from', $from)
						  ->setParameter('to', $to);

		} else {
			$query = $this->getEntityManager()->createNativeQuery($sql.$order, $rsm);
		}

		return $query->getScalarResult();
	}

	/**
	 * Recupère les produits dont le stock est :
	 *  - épuisé
	 *  - en stock critique (sera théoriquement épuisé dans 1j)
	 * @return Liste de produits trié par stock
	 */
	public function getStockCritique() {
		/*
		"SELECT p FROM LogiCorpoBundle:Produit p "
		*/
	}

	/**
	 * Recupère et classe les produits suivant le nombre de
	 * fois dernièrement commandé par un utilisateur
	 * @param $utilisateur : Utilisateur concerné
	 * @param $limitCommande : Nombre de dernière commande, par default 5
	 * @param $limitProduit : Nombre de produit max, si null tout les produits
	 */
	public function getLastOrder(\LogiCorpoBundle\Entity\Utilisateur $utilisateur, $limitCommande = 5, $limitProduit = null) {
		$em = $this->getEntityManager();

		$queryCommandes = $em->createQuery("SELECT commande FROM LogiCorpoBundle:Commande commande
		                                   WHERE commande.utilisateur = :u
		                                   ORDER BY commande.date");
		$queryCommandes->setParameter('u', $utilisateur)
		               ->setMaxResults($limitCommande);
		$lastCommandes = $queryCommandes->getResult();

		$query = $em->createQuery("SELECT produit, COUNT(produitCommande.produit) AS HIDDEN nbFois FROM LogiCorpoBundle:ProduitsCommande produitCommande
		                           JOIN LogiCorpoBundle:Produit produit WITH produit=produitCommande.produit
		                           WHERE produitCommande.commande IN(:lastCommandes)
		                           GROUP BY produit
		                           ORDER BY nbFois DESC");

		$query->setParameter('lastCommandes', $lastCommandes);
		if ($limitProduit !== null)
			$query->setMaxResults($limitProduit);

		return $query->getResult();
	}
}