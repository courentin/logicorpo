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
}