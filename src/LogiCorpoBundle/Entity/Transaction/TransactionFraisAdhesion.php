<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;

/**
 * TransactionCompte
 * @ORM\Entity
 */
class TransactionFraisAdhesion extends Transaction
{

	public function getType() {
		return "Frais d'adhesion";
	}

	public function getUserMontant() {
		return -$this->getMontant();
	}
}