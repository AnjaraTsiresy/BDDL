<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Formation\VocabulaireBundle\Model;

/**
 * Description of Propriete
 *
 * @author Jims
 */
class Propriete
{
    public $largeur = "561.2602px";
    public $longueur = "793.7013px";

    public function getFormatEdition($prototypeAccess){
        $id_format_edition = 0;
        if($prototypeAccess != null)
            $id_format_edition = $prototypeAccess->getFormatEdition()->getId();
        return $id_format_edition;
    }
    public function getPrototypeTitle($prototypeAccess){
        $type = '';
        if($prototypeAccess != null)
            $type = $prototypeAccess->getType();
        return $type;
    }

}

