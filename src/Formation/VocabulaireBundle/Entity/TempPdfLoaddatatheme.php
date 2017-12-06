<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TempPdfLoaddatatheme
 *
 * @ORM\Table(name="temp_pdf_loaddatatheme")
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\TempPdfLoaddatathemeRepository")
 */
class TempPdfLoaddatatheme
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_temp", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="lib", type="string", length=255)
     */
    private $lib;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Societe", inversedBy="tempPdfLoaddatathemes")
     * @ORM\JoinColumn(name="id_societe", referencedColumnName="id_societe")
     */
    private $societe;

    /**
     * @ORM\ManyToOne(targetEntity="PrototypeAccess", inversedBy="tempPdfLoaddatathemes")
     * @ORM\JoinColumn(name="id", referencedColumnName="id_prototype_access")
     */
    private $prototypeAccess;

    /**
     * @ORM\ManyToOne(targetEntity="Theme", inversedBy="tempPdfLoaddatathemes")
     * @ORM\JoinColumn(name="idT", referencedColumnName="id_theme")
     */
    private $theme;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setTheme(\Formation\VocabulaireBundle\Entity\Theme $theme)
    {
        $this->theme = $theme;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setSociete(\Formation\VocabulaireBundle\Entity\Societe $societe)
    {
        $this->societe = $societe;
    }

    public function getSociete()
    {
        return $this->societe;
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
     * Set lib
     *
     * @param string $lib
     * @return TempPdfLoaddatatheme
     */
    public function setLib($lib)
    {
        $this->lib = $lib;

        return $this;
    }

    /**
     * Get lib
     *
     * @return string 
     */
    public function getLib()
    {
        return $this->lib;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return TempPdfLoaddatatheme
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
