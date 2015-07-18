<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="config")
 * @ORM\Entity
 */
class Config
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_config", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="config_id_config_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="seuil", type="decimal", precision=8, scale=2, nullable=false)
     */
    private $seuil;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="default_debut_service", type="time", nullable=true)
     */
    private $defaultDebutService;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="default_fin_service", type="time", nullable=true)
     */
    private $defaultFinService;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="default_debut_commande", type="time", nullable=true)
     */
    private $defaultDebutCommande;

    /**
     * @var boolean
     *
     * @ORM\Column(name="interdiction_commande_seuil", type="boolean", nullable=true)
     */
    private $interdictionCommandeSeuil;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="default_fin_commande", type="time", nullable=true)
     */
    private $defaultFinCommande;


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
     * Set seuil
     *
     * @param string $seuil
     * @return Config
     */
    public function setSeuil($seuil)
    {
        $this->seuil = $seuil;

        return $this;
    }

    /**
     * Get seuil
     *
     * @return string 
     */
    public function getSeuil()
    {
        return $this->seuil;
    }

    /**
     * Set defaultDebutService
     *
     * @param \DateTime $defaultDebutService
     * @return Config
     */
    public function setDefaultDebutService($defaultDebutService)
    {
        $this->defaultDebutService = $defaultDebutService;

        return $this;
    }

    /**
     * Get defaultDebutService
     *
     * @return \DateTime 
     */
    public function getDefaultDebutService()
    {
        return $this->defaultDebutService;
    }

    /**
     * Set defaultFinService
     *
     * @param \DateTime $defaultFinService
     * @return Config
     */
    public function setDefaultFinService($defaultFinService)
    {
        $this->defaultFinService = $defaultFinService;

        return $this;
    }

    /**
     * Get defaultFinService
     *
     * @return \DateTime 
     */
    public function getDefaultFinService()
    {
        return $this->defaultFinService;
    }

    /**
     * Set defaultDebutCommande
     *
     * @param \DateTime $defaultDebutCommande
     * @return Config
     */
    public function setDefaultDebutCommande($defaultDebutCommande)
    {
        $this->defaultDebutCommande = $defaultDebutCommande;

        return $this;
    }

    /**
     * Get defaultDebutCommande
     *
     * @return \DateTime 
     */
    public function getDefaultDebutCommande()
    {
        return $this->defaultDebutCommande;
    }

    /**
     * Set interdictionCommandeSeuil
     *
     * @param boolean $interdictionCommandeSeuil
     * @return Config
     */
    public function setInterdictionCommandeSeuil($interdictionCommandeSeuil)
    {
        $this->interdictionCommandeSeuil = $interdictionCommandeSeuil;

        return $this;
    }

    /**
     * Get interdictionCommandeSeuil
     *
     * @return boolean 
     */
    public function getInterdictionCommandeSeuil()
    {
        return $this->interdictionCommandeSeuil;
    }

    /**
     * Set defaultFinCommande
     *
     * @param \DateTime $defaultFinCommande
     * @return Config
     */
    public function setDefaultFinCommande($defaultFinCommande)
    {
        $this->defaultFinCommande = $defaultFinCommande;

        return $this;
    }

    /**
     * Get defaultFinCommande
     *
     * @return \DateTime 
     */
    public function getDefaultFinCommande()
    {
        return $this->defaultFinCommande;
    }
}
