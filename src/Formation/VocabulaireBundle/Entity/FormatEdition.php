<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FormatEdition
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\FormatEditionRepository")
 */
class FormatEdition
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_format_edition", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="largeur", type="string", length=255)
     */
    private $largeur;

    /**
     * @var string
     *
     * @ORM\Column(name="longueur", type="string", length=255)
     */
    private $longueur;

    /**
     * @var string
     *
     * @ORM\Column(name="max_termes", type="string", length=255)
     */
    private $maxTermes;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=255)
     */
    private $format;
	
	/**
     * @ORM\OneToMany(targetEntity="PrototypeAccess", mappedBy="formatEdition")
     */
    private $prototypeAccesss;
	
	public function __construct()
    {
        $this->prototypeAccesss = new ArrayCollection();
    }

    public function addPrototypeAccess(\Formation\VocabulaireBundle\Entity\PrototypeAccess $prototypeAccess){
        $this->prototypeAccesss[] = $prototypeAccess;
        return $this;
    }

    public function removePrototypeAccess(\Formation\VocabulaireBundle\Entity\PrototypeAccess $prototypeAccess){
        $this->prototypeAccesss->removeElement($prototypeAccess);
    }

    public function getPrototypeAccess()
    {
        return $this->prototypeAccesss;
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
     * Set nom
     *
     * @param string $nom
     * @return FormatEdition
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
     * Set largeur
     *
     * @param string $largeur
     * @return FormatEdition
     */
    public function setLargeur($largeur)
    {
        $this->largeur = $largeur;

        return $this;
    }

    /**
     * Get largeur
     *
     * @return string 
     */
    public function getLargeur()
    {
        return $this->largeur;
    }

    /**
     * Set longueur
     *
     * @param string $longueur
     * @return FormatEdition
     */
    public function setLongueur($longueur)
    {
        $this->longueur = $longueur;

        return $this;
    }

    /**
     * Get longueur
     *
     * @return string 
     */
    public function getLongueur()
    {
        return $this->longueur;
    }

    /**
     * Set maxTermes
     *
     * @param string $maxTermes
     * @return FormatEdition
     */
    public function setMaxTermes($maxTermes)
    {
        $this->maxTermes = $maxTermes;

        return $this;
    }

    /**
     * Get maxTermes
     *
     * @return string 
     */
    public function getMaxTermes()
    {
        return $this->maxTermes;
    }

    /**
     * Set format
     *
     * @param string $format
     * @return FormatEdition
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return string 
     */
    public function getFormat()
    {
        return $this->format;
    }
}
