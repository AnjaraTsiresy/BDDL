<?php

namespace Article\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * nl_termes
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class nl_termes
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
     * @var string
     *
     * @ORM\Column(name="terme", type="string", length=50)
     */
    private $terme;

    /**
     * @var string
     *
     * @ORM\Column(name="traduction_fr", type="string", length=500)
     */
    private $traduction_fr;

    /**
     * @var string
     *
     * @ORM\Column(name="definition", type="string", length=500)
     */


    private $definition;

    /**
     * @var string
     *
     * @ORM\Column(name="EG", type="text")
     */
    private $eG;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="date")
     */
    private $dateCreate;



    /**
     * @ORM\ManyToOne(targetEntity="nl_article", inversedBy="nl_terms")
     * @ORM\JoinColumn(name="Id_article", referencedColumnName="id")
     */
    protected $nl_article;


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
     * Set terme
     *
     * @param string $terme
     * @return nl_termes
     */
    public function setTerme($terme)
    {
        $this->terme = $terme;

        return $this;
    }

    /**
     * Get terme
     *
     * @return string 
     */
    public function getTerme()
    {
        return $this->terme;
    }

    /**
     * Set definition
     *
     * @param string $definition
     * @return nl_termes
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get definition
     *
     * @return string 
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set eG
     *
     * @param string $eG
     * @return nl_termes
     */
    public function setEG($eG)
    {
        $this->eG = $eG;

        return $this;
    }

    /**
     * Get eG
     *
     * @return string 
     */
    public function getEG()
    {
        return $this->eG;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return nl_termes
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }
    public function __construct()
    {
        $this->nl_selection = new ArrayCollection();
    }

    /**
     * Add nl_selection
     *
     * @param \Article\NewsletterBundle\Entity\nl_selection $nlSelection
     * @return nl_termes
     */
    public function addNlSelection(\Article\NewsletterBundle\Entity\nl_selection $nlSelection)
    {
        $this->nl_selection[] = $nlSelection;

        return $this;
    }

    /**
     * Remove nl_selection
     *
     * @param \Article\NewsletterBundle\Entity\nl_selection $nlSelection
     */
    public function removeNlSelection(\Article\NewsletterBundle\Entity\nl_selection $nlSelection)
    {
        $this->nl_selection->removeElement($nlSelection);
    }

    /**
     * Get nl_selection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNlSelection()
    {
        return $this->nl_selection;
    }

    /**
     * Set traduction_fr
     *
     * @param string $traductionFr
     * @return nl_termes
     */
    public function setTraductionFr($traductionFr)
    {
        $this->traduction_fr = $traductionFr;

        return $this;
    }

    /**
     * Get traduction_fr
     *
     * @return string 
     */
    public function getTraductionFr()
    {
        return $this->traduction_fr;
    }

    /**
     * Set nl_article
     *
     * @param \Article\NewsletterBundle\Entity\nl_article $nlArticle
     * @return nl_termes
     */
    public function setNlArticle(\Article\NewsletterBundle\Entity\nl_article $nlArticle = null)
    {
        $this->nl_article = $nlArticle;

        return $this;
    }

    /**
     * Get nl_article
     *
     * @return \Article\NewsletterBundle\Entity\nl_article 
     */
    public function getNlArticle()
    {
        return $this->nl_article;
    }
    public function __toString() {
        return (string) $this->id; }
}
