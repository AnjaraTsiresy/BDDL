<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrototypeAccess
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PrototypeAccessRepository")
 */
class PrototypeAccess
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_page", type="integer")
     */
    private $nbPage;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="createur", type="string", length=255)
     */
    private $createur;

    /**
     * @var string
     *
     * @ORM\Column(name="mis_a_jour", type="string", length=255)
     */
    private $misAJour;

    /**
     * @var string
     *
     * @ORM\Column(name="phase", type="string", length=255)
     */
    private $phase;

    /**
     * @var string
     *
     * @ORM\Column(name="phase_etat", type="string", length=255)
     */
    private $phaseEtat;

    /**
     * @var string
     *
     * @ORM\Column(name="priorite_type", type="string", length=255)
     */
    private $prioriteType;


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
     * Set numero
     *
     * @param integer $numero
     * @return PrototypeAccess
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return PrototypeAccess
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
     * Set date
     *
     * @param \DateTime $date
     * @return PrototypeAccess
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
     * Set nbPage
     *
     * @param integer $nbPage
     * @return PrototypeAccess
     */
    public function setNbPage($nbPage)
    {
        $this->nbPage = $nbPage;

        return $this;
    }

    /**
     * Get nbPage
     *
     * @return integer 
     */
    public function getNbPage()
    {
        return $this->nbPage;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return PrototypeAccess
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set createur
     *
     * @param string $createur
     * @return PrototypeAccess
     */
    public function setCreateur($createur)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur
     *
     * @return string 
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * Set misAJour
     *
     * @param string $misAJour
     * @return PrototypeAccess
     */
    public function setMisAJour($misAJour)
    {
        $this->misAJour = $misAJour;

        return $this;
    }

    /**
     * Get misAJour
     *
     * @return string 
     */
    public function getMisAJour()
    {
        return $this->misAJour;
    }

    /**
     * Set phase
     *
     * @param string $phase
     * @return PrototypeAccess
     */
    public function setPhase($phase)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * Get phase
     *
     * @return string 
     */
    public function getPhase()
    {
        return $this->phase;
    }

    /**
     * Set phaseEtat
     *
     * @param string $phaseEtat
     * @return PrototypeAccess
     */
    public function setPhaseEtat($phaseEtat)
    {
        $this->phaseEtat = $phaseEtat;

        return $this;
    }

    /**
     * Get phaseEtat
     *
     * @return string 
     */
    public function getPhaseEtat()
    {
        return $this->phaseEtat;
    }

    /**
     * Set prioriteType
     *
     * @param string $prioriteType
     * @return PrototypeAccess
     */
    public function setPrioriteType($prioriteType)
    {
        $this->prioriteType = $prioriteType;

        return $this;
    }

    /**
     * Get prioriteType
     *
     * @return string 
     */
    public function getPrioriteType()
    {
        return $this->prioriteType;
    }
}
