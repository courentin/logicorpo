<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;

/**
 * ProduitsCommande
 *  
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="produits_commande",
 *   uniqueConstraints = {
 *    @ORM\UniqueConstraint(columns={"id_commande", "id_produit"})
 *   },
 *   indexes = {
 *    @ORM\Index(name="produits_commande_id_commande_fkey", columns={"id_commande"}),
 *    @ORM\Index(name="produits_commande_id_produit_fkey", columns={"id_produit"})
 *   }
 * )
 */
class ProduitsCommande implements JsonSerializable
{

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     * @Assert\GreaterThanOrEqual(value = 1)
     */
    private $quantite = 1;

    /*
     * @ORM\ManyToMany(targetEntity="Supplement")
     * @ORM\JoinTable(name="supplements_produits_commande",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_produit", referencedColumnName="id_produit")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_supplement", referencedColumnName="id_supplement")
     *   }
     * )
     */
    private $supplements;

    /**
     * @var \Commande
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Commande", inversedBy="produits", cascade={"persist"})
     * @ORM\JoinColumn(name="id_commande", referencedColumnName="id_commande", nullable=false)
     * @Assert\Valid
     */
    private $commande;

    /**
     * @var \Produit
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="id_produit", referencedColumnName="id_produit", nullable=false)
     * @Assert\Valid
     */
    private $produit;

    public function jsonSerialize()
    {
        return array(
            'produit' => $this->getProduit(),
            'quantite' => $this->getQuantite(),
        );
    }

    /**
     * @Assert\IsTrue(message="La quantité de produit n'est plus disponible")
     */
    public function isQuantiteDisponible()
    {
        return $this->getProduit()->getStock() === null || $this->quantite <= $this->getProduit()->getStock();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        $this->produit->removeStock($this->getQuantite());
    }

    /**
     * @ORM\PreUpdate()
     */
    public function prePUpdate(PreUpdateEventArgs $eventArgs) {
        if($eventArgs->hasChangedField('quantite')) {
            $remove = $eventArgs->getOldValue('quantite') - $eventArgs->getNewValue('quantite');
            $this->produit->addStock($remove);
        }
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return ProduitsCommande
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }


    /**
     * Get supplement
     *
     * @return \LogiCorpoBundle\Entity\Supplement 
     */
    public function getSupplements()
    {
        return $this->supplements;
    }

    /**
     * Set commande
     *
     * @param \LogiCorpoBundle\Entity\Commande $commande
     * @return ProduitsCommande
     */
    public function setCommande(\LogiCorpoBundle\Entity\Commande $commande)
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

    /**
     * Set produit
     *
     * @param \LogiCorpoBundle\Entity\Produit $produit
     * @return ProduitsCommande
     */
    public function setProduit(\LogiCorpoBundle\Entity\Produit $produit)
    {
        $this->produit = $produit;
        return $this;
    }

    /**
     * Get produit
     *
     * @return \LogiCorpoBundle\Entity\Produit 
     */
    public function getProduit()
    {
        return $this->produit;
    }

    public function getMontant() {
        $montant = $this->getProduit()->getPrixVente()*$this->getQuantite();
        if(sizeof($this->getSupplements()) > 0) {
            foreach ($this->getSupplements() as $supplement) {
                $montant += $supplements->getPrix();
            }
        }
        return $montant;
    }

    public function __toString()
    {
        return $this->produit->__toString();
    }
}
