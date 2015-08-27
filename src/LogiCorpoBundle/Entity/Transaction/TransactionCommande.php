<?php 

namespace LogiCorpoBundle\Entity\Transaction;
use Doctrine\ORM\Mapping as ORM;

/**
 * TransactionCompte
 * @ORM\Entity
 */
class TransactionCommande extends Transaction
{

    /**
     * @var LogiCorpoBundle\Entity\Commande
     *
     * @ORM\ManyToOne(targetEntity="LogiCorpoBundle\Entity\Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande", referencedColumnName="id_commande")
     * })
     */
    protected $commande;

	public function getType() {
		return "Achat/Commande";
	}

    /**
     * Set commande
     *
     * @param \LogiCorpoBundle\Entity\Commande $commande
     * @return Transactionn
     */
    public function setCommande(\LogiCorpoBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \LogiCorpoBundle\Entity\Commande 
     */
    public function getCommande()
    {
        return $this->commande;
    }

    public function getUserMontant() {
        return -$this->getMontant();
    }
}