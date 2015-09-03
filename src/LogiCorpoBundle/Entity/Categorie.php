<?php
namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="Categorie")
 */
class Categorie
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="id_categorie")
	 * @ORM\GeneratedValue
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", name="libelle")
	 */
	private $libelle;

	/**
	 * @ORM\Column(type="string", name="libelle_pluriel")
	 */
	private $libellePluriel;

	/**
	 * @ORM\Column(type="integer", name="ordre")
	 */
	private $ordre = null;

	/**
	 * @ORM\OneToMany(targetEntity="Produit", mappedBy="categorie")
	 */
	private $produits;


	public function __construct() {
		$this->produits = new ArrayCollection();
	}


	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}


	public function getLibelle() {
		return $this->libelle;
	}

	public function setLibelle($libelle) {
		$this->libelle = $libelle;
		return $this;
	}


	public function getLibellePluriel() {
		if(isset($this->libellePluriel) && !empty($this->libellePluriel))
			return $this->libellePluriel;
		return $this->libelle . 's';
	}

	public function setLibellePluriel($libellePluriel) {
		$this->libellePluriel = $libellePluriel;
		return $this;
	}


	public function getOrdre() {
		return $this->ordre;
	}

	public function setOrdre($ordre) {
		$this->ordre = $ordre;
		return $this;
	}


	public function getProduits() {
		return $this->produits;
	}

	public function __toString() {
		return $this->libelle;
	}
}