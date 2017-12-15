<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Vocabulaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulaireRepository")
 */
class Vocabulaire {

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
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
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

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireDepartement", mappedBy="vocabulaire")
     */
    private $vocabulaireDepartements;

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireSecteur", mappedBy="vocabulaire")
     */
    private $vocabulaireSecteurs;

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireTheme", mappedBy="vocabulaire")
     */
    private $vocabulaireThemes;

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireFonction", mappedBy="vocabulaire")
     */
    private $vocabulaireFonctions;

    /**
     * @ORM\OneToMany(targetEntity="VocabulairePhraseSource", mappedBy="vocabulaire")
     */
    private $vocabulairePhraseSources;

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireEnvirUsage", mappedBy="vocabulaire")
     */
    private $vocabulaireEnvironnementUsages;

    /**
     * @ORM\OneToMany(targetEntity="VocabulairePrototype", mappedBy="vocabulaire")
     */
    private $vocabulairePrototypes;

    /**
     * @ORM\OneToMany(targetEntity="VocabulairePrototypeAccess", mappedBy="vocabulaire")
     */
    private $vocabulairePrototypeAccesss;

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireTraducteur", mappedBy="vocabulaire")
     */
    private $vocabulaireTraducteurs;

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireSociete", mappedBy="vocabulaire")
     */
    private $vocabulaireSocietes;

    public function __construct() {
        $this->vocabulaireSocietes = new ArrayCollection();
        $this->vocabulairePhraseSources = new ArrayCollection();
        $this->vocabulaireSecteurs = new ArrayCollection();
        $this->vocabulaireDepartements = new ArrayCollection();
        $this->vocabulaireThemes = new ArrayCollection();
        $this->vocabulaireFonctions = new ArrayCollection();
        $this->vocabulaireEnvironnementUsages = new ArrayCollection();
        $this->vocabulairePrototypes = new ArrayCollection();
        $this->vocabulaireTraducteurs = new ArrayCollection();
        $this->vocabulairePrototypeAccesss = new ArrayCollection();
    }

    public function addVocabulairePrototypeAcces(\Formation\VocabulaireBundle\Entity\VocabulairePrototypeAccess $vocabulairePrototypeAcces) {
        $this->vocabulairePrototypeAccesss[] = $vocabulairePrototypeAcces;
        return $this;
    }

    public function removeVocabulairePrototypeAcces(\Formation\VocabulaireBundle\Entity\VocabulairePrototypeAccess $vocabulairePrototypeAcces) {
        $this->vocabulairePrototypeAccesss->removeElement($vocabulairePrototypeAcces);
    }

    public function getVocabulairePrototypeAcces() {
        return $this->vocabulairePrototypeAccesss;
    }

    public function addVocabulaireSociete(\Formation\VocabulaireBundle\Entity\VocabulaireSociete $vocabulaireSociete) {
        $this->vocabulaireSocietes[] = $vocabulaireSociete;
        return $this;
    }

    public function removeVocabulaireSociete(\Formation\VocabulaireBundle\Entity\VocabulaireSociete $vocabulaireSociete) {
        $this->vocabulaireSocietes->removeElement($vocabulaireSociete);
    }

    public function getVocabulaireSociete() {
        return $this->vocabulaireSocietes;
    }

    public function addVocabulaireTraducteur(\Formation\VocabulaireBundle\Entity\VocabulaireTraducteur $vocabulaireTraducteur) {
        $this->vocabulaireTraducteurs[] = $vocabulaireTraducteur;
        return $this;
    }

    public function removeVocabulaireTraducteur(\Formation\VocabulaireBundle\Entity\VocabulaireTraducteur $vocabulaireTraducteur) {
        $this->vocabulaireTraducteurs->removeElement($vocabulaireTraducteur);
    }

    public function getVocabulaireTraducteur() {
        return $this->vocabulaireTraducteurs;
    }

    public function addVocabulairePrototype(\Formation\VocabulaireBundle\Entity\VocabulairePrototype $vocabulairePrototype) {
        $this->vocabulairePrototypes[] = $vocabulairePrototype;
        return $this;
    }

    public function removeVocabulairePrototype(\Formation\VocabulaireBundle\Entity\VocabulairePrototype $vocabulairePrototype) {
        $this->vocabulairePrototypes->removeElement($vocabulairePrototype);
    }

    public function getVocabulairePrototype() {
        return $this->vocabulairePrototypes;
    }

    public function addVocabulairePhraseSource(\Formation\VocabulaireBundle\Entity\VocabulairePhraseSource $vocabulairePhraseSource) {
        $this->vocabulairePhraseSources[] = $vocabulairePhraseSource;
        return $this;
    }

    public function removeVocabulairePhraseSource(\Formation\VocabulaireBundle\Entity\VocabulairePhraseSource $vocabulairePhraseSource) {
        $this->vocabulairePhraseSources->removeElement($vocabulairePhraseSource);
    }

    public function getVocabulairePhraseSource() {
        return $this->vocabulairePhraseSources;
    }

    public function addVocabulaireEnvironnementUsage(\Formation\VocabulaireBundle\Entity\VocabulaireEnvirUsage $vocabulaireEnvironnementUsage) {
        $this->vocabulaireEnvironnementUsages[] = $vocabulaireEnvironnementUsage;
        return $this;
    }

    public function removeVocabulaireEnvironnementUsage(\Formation\VocabulaireBundle\Entity\VocabulaireEnvirUsage $vocabulaireEnvironnementUsage) {
        $this->vocabulaireEnvironnementUsages->removeElement($vocabulaireEnvironnementUsage);
    }

    public function getVocabulaireEnvironnementUsage() {
        return $this->vocabulaireEnvironnementUsages;
    }

    public function addVocabulaireTheme(\Formation\VocabulaireBundle\Entity\VocabulaireTheme $vocabulaireTheme) {
        $this->vocabulaireThemes[] = $vocabulaireTheme;
        return $this;
    }

    public function removeVocabulaireTheme(\Formation\VocabulaireBundle\Entity\VocabulaireTheme $vocabulaireTheme) {
        $this->vocabulaireThemes->removeElement($vocabulaireTheme);
    }

    public function getVocabulaireTheme() {
        return $this->vocabulaireThemes;
    }

    public function addVocabulaireFonction(\Formation\VocabulaireBundle\Entity\VocabulaireFonction $vocabulaireFonction) {
        $this->vocabulaireFonctions[] = $vocabulaireFonction;
        return $this;
    }

    public function removeVocabulaireFonction(\Formation\VocabulaireBundle\Entity\VocabulaireFonction $vocabulaireFonction) {
        $this->vocabulaireFonctions->removeElement($vocabulaireFonction);
    }

    public function getVocabulaireFonction() {
        return $this->vocabulaireFonctions;
    }

    public function addVocabulaireSecteur(\Formation\VocabulaireBundle\Entity\VocabulaireSecteur $vocabulaireSecteur) {
        $this->vocabulaireSecteurs[] = $vocabulaireSecteur;
        return $this;
    }

    public function removeVocabulaireSecteur(\Formation\VocabulaireBundle\Entity\VocabulaireSecteur $vocabulaireSecteur) {
        $this->vocabulaireSecteurs->removeElement($vocabulaireSecteur);
    }

    public function getVocabulaireSecteur() {
        return $this->vocabulaireSecteurs;
    }

    public function addVocabulaireDepartement(\Formation\VocabulaireBundle\Entity\VocabulaireDepartement $vocabulaireDepartement) {
        $this->vocabulaireDepartements[] = $vocabulaireDepartement;
        return $this;
    }

    public function removeVocabulaireDepartement(\Formation\VocabulaireBundle\Entity\VocabulaireDepartement $vocabulaireDepartement) {
        $this->vocabulaireDepartements->removeElement($vocabulaireDepartement);
    }

    public function getVocabulaireDepartement() {
        return $this->vocabulaireDepartements;
    }

    public function setLanguage(\Formation\VocabulaireBundle\Entity\Language $language) {
        $this->language = $language;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function setSource(\Formation\VocabulaireBundle\Entity\Source $source) {
        $this->source = $source;
    }

    public function getSource() {
        return $this->source;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Vocabulaire
     */
    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation() {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Vocabulaire
     */
    public function setDateModification($dateModification) {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification() {
        return $this->dateModification;
    }

    /**
     * Set langueOrigine
     *
     * @param string $langueOrigine
     * @return Vocabulaire
     */
    public function setLangueOrigine($langueOrigine) {
        $this->langueOrigine = $langueOrigine;

        return $this;
    }

    /**
     * Get langueOrigine
     *
     * @return string 
     */
    public function getLangueOrigine() {
        return $this->langueOrigine;
    }

    /**
     * Set langueOrigineSansModif
     *
     * @param string $langueOrigineSansModif
     * @return Vocabulaire
     */
    public function setLangueOrigineSansModif($langueOrigineSansModif) {
        $this->langueOrigineSansModif = $langueOrigineSansModif;

        return $this;
    }

    /**
     * Get langueOrigineSansModif
     *
     * @return string 
     */
    public function getLangueOrigineSansModif() {
        return $this->langueOrigineSansModif;
    }

    /**
     * Set langueTraduction
     *
     * @param string $langueTraduction
     * @return Vocabulaire
     */
    public function setLangueTraduction($langueTraduction) {
        $this->langueTraduction = $langueTraduction;

        return $this;
    }

    /**
     * Get langueTraduction
     *
     * @return string 
     */
    public function getLangueTraduction() {
        return $this->langueTraduction;
    }

    /**
     * Set rang
     *
     * @param string $rang
     * @return Vocabulaire
     */
    public function setRang($rang) {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang
     *
     * @return string 
     */
    public function getRang() {
        return $this->rang;
    }

    /**
     * Set isAffiche
     *
     * @param integer $isAffiche
     * @return Vocabulaire
     */
    public function setIsAffiche($isAffiche) {
        $this->isAffiche = $isAffiche;

        return $this;
    }

    /**
     * Get isAffiche
     *
     * @return integer 
     */
    public function getIsAffiche() {
        return $this->isAffiche;
    }

    /**
     * Set nbreCaractLo
     *
     * @param integer $nbreCaractLo
     * @return Vocabulaire
     */
    public function setNbreCaractLo($nbreCaractLo) {
        $this->nbreCaractLo = $nbreCaractLo;

        return $this;
    }

    /**
     * Get nbreCaractLo
     *
     * @return integer 
     */
    public function getNbreCaractLo() {
        return $this->nbreCaractLo;
    }

    /**
     * Set nbreLigneLo
     *
     * @param string $nbreLigneLo
     * @return Vocabulaire
     */
    public function setNbreLigneLo($nbreLigneLo) {
        $this->nbreLigneLo = $nbreLigneLo;

        return $this;
    }

    /**
     * Get nbreLigneLo
     *
     * @return string 
     */
    public function getNbreLigneLo() {
        return $this->nbreLigneLo;
    }

}
