<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PhraseSource
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\PhraseSourceRepository")
 */
class PhraseSource
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_phrase_source", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_phrase_source", type="string", length=255)
     */
    private $libellePhraseSource;

    /**
     * @ORM\OneToMany(targetEntity="VocabulairePhraseSource", mappedBy="phrase_source")
     */
    private $vocabulairePhraseSources;

    public function __construct()
    {
        $this->vocabulairePhraseSources = new ArrayCollection();
    }

    public function addVocabulairePhraseSource(\Formation\VocabulaireBundle\Entity\VocabulairePhraseSource $vocabulairePhraseSource){
        $this->vocabulairePhraseSources[] = $vocabulairePhraseSource;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\VocabulairePhraseSource $vocabulairePhraseSource){
        $this->vocabulairePhraseSources->removeElement($vocabulairePhraseSource);
    }

    public function getVocabulairePhraseSource()
    {
        return $this->vocabulairePhraseSources;
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
     * Set libellePhraseSource
     *
     * @param string $libellePhraseSource
     * @return PhraseSource
     */
    public function setLibellePhraseSource($libellePhraseSource)
    {
        $this->libellePhraseSource = $libellePhraseSource;

        return $this;
    }

    /**
     * Get libellePhraseSource
     *
     * @return string 
     */
    public function getLibellePhraseSource()
    {
        return $this->libellePhraseSource;
    }
}
