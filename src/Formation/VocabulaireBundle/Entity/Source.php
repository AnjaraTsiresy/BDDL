<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\SourceRepository")
 */
class Source
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_source", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="source_type", type="string", length=255)
     */
    private $sourceType;

    /**
     * @var string
     *
     * @ORM\Column(name="source_nom_stagiaire", type="string", length=255)
     */
    private $sourceNomStagiaire;

    /**
     * @var string
     *
     * @ORM\Column(name="lien_nom_doc", type="string", length=255)
     */
    private $lienNomDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=255)
     */
    private $lien;

    /**
     * @ORM\OneToMany(targetEntity="Vocabulaire", mappedBy="source")
     */
    private $vocabulaires;

    public function __construct()
    {
        $this->vocabulaires = new ArrayCollection();
    }

    public function addVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire){
        $this->vocabulaires[] = $vocabulaire;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire){
        $this->vocabulaires->removeElement($vocabulaire);
    }

    public function getVocabulaires()
    {
        return $this->vocabulaires;
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
     * Set sourceType
     *
     * @param string $sourceType
     * @return Source
     */
    public function setSourceType($sourceType)
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    /**
     * Get sourceType
     *
     * @return string 
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }

    /**
     * Set sourceNomStagiaire
     *
     * @param string $sourceNomStagiaire
     * @return Source
     */
    public function setSourceNomStagiaire($sourceNomStagiaire)
    {
        $this->sourceNomStagiaire = $sourceNomStagiaire;

        return $this;
    }

    /**
     * Get sourceNomStagiaire
     *
     * @return string 
     */
    public function getSourceNomStagiaire()
    {
        return $this->sourceNomStagiaire;
    }

    /**
     * Set lienNomDoc
     *
     * @param string $lienNomDoc
     * @return Source
     */
    public function setLienNomDoc($lienNomDoc)
    {
        $this->lienNomDoc = $lienNomDoc;

        return $this;
    }

    /**
     * Get lienNomDoc
     *
     * @return string 
     */
    public function getLienNomDoc()
    {
        return $this->lienNomDoc;
    }

    /**
     * Set lien
     *
     * @param string $lien
     * @return Source
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string 
     */
    public function getLien()
    {
        return $this->lien;
    }
}
