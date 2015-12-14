<?php
namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class CategorieRepository extends EntityRepository {

	public function getDispoProduits()
	{
		$query = $this->createQueryBuilder('c')
			->join('c.produits', 'p')
			->where('p.dispo = true')
			->getQuery();

		return $query->getResult();
	}

	public function getProduits()
	{
		$query = $this->createQueryBuilder('c')
			->join('c.produits', 'p')
			->getQuery();

		return $query->getResult();
	}
}