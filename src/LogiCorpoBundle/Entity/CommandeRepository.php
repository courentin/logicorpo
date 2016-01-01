<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CommandeRepository extends EntityRepository
{
	public function getCommandes(Service $service, Utilisateur $user = null)
	{
		$query = $this->createQueryBuilder('c')
			->where('c.service = :service')
			->setParameter('service', $service);

		if($user != null) {
			$query
				->andWhere('c.utilisateur = :user')
				->setParameter('user', $user);
		}

		return $query->getQuery()->getResult();
	}
}