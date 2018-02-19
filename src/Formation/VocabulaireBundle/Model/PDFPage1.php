<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Formation\VocabulaireBundle\Model;

/**
 * Description of PDFPage
 *
 * @author Jims
 */
class PDFPage1
{
    // Chargement des données
    public $theme = "";
    public $dataS = array();
    public $numpage = 0;
    private $em = array();

    public function __construct($em)
    {
        $this->em = $em;
    }


    private function quote($str)
    {
        return $this->em->quote($str);
    }

    private function fetch($query)
    {
        $stmt = $this->em->prepare($query);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    private function execute($query)
    {
        $stmt = $this->em->prepare($query);
        $stmt->execute();
        //  return $this->em->lastInsertId();
    }


    function PrintChapter($num, $theme, $title,$data, $id, $id_societe)
    {
        $this->BasicTable($data, $title, $num, $theme, $id, $id_societe);
    }

    function colonnedataNombreCaract($data){
        $nbreCaract = 0;
        $tab = array();
        foreach($data as $row)
        {
            $nbre = strlen($row);
            $nbreCaract = $nbreCaract + $nbre;

        }
        return $nbreCaract;
    }

    // tstoto
    function BasicTable($data, $title, $num, $theme,$id, $id_societe){
        $id_societe=intval($id_societe);
        $id=intval($id);
        $numero = $this->numpage +1;
        $initial = 0;
        if ($numero % 2 == 0 && $initial == 0)
        {
            $numero = $numero + 1 ;
        }

        $nb_page = 0;
        $sql = 'INSERT INTO `table_des_matieres_proto` (
				`No_prototype` ,
				`theme` ,
				`ordre_theme` ,
				`sous_theme` ,
				`ordre_sous_theme` ,
				`id_societe`
				)
				VALUES ("'.$id.'", "'.$theme.'", "1", " ", "'.$numero.'", "'.$id_societe.'"
				);';
        $this->execute($sql);
        //echo "-------------- Initial <br/>";
        $isany = count ($data);
        $reste = 0;
        $nbreAffichePage = 22;
        //$nbreAffichePage = 26;
        $nbreLigneInterne = 0;
        $tab0 = array();
        $tab1 = array();$tab2 = array();$tab3 = array();$tab4 = array();$tab5 = array();$tab6 = array();
        $tab7 = array();$tab8 = array();$tab9 = array();$tab10 = array();$tab11 = array();$tab12 = array();
        $tab13 = array();$tab14 = array();$tab15 = array();$tab16 = array();$tab17 = array();$tab18 = array();

        $tab_0 = array();
        $tab_1 = array();$tab_2 = array();$tab_3 = array();$tab_4 = array();$tab_5 = array();$tab_6 = array();
        $tab_7 = array();$tab_8 = array();$tab_9 = array();$tab_10 = array();$tab_11 = array();$tab_12 = array();
        $tab_13 = array();$tab_14 = array();$tab_15 = array();$tab_16 = array();$tab_17 = array();$tab_18 = array();

        $nbre_k = 0;

        $k = 0;
        $totaltableau = 0;
        $count = 1;
        $totalNbre = 0;

        $nbMajk = 0;
        $tabMaj = array();
        $tabligneInt = array();
        $compteurMatavy = 0;

        for ($i = 0; $i < $isany; $i ++){
            $dorig = $data[$i][4];
            if ($dorig >3){
                $compteurMatavy ++;
            }
        }

        $pourcent = ($compteurMatavy * 100)/$isany;
        $pourcent = (int)$pourcent;

        $initiallettre = "";
        for ($i = 0; $i < $isany; $i ++){
            $lettre = substr($data[$i][0],0,5);
            $dorig = $data[$i][4];
            $nbre = 0;
            $nbreLigneLt = $data[$i][5];
            $nbreLo = $data[$i][4];

            if ($nbreLo >= $nbreLigneLt){
                $nbre = $nbreLo;
            }
            else {
                $nbre = $nbreLigneLt;
            }

            $nbreLigneInterne = $nbreLigneInterne + $nbre;

            //echo "$lettre :: $dorig nbre :: $nbre  ...  nbreLigneInterne :: $nbreLigneInterne <br />";


            $premierLettre = substr($data[$i][0],0,1);

            if ($premierLettre!=$initiallettre){
                $premierLettre = $premierLettre;
                $premierLettre = stripslashes(utf8_decode($premierLettre));
                $nbreLigneInterne = $nbreLigneInterne + 1;
                $nbMajk = $nbMajk + 1;
            }
            $initiallettre = $premierLettre;


            if ($dorig >3){
                $compteurMatavy ++;
            }

            $dernierValeur = "";

            if ($nbreLigneInterne <= $nbreAffichePage){
                ${"tab$k"}[]= $data[$i];
                $nbre_k = $k;
                $totaltableau ++;
                $count ++;
            }
            else {
                $k++;
                //echo "$k --- $count -------------- Total nbre base : $totalNbre NbreMajk =  $nbMajk    --- $nbreLigneInterne<br />";
                $tabMaj[] = $nbMajk;
                ${"tab$k"}[]= $data[$i];
                ${"tab_$k"}[]= $nbre;

                $k_plus= $k+1;
                //${"tab$k_plus"}[]= $data[$i];
                $tabligneInt[] = $nbreLigneInterne;

                $nbreLigneInterne = $nbre;
                $totalNbre = 1;
                $nbMajk = 1;
                $count = 1;
            }

        }



        //echo count($tab0);



        for ($i = 0; $i < 19; $i ++){
            $tailleTable = count(${"tab$i"});

            if ($tailleTable!=1 && $tailleTable!=0){
                $this->numpage = $this->numpage+1;
                if ($i==0){
                    if ($this->numpage % 2 == 0)
                    {

                        // Affichage page blache
                        $this->numpage = $this->numpage + 2;
                        //echo "domoina $this->numpage <br />";
                        // Affichage titre centré
                        //$this->numpage = $this->numpage +1;
                    }
                    else {
                        // Affichage titre centré
                        $this->numpage = $this->numpage +1;
                    }
                }


            }

        }
        $nb_page = $this->numpage;

        $this->execute("INSERT INTO `nb_page` (`id_nb_page` ,`id_prototype_access` ,`nbDepage`) VALUES (NULL , '$id', '$nb_page')");
        $initial ++;
    }
    //fin ts toto

    function colonnedataNombre($data){
        $nbreMajuscule = 0;
        $tab = array();
        $initiallettre = "";
        foreach($data as $row)
        {
            $premierLettre = substr($row[0],0,1);
            if ($premierLettre!=$initiallettre){
                $premierLettre = $premierLettre;
                $premierLettre = stripslashes(utf8_decode($premierLettre));

                $maj = strtoupper($premierLettre);
                $nbreMajuscule = $nbreMajuscule + 1;
                $tab[] = $maj;
            }
            $initiallettre = $premierLettre;

        }
        return $nbreMajuscule;
    }

    function getNbPage($id){
        $nbDepage = 0;
        $sql = "select max(nbDepage) as nbDepage from nb_page where id_prototype_access='$id' " ;
        $requete = $this->fetch($sql);
        foreach ($requete as $resp)
            $nbDepage = $resp['nbDepage'];
        return $nbDepage;
    }
}
