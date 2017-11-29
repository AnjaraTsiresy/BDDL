<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulairePhraseSource
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulairePhraseSourceRepository")
 */
class VocabulairePhraseSource
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vocabulaire_phrase_source", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Vocabulaire", inversedBy="vocabulairePhraseSources")
     * @ORM\JoinColumn(name="id_vocabulaire", referencedColumnName="id_vocabulaire")
     */
    private $vocabulaire;

    /**
     * @ORM\ManyToOne(targetEntity="PhraseSource", inversedBy="vocabulairePhraseSources")
     * @ORM\JoinColumn(name="id_phrase_source", referencedColumnName="id_phrase_source")
     */
    private $phrase_source;

    public function setVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setPhraseSource(\Formation\VocabulaireBundle\Entity\PhraseSource $phrase_source)
    {
        $this->phrase_source = $phrase_source;
    }

    public function getPhraseSource()
    {
        return $this->phrase_source;
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
}
