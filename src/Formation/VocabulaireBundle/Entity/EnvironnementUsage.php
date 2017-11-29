<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * EnvironnementUsage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\EnvironnementUsageRepository")
 */
class EnvironnementUsage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_environnement_usage", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_env_usage", type="string", length=255)
     */
    private $libelleEnvironnementUsage;

    /**
     * @ORM\OneToMany(targetEntity="VocabulaireEnvirUsage", mappedBy="environnement_usage")
     */
    private $vocabulaireEnvironnementUsages;

    public function __construct()
    {
        $this->vocabulaireEnvironnementUsages = new ArrayCollection();
    }

    public function addVocabulaireEnvironnementUsage(\Formation\VocabulaireBundle\Entity\VocabulaireEnvirUsage $vocabulaireEnvironnementUsage){
        $this->vocabulaireEnvironnementUsages[] = $vocabulaireEnvironnementUsage;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\VocabulaireEnvirUsage $vocabulaireEnvironnementUsage){
        $this->vocabulaireEnvironnementUsages->removeElement($vocabulaireEnvironnementUsage);
    }

    public function getVocabulaireEnvironnementUsage()
    {
        return $this->vocabulaireEnvironnementUsages;
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
     * Set libelleEnvironnementUsage
     *
     * @param string $libelleEnvironnementUsage
     * @return EnvironnementUsage
     */
    public function setLibelleEnvironnementUsage($libelleEnvironnementUsage)
    {
        $this->libelleEnvironnementUsage = $libelleEnvironnementUsage;

        return $this;
    }

    /**
     * Get libelleEnvironnementUsage
     *
     * @return string 
     */
    public function getLibelleEnvironnementUsage()
    {
        return $this->libelleEnvironnementUsage;
    }
}
