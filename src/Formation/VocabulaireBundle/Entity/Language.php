<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\LanguageRepository")
 */
class Language
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_language", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_language", type="string", length=255)
     */
    private $libelle_language;

    /**
     * @ORM\OneToMany(targetEntity="Vocabulaire", mappedBy="language")
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
     * Set libelle_language
     *
     * @param string $libelle_language
     * @return Language
     */
    public function setLibelleLanguage($libelle_language)
    {
        $this->libelle_language = $libelle_language;

        return $this;
    }

    /**
     * Get libelle_language
     *
     * @return string 
     */
    public function getLibelle_language()
    {
        return $this->libelle_language;
    }
}
