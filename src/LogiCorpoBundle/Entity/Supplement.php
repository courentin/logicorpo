<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Supplement
 *
 * @ORM\Table(name="supplement")
 * @ORM\Entity
 */
class Supplement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_supplement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="supplement_id_supplement_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="text", nullable=false)
     * @Assert\NotBlank(message="Le libelle doit être renseigné")
     */
    private $libelle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="dispo", type="boolean", nullable=false)
     * @Assert\NotBlank(message="Il est nécessaire d'indiquer si le supplément est disponible ou non")
     */
    private $dispo = true;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=8, scale=2, nullable=false)
     * @Assert\NotBlank(message="Il est nécessaire de rentrer le prix")
     * @Assert\Type(type="numeric",message = "Le prix doit être une valeur décimale.")
     */
    private $prix;


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
     * @return Supplement
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
     * @param boolean $stock
     * @return Supplement
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return boolean 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set prix
     *
     * @param string $prix
     * @return Supplement
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    public function getDispo()
    {
        return $this->dispo;
    }

    public function setDispo($dispo)
    {
        $this->dispo = $dispo;
        return $this;
    }

    public function __toString() {
        return $this->getLibelle();
    }
}
