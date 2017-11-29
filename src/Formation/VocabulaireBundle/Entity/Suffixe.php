<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Suffixe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\SuffixeRepository")
 */
class Suffixe
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_suffixe", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_suffixe", type="string", length=255)
     */
    private $libelleSuffixe;

    /**
     * @var string
     *
     * @ORM\Column(name="millesime", type="string", length=255)
     */
    private $millesime;
	
	/**
     * @ORM\OneToMany(targetEntity="SuffixeSociete", mappedBy="suffixe")
     */
    private $suffixeSocietes;

    public function __construct()
    {
        $this->suffixeSocietes = new ArrayCollection();
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
     * Set libelleSuffixe
     *
     * @param string $libelleSuffixe
     * @return Suffixe
     */
    public function setLibelleSuffixe($libelleSuffixe)
    {
        $this->libelleSuffixe = $libelleSuffixe;

        return $this;
    }

    /**
     * Get libelleSuffixe
     *
     * @return string 
     */
    public function getLibelleSuffixe()
    {
        return $this->libelleSuffixe;
    }

    /**
     * Set millesime
     *
     * @param string $millesime
     * @return Suffixe
     */
    public function setMillesime($millesime)
    {
        $this->millesime = $millesime;

        return $this;
    }

    /**
     * Get millesime
     *
     * @return string 
     */
    public function getMillesime()
    {
        return $this->millesime;
    }
}
