<?php

namespace LogiCorpoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur", uniqueConstraints={@ORM\UniqueConstraint(name="utilisateur_login_key", columns={"login"})})
 * @ORM\Entity
 * @UniqueEntity("username")
 */

class Utilisateur implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="utilisateur_id_utilisateur_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="text", nullable=false, unique=true)
     * @Assert\NotBlank(message="Le login doit être renseigné")
     * @Assert\Length(min="3",max="15", minMessage="Le login doit contenir au moins 3 caractères", maxMessage="Le login ne doit pas dépasser 15 caractères.")
     * @Assert\Regex("/^[A-z0-9_]*$/", message="Le format du login est incorrect (lettres, chiffres, underscores)")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="text", nullable=false)
     */
    private $salt;


    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="text", nullable=false)
     * @Assert\NotBlank(message="Le nom doit être renseigné")
     * @Assert\Length(min="2", minMessage="Le nom doit contenir au moins 3 caractères")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="text", nullable=false)
     * @Assert\NotBlank(message="Le prénom doit être renseigné")
     * @Assert\Length(min="3", minMessage="Le le prénom doit contenir au moins 3 caractères")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="text", nullable=false)
     * @Assert\NotBlank(message="Le mot de passe doit être renseigné")
     * @Assert\Length(min="5", minMessage="Le mot de passe doit contenir au moins 5 caractères")
     */
    private $password;

    /**
     * @var decimal
     *
     * @ORM\Column(name="solde", type="decimal", precision=8, scale=2, nullable=true)
     * @Assert\NotBlank(message="Le solde doit être renseigné")
     * @Assert\Type(type="numeric", message = "solde doit être de type numérique")
     */
    private $solde = 0.00;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_log", type="datetime", nullable=true)
     */
    private $lastLog;

    /**
     * @var \Rang
     *
     * @ORM\ManyToOne(targetEntity="Rang")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="slug", referencedColumnName="slug", nullable=false)
     * })
     */
    private $rang;

    public function __construct() {
        $this->salt = md5(uniqid(rand(),true));
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
     * Set login
     *
     * @param string $login
     * @return Utilisateur
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set mdp
     *
     * @param string $mdp
     * @return Utilisateur
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Set nom
     *
     * @param string $nom
     * @return Utilisateur
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
     * Set prenom
     *
     * @param string $prenom
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }



    /**
     * Set solde
     *
     * @param string $solde
     * @return Utilisateur
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return string 
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * Set lastLog
     *
     * @param \DateTime $lastLog
     * @return Utilisateur
     */
    public function setLastLog($lastLog)
    {
        $this->lastLog = $lastLog;

        return $this;
    }

    /**
     * Get lastLog
     *
     * @return \DateTime 
     */
    public function getLastLog()
    {
        return $this->lastLog;
    }

    /**
     * Set rang
     *
     * @param \LogiCorpoBundle\Entity\Rang $rang
     * @return Utilisateur
     */
    public function setRang(\LogiCorpoBundle\Entity\Rang $rang = null)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang
     *
     * @return \LogiCorpoBundle\Entity\Rang 
     */
    public function getRang()
    {
        return $this->rang;
    }

    public function getRoles() {

        return ['ROLE_'.$this->getRang()->getSlug()];
    }

    public function getSalt() {
        return $this->salt;
    }

    public function eraseCredentials() {

    }


    /**
     * Set salt
     *
     * @param string $salt
     * @return Utilisateur
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    public function __toString() {
        return $this->getUsername();
    }
}
