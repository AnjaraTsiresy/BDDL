<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * EnvironnementUsage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EnvironnementUsageRepository")
 */
class EnvironnementUsage
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
     * @ORM\Column(name="libelle_env_usage", type="string", length=255)
     */
    private $libelleEnvUsage;

	/**
	* @ORM\OneToMany(targetEntity="VocabulaireEnvirUsage", mappedBy="environnement_usage")
	*/
	private $vocabulaire_envir_usages;
	
	public function __contruct()
	{
		$this->vocabulaire_envir_usages = new ArrayCollection();
	}
	
	public function setVocabulaireEnvirUsages()
	
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
     * Set libelleEnvUsage
     *
     * @param string $libelleEnvUsage
     * @return EnvironnementUsage
     */
    public function setLibelleEnvUsage($libelleEnvUsage)
    {
        $this->libelleEnvUsage = $libelleEnvUsage;

        return $this;
    }

    /**
     * Get libelleEnvUsage
     *
     * @return string 
     */
    public function getLibelleEnvUsage()
    {
        return $this->libelleEnvUsage;
    }
}
