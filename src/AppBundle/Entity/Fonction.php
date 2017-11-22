<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fonction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FonctionRepository")
 */
class Fonction
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
     * @ORM\Column(name="libelle_fonction", type="string", length=255)
     */
    private $libelleFonction;


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
     * Set libelleFonction
     *
     * @param string $libelleFonction
     * @return Fonction
     */
    public function setLibelleFonction($libelleFonction)
    {
        $this->libelleFonction = $libelleFonction;

        return $this;
    }

    /**
     * Get libelleFonction
     *
     * @return string 
     */
    public function getLibelleFonction()
    {
        return $this->libelleFonction;
    }
}
