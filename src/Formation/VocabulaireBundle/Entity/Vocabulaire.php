<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vocabulaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulaireRepository")
 */
class Vocabulaire
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vocabulaire", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime")
     */
    private $dateModification;

    /**
     * @var string
     *
     * @ORM\Column(name="langue_origine", type="string", length=255)
     */
    private $langueOrigine;

    /**
     * @var string
     *
     * @ORM\Column(name="langue_origine_sans_modif", type="string", length=255)
     */
    private $langueOrigineSansModif;

    /**
     * @var string
     *
     * @ORM\Column(name="langue_traduction", type="string", length=255)
     */
    private $langueTraduction;

    /**
     * @var string
     *
     * @ORM\Column(name="rang", type="string", length=255)
     */
    private $rang;

    /**
     * @var integer
     *
     * @ORM\Column(name="isAffiche", type="integer")
     */
    private $isAffiche;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbreCaractLo", type="integer")
     */
    private $nbreCaractLo;

    /**
     * @var string
     *
     * @ORM\Column(name="nbreLigneLo", type="string", length=255)
     */
    private $nbreLigneLo;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="vocabulaires")
     * @ORM\JoinColumn(name="id_language", referencedColumnName="id_language")
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="vocabulaires")
     * @ORM\JoinColumn(name="id_source", referencedColumnName="id_source")
     */
    private $source;

    public function setLanguage(\Formation\VocabulaireBundle\Entity\Language $language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setSource(\Formation\VocabulaireBundle\Entity\Source $source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Vocabulaire
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Vocabulaire
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set langueOrigine
     *
     * @param string $langueOrigine
     * @return Vocabulaire
     */
    public function setLangueOrigine($langueOrigine)
    {
        $this->langueOrigine = $langueOrigine;

        return $this;
    }

    /**
     * Get langueOrigine
     *
     * @return string 
     */
    public function getLangueOrigine()
    {
        return $this->langueOrigine;
    }

    /**
     * Set langueOrigineSansModif
     *
     * @param string $langueOrigineSansModif
     * @return Vocabulaire
     */
    public function setLangueOrigineSansModif($langueOrigineSansModif)
    {
        $this->langueOrigineSansModif = $langueOrigineSansModif;

        return $this;
    }

    /**
     * Get langueOrigineSansModif
     *
     * @return string 
     */
    public function getLangueOrigineSansModif()
    {
        return $this->langueOrigineSansModif;
    }

    /**
     * Set langueTraduction
     *
     * @param string $langueTraduction
     * @return Vocabulaire
     */
    public function setLangueTraduction($langueTraduction)
    {
        $this->langueTraduction = $langueTraduction;

        return $this;
    }

    /**
     * Get langueTraduction
     *
     * @return string 
     */
    public function getLangueTraduction()
    {
        return $this->langueTraduction;
    }

    /**
     * Set rang
     *
     * @param string $rang
     * @return Vocabulaire
     */
    public function setRang($rang)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang
     *
     * @return string 
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set isAffiche
     *
     * @param integer $isAffiche
     * @return Vocabulaire
     */
    public function setIsAffiche($isAffiche)
    {
        $this->isAffiche = $isAffiche;

        return $this;
    }

    /**
     * Get isAffiche
     *
     * @return integer 
     */
    public function getIsAffiche()
    {
        return $this->isAffiche;
    }

    /**
     * Set nbreCaractLo
     *
     * @param integer $nbreCaractLo
     * @return Vocabulaire
     */
    public function setNbreCaractLo($nbreCaractLo)
    {
        $this->nbreCaractLo = $nbreCaractLo;

        return $this;
    }

    /**
     * Get nbreCaractLo
     *
     * @return integer 
     */
    public function getNbreCaractLo()
    {
        return $this->nbreCaractLo;
    }

    /**
     * Set nbreLigneLo
     *
     * @param string $nbreLigneLo
     * @return Vocabulaire
     */
    public function setNbreLigneLo($nbreLigneLo)
    {
        $this->nbreLigneLo = $nbreLigneLo;

        return $this;
    }

    /**
     * Get nbreLigneLo
     *
     * @return string 
     */
    public function getNbreLigneLo()
    {
        return $this->nbreLigneLo;
    }
}
