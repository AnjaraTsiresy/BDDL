<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TableDesMatieresProto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\TableDesMatieresProtoRepository")
 */
class TableDesMatieresProto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_table_des_matieres_proto", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PrototypeAccess", inversedBy="tableDesMatieresProtos")
     * @ORM\JoinColumn(name="No_prototype", referencedColumnName="id_prototype_access")
     */
    private $noPrototype;

    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=255)
     */
    private $theme;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordre_theme", type="integer")
     */
    private $ordreTheme;

    /**
     * @var string
     *
     * @ORM\Column(name="sous_theme", type="string", length=255)
     */
    private $sousTheme;

    /**
     * @ORM\ManyToOne(targetEntity="Societe", inversedBy="tableDesMatieresProtos")
     * @ORM\JoinColumn(name="id_societe", referencedColumnName="id_societe")
     */
    private $societe;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordre_sous_theme", type="integer")
     */
    private $ordreSousTheme;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    public function setNoPrototype(\Formation\VocabulaireBundle\Entity\PrototypeAccess $noPrototype)
    {
        $this->noPrototype = $noPrototype;

        return $this;
    }

    public function setSociete(\Formation\VocabulaireBundle\Entity\Societe $societe)
    {
        $this->societe = $societe;
    }

    public function getSociete()
    {
        return $this->societe;
    }


    public function getNoPrototype()
    {
        return $this->noPrototype;
    }

    /**
     * Set theme
     *
     * @param string $theme
     * @return TableDesMatieresProto
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set ordreTheme
     *
     * @param integer $ordreTheme
     * @return TableDesMatieresProto
     */
    public function setOrdreTheme($ordreTheme)
    {
        $this->ordreTheme = $ordreTheme;

        return $this;
    }

    /**
     * Get ordreTheme
     *
     * @return integer 
     */
    public function getOrdreTheme()
    {
        return $this->ordreTheme;
    }

    /**
     * Set sousTheme
     *
     * @param string $sousTheme
     * @return TableDesMatieresProto
     */
    public function setSousTheme($sousTheme)
    {
        $this->sousTheme = $sousTheme;

        return $this;
    }

    /**
     * Get sousTheme
     *
     * @return string 
     */
    public function getSousTheme()
    {
        return $this->sousTheme;
    }

    /**
     * Set ordreSousTheme
     *
     * @param integer $ordreSousTheme
     * @return TableDesMatieresProto
     */
    public function setOrdreSousTheme($ordreSousTheme)
    {
        $this->ordreSousTheme = $ordreSousTheme;

        return $this;
    }

    /**
     * Get ordreSousTheme
     *
     * @return integer 
     */
    public function getOrdreSousTheme()
    {
        return $this->ordreSousTheme;
    }
}
