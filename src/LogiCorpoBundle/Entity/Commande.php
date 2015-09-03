<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={
 *       @ORM\Index(name="commande_id_utilisateur_fkey", columns={"id_utilisateur"}),
 *       @ORM\Index(name="commande_id_transaction_fkey", columns={"id_transaction"}),
 *       @ORM\Index(name="commande_id_service_fkey", columns={"id_service"})
 * })
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
     * @var LogiCorpoBundle\Entity\Transaction\TransactionCommande
     *
     * @ORM\ManyToOne(targetEntity="LogiCorpoBundle\Entity\Transaction\TransactionCommande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_transaction", referencedColumnName="id_transaction")
     * })
     */
    private $transaction;

    /**
     * @var ProduitsCommande
     * @ORM\OneToMany(targetEntity="ProduitsCommande", mappedBy="commande")
     */
    private $produits;

    public function __construct() {
        $this->produits = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getProduits() {
        return $this->produits;
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
    public function setTransaction(\LogiCorpoBundle\Entity\Transaction\Transaction $transaction = null)
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


    public function addTransaction(\LogiCorpoBundle\Entity\Transaction\Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    public function removeTransaction(\LogiCorpoBundle\Entity\Transaction\Transaction $transaction)
    {
        $this->transaction->removeElement($transaction);
    }

    public function getService() {
        return $this->service;
    }

}
