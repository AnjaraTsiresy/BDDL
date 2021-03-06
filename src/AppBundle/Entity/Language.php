<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\LanguageRepository")
 */
class Language
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
     * @ORM\Column(name="libelle_language", type="string", length=255)
     */
    private $libelle_language;


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
     * Set libelle_language
     *
     * @param string $libelle_language
     * @return Language
     */
    public function setLibelleLanguage($libelle_language)
    {
        $this->libelle_language = $libelle_language;

        return $this;
    }

    /**
     * Get libelle_language
     *
     * @return string 
     */
    public function getLibelleLanguage()
    {
        return $this->libelle;
    }
}
