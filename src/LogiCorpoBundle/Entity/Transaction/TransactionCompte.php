<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;
use LogiCorpoBundle\Entity\MoyenPaiement;

/**
 * @ORM\Entity
 */
class TransactionCompte extends Transaction
{

	public function __construct() {
		parent::__construct();
		$this->moyenPaiement = MoyenPaiement::ESPECE;
	}

	public function getType() {
		return "Approvisionnement compte";
	}

	public function getUserMontant() {
		return -$this->getMontant();
	}
}