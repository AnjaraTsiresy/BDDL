<?php

namespace Article\NewsletterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * nl_monthnewsletter
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class nl_monthnewsletter
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
     * @ORM\Column(name="monthNewsletter", type="string", length=50)
     */
    private $monthNewsletter;

    /**
     * @ORM\OneToMany(targetEntity="nl_article", mappedBy="nl_monthnewsletter")
     */
    protected $nl_articles;


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
     * Set monthNewsletter
     *
     * @param string $monthNewsletter
     * @return nl_monthnewsletter
     */
    public function setMonthNewsletter($monthNewsletter)
    {
        $this->monthNewsletter = $monthNewsletter;

        return $this;
    }

    /**
     * Get monthNewsletter
     *
     * @return string 
     */
    public function getMonthNewsletter()
    {
        return $this->monthNewsletter;
    }

    public function __construct()
    {
        $this->nl_articles = new ArrayCollection();

    }

    public function __toString() {
        return (string) $this->monthNewsletter; }

    /**
     * Add nl_articles
     *
     * @param \Article\NewsletterBundle\Entity\nl_article $nlArticles
     * @return nl_monthnewsletter
     */
    public function addNlArticle(\Article\NewsletterBundle\Entity\nl_article $nlArticles)
    {
        $this->nl_articles[] = $nlArticles;

        return $this;
    }

    /**
     * Remove nl_articles
     *
     * @param \Article\NewsletterBundle\Entity\nl_article $nlArticles
     */
    public function removeNlArticle(\Article\NewsletterBundle\Entity\nl_article $nlArticles)
    {
        $this->nl_articles->removeElement($nlArticles);
    }

    /**
     * Get nl_articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNlArticles()
    {
        return $this->nl_articles;
    }
}
