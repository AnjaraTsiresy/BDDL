<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Secteur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SecteurRepository")
 */
class Secteur
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
     * @ORM\Column(name="libelle_secteur", type="string", length=255)
     */
    private $libelleSecteur;


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
     * Set libelleSecteur
     *
     * @param string $libelleSecteur
     * @return Secteur
     */
    public function setLibelleSecteur($libelleSecteur)
    {
        $this->libelleSecteur = $libelleSecteur;

        return $this;
    }

    /**
     * Get libelleSecteur
     *
     * @return string 
     */
    public function getLibelleSecteur()
    {
        return $this->libelleSecteur;
    }
}
