<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Societe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\SocieteRepository")
 */
class Societe
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_societe", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="compteur", type="bigint")
     */
    private $compteur;

    /**
     * @var string
     *
     * @ORM\Column(name="codeTiers", type="string", length=255)
     */
    private $codeTiers;

    /**
     * @var string
     *
     * @ORM\Column(name="typeTiers", type="string", length=255)
     */
    private $typeTiers;

    /**
     * @var string
     *
     * @ORM\Column(name="comptaTiers", type="string", length=255, nullable=true)
     */
    private $comptaTiers;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="direction", type="string", length=255, nullable=true)
     */
    private $direction;

    /**
     * @var string
     *
     * @ORM\Column(name="departement", type="string", length=255, nullable=true)
     */
    private $departement;

    /**
     * @var string
     *
     * @ORM\Column(name="adress1", type="string", length=255, nullable=true)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="adress2", type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @var integer
     *
     * @ORM\Column(name="cp", type="integer")
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="telStandard", type="string", length=255, nullable=true)
     */
    private $telStandard;

    /**
     * @var string
     *
     * @ORM\Column(name="faxStandard", type="string", length=255, nullable=true)
     */
    private $faxStandard;

	/**
     * @ORM\OneToMany(targetEntity="SuffixeSociete", mappedBy="societe")
     */
    private $suffixeSocietes;
	
	/**
     * @ORM\OneToMany(targetEntity="Lexique", mappedBy="societe")
     */
    private $lexiques;
	
	/**
     * @ORM\OneToMany(targetEntity="PrototypeAccess", mappedBy="societe")
     */
    private $prototypeAccesss;
	
	/**
     * @ORM\OneToMany(targetEntity="VocabulaireSociete", mappedBy="societe")
     */
    private $vocabulaireSocietes;

    /**
     * @ORM\OneToMany(targetEntity="TempPdfLoaddatatheme", mappedBy="societe")
     */
    private $tempPdfLoaddatathemes;

    /**
     * @ORM\OneToMany(targetEntity="TableDesMatieresProto", mappedBy="societe")
     */
    private $tableDesMatieresProtos;

    public function __construct()
    {
		$this->suffixeSocietes = new ArrayCollection();
        $this->vocabulaireSocietes = new ArrayCollection();
		$this->lexiques = new ArrayCollection();
        $this->prototypeAccesss = new ArrayCollection();
        $this->tempPdfLoaddatathemes = new ArrayCollection();
        $this->tableDesMatieresProtos = new ArrayCollection();
    }

    public function addTableDesMatieresProtos(\Formation\VocabulaireBundle\Entity\TableDesMatieresProto $tableDesMatieresProto){
        $this->tableDesMatieresProtos[] = $tableDesMatieresProto;
        return $this;
    }

    public function removeTableDesMatieresProtos(\Formation\VocabulaireBundle\Entity\TableDesMatieresProto $tableDesMatieresProto){
        $this->tableDesMatieresProtos->removeElement($tableDesMatieresProto);
    }

    public function getTableDesMatieresProtos()
    {
        return $this->tableDesMatieresProtos;
    }

    public function addTempPdfLoaddatathemes(\Formation\VocabulaireBundle\Entity\TempPdfLoaddatatheme $tempPdfLoaddatatheme){
        $this->tempPdfLoaddatathemes[] = $tempPdfLoaddatatheme;
        return $this;
    }

    public function removeTempPdfLoaddatathemes(\Formation\VocabulaireBundle\Entity\TempPdfLoaddatatheme $tempPdfLoaddatatheme){
        $this->tempPdfLoaddatathemes->removeElement($tempPdfLoaddatatheme);
    }

    public function getTempPdfLoaddatathemes()
    {
        return $this->tempPdfLoaddatathemes;
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
	public function addLexique(\Formation\VocabulaireBundle\Entity\Lexique $lexique){
        $this->lexiques[] = $lexique;
        return $this;
    }

    public function removeLexique(\Formation\VocabulaireBundle\Entity\Lexique $lexique){
        $this->lexiques->removeElement($lexique);
    }

    public function getLexique()
    {
        return $this->lexiques;
    }
	
	public function addSuffixeSociete(\Formation\VocabulaireBundle\Entity\SuffixeSociete $suffixeSociete){
        $this->suffixeSocietes[] = $suffixeSociete;
        return $this;
    }

    public function removeSuffixeSociete(\Formation\VocabulaireBundle\Entity\SuffixeSociete $suffixeSociete){
        $this->suffixeSocietes->removeElement($suffixeSociete);
    }

    public function getSuffixeSociete()
    {
        return $this->suffixeSocietes;
    }

    public function addVocabulaireSociete(\Formation\VocabulaireBundle\Entity\VocabulaireSociete $vocabulaireSociete){
        $this->vocabulaireSocietes[] = $vocabulaireSociete;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\VocabulaireSociete $vocabulaireSociete){
        $this->vocabulaireSocietes->removeElement($vocabulaireSociete);
    }

    public function getVocabulaireSociete()
    {
        return $this->vocabulaireSocietes;
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
     * Set compteur
     *
     * @param integer $compteur
     * @return Societe
     */
    public function setCompteur($compteur)
    {
        $this->compteur = $compteur;

        return $this;
    }

    /**
     * Get compteur
     *
     * @return integer 
     */
    public function getCompteur()
    {
        return $this->compteur;
    }

    /**
     * Set codeTiers
     *
     * @param string $codeTiers
     * @return Societe
     */
    public function setCodeTiers($codeTiers)
    {
        $this->codeTiers = $codeTiers;

        return $this;
    }

    /**
     * Get codeTiers
     *
     * @return string 
     */
    public function getCodeTiers()
    {
        return $this->codeTiers;
    }

    /**
     * Set typeTiers
     *
     * @param string $typeTiers
     * @return Societe
     */
    public function setTypeTiers($typeTiers)
    {
        $this->typeTiers = $typeTiers;

        return $this;
    }

    /**
     * Get typeTiers
     *
     * @return string 
     */
    public function getTypeTiers()
    {
        return $this->typeTiers;
    }

    /**
     * Set comptaTiers
     *
     * @param string $comptaTiers
     * @return Societe
     */
    public function setComptaTiers($comptaTiers)
    {
        $this->comptaTiers = $comptaTiers;

        return $this;
    }

    /**
     * Get comptaTiers
     *
     * @return string 
     */
    public function getComptaTiers()
    {
        return $this->comptaTiers;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Societe
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set direction
     *
     * @param string $direction
     * @return Societe
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return string 
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set departement
     *
     * @param string $departement
     * @return Societe
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return string 
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set address1
     *
     * @param string $address1
     * @return Societe
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return Societe
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set cp
     *
     * @param integer $cp
     * @return Societe
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return integer 
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Societe
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set telStandard
     *
     * @param string $telStandard
     * @return Societe
     */
    public function setTelStandard($telStandard)
    {
        $this->telStandard = $telStandard;

        return $this;
    }

    /**
     * Get telStandard
     *
     * @return string 
     */
    public function getTelStandard()
    {
        return $this->telStandard;
    }

    /**
     * Set faxStandard
     *
     * @param string $faxStandard
     * @return Societe
     */
    public function setFaxStandard($faxStandard)
    {
        $this->faxStandard = $faxStandard;

        return $this;
    }

    /**
     * Get faxStandard
     *
     * @return string 
     */
    public function getFaxStandard()
    {
        return $this->faxStandard;
    }
}
