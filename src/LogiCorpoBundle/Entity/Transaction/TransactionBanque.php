<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;
use LogiCorpoBundle\Entity\MoyenPaiement;

/**
 * TransactionCompte
 * @ORM\Entity
 */
class TransactionBanque extends Transaction
{
	public function __construct() {
		parent::__construct();
		$this->moyenPaiement = MoyenPaiement::ESPECE;
	}

	public function getType() {
		return "Mouvement banque";
	}
}