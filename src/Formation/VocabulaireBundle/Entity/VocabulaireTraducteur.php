<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulaireTraducteur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\VocabulaireTraducteurRepository")
 */
class VocabulaireTraducteur
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
     * @ORM\ManyToOne(targetEntity="Vocabulaire", inversedBy="vocabulaireSecteurs")
     * @ORM\JoinColumn(name="id_vocabulaire", referencedColumnName="id_vocabulaire")
     */
    private $vocabulaire;

    /**
     * @ORM\ManyToOne(targetEntity="Traducteur", inversedBy="vocabulaireTraducteurs")
     * @ORM\JoinColumn(name="id_traducteur", referencedColumnName="id_traducteur")
     */
    private $traducteur;

    public function setVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setTraducteur(\Formation\VocabulaireBundle\Entity\Traducteur $traducteur)
    {
        $this->traducteur = $traducteur;
    }

    public function getTraducteur()
    {
        return $this->traducteur;
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
