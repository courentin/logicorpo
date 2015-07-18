<?php
namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProduitRepository extends EntityRepository {
	public function getAllCategories() {
		$query = $this->getEntityManager()->createQuery('SELECT p.categories FROM LogiCorpoBundle:Produit p GROUP BY p.categories');
		return $query->getResult();
	}
}