<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;

/**
 * TransactionCompte
 * @ORM\Entity
 */
class TransactionErreurCaisse extends Transaction
{
	public function __construct() {
		parent::__construct();
		$this->moyenPaiement  = "espece";
	}

	public function getType() {
		return "Erreur de caisse";
	}
}