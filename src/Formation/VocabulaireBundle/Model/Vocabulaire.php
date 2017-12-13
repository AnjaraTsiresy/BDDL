<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Formation\VocabulaireBundle\Model;

/**
 * Description of Vocabulaire
 *
 * @author Jims
 */
class Vocabulaire {
    
    private $id_societe;
    private $description;
    private $libelle_secteur;
    private $prototypes;
    
    function id_societe() {
        return $this->id_societe;
    }

    function description() {
        return $this->description;
    }

    function libelle_secteur() {
        return $this->libelle_secteur;
    }

    function prototypes() {
        return $this->prototypes;
    }

    function set_id_societe($id_societe) {
        $this->id_societe = $id_societe;
    }

    function set_description($description) {
        $this->description = $description;
    }

    function set_libelle_secteur($libelle_secteur) {
        $this->libelle_secteur = $libelle_secteur;
    }

    function set_prototypes($prototypes) {
        $this->prototypes = $prototypes;
    }


    
}
