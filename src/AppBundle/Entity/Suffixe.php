<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Suffixe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SuffixeRepository")
 */
class Suffixe
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
     * @ORM\Column(name="libelle_suffixe", type="string", length=255)
     */
    private $libelleSuffixe;

    /**
     * @var string
     *
     * @ORM\Column(name="millesime", type="string", length=255)
     */
    private $millesime;


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
     * Set libelleSuffixe
     *
     * @param string $libelleSuffixe
     * @return Suffixe
     */
    public function setLibelleSuffixe($libelleSuffixe)
    {
        $this->libelleSuffixe = $libelleSuffixe;

        return $this;
    }

    /**
     * Get libelleSuffixe
     *
     * @return string 
     */
    public function getLibelleSuffixe()
    {
        return $this->libelleSuffixe;
    }

    /**
     * Set millesime
     *
     * @param string $millesime
     * @return Suffixe
     */
    public function setMillesime($millesime)
    {
        $this->millesime = $millesime;

        return $this;
    }

    /**
     * Get millesime
     *
     * @return string 
     */
    public function getMillesime()
    {
        return $this->millesime;
    }
}
