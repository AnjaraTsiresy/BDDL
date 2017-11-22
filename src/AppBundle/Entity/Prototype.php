<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prototype
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PrototypeRepository")
 */
class Prototype
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
     * @ORM\Column(name="libelle_prototype", type="string", length=255)
     */
    private $libellePrototype;


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
     * Set libellePrototype
     *
     * @param string $libellePrototype
     * @return Prototype
     */
    public function setLibellePrototype($libellePrototype)
    {
        $this->libellePrototype = $libellePrototype;

        return $this;
    }

    /**
     * Get libellePrototype
     *
     * @return string 
     */
    public function getLibellePrototype()
    {
        return $this->libellePrototype;
    }
}
