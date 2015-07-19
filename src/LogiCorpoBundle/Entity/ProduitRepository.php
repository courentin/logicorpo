<?php
namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProduitRepository extends EntityRepository {
	public function getAllCategories() {
		$query = $this->getEntityManager()->createQuery('SELECT DISTINCT p.categorie FROM LogiCorpoBundle:Produit p');
		$categ = $query->getScalarResult();

		$return = [];
		foreach ($categ as $value) {
			$return[] = $value['categorie'];
		}
		return $return;
	}

	public function getProductsByCategory() {

		$products = [];
		$em = $this->getEntityManager();

		foreach ($this->getAllCategories() as $categorie) {
			$query = $em->createQuery('SELECT p FROM LogiCorpoBundle:Produit p WHERE p.categorie = :categorie');
			$query->setParameter('categorie', $categorie);
			$products[$categorie] =  $query->getArrayResult();

		}
		return $products;
	}
}