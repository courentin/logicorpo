<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;
use LogiCorpoBundle\Entity\MoyenPaiement;

/**
 * TransactionCompte
 * @ORM\Entity
 */
class TransactionRemboursement extends Transaction
{
	public function __construct() {
		parent::__construct();
		$this->moyenPaiement = MoyenPaiement::COMPTE;
	}

	public function getType() {
		return "Remboursement";
	}

	public function getUserMontant() {
		return -$this->getMontant();
	}
}