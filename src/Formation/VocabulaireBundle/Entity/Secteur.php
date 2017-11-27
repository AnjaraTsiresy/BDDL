<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Secteur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\SecteurRepository")
 */
class Secteur
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_secteur", type="string", length=255)
     */
    private $libelleSecteur;

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireSecteur", mappedBy="secteur")
     */
    private $vocabulaireSecteurs;

    public function __construct()
    {
        $this->vocabulaireSecteurs = new ArrayCollection();
    }

    public function addVocabulaireSecteur(\Formation\VocabulaireBundle\Entity\VocabulaireSecteur $vocabulaireSecteur){
        $this->vocabulaireSecteurs[] = $vocabulaireSecteur;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\VocabulaireSecteur $vocabulaireSecteur){
        $this->vocabulaireSecteurs->removeElement($vocabulaireSecteur);
    }

    public function getVocabulaireSecteur()
    {
        return $this->vocabulaireSecteurs;
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
     * Set libelleSecteur
     *
     * @param string $libelleSecteur
     * @return Secteur
     */
    public function setLibelleSecteur($libelleSecteur)
    {
        $this->libelleSecteur = $libelleSecteur;

        return $this;
    }

    /**
     * Get libelleSecteur
     *
     * @return string 
     */
    public function getLibelleSecteur()
    {
        return $this->libelleSecteur;
    }
}
