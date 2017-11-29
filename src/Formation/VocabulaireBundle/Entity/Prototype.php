<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Prototype
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\PrototypeRepository")
 */
class Prototype
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_prototype", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_prototype", type="string", length=255)
     */
    private $libellePrototype;

    /**
     * @ORM\OneToMany(targetEntity="VocabulairePrototype", mappedBy="prototype")
     */
    private $vocabulairePrototypes;

    public function __construct()
    {
        $this->vocabulairePrototypes = new ArrayCollection();
    }

    public function addVocabulairePrototype(\Formation\VocabulaireBundle\Entity\VocabulairePrototype $vocabulairePrototype){
        $this->vocabulairePrototypes[] = $vocabulairePrototype;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\VocabulairePrototype $vocabulairePrototype){
        $this->vocabulairePrototypes->removeElement($vocabulairePrototype);
    }

    public function getVocabulairePrototype()
    {
        return $this->vocabulairePrototypes;
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
     * Set libellePrototype
     *
     * @param string $libellePrototype
     * @return Prototype
     */
    public function setLibellePrototype($libellePrototype)
    {
        $this->libellePrototype = $libellePrototype;

        return $this;
    }

    /**
     * Get libellePrototype
     *
     * @return string 
     */
    public function getLibellePrototype()
    {
        return $this->libellePrototype;
    }
}
