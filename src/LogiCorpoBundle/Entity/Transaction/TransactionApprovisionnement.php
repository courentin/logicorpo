<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TransactionApprovisionnement extends Transaction
{
	public function __construct() {
		$this->moyenPaiement  = "espece";
	}

	public function getType() {
		return "Approvisionnement stock";
	}
}