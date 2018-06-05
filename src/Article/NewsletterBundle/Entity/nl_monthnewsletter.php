<?php

namespace Article\NewsletterBundle\Entity;

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
}
