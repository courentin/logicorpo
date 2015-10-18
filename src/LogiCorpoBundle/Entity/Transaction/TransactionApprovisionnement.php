<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;
use LogiCorpoBundle\Entity\MoyenPaiement;

/**
 * @ORM\Entity
 */
class TransactionApprovisionnement extends Transaction
{
	public function __construct() {
		$this->moyenPaiement = MoyenPaiement::ESPECE;
	}

	public function getType() {
		return "Approvisionnement stock";
	}
}