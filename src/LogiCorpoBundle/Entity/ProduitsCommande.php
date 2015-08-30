<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitsCommande
 *
 * @ORM\Entity
 * @ORM\Table(name="produits_commande", 
 *   uniqueConstraints = {
 *    @ORM\UniqueConstraint(name="produits_commande_id_commande_id_produit_id_supplement_key",
 *                          columns={"id_commande", "id_produit", "id_supplement"})
 *   },
 *   indexes = {
 *    @ORM\Index(name="IDX_91DC5EAF3E314AE8", columns={"id_commande"}),
 *    @ORM\Index(name="IDX_91DC5EAFF7384557", columns={"id_produit"})
 *   }
 * )
 */
class ProduitsCommande extends Produit
{

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Supplement
     *
     * @ORM\OneToOne(targetEntity="Supplement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_supplement", referencedColumnName="id_supplement")
     * })
     */
    private $supplement;

    /**
     * @var \Commande
     * @ORM\ManyToOne(targetEntity="Commande", inversedBy="produits")
     */
    private $commande;

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
     * Set supplement
     *
     * @param \LogiCorpoBundle\Entity\Supplement $supplement
     * @return ProduitsCommande
     */
    public function setSupplement(\LogiCorpoBundle\Entity\Supplement $supplement)
    {
        $this->supplement = $supplement;
        return $this;
    }

    /**
     * Get supplement
     *
     * @return \LogiCorpoBundle\Entity\Supplement 
     */
    public function getSupplement()
    {
        return $this->supplement;
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
}
