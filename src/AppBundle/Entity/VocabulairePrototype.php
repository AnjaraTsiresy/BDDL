<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulairePrototype
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\VocabulairePrototypeRepository")
 */
class VocabulairePrototype
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
