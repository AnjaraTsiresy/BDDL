<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhraseSource
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PhraseSourceRepository")
 */
class PhraseSource
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
     * @ORM\Column(name="libelle_phrase_source", type="string", length=255)
     */
    private $libellePhraseSource;


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
     * Set libellePhraseSource
     *
     * @param string $libellePhraseSource
     * @return PhraseSource
     */
    public function setLibellePhraseSource($libellePhraseSource)
    {
        $this->libellePhraseSource = $libellePhraseSource;

        return $this;
    }

    /**
     * Get libellePhraseSource
     *
     * @return string 
     */
    public function getLibellePhraseSource()
    {
        return $this->libellePhraseSource;
    }
}
