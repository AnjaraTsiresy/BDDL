<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Fonction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\FonctionRepository")
 */
class Fonction
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_fonction", type="integer")
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
     * @ORM\OneToMany(targetEntity="VocabulaireFonction", mappedBy="fonction")
     */
    private $vocabulaireFonctions;

    public function __construct()
    {
        $this->vocabulaireFonctions = new ArrayCollection();
    }

    public function addVocabulaireFonction(\Formation\VocabulaireBundle\Entity\VocabulaireFonction $vocabulaireFonction){
        $this->vocabulaireFonctions[] = $vocabulaireFonction;
        return $this;
    }

    public function removeVocabulaire(\Formation\VocabulaireBundle\Entity\VocabulaireFonction $vocabulaireFonction){
        $this->vocabulaireFonctions->removeElement($vocabulaireFonction);
    }

    public function getVocabulaireFonction()
    {
        return $this->vocabulaireFonctions;
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
