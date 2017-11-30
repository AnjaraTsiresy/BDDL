<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lexique
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\LexiqueRepository")
 */
class Lexique
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_lexique", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="rang", type="integer")
     */
    private $rang;

	/**
     * @ORM\ManyToOne(targetEntity="Theme", inversedBy="lexiques")
     * @ORM\JoinColumn(name="id_theme", referencedColumnName="id_theme")
     */
    private $theme;
    /**
     * @ORM\ManyToOne(targetEntity="PrototypeAccess", inversedBy="lexiques")
     * @ORM\JoinColumn(name="id_prototype_access", referencedColumnName="id_prototype_access")
     */
    private $prototypeAccess;

    /**
     * @ORM\ManyToOne(targetEntity="Societe", inversedBy="lexiques")
     * @ORM\JoinColumn(name="id_societe", referencedColumnName="id_societe")
     */
    private $societe;

    public function setPrototypeAccess(\Formation\VocabulaireBundle\Entity\PrototypeAccess $prototypeAccess)
    {
        $this->prototypeAccess = $prototypeAccess;
    }

    public function getPrototypeAccess()
    {
        return $this->prototypeAccess;
    }

    public function setSociete(\Formation\VocabulaireBundle\Entity\Societe $societe)
    {
        $this->societe = $societe;
    }

    public function getSociete()
    {
        return $this->societe;
    }
    public function setTheme(\Formation\VocabulaireBundle\Entity\Theme $theme)
    {
        $this->theme = $theme;
    }

    public function getTheme()
    {
        return $this->theme;
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
     * Set rang
     *
     * @param integer $rang
     * @return Lexique
     */
    public function setRang($rang)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang
     *
     * @return integer 
     */
    public function getRang()
    {
        return $this->rang;
    }
}
