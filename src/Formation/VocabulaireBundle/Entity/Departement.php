<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Departement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\DepartementRepository")
 */
class Departement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_departement", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_departement", type="string", length=255)
     */
    private $libelleDepartement;

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireDepartement", mappedBy="departement")
     */
    private $vocabulaireDepartements;

    public function __construct()
    {
        $this->vocabulaireDepartements = new ArrayCollection();
    }

    public function addVocabulaireDepartement(\Formation\VocabulaireBundle\Entity\VocabulaireDepartement $vocabulaireDepartement){
        $this->vocabulaireDepartements[] = $vocabulaireDepartement;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\VocabulaireDepartement $vocabulaireDepartement){
        $this->vocabulaireDepartements->removeElement($vocabulaireDepartement);
    }

    public function getVocabulaireDepartement()
    {
        return $this->vocabulaireDepartements;
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
     * Set libelleDepartement
     *
     * @param string $libelleDepartement
     * @return Departement
     */
    public function setLibelleDepartement($libelleDepartement)
    {
        $this->libelleDepartement = $libelleDepartement;

        return $this;
    }

    /**
     * Get libelleDepartement
     *
     * @return string 
     */
    public function getLibelleDepartement()
    {
        return $this->libelleDepartement;
    }
}
