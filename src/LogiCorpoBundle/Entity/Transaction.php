<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Transactionn
 *
 * @ORM\Table(name="transactionn", indexes={@ORM\Index(name="IDX_89AE769550EAE44", columns={"id_utilisateur"}), @ORM\Index(name="IDX_89AE76953E314AE8", columns={"id_commande"})})
 * @ORM\Entity
 * @ORM\Entity(
 *  repositoryClass="LogiCorpoBundle\Entity\TransactionRepository"
 * )
 */
class Transaction
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_transaction", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="transactionn_id_transaction_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type_transaction", type="text", nullable=false)
     * @Assert\NotBlank(message="Le type de transaction doit être renseigné")
     * @Assert\Choice(
     *     choices = {"approvisionnement","mouvement_carte","achat_commande","mouvement_banque","erreur_caisse","remboursement", "frais_adhesion"},
     *     message = "Erreur type incorrect"
     * )
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="moyen_paiement", type="text", nullable=false)
     * @Assert\NotBlank(message="Le moyen de transaction doit être renseigné")
     * @Assert\Choice(
     *     choices = {"espece","compte"},
     *     message = "Erreur type incorrect"
     * )
     */
    private $moyenPaiement;

    /**
     * @var string
     *
     * @ORM\Column(name="solde", type="decimal", precision=8, scale=2, nullable=false)
     * @Assert\NotBlank(message="Le motant doit être renseigné")
     * @Assert\Type(type="numeric",message = "Le motant doit être une valeur décimale.")
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_transaction", type="datetime", nullable=false)
     * @Assert\NotBlank(message="La date doit être renseignée")
     */
    private $date;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_utilisateur", nullable=false)
     * })
     * @Assert\NotBlank(message="L'id de l'utilisateur doit être renseigné")
     */
    private $utilisateur;

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande", referencedColumnName="id_commande")
     * })
     */
    private $commande;


    private $viewType = [
        'approvisionnement'=> 'Approvisionnement stock',
        'mouvement_carte'  => 'Approvisionnement compte',
        'achat_commande'   => 'Achat/Commande',
        'mouvement_banque' => 'Mouvement banque',
        'erreur_caisse'    => 'Erreur de caisse',
        'remboursement'    => 'Remboursement',
        'frais_adhesion'   => "Frais d'adhesion"
    ];

    public function __construct() {
        $this->date = new \DateTime();
    }

    public function isForLogiCorpo()
    {
        $types = ['mouvement_banque', 'approvisionnement', 'erreur_caisse'];
        return in_array($this->type, $types);
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
     * Set type
     *
     * @param string $type
     * @return Transactionn
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
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

    public function getViewType() {
        return $this->viewType[$this->type];
    }

    public function __toString() {
        return '#'.$this->getId().' - [['.$this->getMontant().' | currency ]]';
    }
}
