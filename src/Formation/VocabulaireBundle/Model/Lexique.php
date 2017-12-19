<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Formation\VocabulaireBundle\Model;

/**
 * Description of Lexique
 *
 * @author Jims
 */
class Lexique {
    private $prototype_access;
    private $id_prototype_access;
    private $nb_LE;
    private $id_theme;
    private $id_lexique;
    private $rang;
    private $id_societe;
    private $libelle_theme;
    private $theme_eng;
    private $nb_termes;
    private $nom_societe;
    private $nb_lxq;
    
    public function nb_lxq()
    {
        return $this->nb_lxq;
    }

    public function setNb_lxq($nb_lxq)
    {
        $this->nb_lxq = $nb_lxq;
    }
    public function theme_eng()
    {
        return $this->theme_eng;
    }

    public function setTheme_eng($theme_eng)
    {
        $this->theme_eng = $theme_eng;
    }

    public function id_lexique()
    {
        return $this->id_lexique;
    }

    public function setIdLexique($id_lexique)
    {
        $this->id_lexique = $id_lexique;
    }

    public function rang()
    {
        return $this->rang;
    }

    public function setRang($rang)
    {
        $this->rang = $rang;
    }


    public function setIdSociete($id_societe)
    {
        $this->id_societe = $id_societe;
    }

    public function id_theme()
    {
        return $this->id_theme;
    }

    public function libelle_theme()
    {
        $var = utf8_decode($this->libelle_theme);
        return utf8_encode($var);
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

    public function prototype_access()
    {
        return $this->prototype_access;
    }

    public function setPrototype_access($prototype_access)
    {
        $this->prototype_access = $prototype_access;
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
    public function id_societe()
    {
        return $this->id_societe;
    }

    public function setId_theme($id_theme)
    {
        $this->id_theme = $id_theme;
    }
}
