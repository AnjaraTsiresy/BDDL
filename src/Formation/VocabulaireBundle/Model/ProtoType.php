<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Formation\VocabulaireBundle\Model;

/**
 * Description of ProtoModel
 *
 * @author Jims
 */
class ProtoType
{
    private $id;
    private $nb_soloc;
    private $nb_le_gen;
    private $nb_page;
    private $nb_termes;
    private $societe;
    private $traducteur;
    private $type;
    private $date;
    private $id_societe;
    private $id_theme;
    private $libelle_theme;
    private $nom_societe;
    private $nb_LE;
    private $id_prototype_access;
    private $prototype_access;
    
    public function id()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function id_societe()
    {
        return $this->id_societe;
    }

    public function setIdSociete($id_societe)
    {
        $this->id_societe = $id_societe;
    }

    public function id_theme()
    {
        return $this->id_theme;
    }

    public function setId_theme($id_theme)
    {
        $this->id_theme = $id_theme;
    }

    public function libelle_theme()
    {
        return $this->libelle_theme;
    }

    public function setLibelle_theme($libelle_theme)
    {
        $this->libelle_theme = $libelle_theme;
    }

    public function nb_termes()
    {
        return $this->nb_termes;
    }

    public function setNb_termes($nb_termes)
    {
        $this->nb_termes = $nb_termes;
    }

    public function nom_societe()
    {
        return $this->nom_societe;
    }

    public function setNom_societe($nom_societe)
    {
        $this->nom_societe = $nom_societe;
    }

    public function id_prototype_access()
    {
        return $this->id_prototype_access;
    }

    public function setId_prototype_access($id_prototype_access)
    {
        $this->id_prototype_access = $id_prototype_access;
    }

    public function nb_LE()
    {
        return $this->nb_LE;
    }

    public function setNb_LE($nb_LE)
    {
        $this->nb_LE = $nb_LE;
    }

    public function prototype_access()
    {
        return $this->prototype_access;
    }

    public function setPrototype_access($prototype_access)
    {
        $this->prototype_access = $prototype_access;
    }

    public function type()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function date()
    {
        return $this->date;
    }

    public function setDate(\Datetime $date)
    {
        $this->date = $date;
    }

    public function nb_soloc()
    {
        return $this->nb_soloc;
    }

    public function setNbSoloc($nb_soloc)
    {
        $this->nb_soloc = $nb_soloc;
    }

    public function nb_le_gen()
    {
        return $this->nb_le_gen;
    }

    public function setNbLeGen($nb_le_gen)
    {
        $this->nb_le_gen = $nb_le_gen;
    }

    public function nb_page()
    {
        return $this->nb_page;
    }

    public function setNbPage($nb_page)
    {
        $this->nb_page = $nb_page;
    }

    public function societe()
    {
        return $this->societe;
    }

    public function setSociete($societe)
    {
        $this->societe = $societe;
    }

    public function traducteur()
    {
        return $this->traducteur;
    }

    public function setTraducteur($traducteur)
    {
        $this->traducteur = $traducteur;
    }

}