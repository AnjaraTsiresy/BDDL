<?php

namespace Article\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * nl_article
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class nl_article
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
     * @ORM\Column(name="article", type="string", length=5000)
     */
    private $article;

    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=5000)
     */
    private $theme;

    /**
     * @var string
     *
     * @ORM\Column(name="monthNewsLetter", type="string", length=10)
     */


    private $monthNewsLetter;

    /**
     * @var string
     *
     * @ORM\Column(name="origine", type="string", length=500)
     */
    private $origine;

    /**
     * @var string
     *
     * @ORM\Column(name="journaliste", type="string", length=500)
     */
    private $journaliste;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="text")
     */
    private $texte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="nl_selection", mappedBy="nl_article")
     */
    protected $nl_selection;




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
     * Set article
     *
     * @param string $article
     * @return nl_article
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return string 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set monthNewsLetter
     *
     * @param string $monthNewsLetter
     * @return nl_article
     */
    public function setMonthNewsLetter($monthNewsLetter)
    {
        $this->monthNewsLetter = $monthNewsLetter;

        return $this;
    }

    /**
     * Get monthNewsLetter
     *
     * @return string 
     */
    public function getMonthNewsLetter()
    {
        return $this->monthNewsLetter;
    }

    /**
     * Set origine
     *
     * @param string $origine
     * @return nl_article
     */
    public function setOrigine($origine)
    {
        $this->origine = $origine;

        return $this;
    }

    /**
     * Get origine
     *
     * @return string 
     */
    public function getOrigine()
    {
        return $this->origine;
    }

    /**
     * Set journaliste
     *
     * @param string $journaliste
     * @return nl_article
     */
    public function setJournaliste($journaliste)
    {
        $this->journaliste = $journaliste;

        return $this;
    }

    /**
     * Get journaliste
     *
     * @return string 
     */
    public function getJournaliste()
    {
        return $this->journaliste;
    }

    /**
     * Set texte
     *
     * @param string $texte
     * @return nl_article
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return nl_article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    public function __construct()
    {
        $this->nl_selection = new ArrayCollection();
    }

    /**
     * Add nl_selection
     *
     * @param \Article\NewsletterBundle\Entity\nl_selection $nlSelection
     * @return nl_article
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
     * Set theme
     *
     * @param string $theme
     * @return nl_article
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
}
