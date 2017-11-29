<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulaireEnvirUsage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulaireEnvirUsageRepository")
 */
class VocabulaireEnvirUsage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vocabulaire_envir_usage", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Vocabulaire", inversedBy="vocabulaireEnvironnementUsages")
     * @ORM\JoinColumn(name="id_vocabulaire", referencedColumnName="id_vocabulaire")
     */
    private $vocabulaire;

    /**
     * @ORM\ManyToOne(targetEntity="EnvironnementUsage", inversedBy="vocabulaireEnvironnementUsages")
     * @ORM\JoinColumn(name="id_environnement_usage", referencedColumnName="id_environnement_usage")
     */
    private $environnement_usage;

    public function setVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setEnvironnementUsage(\Formation\VocabulaireBundle\Entity\EnvironnementUsage $environnement_usage)
    {
        $this->environnement_usage = $environnement_usage;
    }

    public function getEnvironnementUsage()
    {
        return $this->environnement_usage;
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