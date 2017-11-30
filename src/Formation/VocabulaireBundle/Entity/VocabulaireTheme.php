<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulaireTheme
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulaireThemeRepository")
 */
class VocabulaireTheme
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vocabulaire_theme", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
		/**
     * @ORM\ManyToOne(targetEntity="Vocabulaire", inversedBy="vocabulaireThemes")
     * @ORM\JoinColumn(name="id_vocabulaire", referencedColumnName="id_vocabulaire")
     */
    private $vocabulaire;

    /**
     * @ORM\ManyToOne(targetEntity="Theme", inversedBy="vocabulaireThemes")
     * @ORM\JoinColumn(name="id_theme", referencedColumnName="id_theme")
     */
    private $theme;

    public function setVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setTheme(\Formation\VocabulaireBundle\Entity\Theme $theme)
    {
        $this->theme = $theme;
    }

    public function getTheme()
    {
        return $this->theme;
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
