<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VocabulaireFonction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\VocabulaireFonctionRepository")
 */
class VocabulaireFonction
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_vocabulaire_fonction", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Vocabulaire", inversedBy="vocabulaireFonctions")
     * @ORM\JoinColumn(name="id_vocabulaire", referencedColumnName="id_vocabulaire")
     */
    private $vocabulaire;

    /**
     * @ORM\ManyToOne(targetEntity="Fonction", inversedBy="vocabulaireFonctions")
     * @ORM\JoinColumn(name="id_fonction", referencedColumnName="id_fonction")
     */
    private $fonction;

    public function setVocabulaire(\Formation\VocabulaireBundle\Entity\Vocabulaire $vocabulaire)
    {
        $this->vocabulaire = $vocabulaire;
    }

    public function getVocabulaire()
    {
        return $this->vocabulaire;
    }

    public function setFonction(\Formation\VocabulaireBundle\Entity\Fonction $fonction)
    {
        $this->fonction = $fonction;
    }

    public function getFonction()
    {
        return $this->fonction;
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
