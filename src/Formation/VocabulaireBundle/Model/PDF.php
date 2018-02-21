<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Formation\VocabulaireBundle\Model;

/**
 * Description of PDF
 *
 * @author Jims
 */

class PDF
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

    private function fetch($query)
    {
        $stmt = $this->em->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function execute($query)
    {
        $stmt = $this->em->prepare($query);
        $stmt->execute();
        //  return $this->em->lastInsertId();
    }

    private function convert_utf8( $str ) {

        if (strpos($str, 'é') !== false) {
            return $str;
        }
        if (strpos($str, 'è') !== false) {
            return $str;
        }
        if (strpos($str, 'à') !== false) {
            return $str;
        }
        if (strpos($str, 'ç') !== false) {
            return $str;
        }
        if (strpos($str, 'î') !== false) {
            return $str;
        }
        if (strpos($str, 'ê') !== false) {
            return $str;
        }
        if (strpos($str, 'ë') !== false) {
            return $str;
        }
        if (strpos($str, 'â') !== false) {
        	 if (strpos($str, 'â') !== false) {
        		$str = str_replace("â€™", "’", $str);
        	}
            return $str;
        }
        if (strpos($str, 'ï') !== false) {
            return $str;
        }
        if (strpos($str, 'ô') !== false) {
            return $str;
        }
        if (strpos($str, 'ù') !== false) {
            return $str;
        }
        if (strpos($str, 'û') !== false) {
            return $str;
        }
        if (strpos($str, '’') !== false) {
            return $str;
        }
        if (strpos($str, '”') !== false) {
            return $str;
        }
        if (strpos($str, '“') !== false) {
            return $str;
        }
        if (strpos($str, 'É') !== false) {
            return $str;
        }
        if (strpos($str, 'applications') !== false) {
            return $str;
        }
        if (strpos($str, 'intervenants') !== false) {
            return $str;
        }
        if (strpos($str, 'phase') !== false) {
            return $str;
        }
        if (strpos($str, 'licensing') !== false) {
            return $str;
        }
        if (strpos($str, 'contra') !== false) {
            return $str;
        }
        if (strpos($str, 'law') !== false) {
            return $str;
        }
         if (strpos($str, 'intellectual ') !== false) {
            return $str;
        }
        if (strpos($str, 'employ') !== false) {
            return $str;
        }

         if (strpos($str, 'what') !== false) {
            return $str;
        }

        if (strpos($str, 'how') !== false) {
            return $str;
        }
        if (strpos($str, 'director') !== false) {
            return $str;
        }

        $decoded = str_replace("â€™", "<<<<<<<<<<", $decoded);
        $decoded = str_replace("â€", "wwwwwwwwwwwwwwwwwww", $decoded);
        $decoded = str_replace("â€œ", "??", $decoded);
        $decoded = str_replace("â€¦â€", "++++", $decoded);
        $decoded = str_replace("â€¦", ">>>>>>>>>>>>>>>>>>>", $decoded);
        $decoded = str_replace("â€", "----", $decoded);
        $decoded = str_replace("Å“", "======", $decoded);
        $decoded = str_replace("Ã‰", "@@@@@@@@@@@@@@@@@@@@@@@@@@", $decoded);



        $decoded = mb_convert_encoding($decoded, 'ISO-8859-1', 'UTF-8');

       
        $decoded = str_replace("??", "'", $decoded);
        $decoded = str_replace("wwwwwwwwwwwwwwwwwww", "'", $decoded);
        $decoded = str_replace("<<<<<<<<<<", "'", $decoded);
        $decoded = str_replace("----", "“", $decoded);
        $decoded = str_replace("++++", "…”", $decoded);
        $decoded = str_replace(">>>>>>>>>>>>>>>>>>>", "…", $decoded);
        $decoded = str_replace("======", "œ", $decoded);
        $decoded = str_replace("@@@@@@@@@@@@@@@@@@@@@@@@@@", "É", $decoded);

         return $decoded;
       //  return $str;
    }

    // Chargement des données
    function LoadDataTheme($id)
    {
        //data checkimpression.php
        $SQL = "SELECT lib, idT, description, id_societe FROM `temp_Pdf_LoadDataTheme` where id=$id";
        $this->execute("set sql_big_selects=1");
        $data = array();
        $resulttheme = $this->fetch($SQL);
        foreach ($resulttheme as $row) {
            $lib = $row["lib"];
            $idT = $row["idT"];
            $description = $row["description"];
            $id_societe = $row["id_societe"];
            $data[] = array($lib, $idT, $description, $id_societe);
        }
        return $data;
    }

    function clean($string)
    {
        //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        // $string = str_replace(' ', '', $string);

        //return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        // return preg_replace('/[^A-Za-z\-]/', '', $string); // Removes special chars.
        return $string;
    }


    function mb_ucfirst($stringMaj)
    {

        /*  $stringMaj = explode(" ", $stringMaj);
          $stringMaj = str_replace(
              array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
              array('E', 'E', 'E', 'E', 'A', 'A', 'I', 'I', 'O', 'U', 'U'),
              $stringMaj
          );
          return mb_strtoupper(mb_substr( $stringMaj, 0, 1 )).mb_substr( $stringMaj, 1 );*/
        return $stringMaj;
    }

    function strtoupperFrFirst($string)
    {
        /* $keywords = array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û');
         $string1 = $string[0];
         $hasString = true;
         $string = stripslashes(utf8_decode($string));
         $stringMaj = strtoupper($string);
         $stringMaj = str_replace(
             array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
             array('E', 'E', 'E', 'E', 'A', 'A', 'I', 'I', 'O', 'U', 'U'),
             $stringMaj
         );
         $premierLettre =  substr($stringMaj[0],0,1);
         $nombre_caract_a_suppr=1;
         $chaine=substr($string,-strlen($string)+$nombre_caract_a_suppr);
         $string =stripslashes($premierLettre.$chaine) ;
         $string = stripslashes(utf8_decode($string));*/
        return $string;
    }


    function trim_ucfirst_strtolower_utf8($stri)
    {
        //Met le premier caractère de la chaîne en majuscule et le reste de la chaîne en minuscule (accentué ou non)
        /*$stri = trim(($stri)," "); //On supprime les espases en début et fin de chaînes ..
        $stringMaj = strtoupper($stri);
        $stringMaj = str_replace(
            array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
            array('E', 'E', 'E', 'E', 'A', 'A', 'I', 'I', 'O', 'U', 'U'),
            $stringMaj
        );
        //$premierLettre =  substr($stringMaj[0],0,1);
        $premierLettre = mb_strtoupper(mb_substr($stringMaj[0], 0, 1,'utf-8'),'utf-8');

        $ucfirst = mb_strtoupper(mb_substr($stri, 0, 1,'utf-8'),'utf-8');
        $strtolower = mb_strtolower(mb_substr($stri, 1, 1000,'utf-8'),'utf-8');
        $stri = $premierLettre.$strtolower;
        //$stri = $ucfirst.$strtolower;*/
        return $stri;
    }

    function LoadDtaWithTheme($id, $id_theme)
    {

        $query = "SELECT distinct vocabulaire.langue_origine as langue_origine, vocabulaire.langue_traduction as langue_traduction,
		vocabulaire.langue_origine_sans_modif as langue_origine_sans_modif, vocabulaire.nbreLigneLo as nbreLigneLo, vocabulaire.nbreCaractLo as nbreCaractLo, vocabulaire.nbreLigneLt as nbreLigneLt FROM vocabulaire_prototype_access
		INNER JOIN vocabulaire ON vocabulaire_prototype_access.id_vocabulaire = vocabulaire.id_vocabulaire
		INNER JOIN vocabulaire_theme ON vocabulaire_theme.id_vocabulaire = vocabulaire.id_vocabulaire and vocabulaire_theme.id_theme = $id_theme
		INNER JOIN theme ON theme.id_theme = $id_theme
		WHERE vocabulaire_prototype_access.id_prototype_access=$id  order by langue_origine_clean collate utf8_general_ci";

        /*
        $sql = "SELECT * FROM t1 LEFT JOIN t2 ON (column1) WHERE t2.column2=5;"
        $sql = "SELECT * FROM t1, t2 WHERE t2.column2=5 AND t1.column1=t2.column1;"
        */
        //echo $query."<br />";
        $this->execute("set sql_big_selects=1");
        $k = 0;
        $result = $this->fetch($query);

        foreach ($result as $row) {
            $langue_origine = $row["langue_origine"];
            $langue_traduction = $row["langue_traduction"];
            $langue_origine_sans_modif = $row["langue_origine_sans_modif"];
            $nbreLigneLo = $row["nbreLigneLo"];
            $nbreLigneLt = $row["nbreLigneLt"];
            $nbreCaractLo = $row["nbreCaractLo"];
            //$langue_origine = strtolower ( $langue_origine );
            $data[] = array($langue_origine, $langue_traduction, $langue_origine_sans_modif, $nbreCaractLo, $nbreLigneLo, $nbreLigneLt);
            $k++;
        }
        $result = array();
        return $data;
    }

    function PrintChapter($num, $theme, $title, $data, $societe, $nb_page)
    {
        $this->BasicTable($data, $title, $num, $theme, $societe, $nb_page);
    }

    function array_insert(&$array, $position, $insert)
    {
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }

    function nombreIsP1($tab)
    {
        $isanyInterne = $i * $nbreParPage;
        $k = ($i - 1) * $nbreParPage;
        $tabNbrePartieALaliggne = array();
        $totalNbrecaractData = 0;
        for ($j = $k; $j < $isanyInterne; $j++) {
            $kj = 1;
            $string = "";
            foreach ($data[$j] as $coco) {
                $string = $string . "" . $coco;
                $kj++;
            }
            $nbreCaractere = strlen($string);
            $totalNbrecaractData = $totalNbrecaractData + $nbreCaractere;
            //$fixeNbreCaractreLigne = 98;
            $fixeNbreCaractreLigne = 70;
            $partieEntiere = (int)($nbreCaractere / $fixeNbreCaractreLigne);
            $tabNbrePartieALaliggne[] = $partieEntiere;

            $tab1[] = $data[$j];
        }

        $i1 = $i;
        $isVoaloany = 0;


        $isP1 = 0;

        foreach ($tabNbrePartieALaliggne as $isaPart1) {
            if ($isaPart1 != 0 && $isaPart1 > 1) {
                $isP1 = $isP1 + $isaPart1;
            }
        }
        return $isP1;
    }

    // Tableau simple
    function BasicTable($data, $title, $num, $theme, $societe, $nb_page)
    {
        //echo "-------------- Initial <br/>";


        $isany = count($data);
        $reste = 0;
        $nbreAffichePage = 22;
        //$nbreAffichePage = 26;
        $nbreLigneInterne = 0;
        $tab0 = array();
        $tab1 = array();
        $tab2 = array();
        $tab3 = array();
        $tab4 = array();
        $tab5 = array();
        $tab6 = array();
        $tab7 = array();
        $tab8 = array();
        $tab9 = array();
        $tab10 = array();
        $tab11 = array();
        $tab12 = array();
        $tab13 = array();
        $tab14 = array();
        $tab15 = array();
        $tab16 = array();
        $tab17 = array();
        $tab18 = array();

        $tab_0 = array();
        $tab_1 = array();
        $tab_2 = array();
        $tab_3 = array();
        $tab_4 = array();
        $tab_5 = array();
        $tab_6 = array();
        $tab_7 = array();
        $tab_8 = array();
        $tab_9 = array();
        $tab_10 = array();
        $tab_11 = array();
        $tab_12 = array();
        $tab_13 = array();
        $tab_14 = array();
        $tab_15 = array();
        $tab_16 = array();
        $tab_17 = array();
        $tab_18 = array();

        $nbre_k = 0;

        $k = 0;
        $totaltableau = 0;
        $count = 1;
        $totalNbre = 0;

        $nbMajk = 0;
        $tabMaj = array();
        $tabligneInt = array();
        $compteurMatavy = 0;

        for ($i = 0; $i < $isany; $i++) {
            $dorig = $data[$i][4];
            if ($dorig > 3) {
                $compteurMatavy++;
            }
        }

        $pourcent = ($compteurMatavy * 100) / $isany;
        $pourcent = (int)$pourcent;

        $initiallettre = "";
        for ($i = 0; $i < $isany; $i++) {
            $lettre = substr($data[$i][0], 0, 5);
            $dorig = $data[$i][4];
            $nbre = 0;
            $nbreLigneLt = $data[$i][5];
            $nbreLo = $data[$i][4];

            if ($nbreLo >= $nbreLigneLt) {
                $nbre = $nbreLo;
            } else {
                $nbre = $nbreLigneLt;
            }

            $nbreLigneInterne = $nbreLigneInterne + $nbre;

            //echo "$lettre :: $dorig nbre :: $nbre  ...  nbreLigneInterne :: $nbreLigneInterne <br />";


            $premierLettre = substr($data[$i][0], 0, 1);

            if ($premierLettre != $initiallettre) {
                $premierLettre = $premierLettre;
                //$premierLettre = stripslashes(utf8_decode($premierLettre));
                $nbreLigneInterne = $nbreLigneInterne + 1;
                $nbMajk = $nbMajk + 1;
            }
            $initiallettre = $premierLettre;


            if ($dorig > 3) {
                $compteurMatavy++;
            }

            $dernierValeur = "";

            if ($nbreLigneInterne <= $nbreAffichePage) {
                ${"tab$k"}[] = $data[$i];
                $nbre_k = $k;
                $totaltableau++;
                $count++;
            } else {
                $k++;
                //echo "$k --- $count -------------- Total nbre base : $totalNbre NbreMajk =  $nbMajk    --- $nbreLigneInterne<br />";
                $tabMaj[] = $nbMajk;
                ${"tab$k"}[] = $data[$i];
                ${"tab_$k"}[] = $nbre;

                $k_plus = $k + 1;
                //${"tab$k_plus"}[]= $data[$i];
                $tabligneInt[] = $nbreLigneInterne;

                $nbreLigneInterne = $nbre;
                $totalNbre = 1;
                $nbMajk = 1;
                $count = 1;
            }

        }


        for ($i = 0; $i < 19; $i++) {
            $tailleTable = count(${"tab$i"});

            if ($tailleTable != 1 && $tailleTable != 0) {
                $this->numpage = $this->numpage + 1;

                if ($i == 0) {

                    if ($this->numpage % 2 == 0) {
                        // Affichage page blache
                        echo '<page size="A4">';
                        echo "<div class='conteneur'>";
                        echo "<div class='colonne1'>";
                        echo "<div class='numpage1'>" . $this->numpage . "/" . $nb_page . "</div>";
                        echo '</div>';
                        echo "<div class='colonne2'>";
                        echo "<div class='numpage2'>" . $this->numpage . "/" . $nb_page . "</div>";
                        echo '</div>';
                        echo '</div>';
                        echo '</page>';
                        $this->numpage = $this->numpage + 1;
                        // Affichage titre centré
                        echo '<page size="A4">';
                        echo "<div class='conteneur'>";
                        echo "<div class='colonne1'>";
                        echo "<span style='position:absolute;
						font-size: 10pt;
						font-weight: bold;
						left: 35%;
						top: 50%;
						width: 200px;
						height: 200px;
						margin-left: 0;
						margin-top: 0; '>" . $this->convert_utf8($title) . "</span>";
                        echo "<div class='numpage1'>" . $this->numpage . "/" . $nb_page . "</div>";
                        echo '</div>';
                        echo "<div class='colonne2'>";
                        echo "<span style='position:absolute;
											font-size: 10pt;
											font-weight: bold;
											left: 35%;
											top: 50%;
											width: 200px;
											height: 200px;
											margin-left: 0;
											margin-top: 0; '>" . $this->convert_utf8($title) . "</span>";
                        echo "<div class='numpage2'>" . $this->numpage . "/" . $nb_page . "</div>";
                        echo '</div>';
                        echo '</div>';
                        echo '</page>';
                        $this->numpage = $this->numpage + 1;
                    } else {
                        // Affichage titre centré
                        echo '<page size="A4">';
                        echo "<div class='conteneur'>";
                        echo "<div class='colonne1'>";
                        echo "<span style='position:absolute;
						font-size: 10pt;
						font-weight: bold;
						left: 35%;
						top: 50%;
						width: 200px;
						height: 200px;
						margin-left: 0;
						margin-top: 0; '>" . $this->convert_utf8($title) . "</span>";
                        echo "<div class='numpage1'>" . $this->numpage . "/" . $nb_page . "</div>";
                        echo '</div>';
                        echo "<div class='colonne2'>";
                        echo "<span style='position:absolute;
											font-size: 10pt;
											font-weight: bold;
											left: 35%;
											top: 50%;
											width: 200px;
											height: 200px;
											margin-left: 0;
											margin-top: 0; '>" . $this->convert_utf8($title) . "</span>";
                        echo "<div class='numpage2'>" . $this->numpage . "/" . $nb_page . "</div>";
                        echo '</div>';
                        echo '</div>';
                        echo '</page>';
                        $this->numpage = $this->numpage + 1;
                    }


                }

                echo '<page size="A4">';
                echo "<div class='conteneur'>";
                echo "<div class='colonne1'>";

                //if ($i==0){
                //echo "<b  class='titremangamanga'>$title</b>";
                //echo "<span class='titremangamanga'>$title</span>";
                echo " <table class='titremangamanga'><tbody><tr><td colspan='2'><center><span>" . $this->convert_utf8($title) . "</span></center></td></tr></tbody></table>";

                //}

                $tab = ${"tab$i"};
                $isanytab = count($tab);
                $nbreMaj1 = 0;

                if ($isanytab <= 5) {
                    $nbreMaj1 = $this->colonnedataBigData($tab);
                } else {

                    $nbreMaj1 = $this->colonnedata($tab);
                }
                // pied de page echo " <table class='titremangamanga'><tbody><tr><td colspan='2'><center><span>$title</span></center></td></tr></tbody></table>";
                echo "<div class='numpage1'>" . $this->numpage . "/" . $nb_page . "</div>";
                echo "</div>";
                echo "<div class='colonne2'>";
                //if ($i==0){
                //echo "<b class='titremangamanga' >$title</b>";
                //echo "<span class='titremangamanga'>$title</span>";
                //echo " <table class='titremangamanga'><tbody><tr><td><span>$title</span></td><td></td></tr></tbody></table>";
                //}
                echo " <table class='titremangamanga'><tbody><tr><td colspan='2'><center><span>" . $this->convert_utf8($title) . "</span></center></td></tr></tbody></table>";
                $nbreMaj2 = 0;
                if ($isanytab <= 5) {
                    $nbreMaj2 = $this->colonnedataBigData($tab);
                } else {

                    $nbreMaj2 = $this->colonnedata($tab);
                }
                //$nbreMaj2 = $this->colonnedata($tab);
                // pied de page echo " <table class='titremangamanga'><tbody><tr><td colspan='2'><center><span>$title</span></center></td></tr></tbody></table>";
                echo "<div class='numpage2'>" . $this->numpage . "/" . $nb_page . "</div>";
                echo "</div>";
                echo "</div>";
                echo '</page>';

            }

        }

    }

    function colonnedataNombre($data)
    {
        $nbreMajuscule = 0;
        $tab = array();
        $initiallettre = "";
        foreach ($data as $row) {
            $premierLettre = substr($row[0], 0, 1);
            if ($premierLettre != $initiallettre) {
                $premierLettre = $premierLettre;
                //$premierLettre = stripslashes(utf8_decode($premierLettre));

                $maj = strtoupper($premierLettre);
                $nbreMajuscule = $nbreMajuscule + 1;
                $tab[] = $maj;
            }
            $initiallettre = $premierLettre;

        }
        return $nbreMajuscule;
    }


    function colonnedataNombreCaract($data)
    {
        $nbreCaract = 0;
        $tab = array();
        foreach ($data as $row) {
            $nbre = strlen($row);
            $nbreCaract = $nbreCaract + $nbre;

        }
        return $nbreCaract;
    }

    function colonnedata($data)
    {
        $nbreMajuscule = 0;
        $tab = array();

        echo "<table class='colonnedata'>";
        $initiallettre = "";
        foreach ($data as $row) {
            $string_clean = $row[0];
            //$premierLettre = substr($row[0],0,1);
            $premierLettre = substr($string_clean, 0, 1);
            if ($premierLettre != $initiallettre) {

                $maj = strtoupper($premierLettre);
                $nbreMajuscule = $nbreMajuscule + 1;
                $tab[] = $maj;
                if ($maj != "") {

                    echo "<tr><td colspan='2' style='padding-top:0mm;'><b>" . $this->convert_utf8($maj) . "</b></td></tr>";
                } else {
                    echo "<tr><td colspan='2'></td></tr>";
                }
            }
            $i = 1;
            echo "<tr>";
            // print_r($row);
            //  die();
            foreach ($row as $col) {
                //$col = stripslashes(html_entity_decode($col));
                if ($i % 2) {
                    if ($i == 1) {
                        if ($row[2] != "") {

                            //echo "<td style='width:70mm; padding-top:2mm; vertical-align:top;'><b> $row[2]</b></td>";
                            echo "<td><b> " . $this->convert_utf8($row[2]) . "</b></td>";
                        } else {
                            //echo "<td style='width:70mm; padding-top:2mm; vertical-align:top;'><b>$row[0]</b></td>";

                            echo "<td><b>" . $this->convert_utf8($row[0]) . "</b></td>";
                        }
                    }
                } else {
                    if ($col != "") {
                        if (!is_numeric($col)) {

                            //echo "<td style='width:70mm; padding-top:2mm; vertical-align:top;'><i>$col</i></td>";
                            echo "<td><i>" . $this->convert_utf8($col) . "</i></td>";
                            //milalao colonnedata
                        }

                    } else {
                        //echo "<td></td>";
                    }
                }
                $i++;
            }
            echo "</tr>";
            $initiallettre = $premierLettre;

        }
        echo "</table>";
        return $nbreMajuscule;
        //return $tab;
    }


    function colonnedataBigData($data)
    {
        $nbreMajuscule = 0;
        $tab = array();
        $initiallettre = "";
        echo "<table class='colonnedata'>";
        foreach ($data as $row) {
            $string_clean = $row[0];
            //$premierLettre = substr($row[0],0,1);
            $premierLettre = substr($string_clean, 0, 1);
            if ($premierLettre != $initiallettre) {
                $premierLettre = $premierLettre;
                // $premierLettre = stripslashes(utf8_decode($premierLettre));
                $maj = strtoupper($premierLettre);
                $nbreMajuscule = $nbreMajuscule + 1;
                $tab[] = $maj;
                if ($maj != "") {
                    echo "<tr><td colspan='2' style='padding-top:2.5mm;'><b>" . $this->convert_utf8($maj) . "</b></td></tr>";
                } else {
                    echo "<tr><td colspan='2'></td></tr>";
                }
            }
            $i = 1;
            echo "<tr>";
            foreach ($row as $col) {
                // $col = stripslashes(html_entity_decode($col));
                if ($i % 2) {
                    if ($i == 1) {
                        if ($row[2] != "") {
                            echo "<td style='padding-top:2.4mm;'><b> " . $this->convert_utf8($row[2]) . "</b></td>";
                        } else {
                            echo "<td style='padding-top:2.4mm;'><b>" . $this->convert_utf8($row[0]) . "</b></td>";
                        }
                    }
                } else {
                    if ($col != "") {
                        if (!is_numeric($col)) {
                            echo "<td style='padding-top:2.4mm;'><i>" . $this->convert_utf8($col) . "</i></td>";
                        }

                    } else {
                        //echo "<td></td>";
                    }
                }
                $i++;
            }
            echo "</tr>";
            $initiallettre = $premierLettre;

        }
        echo "</table>";
        return $nbreMajuscule;
        //return $tab;
    }


    function getClient($id_societe)
    {
        $societe = "Aucun";
        $sql = "select * from societe where id_societe ='$id_societe' ";
        $requete = $this->fetch($sql);
        foreach ($requete as $row)
            $societe = $row['description'];
        return $societe;
    }
}