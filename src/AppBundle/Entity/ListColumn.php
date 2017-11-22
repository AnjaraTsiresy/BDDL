<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListColumn
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ListColumnRepository")
 */
class ListColumn
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
     * @ORM\Column(name="nom_column", type="string", length=255)
     */
    private $nomColumn;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_table", type="string", length=255)
     */
    private $nomTable;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_champ", type="string", length=255)
     */
    private $nomChamp;


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
     * Set nomColumn
     *
     * @param string $nomColumn
     * @return ListColumn
     */
    public function setNomColumn($nomColumn)
    {
        $this->nomColumn = $nomColumn;

        return $this;
    }

    /**
     * Get nomColumn
     *
     * @return string 
     */
    public function getNomColumn()
    {
        return $this->nomColumn;
    }

    /**
     * Set nomTable
     *
     * @param string $nomTable
     * @return ListColumn
     */
    public function setNomTable($nomTable)
    {
        $this->nomTable = $nomTable;

        return $this;
    }

    /**
     * Get nomTable
     *
     * @return string 
     */
    public function getNomTable()
    {
        return $this->nomTable;
    }

    /**
     * Set nomChamp
     *
     * @param string $nomChamp
     * @return ListColumn
     */
    public function setNomChamp($nomChamp)
    {
        $this->nomChamp = $nomChamp;

        return $this;
    }

    /**
     * Get nomChamp
     *
     * @return string 
     */
    public function getNomChamp()
    {
        return $this->nomChamp;
    }
}
