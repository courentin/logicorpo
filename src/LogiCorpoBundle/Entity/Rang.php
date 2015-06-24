<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rang
 *
 * @ORM\Table(name="rang")
 * @ORM\Entity(readOnly=true)
 */
class Rang
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="slug", type="text", nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="text", nullable=false)
     * @Assert\NotBlank(message="Le nom du rang doit être renseignée")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="reduction", type="decimal", precision=8, scale=2, nullable=true)
     */
    private $reduction;

    /**
     * @var string
     *
     * @ORM\Column(name="type_reduc", type="text", nullable=true)
     */
    private $typeReduc;

    /**
     * Get slug
     *
     * @return integer 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Rang
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set reduction
     *
     * @param string $reduction
     * @return Rang
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return string 
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * Set typeReduc
     *
     * @param string $typeReduc
     * @return Rang
     */
    public function setTypeReduc($typeReduc)
    {
        $this->typeReduc = $typeReduc;

        return $this;
    }

    /**
     * Get typeReduc
     *
     * @return string 
     */
    public function getTypeReduc()
    {
        return $this->typeReduc;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Rang
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function __toString() {
        return $this->getNom();
    }
}
