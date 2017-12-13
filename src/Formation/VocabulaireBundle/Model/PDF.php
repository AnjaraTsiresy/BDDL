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
    private $dataTheme;

    public function setLoadDataTheme($dataTheme)
    {
        $this->dataTheme = $dataTheme;
    }
    // Chargement des données
    function LoadDataTheme() {
        return $this->dataTheme;
    }

    function mb_ucfirst( $stringMaj )
    {

        $stringMaj = explode(" ", $stringMaj);
        $stringMaj = str_replace(
            array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
            array('E', 'E', 'E', 'E', 'A', 'A', 'I', 'I', 'O', 'U', 'U'),
            $stringMaj
        );
        return mb_strtoupper(mb_substr( $stringMaj, 0, 1 )).mb_substr( $stringMaj, 1 );
    }

    function strtoupperFrFirst($string) {
        $keywords = array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û');
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
        $string = stripslashes(utf8_decode($string));
        return $string;
    }


    function trim_ucfirst_strtolower_utf8($stri) {
        //Met le premier caractère de la chaîne en majuscule et le reste de la chaîne en minuscule (accentué ou non)
        $stri = trim(($stri)," "); //On supprime les espases en début et fin de chaînes ..
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
        //$stri = $ucfirst.$strtolower;
        return $stri;
    }

    function LoadDtaWithTheme ($id, $id_theme){
        return 0;
    }

    function PrintChapter($num, $theme, $title,$data,$societe,$nb_page)
	{
		$this->BasicTable($data, $title, $num, $theme,$societe,$nb_page);
	}

    function array_insert(&$array, $position, $insert)
    {
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos   = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }

    function nombreIsP1($tab){
        $isanyInterne = $i * $nbreParPage;
        $k = ($i-1)*$nbreParPage;
        $tabNbrePartieALaliggne = array();
        $totalNbrecaractData = 0;
        for ($j = $k; $j < $isanyInterne; $j ++){
            $kj = 1;
            $string = "";
            foreach ($data[$j] as $coco){
                $string = $string."".$coco;
                $kj ++;
            }
            $nbreCaractere = strlen($string);
            $totalNbrecaractData = $totalNbrecaractData + $nbreCaractere;
            //$fixeNbreCaractreLigne = 98;
            $fixeNbreCaractreLigne = 70;
            $partieEntiere = (int)($nbreCaractere/$fixeNbreCaractreLigne);
            $tabNbrePartieALaliggne[] = $partieEntiere;

            $tab1[] = $data[$j];
        }

        $i1 = $i;
        $isVoaloany = 0;


        $isP1 = 0;

        foreach ($tabNbrePartieALaliggne as $isaPart1){
            if ($isaPart1 != 0 && $isaPart1> 1){
                $isP1  = $isP1+$isaPart1 ;
            }
        }
        return $isP1;
    }

    // Tableau simple	
	function BasicTable($data, $title, $num, $theme,$societe,$nb_page){
		//echo "-------------- Initial <br/>";
		$isany = count ($data);
		//$nbreParPage = 16;
		$reste = 0;
		$nbreAffichePage = 19;
		$nbreLigneInterne = 0;
		$tab0 = array();
		$tab1 = array();$tab2 = array();$tab3 = array();$tab4 = array();$tab5 = array();$tab6 = array();
		$tab7 = array();$tab8 = array();$tab9 = array();$tab10 = array();$tab11 = array();$tab12 = array();
		$tab13 = array();$tab14 = array();$tab15 = array();$tab16 = array();$tab17 = array();$tab18 = array();
		
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
                    $j = count($data[$i]);
			$dorig = $data[$i][$j - 1];
			if ($dorig >3){
				$compteurMatavy ++;
			}
		}
		
		
		$pourcent = ($compteurMatavy * 100)/$isany;
		$pourcent = (int)$pourcent;
		
		if ($pourcent > 50){
			$nbreAffichePage = 15;
		}
		else if ($pourcent > 25 && $pourcent <= 50){
			$nbreAffichePage = 17;
		}
		else if ($pourcent >= 10 && $pourcent <= 25){
			$nbreAffichePage = 18;
		}
		
		
		
		//$tab0[]= "";
		for ($i = 0; $i < $isany; $i ++){
                    $j = count($data[$i]);
			$dorig = $data[$i][$j - 1];
			$nbre = $data[$i][$j - 1];
			//$nbre = (int)($data[$i][4]/2);
			if ($nbre <= 2){
				$totalNbre = $totalNbre + $nbre;
			}
			else if ($nbre >2 && $nbre <= 4){
				$nbre = (int)(($nbre / 3) * 2);
				$totalNbre = $totalNbre + $nbre;
			}
			else if ($nbre > 4){
				$nbre = (int)(($nbre / 11) * 9);
				$totalNbre = $totalNbre + $nbre;
			}	
			
			$nbreLigneInterne = $nbreLigneInterne + $nbre;
			
			
			$premierLettre = substr($data[$i][0],0,1);
			$initiallettre = "";
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
			
			if ($nbreLigneInterne < $nbreAffichePage){
				${"tab$k"}[]= $data[$i];
				$nbre_k = $k;
				$totaltableau ++;
				$count ++;
			}

			else {
				$k++;
				$tabMaj[] = $nbMajk;
				
				$k_plus= $k+1;
				$tabligneInt[] = $nbreLigneInterne;
				${"tab$k"}[]= $data[$i];
				
				
				//echo "------------------ <br />";
				
				$nbreLigneInterne = 1;
				$totalNbre = 1;
				$nbMajk = 1;
				$count = 1;
			}
			
			
		}
		
		for ($i = 0; $i < $totaltableau; $i ++){
		    	$k_length = count($tabMaj);
                        if($i  < $k_length)  {	
			$tailleTable = count(${"tab$i"});
			$numeroTailleTable = $tailleTable -1;
                        $j_length =count(${"tab$i"}[$numeroTailleTable]); 
                        $valeurLaligne =  ${"tab$i"}[$numeroTailleTable][$j_length - 1];
                        
                        $nbMajk1 = $tabMaj[$i];
			$nbreLigneInterne1 = $tabligneInt[$i];
				
				
			if ($tailleTable!=0){
				
				if ($tailleTable==1 && $valeurLaligne <=2){
					$elementsuprim = array_pop(${"tab$i"});
					$dataSurplus[]  = $elementsuprim;
					$i_moins= $i-1;
					$tailleTableMoins = count(${"tab$i_moins"});
					if ($tailleTableMoins <=14){
						array_push(${"tab$i_moins"}, $dataSurplus[0]);
					}
				}
		
				else if ($tailleTable <=8 && $tailleTable !=0 && $nbreLigneInterne1 >=17 && $nbreLigneInterne1 <=19 && $nbreAffichePage ==17 && $nbMajk1==1 ){
					$i_plus= $i+1;
					$supp = array_pop(${"tab$i"});
					array_unshift(${"tab$i_plus"}, $supp);
					$i_plus = 0;
				}
				
				//4 --- 8 -------------- Total nbre base : 19 NbreMajk = 1 --- 19
				
				else if ($tailleTable <=8 && $tailleTable !=0 && $nbreLigneInterne1 >=17 && $nbreLigneInterne1 <=19 && $nbreAffichePage ==18 && $nbMajk1==1 ){
					$i_plus= $i+1;
					$supp = array_pop(${"tab$i"});
					array_unshift(${"tab$i_plus"}, $supp);
					$i_plus = 0;
				}
		
			}
                        }
		}
				
		

		
		for ($i = 0; $i <= $nbre_k; $i ++){
			$tailleTable = count(${"tab$i"});
				
			if ($tailleTable!=1 && $tailleTable!=0){
				//$nbreMaj1 = $this->colonnedata(${"tab$i"});
				//echo "--------------------------------------------<br/>";
				$this->numpage = $this->numpage+1;
				if ($i==0){
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
					margin-top: 0; '>$title</span>";
					echo "<div class='numpage1'>".$this->numpage."/".$nb_page."</div>";
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
									margin-top: 0; '>$title</span>";
					echo "<div class='numpage2'>".$this->numpage."/".$nb_page."</div>";
					echo '</div>';
					echo '</div>';
					echo '</page>';
					$this->numpage = $this->numpage +1;
				}
				
				echo '<page size="A4">';
				echo "<div class='conteneur'>";
				echo "<div class='colonne1'>";
				
				if ($i==0){
						//echo "<b  class='titremangamanga'>$title</b>";
						//echo "<span class='titremangamanga'>$title</span>";
						echo " <table class='titremangamanga'><tbody><tr><td><span>$title</span></td><td></td></tr></tbody></table>";
						
				}
				
				$tab = ${"tab$i"};
				$nbreMaj1 = $this->colonnedata($tab);
				
				echo "<div class='numpage1'>".$this->numpage."/".$nb_page."</div>";
				echo "</div>";
				echo "<div class='colonne2'>";
				if ($i==0){
					//echo "<b class='titremangamanga' >$title</b>";
					//echo "<span class='titremangamanga'>$title</span>";
					echo " <table class='titremangamanga'><tbody><tr><td><span>$title</span></td><td></td></tr></tbody></table>";
				}
				$nbreMaj2 = $this->colonnedata($tab);
				echo "<div class='numpage2'>".$this->numpage."/".$nb_page."</div>";
				echo "</div>";
				echo "</div>";
				echo '</page>';
				
			}
				
		}
		
		
	}	

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

    function colonnedata($data){
        $nbreMajuscule = 0;
        $tab = array();
        $initiallettre = "";
        echo "<table>";
        foreach($data as $row)
        {
           $premierLettre = substr($row[0],0,1);

            if ($premierLettre!=$initiallettre){
                $premierLettre = $premierLettre;
                $premierLettre = stripslashes(utf8_decode($premierLettre));

                $maj = strtoupper($premierLettre);
                $nbreMajuscule = $nbreMajuscule + 1;
                $tab[] = $maj;
                if($maj != ""){
                    echo "<tr><td colspan='2' style='padding-top:2mm;'><b>$maj</b></td></tr>";
                }else{
                    echo "<tr><td colspan='2'></td></tr>";
                }
            }
            $i=1;
            echo "<tr>";
            foreach($row as $col){
                $col = stripslashes(html_entity_decode($col));
                if ($i%2){
                    if($i == 1){
                        if($row[2] != ""){
                            echo "<td style='width:70mm; padding-top:2mm; vertical-align:top;'><b>$row[2]</b></td>";
                        }else{
                            echo "<td style='width:70mm; padding-top:2mm; vertical-align:top;'><b>".$row[0]."</b></td>";
                        }
                    }
                }
                else {
                    if($col != ""){
                        echo "<td style='width:70mm; padding-top:2mm; vertical-align:top;'><i>$col</i></td>";
                    }else{
                        echo "<td></td>";
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

    function getClient($societe_obj)
    {
        $societe = "Aucun";
        if($societe_obj != null)
            $societe = $societe_obj->getDescription();
        return $societe;
    }
}