<?php

namespace Formation\VocabulaireBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * SuffixeSociete
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Formation\VocabulaireBundle\Entity\SuffixeSocieteRepository")
 */
class SuffixeSociete
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_suffixe_societe", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @ORM\ManyToOne(targetEntity="Societe", inversedBy="societeSecteurs")
     * @ORM\JoinColumn(name="id_societe", referencedColumnName="id_societe")
     */
    private $societe;

    /**
     * @ORM\ManyToOne(targetEntity="Suffixe", inversedBy="societeSuffixes")
     * @ORM\JoinColumn(name="id_suffixe", referencedColumnName="id_suffixe")
     */
    private $suffixe;

    public function setSociete(\Formation\VocabulaireBundle\Entity\Societe $societe)
    {
        $this->societe = $societe;
    }

    public function getSociete()
    {
        return $this->societe;
    }

    public function setSuffixe(\Formation\VocabulaireBundle\Entity\Suffixe $suffixe)
    {
        $this->suffixe = $suffixe;
    }

    public function getSuffixe()
    {
        return $this->suffixe;
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
