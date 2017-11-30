<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulairePrototypeAccess
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulairePrototypeAccessRepository")
 */
class VocabulairePrototypeAccess
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vocabulaire_prototype_access", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

	/**
     * @ORM\ManyToOne(targetEntity="Vocabulaire", inversedBy="vocabulairePrototypeAccesss")
     * @ORM\JoinColumn(name="id_vocabulaire", referencedColumnName="id_vocabulaire")
     */
    private $vocabulaire;

    /**
     * @ORM\ManyToOne(targetEntity="PrototypeAccess", inversedBy="vocabulairePrototypeAccesss")
     * @ORM\JoinColumn(name="id_prototype_access", referencedColumnName="id_prototype_access")
     */
    private $prototypeAccess;

    public function setVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setPrototypeAccess(\Formation\VocabulaireBundle\Entity\PrototypeAccess $prototypeAccess)
    {
        $this->prototypeAccess = $prototypeAccess;
    }

    public function getPrototypeAccess()
    {
        return $this->prototypeAccess;
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
