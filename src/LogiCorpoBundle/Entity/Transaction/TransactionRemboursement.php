<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;

/**
 * TransactionCompte
 * @ORM\Entity
 */
class TransactionRemboursement extends Transaction
{
	public function __construct() {
		parent::__construct();
		$this->moyenPaiement = "compte";
	}

	public function getType() {
		return "Remboursement";
	}

	public function getUserMontant() {
		return -$this->getMontant();
	}
}