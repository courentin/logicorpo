<?php
namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ServiceRepository extends EntityRepository {

	public function atDay($date = false) {
		if(!$date) $date = new \DateTime("now");
		$query = $this->getEntityManager()->createQuery('SELECT s FROM LogiCorpoBundle:Service s WHERE s.debut BETWEEN :start AND :end ');
		$query->setParameter('start', date_format($date,"Y-m-d")." 00:00:00");
		$query->setParameter('end', date_format($date,"Y-m-d")." 23:59:59");
		return $query->getResult();
	}

	public function getNextServices($marge = 30) {
		$query = $this->getEntityManager()->createQuery("SELECT s FROM LogiCorpoBundle:Service s WHERE s.fin > CURRENT_TIMESTAMP()");
		return $query->getResult();
	}

	public function getServicesAndCommandes(Utilisateur $user, $date = false) {
		if(!$date) $date = new \DateTime("now");

		$query = $this->createQueryBuilder('s')
			          ->addSelect('c')
			          ->addSelect('pc')
			          ->addSelect('p')
			          ->leftJoin('s.commandes', 'c', 'WITH', 'c.utilisateur = :user')
			          ->leftJoin('c.produits', 'pc')
			          ->leftJoin('pc.produit', 'p')
			          ->where('s.debut BETWEEN :start AND :end')
		              ->orderBy('s.debut');

		$query->setParameter('user', $user)
		      ->setParameter('start', date_format($date,"Y-m-d")." 00:00:00")
		      ->setParameter('end', date_format($date,"Y-m-d")." 23:59:59");
		return $query->getQuery()->getResult();
	}
}