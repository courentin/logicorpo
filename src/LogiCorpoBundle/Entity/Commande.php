<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="IDX_6EEAA67D50EAE44", columns={"id_utilisateur"}), @ORM\Index(name="IDX_6EEAA67D6A25C826", columns={"id_transaction"})})
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_commande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="commande_id_commande_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Service
     *
     * @ORM\ManyToOne(
     *    targetEntity="LogiCorpoBundle\Entity\Service",
     *    inversedBy="commandes"
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_service", referencedColumnName="id_service", nullable=false)
     * })
     */
    private $service;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_commande", type="datetime", nullable=false)
     */
    private $dateCommande;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="text", nullable=false)
     */
    private $etat;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_utilisateur", nullable=false)
     * })
     */
    private $utilisateur;

    /**
     * @var \Transactionn
     *
     * @ORM\ManyToOne(targetEntity="Transaction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_transaction", referencedColumnName="id_transaction")
     * })
     */
    private $transaction;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     * @return Commande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime 
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * Set etat
     *
     * @param string $etat
     * @return Commande
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set utilisateur
     *
     * @param \LogiCorpoBundle\Entity\Utilisateur $utilisateur
     * @return Commande
     */
    public function setUtilisateur(\LogiCorpoBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \LogiCorpoBundle\Entity\Utilisateur 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set transaction
     *
     * @param \LogiCorpoBundle\Entity\Transaction $transaction
     * @return Commande
     */
    public function setTransaction(\LogiCorpoBundle\Entity\Transaction $transaction = null)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return \LogiCorpoBundle\Entity\Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }


    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    public function removeTransaction(Transaction $transaction)
    {
        $this->transaction->removeElement($transaction);
    }

    public function getService() {
        return $this->service;
    }

}
