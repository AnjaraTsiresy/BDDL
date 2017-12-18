<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulaireDepartement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulaireDepartementRepository")
 */
class VocabulaireDepartement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vocabulaire_departement", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Vocabulaire", inversedBy="vocabulaireDepartements")
     * @ORM\JoinColumn(name="id_vocabulaire", referencedColumnName="id_vocabulaire")
     */
    private $vocabulaire;

    /**
     * @ORM\ManyToOne(targetEntity="Departement", inversedBy="vocabulaireDepartements")
     * @ORM\JoinColumn(name="id_departement", referencedColumnName="id_departement")
     */
    private $departement;

    public function setVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setDepartement(\Formation\VocabulaireBundle\Entity\Departement $departement)
    {
        $this->departement = $departement;
    }

    public function getDepartement()
    {
        return $this->departement;
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
