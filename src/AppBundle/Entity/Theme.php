<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Theme
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ThemeRepository")
 */
class Theme
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
