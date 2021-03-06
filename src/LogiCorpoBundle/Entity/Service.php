<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Service
 *
 * @ORM\Table(name="service", indexes={@ORM\Index(name="IDX_E19D9AD21F038BC2", columns={"caissier"})})
 * @ORM\Entity(
 *  repositoryClass="LogiCorpoBundle\Entity\ServiceRepository"
 * )
 */
class Service
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_service", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="service_id_service_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debut", type="datetime", nullable=false)
     * @Assert\NotBlank(message="La date et l'heure de début de service doivent être rentrées")
     */
    private $debut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin", type="datetime", nullable=true)
     */
    private $fin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debut_commande", type="datetime", nullable=true)
     */
    private $debutCommande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin_commande", type="datetime", nullable=true)
     */
    private $finCommande;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="caissier", referencedColumnName="id_utilisateur")
     * })
     */
    private $caissier;

    /**
     * @var \Commande
     *
     * @ORM\OneToMany(
     *   targetEntity="LogiCorpoBundle\Entity\Commande",
     *   mappedBy="service"
     * )
     */
    private $commandes;

    public function getCommandes() {
        return $this->commandes;
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
     * Set debut
     *
     * @param \DateTime $debut
     * @return Service
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime 
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     * @return Service
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime 
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set debutCommande
     *
     * @param \DateTime $debutCommande
     * @return Service
     */
    public function setDebutCommande($debutCommande)
    {
        $this->debutCommande = $debutCommande;

        return $this;
    }

    /**
     * Get debutCommande
     *
     * @return \DateTime 
     */
    public function getDebutCommande()
    {
        return $this->debutCommande;
    }

    /**
     * Set finCommande
     *
     * @param \DateTime $finCommande
     * @return Service
     */
    public function setFinCommande($finCommande)
    {
        $this->finCommande = $finCommande;

        return $this;
    }

    /**
     * Get finCommande
     *
     * @return \DateTime 
     */
    public function getFinCommande()
    {
        return $this->finCommande;
    }

    /**
     * Set caissier
     *
     * @param \LogiCorpoBundle\Entity\Utilisateur $caissier
     * @return Service
     */
    public function setCaissier(\LogiCorpoBundle\Entity\Utilisateur $caissier = null)
    {
        $this->caissier = $caissier;

        return $this;
    }

    /**
     * Get caissier
     *
     * @return \LogiCorpoBundle\Entity\Utilisateur 
     */
    public function getCaissier()
    {
        return $this->caissier;
    }
}
