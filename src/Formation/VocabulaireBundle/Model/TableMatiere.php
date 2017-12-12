<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Formation\VocabulaireBundle\Model;

/**
 * Description of TableMatiere
 *
 * @author Jims
 */
class TableMatiere {
   private $theme;
    private $nbPage;

    public function  theme()
    {
        return $this->theme;
    }

    public function  seTheme($theme)
    {
        $this->theme = $theme;
    }

    public function  nbPage()
    {
        return $this->nbPage;
    }

    public function  setNbPage($nbPage)
    {
        $this->nbPage = $nbPage;
    }
}


