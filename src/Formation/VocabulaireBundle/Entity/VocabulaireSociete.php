<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulaireSociete
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulaireSocieteRepository")
 */
class VocabulaireSociete
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
     * @ORM\ManyToOne(targetEntity="Vocabulaire", inversedBy="vocabulaireSocietes")
     * @ORM\JoinColumn(name="id_vocabulaire", referencedColumnName="id_vocabulaire")
     */
    private $vocabulaire;

    /**
     * @ORM\ManyToOne(targetEntity="Societe", inversedBy="vocabulaireSocietes")
     * @ORM\JoinColumn(name="id_societe", referencedColumnName="id_societe")
     */
    private $societe;

    public function setVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setSociete(\Formation\VocabulaireBundle\Entity\Societe $societe)
    {
        $this->societe = $societe;
    }

    public function getSociete()
    {
        return $this->societe;
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
