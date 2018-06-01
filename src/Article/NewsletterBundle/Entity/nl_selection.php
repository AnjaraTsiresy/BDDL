<?php

namespace Article\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * nl_selection
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class nl_selection
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
     * @ORM\Column(name="terme_article", type="string", length=50)
     */
    private $termeArticle;

    /**
     * @ORM\ManyToOne(targetEntity="nl_article", inversedBy="nl_selection")
     * @ORM\JoinColumn(name="ID_article", referencedColumnName="id")
     */
    protected $nl_article;

    /**
     * @ORM\ManyToOne(targetEntity="nl_termes", inversedBy="nl_selection")
     * @ORM\JoinColumn(name="ID_terme", referencedColumnName="id")
     */
    protected $nl_termes;


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
     * Set termeArticle
     *
     * @param string $termeArticle
     * @return nl_selection
     */
    public function setTermeArticle($termeArticle)
    {
        $this->termeArticle = $termeArticle;

        return $this;
    }

    /**
     * Get termeArticle
     *
     * @return string 
     */
    public function getTermeArticle()
    {
        return $this->termeArticle;
    }

    /**
     * Set nl_article
     *
     * @param \Article\NewsletterBundle\Entity\nl_article $nlArticle
     * @return nl_selection
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

    /**
     * Set nl_termes
     *
     * @param \Article\NewsletterBundle\Entity\nl_termes $nlTermes
     * @return nl_selection
     */
    public function setNlTermes(\Article\NewsletterBundle\Entity\nl_termes $nlTermes = null)
    {
        $this->nl_termes = $nlTermes;

        return $this;
    }

    /**
     * Get nl_termes
     *
     * @return \Article\NewsletterBundle\Entity\nl_termes 
     */
    public function getNlTermes()
    {
        return $this->nl_termes;
    }
}
