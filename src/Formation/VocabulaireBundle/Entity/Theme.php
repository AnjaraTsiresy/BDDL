<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Theme
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\ThemeRepository")
 */
class Theme
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_theme", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_theme", type="string", length=255)
     */
    private $libelleTheme;

    /**
     * @var string
     *
     * @ORM\Column(name="theme_eng", type="string", length=255)
     */
    private $themeEng;

	
	/**
     * @ORM\OneToMany(targetEntity="VocabulaireTheme", mappedBy="theme")
     */
    private $vocabulaireThemes;
	
	/**
     * @ORM\OneToMany(targetEntity="Lexique", mappedBy="theme")
     */
    private $lexiques;

    /**
     * @ORM\OneToMany(targetEntity="TempPdfLoaddatatheme", mappedBy="theme")
     */
    private $tempPdfLoaddatathemes;

    public function __construct()
    {
        $this->vocabulaireThemes = new ArrayCollection();
		$this->lexiques = new ArrayCollection();
        $this->tempPdfLoaddatathemes = new ArrayCollection();
    }

    public function addTempPdfLoaddatathemes(\Formation\VocabulaireBundle\Entity\TempPdfLoaddatatheme $tempPdfLoaddatatheme){
        $this->tempPdfLoaddatathemes[] = $tempPdfLoaddatatheme;
        return $this;
    }

    public function removeTempPdfLoaddatathemes(\Formation\VocabulaireBundle\Entity\TempPdfLoaddatatheme $tempPdfLoaddatatheme){
        $this->tempPdfLoaddatathemes->removeElement($tempPdfLoaddatatheme);
    }

    public function getTempPdfLoaddatathemes()
    {
        return $this->tempPdfLoaddatathemes;
    }
	
	public function addLexique(\Formation\VocabulaireBundle\Entity\Lexique $lexique){
        $this->lexiques[] = $lexique;
        return $this;
    }

    public function removeLexique(\Formation\VocabulaireBundle\Entity\Lexique $lexique){
        $this->lexiques->removeElement($lexique);
    }

    public function getLexique()
    {
        return $this->lexiques;
    }

    public function addVocabulaireTheme(\Formation\VocabulaireBundle\Entity\VocabulaireTheme $vocabulaireTheme){
        $this->vocabulaireThemes[] = $vocabulaireTheme;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\VocabulaireTheme $vocabulaireTheme){
        $this->vocabulaireThemes->removeElement($vocabulaireTheme);
    }

    public function getVocabulaireTheme()
    {
        return $this->vocabulaireThemes;
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
     * Set libelleTheme
     *
     * @param string $libelleTheme
     * @return Theme
     */
    public function setLibelleTheme($libelleTheme)
    {
        $this->libelleTheme = $libelleTheme;

        return $this;
    }

    /**
     * Get libelleTheme
     *
     * @return string 
     */
    public function getLibelleTheme()
    {
        return $this->libelleTheme;
    }

    /**
     * Set themeEng
     *
     * @param string $themeEng
     * @return Theme
     */
    public function setThemeEng($themeEng)
    {
        $this->themeEng = $themeEng;

        return $this;
    }

    /**
     * Get themeEng
     *
     * @return string 
     */
    public function getThemeEng()
    {
        return $this->themeEng;
    }
}
