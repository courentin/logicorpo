<?php

namespace LogiCorpoBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Transaction
 *
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type_transaction", type="text")
 * @ORM\DiscriminatorMap({"approvisionnement" = "TransactionApprovisionnement",
 *                        "mouvement_carte"   = "TransactionCompte",
 *                        "achat_commande"    = "TransactionCommande",
 *                        "mouvement_banque"  = "TransactionBanque",
 *                        "erreur_caisse"     = "TransactionErreurCaisse",
 *                        "remboursement"     = "TransactionRemboursement",
 *                        "frais_adhesion"    = "TransactionFraisAdhesion"})
 * @ORM\Table(name="transactionn", indexes={
 *               @ORM\Index(name="transactionn_id_utilisateur_fkey",  columns={"id_utilisateur"}),
 *               @ORM\Index(name="transactionn_id_commande_fkey", columns={"id_commande"}),
 *               @ORM\Index(name="transactionn_id_caissier_fkey", columns={"id_caissier"})
 * })
 *
 * @ORM\Entity(
 *  repositoryClass="LogiCorpoBundle\Entity\TransactionRepository"
 * )
 */
abstract class Transaction
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_transaction", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="transactionn_id_transaction_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="moyen_paiement", type="text", nullable=false)
     * @Assert\NotBlank(message="Le moyen de paiement doit être renseigné")
     * @Assert\Choice(
     *     choices = {"espece","compte"},
     *     message = "Erreur type incorrect"
     * )
     */
    protected $moyenPaiement;

    /**
     * @var string
     *
     * @ORM\Column(name="solde", type="decimal", precision=8, scale=2, nullable=false)
     * @Assert\NotBlank(message="Le motant doit être renseigné")
     * @Assert\Type(type="numeric",message = "Le motant doit être une valeur décimale.")
     */
    protected $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_transaction", type="datetime", nullable=false)
     * @Assert\NotBlank(message="La date doit être renseignée")
     */
    protected $date;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="LogiCorpoBundle\Entity\Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_utilisateur")
     * })
     */
    protected $utilisateur = null;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="LogiCorpoBundle\Entity\Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_caissier", referencedColumnName="id_utilisateur")
     * })
     */
    private $caissier = null;

    public function __construct() {
        $this->date = new \DateTime();
    }

    /*
     * @Assert\True(message="La transaction doit avoir un utilisateur ou un caissier")
     */
    public function hasUser() {
        return $this->utilisateur !== null || $this->caissier !== null;
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
     * Set moyenPaiement
     *
     * @param string $moyenPaiement
     * @return Transactionn
     */
    public function setMoyenPaiement($moyenPaiement)
    {
        $this->moyenPaiement = $moyenPaiement;
        return $this;
    }

    /**
     * Get moyenPaiement
     *
     * @return string 
     */
    public function getMoyenPaiement()
    {
        return $this->moyenPaiement;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return Transactionn
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
        return $this;
    }

    /**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Transactionn
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
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
     * @return Transactionn
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

    public function setCaissier(\LogiCorpoBundle\Entity\Utilisateur $caissier = null) {
        $this->caissier = $caissier;
        return $this;
    }

    public function getCaissier() {
        return $this->caissier;
    }

    public function __toString() {
        return '#'.$this->getId().' - [['.$this->getMontant().' | currency ]]';
    }
}
