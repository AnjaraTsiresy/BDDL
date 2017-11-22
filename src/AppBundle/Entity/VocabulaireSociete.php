<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulaireSociete
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\VocabulaireSocieteRepository")
 */
class VocabulaireSociete
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
