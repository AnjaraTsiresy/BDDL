<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Traducteur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\TraducteurRepository")
 */
class Traducteur
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_traducteur", type="integer")
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
     * @ORM\Column(name="fonction", type="string", length=255)
     */
    private $fonction;

    /**
     * @ORM\OneToMany(targetEntity="PrototypeAccess", mappedBy="traducteur")
     */
    private $prototypeAccesss;


    /**
     * @ORM\OneToMany(targetEntity="VocabulaireTraducteur", mappedBy="traducteur")
     */
    private $vocabulaireTraducteurs;



    public function __construct()
    {
        $this->vocabulaireTraducteurs = new ArrayCollection();
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
    public function addVocabulaireTraducteur(\Formation\VocabulaireBundle\Entity\VocabulaireTraducteur $vocabulaireTraducteur){
        $this->vocabulaireTraducteurs[] = $vocabulaireTraducteur;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\VocabulaireTraducteur $vocabulaireTraducteur){
        $this->vocabulaireTraducteurs->removeElement($vocabulaireTraducteur);
    }

    public function getVocabulaireTraducteur()
    {
        return $this->vocabulaireTraducteurs;
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
     * @return Traducteur
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
     * Set fonction
     *
     * @param string $fonction
     * @return Traducteur
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string 
     */
    public function getFonction()
    {
        return $this->fonction;
    }
}
