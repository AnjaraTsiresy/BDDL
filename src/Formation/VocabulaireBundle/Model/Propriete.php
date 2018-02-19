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
    private $em = array();
    public function __construct($em)
    {
        $this->em = $em;
    }

    private function fetch($query)
    {
        $stmt = $this->em->prepare($query);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    public function getFormatEdition($id){
        $id_format_edition = 0;
        $sql = "SELECT id_format_edition FROM prototype_access where id_prototype_access='$id'" ;
        $requete = $this->fetch($sql);
        foreach ($requete as $resp)
            $id_format_edition = $resp['id_format_edition'];
        return $id_format_edition;
    }
    public function getPrototypeTitle($id){
        $type = '';
        $sql = "SELECT type FROM prototype_access where id_prototype_access='$id'" ;
        $requete = $this->fetch($sql);
        foreach ($requete as $resp)
            $type = $resp['type'];
        return $type;
    }

}

