<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuffixeSociete
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SuffixeSocieteRepository")
 */
class SuffixeSociete
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
