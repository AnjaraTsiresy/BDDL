<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NbPage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\NbPageRepository")
 */
class NbPage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_nb_page", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbDepage", type="integer")
     */
    private $nbDepage;

    /**
     * @ORM\ManyToOne(targetEntity="PrototypeAccess", inversedBy="nbPages")
     * @ORM\JoinColumn(name="id_prototype_access", referencedColumnName="id_prototype_access")
     */
    private $prototypeAccess;

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

    /**
     * Set nbDepage
     *
     * @param integer $nbDepage
     * @return NbPage
     */
    public function setNbDepage($nbDepage)
    {
        $this->nbDepage = $nbDepage;

        return $this;
    }

    /**
     * Get nbDepage
     *
     * @return integer 
     */
    public function getNbDepage()
    {
        return $this->nbDepage;
    }
}
