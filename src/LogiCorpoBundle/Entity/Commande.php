<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={
 *       @ORM\Index(name="commande_id_utilisateur_fkey", columns={"id_utilisateur"}),
 *       @ORM\Index(name="commande_id_service_fkey", columns={"id_service"})
 * })
 * @ORM\Entity( repositoryClass="LogiCorpoBundle\Entity\CommandeRepository")
 */
class Commande implements JsonSerializable
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
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="paye", type="boolean", nullable=false)
     */
    private $paye = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="servie", type="boolean", nullable=false)
     */
    private $servie = false;

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
     * @ORM\OneToMany(targetEntity="LogiCorpoBundle\Entity\Transaction\TransactionCommande", mappedBy="commande")
     */
    private $transactions;

    /**
     * @var ProduitsCommande
     * @ORM\OneToMany(targetEntity="ProduitsCommande", mappedBy="commande", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $produits;

    public function __construct() {
        $this->produits = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function __toString()
    {
        return "#$this->id";
    }

    public function preSave()
    {
        $montantTransaction = 0;
        foreach ($this->transactions as $transaction) {
            $montantTransaction += $transaction->getMontant();
        }
        if($montantTransaction == $this->getMontant()) $this->paye = true;
        else $this->paye = false;
    }

    public function getProduits()
    {
        return $this->produits;
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

    /**
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
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
    public function setTransactions(\LogiCorpoBundle\Entity\Transaction\TransactionCommande $transactions = null)
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return \LogiCorpoBundle\Entity\Transaction
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    public function addTransaction(\LogiCorpoBundle\Entity\Transaction\TransactionCommande $transaction)
    {
        $this->transactions->add($transaction);

        return $this;
    }

    public function removeTransaction(\LogiCorpoBundle\Entity\Transaction\TransactionCommande $transaction)
    {
        $this->transaction->removeElement($transaction);
    }

    public function getService()
    {
        return $this->service;
    }

    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }
    /**
     * Retourne le montant total de la commande
     * @return double
     */
    public function getMontant() {
        $montant = 0;
        foreach ($this->produits as $produit) {
            $montant += $produit->getMontant();
        }
        return $montant;
    }

    public function addProduit(ProduitsCommande $produit)
    {
        $produit->setCommande($this);
        $this->produits->add($produit);
    }

    public function removeProduit(ProduitsCommande $produit)
    {
        $this->produits->remove($produit);
    }

    public function isPaye()
    {
        return $this->paye;
    }

    public function setPaye( $paye)
    {
        $this->paye = $paye;
        return $this;
    }

    public function isServie()
    {
        return $this->servie;
    }

    public function setServie($servie)
    {
        $this->servie = $servie;
        return $this;
    }

    /**
     *  P &&  S : SUCCESS 
     *  P && !S : WARN
     * !P &&  S : ERR
     * !P && !S : OK
     */
    public function getEtat()
    {
        if($this->paye && $this->servie)
            return "vert";
        else if($this->paye && !$this->servie)
            return "orange";
        else if(!$this->paye && $this->servie)
            return "rouge";
        else
            return "";
    }

    public function jsonSerialize()
    {
        return array(
            'id'          => $this->getId(),
            'date'        => $this->getDate(),
            'paye'        => $this->isPaye(),
            'servie'      => $this->isServie(),
            'etat'        => $this->getEtat(),
            'utilisateur' => $this->getUtilisateur(),
            'produits'    => $this->getProduits()->toArray(),
            'montant'     => $this->getMontant()
        );
    }

    public function isEditable()
    {
        return new \DateTime() < $this->service->getDebut() && !$this->paye && !$this->servie;
    }
}
