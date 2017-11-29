<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulairePrototype
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulairePrototypeRepository")
 */
class VocabulairePrototype
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vocabulaire_prototype", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Vocabulaire", inversedBy="vocabulairePrototypes")
     * @ORM\JoinColumn(name="id_vocabulaire", referencedColumnName="id_vocabulaire")
     */
    private $vocabulaire;

    /**
     * @ORM\ManyToOne(targetEntity="Prototype", inversedBy="vocabulairePrototypes")
     * @ORM\JoinColumn(name="id_prototype", referencedColumnName="id_prototype")
     */
    private $prototype;

    public function setVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setPrototype(\Formation\VocabulaireBundle\Entity\Prototype $prototype)
    {
        $this->prototype = $prototype;
    }

    public function getPrototype()
    {
        return $this->prototype;
    }
    
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
