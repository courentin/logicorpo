<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;
use LogiCorpoBundle\Entity\MoyenPaiement;

/**
 * TransactionCompte
 * @ORM\Entity
 */
class TransactionErreurCaisse extends Transaction
{
	public function __construct() {
		parent::__construct();
		$this->moyenPaiement = ESPECE;
	}

	public function getType() {
		return "Erreur de caisse";
	}
}