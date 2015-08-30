<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(
 *  repositoryClass="LogiCorpoBundle\Entity\ProduitRepository"
 * )
 * @ORM\InheritanceType("JOINED")
 */
class Produit
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id_produit", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="text", nullable=false)
     * @Assert\NotBlank(message="Le libéllé doit être renseigné")
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(0, message = "Le stock doit être nul ou positif")
     */
    private $stock=null;

    /**
     * @var decimal
     *
     * @ORM\Column(name="prix_vente", type="decimal", precision=8, scale=2, nullable=false)
     * @Assert\Type(type="numeric", message = "Le prix de vente doit être de type numérique")
     * @Assert\NotBlank(message="Le prix de vente doit être renseigné")
     */
    private $prixVente;

    /**
     * @var decimal
     *
     * @ORM\Column(name="prix_achat", type="decimal", precision=8, scale=2, nullable=true)
     * @Assert\Type(type="numeric", message = "Le prix d'achat doit être de type numérique")
     */
    private $prixAchat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reduction", type="boolean", nullable=false)
     * @Assert\NotBlank(message="Il est nécessaire de savoir si le produit est soumis aux réductions.")
     */
    private $reduction = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="dispo", type="boolean", nullable=false)
     * @Assert\NotBlank(message="Il est nécessaire de savoir si le produit est disponible.")
     */
    private $dispo = true;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="text", nullable=false)
     * @Assert\NotBlank(message="Il est nécessaire de renseigner la catégorie du produit.")
     */
    private $categorie;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Supplement")
     * @ORM\JoinTable(name="disponible",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_produit", referencedColumnName="id_produit")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_supplement", referencedColumnName="id_supplement")
     *   }
     * )
     */
    private $supplementDisponible;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supplementDisponible = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     * @return Produit
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     * @return Produit
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set prixVente
     *
     * @param string $prixVente
     * @return Produit
     */
    public function setPrixVente($prixVente)
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    /**
     * Get prixVente
     *
     * @return string 
     */
    public function getPrixVente()
    {
        return $this->prixVente;
    }

    /**
     * Set prixAchat
     *
     * @param string $prixAchat
     * @return Produit
     */
    public function setPrixAchat($prixAchat)
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    /**
     * Get prixAchat
     *
     * @return string 
     */
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    /**
     * Set reduction
     *
     * @param boolean $reduction
     * @return Produit
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return boolean 
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * Set dispo
     *
     * @param boolean $dispo
     * @return Produit
     */
    public function setDispo($dispo)
    {
        $this->dispo = $dispo;

        return $this;
    }

    /**
     * Get dispo
     *
     * @return boolean 
     */
    public function getDispo()
    {
        return $this->dispo;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return Produit
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Add supplementDisponible
     *
     * @param \LogiCorpoBundle\Entity\Supplement $supplementDisponible
     * @return Produit
     */
    public function addSupplementDisponible(\LogiCorpoBundle\Entity\Supplement $supplementDisponible)
    {
        $this->supplementDisponible[] = $supplementDisponible;

        return $this;
    }

    /**
     * Remove supplementDisponible
     *
     * @param \LogiCorpoBundle\Entity\Supplement $supplementDisponible
     */
    public function removeSupplementDisponible(\LogiCorpoBundle\Entity\Supplement $supplementDisponible)
    {
        $this->supplementDisponible->removeElement($supplementDisponible);
    }

    /**
     * Get supplementDisponible
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplementDisponible()
    {
        return $this->supplementDisponible;
    }

    public function addStock($number) {
        if($this->solde !=null) $this->stock += $number;
        return $this;
    }

    public function __toString() {
        return $this->getLibelle();
    }
}
