<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\Departement;
use Formation\VocabulaireBundle\Entity\EnvironnementUsage;
use Formation\VocabulaireBundle\Entity\Fonction;
use Formation\VocabulaireBundle\Entity\Lexique;
use Formation\VocabulaireBundle\Entity\PhraseSource;
use Formation\VocabulaireBundle\Entity\Prototype;
use Formation\VocabulaireBundle\Entity\PrototypeAccess;
use Formation\VocabulaireBundle\Entity\Secteur;
use Formation\VocabulaireBundle\Entity\Source;
use Formation\VocabulaireBundle\Entity\Suffixe;
use Formation\VocabulaireBundle\Entity\SuffixeSociete;
use Formation\VocabulaireBundle\Entity\Theme;
use Formation\VocabulaireBundle\Entity\Vocabulaire;
use Formation\VocabulaireBundle\Entity\VocabulaireDepartement;
use Formation\VocabulaireBundle\Entity\VocabulaireEnvirUsage;
use Formation\VocabulaireBundle\Entity\VocabulaireFonction;
use Formation\VocabulaireBundle\Entity\VocabulairePhraseSource;
use Formation\VocabulaireBundle\Entity\VocabulairePrototype;
use Formation\VocabulaireBundle\Entity\VocabulairePrototypeAccess;
use Formation\VocabulaireBundle\Entity\VocabulaireSecteur;
use Formation\VocabulaireBundle\Entity\VocabulaireSociete;
use Formation\VocabulaireBundle\Entity\VocabulaireTheme;
use Formation\VocabulaireBundle\Entity\VocabulaireTraducteur;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/mise_a_jour_vocab", name="mise_a_jour_vocab")
     */
    public function mise_a_jour_vocab_pageAction(Request $request)
    {

        $repositoryLanguage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Language');
        $languages = $repositoryLanguage->findAll();

        $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
        $traducteurs = $repositoryTraducteur->findAll();

        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societes = $repositorySociete->findAll();

        return $this->render('FormationVocabulaireBundle:Default:mise_a_jour_vocab.html.twig', array(
            'languages' => $languages,
            'traducteurs' => $traducteurs,
            'societes' => $societes,
        ));
    }


    /**
     * @Route("/import_prototype_LE/{id}/{id_societe}", name="import_prototype_LE")
     */
    public function import_prototype_LEAction($id, $id_societe)
    {

        $repositoryLanguage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Language');
        $languages = $repositoryLanguage->findAll();

        $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
        $traducteurs = $repositoryTraducteur->findAll();

        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societes = $repositorySociete->findAll();

        return $this->render('FormationVocabulaireBundle:Prototype:import_prototype.html.twig', array(
            'languages' => $languages,
            'traducteurs' => $traducteurs,
            'societes' => $societes,
            'id_prot' => $id
        ));
    }
    
    /**
     * @Route("/add_new_termes_le", name="add_new_termes_le")
     */
    public function add_new_termes_LEAction(Request $request)
    {

        $repositoryLanguage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Language');
        $languages = $repositoryLanguage->findAll();

        $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
        $traducteurs = $repositoryTraducteur->findAll();

        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societes = $repositorySociete->findAll();

        return $this->render('FormationVocabulaireBundle:Default:add_new_termes_le.html.twig', array(
            'languages' => $languages,
            'traducteurs' => $traducteurs,
            'societes' => $societes,
        ));
    }


    /**
     * @Route("/uploadExcel", name="uploadExcel")
     */
    public function uploadExcelAction(Request $request)
    {
        $file = "";
        $fileType = "";
        $uploadDir= "";
        if($request->files->get('userfile'))
        {

            $fileType = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);

            //$uploadDir = 'C:/wamp/www' . $request->getBasePath() . '/uploads/'; # serveur dev
            $uploadDir = '/var/www/html' . $request->getBasePath() . '/uploads/'; # serveur prod
            
            $file = $request->files->get('userfile');
            $fileName = $file->getClientOriginalName();
            $file->move($uploadDir, $fileName);
        }
        if ($fileType == "xls" || $fileType == "xlsx" || $fileType == "ods") {
            $repositoryListColumn = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:ListColumn');
            $listColumns = $repositoryListColumn->findAll();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($uploadDir . $fileName);
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                $worksheetTitle = $worksheet->getTitle();
                echo $worksheetTitle;
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                for ($row = 1; $row <= $highestRow; ++$row) {
                    $tab = array();
                    echo "<input type='hidden' id='nbreCol' value='$highestColumnIndex'/>";
                    echo "<table id='tableResult'>";
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        if ($row == 1) {
                            $valCol = $col + 1;
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $val = $cell->getValue();
                            $tab[$col] = $val;
                            echo '<tr><td>' . $val . '<input type="hidden" id="valueCol_' . $valCol . '" value="' . $valCol . '"/></td><td>';

                            echo '<select name="colonne' . $valCol . '" id="colonne' . $valCol . '">';
                            echo '<option value="0">Ne pas importer</option>';
                            foreach ($listColumns as $listColumn) {
                                $nom_column = $this->texteUnicode($listColumn->getNomColumn());
                                $selected = '';
                                if ($nom_column == $val) {
                                    $selected = 'selected=selected';
                                }
                                echo '<option  value="' . $listColumn->getId() . '"' . $selected . '">"' . $nom_column . '"</option>';
                            }
                            echo '</select>';
                        }
                    }
                    echo '</table>';
                }
            }
        }
        return $this->render('FormationVocabulaireBundle:Default:uploadExcel.html.twig', array());
    }

    private function texteUnicode($texte)
    {
        $tab2 = array("à", "è", "é", "ê", "ù", "»", "«", "°", "œ", "oeil", "ç");
        $tab1 = array("&agrave;", "&egrave;", "&eacute;", "&ecirc;", "&ugrave;", "&raquo;", "&laquo;", "&deg;", "œ", "œil", "&ccedil");
        $texte = str_replace($tab1, $tab2, $texte);

        return $texte;
    }



    /**
     * @Route("/mise_a_jour_vocab_action", name="mise_a_jour_vocab_action")
     */
    public function mise_a_jour_vocabAction(Request $request)
    {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');

        $id_societe = intval($request->get('societe'));
        $id_language = intval($request->get('langue'));
        $id_traducteur = intval($request->get('id_traducteur'));

        $date = date("Y-m-d H:i:s");
        $date_today = date("Y-m-d");
        $colonneDroite = $request->get('colonneDroite');
        $colonneGauche = $request->get('colonneGauche');

        $colonneDroiteValue = explode(",", $colonneDroite);
        $colonneGaucheValue = explode(",", $colonneGauche);
        $index_theme = 0;
        $index_contexte_usage = 0;
        $index_langue_origine1 = 0;
        $index_langue_traduction1 = 0;
        $index_source_type = 0;
        $index_nom_stagiaire = 0;
        $index_nom_doc = 0;
        $index_lien = 0;
        $index_rang = 0;
        $index_secteur = 0;
        $index_departement = 0;
        $index_theme_eng = 0;
        $index_phrase_source = 0;
        $index_fonction = 0;
        $index_prototype = 0;
        $index_suffixe = 0;
        $index_millesime = 0;

        for ($index = 1; $index < count($colonneDroiteValue); $index++) {
            if ($colonneDroiteValue[$index] == 4) {
                $index_theme = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 5) {
                $index_contexte_usage = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 6) {
                $index_langue_origine1 = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 7) {
                $index_langue_traduction1 = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 8) {
                $index_source_type = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 9) {
                $index_nom_stagiaire = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 10) {
                $index_nom_doc = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 11) {
                $index_lien = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 12) {
                $index_rang = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 13) {
                $index_secteur = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 14) {
                $index_departement = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 15) {
                $index_theme_eng = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 16) {
                $index_phrase_source = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 17) {
                $index_fonction = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 18) {
                $index_prototype = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 19) {
                $index_suffixe = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 20) {
                $index_millesime = $colonneGaucheValue[$index];
            }
        }

        $fileType = pathinfo($_FILES['userfile2']['name'], PATHINFO_EXTENSION);
        //$uploadDir = 'C:/wamp/www' . $request->getBasePath() . '/uploads/'; # serveur dev
        $uploadDir = '/var/www/html' . $request->getBasePath() . '/uploads/'; # serveur prod
        $file = $request->files->get('userfile2');
        $fileName = $file->getClientOriginalName();
        $file->move($uploadDir, $fileName);

        if ($fileType == "xls" || $fileType == "xlsx" || $fileType == "ods") {

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($uploadDir . $fileName);
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                for ($row = 2; $row <= $highestRow; ++$row) {
                    $tab = array();
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getValue();
                        $tab[$col] = $val;
                    }
                    $theme = "";
					$contexte_usage = "";
					$langue_origine1 = "";
					$langue_traduction1 = "";
					$source_type = "";
					$nom_stagiaire = "";
					$nom_doc = "";
					$rang = "";
					$lien = "";
					$secteur = "";
					$departement = "";
					$prototype = "";
					$theme_eng = "";
					$fonction = "";
					$phrase_source = "";
					$suffixe = "";
					$millesime = "";

                    if ($index_theme != 0) {
                        $theme = $this->quote($tab[$index_theme - 1]);
                        $theme = trim($theme);
                       
                    }
                    if ($index_contexte_usage != 0) {
                        $environnement_usage = $this->quote($tab[$index_contexte_usage - 1]);
                        $environnement_usage = trim($environnement_usage);
                    }
                    if ($index_langue_origine1 != 0) {
                        $langue_origine1 = $this->quote($tab[$index_langue_origine1 - 1]);
                        $langue_origine1 = trim($langue_origine1);
                    }
                    if ($index_langue_traduction1 != 0) {
                        $langue_traduction1 = $this->quote($tab[$index_langue_traduction1 - 1]);
                        $langue_traduction1 = trim($langue_traduction1);
                    }
                    if ($index_source_type != 0) {
                        $source_type = $this->quote($tab[$index_source_type - 1]);
                        $source_type = trim($source_type);
                    }
                    if ($index_nom_stagiaire != 0) {
                        $nom_stagiaire = $this->quote($tab[$index_nom_stagiaire - 1]);
                        $nom_stagiaire = trim($nom_stagiaire);
                    }
                    if ($index_nom_doc != 0) {
                        $nom_doc = $this->quote($tab[$index_nom_doc - 1]);
                        $nom_doc = trim($nom_doc);
                    }
                    if ($index_rang != 0) {
                        $rang = $this->quote($tab[$index_rang - 1]);
                        $rang = trim($rang);
                    }
                    if ($index_lien != 0) {
                        $lien = $this->quote($tab[$index_lien - 1]);
                        $lien = trim($lien);
                    }
                    if ($index_secteur != 0) {
                        $secteur = $this->quote($tab[$index_secteur - 1]);
                        $secteur = trim($secteur);
                    }
                    if ($index_departement != 0) {
                        $departement = $this->quote($tab[$index_departement - 1]);
                        $departement = trim($departement);
                    }
                    if ($index_theme_eng != 0) {
                        $theme_eng = $this->quote($tab[$index_theme_eng - 1]);
                        $theme_eng = trim($theme_eng);
                    }
                    if ($index_fonction != 0) {
                        $fonction = $this->quote($tab[$index_fonction - 1]);
                        $fonction = trim($fonction);
                    }
                    if ($index_prototype != 0) {
                        $prototype = $this->quote($tab[$index_prototype - 1]);
                        $prototype = trim($prototype);
                    }
                    if ($index_phrase_source != 0) {
                        $phrase_source = $this->quote($tab[$index_phrase_source - 1]);
                        $phrase_source = trim($phrase_source);
                    }
                    if ($index_suffixe != 0) {
                        $suffixe = $this->quote($tab[$index_suffixe - 1]);
                        $suffixe = trim($suffixe);
                    }
                    if ($index_millesime != 0) {
                        $millesime = $this->quote($tab[$index_millesime - 1]);
                        $millesime = trim($millesime);
                    }


					if($langue_origine1 != "" && $langue_traduction1 != ""){
						$id_source = 0;
						if($source_type != "" || $nom_stagiaire != "" || $nom_doc != "" || $lien != ""){
							//insertion source
							$source_sql = "INSERT IGNORE INTO source VALUES ('', $source_type, $nom_stagiaire, $nom_doc, $lien)";
							$id_source = $this->execute($source_sql);
							//$id_source = mysql_insert_id() ;						
						}	
						//verification existence vocabulaire
						$langue_origine_avec_maj = $this->trim_ucfirst_strtolower_utf8($langue_origine1);
						$sql1 ='select * from  vocabulaire where langue_origine="$langue_origine_avec_maj" and langue_traduction="$langue_traduction1" and id_language="$id_language" ';
						$query= $this->fetch($sql1);
						//$query= mysql_query($sql1) or die(mysql_error());
						
						//$rowvcb = mysql_fetch_array($query);
						if(count($query) > 0){
						foreach($query as $rowvcb){
							$id_vocabulaire = $rowvcb['id_vocabulaire'];
							//le vocabulaire existe deja donc modif date de modification
							$vocab_update_date_sql = "UPDATE vocabulaire SET date_modification = '$date' WHERE id_vocabulaire='$id_vocabulaire'";
							$this->execute($vocab_update_date_sql);
						}
						}else{
							$langue_origine_sans_modif = "";
							$alphabet_min = range('a', 'z');
							$alphabet_maj = range('A', 'Z');
							$first_lettre = $langue_origine1[0];
							if(!in_array($first_lettre,$alphabet_min) && !in_array($first_lettre,$alphabet_maj)){
								$langue_origine_sans_modif = $langue_origine1;
							}
							$nb_caract = strlen($langue_origine1);
							$nb_caract_lt = strlen($langue_traduction1);
							$fixeNbreCaractreLigne = 44;
							$langue_origine_clean = $this->cleanLangueOrigine($langue_origine1);
							$nb_ligne = ($nb_caract/$fixeNbreCaractreLigne);
							$nb_ligne_lt = ($nb_caract_lt/$fixeNbreCaractreLigne);
							$partieEntiere = (int)($nb_caract/$fixeNbreCaractreLigne);
							$partieEntiere_lt = (int)($nb_caract_lt/$fixeNbreCaractreLigne);
							if($partieEntiere < $nb_ligne){
								$partieEntiere = $partieEntiere + 1;
							}	
							if($partieEntiere_lt < $nb_ligne_lt){
								$partieEntiere_lt = $partieEntiere_lt + 1;
							}
							//insertion vocabulaire
							$vocab_sql = 'INSERT IGNORE INTO vocabulaire VALUES ("", "$date", "", "$langue_origine_avec_maj", "$langue_origine_sans_modif", "$langue_traduction1", "$id_source", "$id_language", "$rang", "1" , "$nb_caract", "$partieEntiere", "$langue_origine_clean", "$nb_caract_lt", "$partieEntiere_lt")';
							//echo $vocab_sql.";<br/>";"
							//mysql_query($vocab_sql);
							$id_vocabulaire = $this->execute($vocab_sql);
						}
						$id_secteur = 0;
						if($secteur != ""){
							//verif secteur d'activité
							$sql_secteur ="select * from secteur where libelle_secteur=$secteur ";
							$query_secteur= $this->fetch($sql_secteur);
							if(count($query_secteur) > 0){
							foreach($query_secteur as $row_secteur){
								$id_secteur = $row_secteur['id_secteur'];
							}
						   }else{
								$secteur_sql = "INSERT IGNORE INTO secteur VALUES ('', $secteur)";
								//mysql_query($secteur_sql);
								$id_secteur = $this->execute($secteur_sql);
							}	
							$sql_test_secteur ="select * from vocabulaire_secteur where id_secteur='$id_secteur' AND id_vocabulaire='$id_vocabulaire' ";
							$query_test_secteur = $this->fetch($sql_test_secteur);
							if(count($query_test_secteur) == 0){
								$vocab_secteur_sql = "INSERT IGNORE INTO vocabulaire_secteur VALUES ('','$id_secteur','$id_vocabulaire')";
								$this->execute($vocab_secteufr_sql);
							}					 								
						}

						$id_departement = 0;
						if($departement != ""){
							//verif departement
							$sql_departement ="select * from departement where libelle_departement=$departement ";
							$query_departement= $this->fetch($sql_departement);
							if(count($query_departement) > 0){
							foreach($query_departement as $row_departement){
								$id_departement = $row_departement['id_departement'];
							}
						}else{
								$departement_sql = "INSERT IGNORE INTO departement VALUES ('', $departement)";
								$id_departement = $this->execute($departement_sql) ;
							}	
							$sql_test_dept ="select * from vocabulaire_departement where id_departement='$id_departement' AND id_vocabulaire='$id_vocabulaire' ";
							$query_test_dept= $this->fetch($sql_test_dept);
							if(count($query_test_dept) == 0 ){
								$vocab_dept_sql = "INSERT IGNORE INTO vocabulaire_departement VALUES ('','$id_departement','$id_vocabulaire')";
								$this->execute($vocab_dept_sql);
							}																			
						}
						
						$id_theme = 0;
						if($theme != ""){
							//verif theme
							$sql_theme ="select * from theme where libelle_theme=$theme";
							$query_theme= $this->fetch($sql_theme);
							if(count($query_theme) > 0 ){
							foreach($query_theme as $row_theme){
								$id_theme = $row_theme['id_theme'];
							}
						}else{
								$theme_sql = "INSERT IGNORE INTO theme VALUES ('', $theme, $theme_eng)";
								//mysql_query($theme_sql);
								$id_theme = $this->execute($theme_sql) ;
							}	
							$sql_test_theme ="select * from vocabulaire_theme where id_theme='$id_theme' AND id_vocabulaire='$id_vocabulaire' ";
							$query_test_theme= $this->fetch($sql_test_theme);
							if(count($query_test_theme) == 0 ){
								$vocab_theme_sql = "INSERT IGNORE INTO vocabulaire_theme VALUES ('','$id_theme','$id_vocabulaire')";
								$this->execute($vocab_theme_sql);
							}													
						}

						$id_fonction = 0;
						if($fonction != ""){
							//verif fonction
							$sql_fonction ="select * from fonction where libelle_fonction=$fonction ";
							$query_fonction= $this->fetch($sql_fonction);
							if(count($query_fonction) > 0){
							foreach($query_fonction as $row_fonction){
								$id_fonction = $row_fonction['id_fonction'];
							}
							}else{
								$fonction_sql = "INSERT IGNORE INTO fonction VALUES ('', $fonction)";
								$id_fonction = $this->execute($fonction_sql);
						
							}
							$sql_test_fonction ="select * from vocabulaire_fonction where id_fonction='$id_fonction' AND id_vocabulaire='$id_vocabulaire' ";
							$query_test_fonction= $this->fetch($sql_test_fonction);
							if(count($query_test_fonction) == 0){
								$vocab_fonction_sql = "INSERT IGNORE INTO vocabulaire_fonction VALUES ('','$id_fonction','$id_vocabulaire')";
								$this->execute($vocab_fonction_sql);
							}													
						}

						$id_env_usage = 0;
						if($contexte_usage != ""){
							//verif env usage
							$sql_env_usage ="select * from environnement_usage where libelle_env_usage=$contexte_usage ";
							$query_env_usage= $this->fetch($sql_env_usage);
							if(count($query_env_usage) > 0){
							 foreach($query_env_usage as $row_env_usage){
								$id_env_usage = $row_env_usage['id_environnement_usage'];
							}
							}else{
								$env_usage_sql = "INSERT IGNORE INTO environnement_usage VALUES ('', $contexte_usage)";
								$id_env_usage = $this->execute($env_usage_sql);
							}
							$sql_test_envir_usage ="select * from vocabulaire_envir_usage where id_environnement_usage='$id_env_usage' AND id_vocabulaire='$id_vocabulaire' ";
							$query_test_envir_usage=  $this->fetch($sql_test_envir_usage);
							if(count($query_test_envir_usage) == 0){
								$vocab_envir_usage_sql = "INSERT IGNORE INTO vocabulaire_envir_usage VALUES ('','$id_env_usage','$id_vocabulaire')";
								$this->execute($vocab_envir_usage_sql);
							}													
						}

						if($id_traducteur != "" && $id_traducteur != 0){
							$sql_test_traducteur ="select * from vocabulaire_traducteur where id_traducteur='$id_traducteur' AND id_vocabulaire='$id_vocabulaire' ";
							$query_test_traducteur= $this->fetch($sql_test_traducteur);
							if(count($query_test_traducteur) == 0){
								$vocab_traducteur_sql = "INSERT IGNORE INTO vocabulaire_traducteur VALUES ('','$id_traducteur','$id_vocabulaire')";
								$this->execute($vocab_traducteur_sql);
							}							
						}

						$id_prototype = 0;
						if($prototype != ""){
							//verif prototype
							$sql_prototype ="select * from prototype where libelle_prototype=$prototype ";
							$query_prototype= $this->fetch($sql_prototype);
							if(count($query_prototype) > 0){
							foreach($query_prototype as $row_prototype){
								$id_prototype = $row_prototype['id_prototype'];
							}
							}else{
								$prototype_sql = "INSERT IGNORE INTO prototype VALUES ('', $prototype)";
								echo $prototype_sql;
								
								$id_prototype = $this->execute($prototype_sql) ;
								$date_today = date("Y-m-d");
								die();
							}
							$sql_test_prototype ="select * from vocabulaire_prototype where id_prototype='$id_prototype' AND id_vocabulaire='$id_vocabulaire' ";
							$query_test_prototype= $this->fetch($sql_test_prototype);
							if(count($query_test_prototype)  == 0){
								$vocab_dept_sql = "INSERT IGNORE INTO vocabulaire_prototype VALUES ('','$id_prototype','$id_vocabulaire')";
								$this->execute($vocab_dept_sql);
							}
							//$traducteur = getTraducteur($id_traducteur);
							$sql_test_prot_access ="select * from prototype_access where type=$prototype AND id_societe='$id_societe' ";
							
							$query_test_prot_access= $this->fetch($sql_test_prot_access);
							if(count($query_test_prot_access)  == 0){
								$sql="INSERT IGNORE INTO `prototype_access` (`id_prototype_access`, `numero`, `type`, `date`, `nb_page`, `statut`, `createur`, `mis_a_jour`, `phase`, `phase_etat`, `priorite_type`, `id_format_edition`, `id_societe`) 
								VALUES (NULL, '', $prototype, '$date_today', '', '', '$id_traducteur', '', '', '', '', '3', '$id_societe');";
								
								$id_prot = $this->execute($sql) ;
							}else{
								foreach ($query_test_prot_access as $row_test_prot_access) {
									$id_prot = $row_test_prot_access['id_prototype_access'] ;
								}
								
							}
							////ajout dans la table lexique pour gerer les rangs des LE
							if($id_societe != 653){ 
								$rang_lexique = $this->recupRangLE($id_societe, $id_theme, $id_prot);
								$rangLE = $this->getMaxRangLE($id_prot);
								$rangLE = $rangLE+1;
								if($rang_lexique == 0 || $rang_lexique == ""){
									$sql_insert1="INSERT IGNORE INTO lexique(id_societe,id_theme,id_prototype_access,rang) VALUES ('$id_societe', '$id_theme', '$id_prot', '$rangLE')";
									$this->execute($sql_insert1);	
								}
							}
							$sql_test_prototype_access ='select * from vocabulaire_prototype_access where id_vocabulaire="$id_vocabulaire" AND id_prototype_access="$id_prot" ';
							
							$query_test_prototype_access= $this->fetch($sql_test_prototype_access);
							if(count($query_test_prototype_access)  == 0){
								$sql_insert="INSERT IGNORE INTO vocabulaire_prototype_access VALUES ('', '$id_vocabulaire', '$id_prot')";
								$this->execute($sql_insert);
							}
						}					

						$sql_test_societe ="select * from vocabulaire_societe where id_societe='$id_societe' AND id_vocabulaire='$id_vocabulaire' ";
						$query_test_societe= $this->fetch($sql_test_societe);
						if(count($query_test_societe) == 0){
							$vocab_soc_sql = "INSERT IGNORE INTO vocabulaire_societe VALUES ('','$id_societe','$id_vocabulaire')";
							$this->execute($vocab_soc_sql);
						}
						
						$id_suffixe = 0;
						if($suffixe != ""){
							//verif suffixe
							//$suffixe_verif = strtolower($suffixe);
							$sql_suffixe ="select * from suffixe where libelle_suffixe=$suffixe and millesime=$millesime ";
							$query_suffixe= $this->fetch($sql_suffixe);
							if(count($query_suffixe) > 0){
							foreach($query_suffixe as $row_suffixe){
								$id_suffixe = $row_suffixe['id_suffixe'];
								}
							}else{
								$suffixe_sql = "INSERT IGNORE INTO suffixe VALUES ('', $suffixe, $millesime)";
								
								$id_suffixe = $this->execute($suffixe_sql) ;
							}
							$sql_test_suffixe ="select * from suffixe_societe where id_suffixe='$id_suffixe' AND id_societe='$id_societe' ";
							$query_test_suffixe= $this->fetch($sql_test_suffixe);
							if(count($query_test_suffixe) == 0){
								$vocab_sfx_sql = "INSERT IGNORE INTO suffixe_societe VALUES ('','$id_suffixe','$id_societe')";
								$this->execute($vocab_sfx_sql);
							}													
						}

						//phrase source non vide
						if($index_phrase_source != null && $index_phrase_source != 0){
							$phrase_source = $this->quote($tab[$index_phrase_source]);
							//verif phrase source
							$sql_phrase_source ="select * from phrase_source where libelle_phrase_source=$phrase_source ";
							$query_phrase_source = $this->fetch($sql_phrase_source);
							if(count($query_phrase_source) > 0){
							foreach($query_phrase_source as $row_phrase_source){
								$id_phrase_source = $row_phrase_source['id_phrase_source'];
							}
							}else{
								$phrase_source_sql = "INSERT IGNORE INTO phrase_source VALUES ('', $phrase_source)";
								
								$id_phrase_source = $this->execute($phrase_source_sql) ;
							}
							$sql_test_phrase_source ="select * from vocabulaire_phrase_source where id_phrase_source='$id_phrase_source' AND id_vocabulaire='$id_vocabulaire' ";
							$query_test_phrase_source= $this->fetch($sql_test_phrase_source);
							if(count($query_test_phrase_source) == 0){
								$vocab_phrase_source_sql = "INSERT IGNORE INTO vocabulaire_phrase_source VALUES ('','$id_phrase_source','$id_vocabulaire')";
								$this->execute($vocab_phrase_source_sql);
							}
						}
					}
				}

                if ($id_societe == 653) {
                    return $this->redirect($this->generateUrl('consulter_prototype'));
                } else {

                    return $this->redirectToRoute('modif_prototype', array('id' => $id_prot));
                }

            }
        } else {
            echo "<SCRIPT language=javascript> alert('Format de fichier non reconnu'); window.location = 'mise_a_jour_vocab';</SCRIPT>";
        }
        return $this->render('FormationVocabulaireBundle:Default:uploadExcel.html.twig', array());
		
    }

     private function fetch($query)
    {
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($query);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    private function execute($query)
    {
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($query);
        $stmt->execute();
		return $this->getDoctrine()->getManager()->getConnection()->lastInsertId();
    }
	
	 private function quote($str)
    {
		return $this->getDoctrine()->getManager()->getConnection()->quote($str);
    }
	
        /**
     * @Route("/mise_a_jour_vocab_action1", name="mise_a_jour_vocab_action1")
     */
    public function mise_a_jour_vocabAction1(Request $request)
    {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');

        $id_societe = intval($request->get('societe'));
        $id_language = intval($request->get('langue'));
        $id_traducteur = intval($request->get('id_traducteur'));

        $date = date("Y-m-d H:i:s");
        $date_today = date("Y-m-d");
        $colonneDroite = $request->get('colonneDroite');
        $colonneGauche = $request->get('colonneGauche');

        $colonneDroiteValue = explode(",", $colonneDroite);
        $colonneGaucheValue = explode(",", $colonneGauche);
        $index_theme = 0;
        $index_contexte_usage = 0;
        $index_langue_origine1 = 0;
        $index_langue_traduction1 = 0;
        $index_source_type = 0;
        $index_nom_stagiaire = 0;
        $index_nom_doc = 0;
        $index_lien = 0;
        $index_rang = 0;
        $index_secteur = 0;
        $index_departement = 0;
        $index_theme_eng = 0;
        $index_phrase_source = 0;
        $index_fonction = 0;
        $index_prototype = 0;
        $index_suffixe = 0;
        $index_millesime = 0;

        for ($index = 1; $index < count($colonneDroiteValue); $index++) {
            if ($colonneDroiteValue[$index] == 4) {
                $index_theme = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 5) {
                $index_contexte_usage = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 6) {
                $index_langue_origine1 = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 7) {
                $index_langue_traduction1 = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 8) {
                $index_source_type = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 9) {
                $index_nom_stagiaire = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 10) {
                $index_nom_doc = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 11) {
                $index_lien = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 12) {
                $index_rang = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 13) {
                $index_secteur = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 14) {
                $index_departement = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 15) {
                $index_theme_eng = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 16) {
                $index_phrase_source = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 17) {
                $index_fonction = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 18) {
                $index_prototype = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 19) {
                $index_suffixe = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 20) {
                $index_millesime = $colonneGaucheValue[$index];
            }
        }

        $fileType = pathinfo($_FILES['userfile2']['name'], PATHINFO_EXTENSION);
        //$uploadDir = 'C:/wamp/www' . $request->getBasePath() . '/uploads/'; # serveur dev
        $uploadDir = '/var/www/html' . $request->getBasePath() . '/uploads/'; # serveur prod
        $file = $request->files->get('userfile2');
        $fileName = $file->getClientOriginalName();
        $file->move($uploadDir, $fileName);

        if ($fileType == "xls" || $fileType == "xlsx" || $fileType == "ods") {

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($uploadDir . $fileName);
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                for ($row = 2; $row <= $highestRow; ++$row) {
                    $tab = array();
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getValue();
                        $tab[$col] = $val;
                    }
                    $theme = "";
                    $environnement_usage = "";
                    $langue_origine1 = "";
                    $langue_traduction1 = "";
                    $source_type = "";
                    $nom_stagiaire = "";
                    $nom_doc = "";
                    $rang = "";
                    $lien = "";
                    $secteur = "";
                    $departement = "";
                    $prototype = "";
                    $theme_eng = "";
                    $fonction = "";
                    $suffixe = "";
                    $millesime = "";

                    if ($index_theme != 0) {
                        $theme = $this->quote($tab[$index_theme - 1]);
                        $theme = trim($theme);
                       
                    }
                    if ($index_contexte_usage != 0) {
                        $environnement_usage = $this->quote($tab[$index_contexte_usage - 1]);
                        $environnement_usage = trim($environnement_usage);
                    }
                    if ($index_langue_origine1 != 0) {
                        $langue_origine1 = $this->quote($tab[$index_langue_origine1 - 1]);
                        $langue_origine1 = trim($langue_origine1);
                    }
                    if ($index_langue_traduction1 != 0) {
                        $langue_traduction1 = $this->quote($tab[$index_langue_traduction1 - 1]);
                        $langue_traduction1 = trim($langue_traduction1);
                    }
                    if ($index_source_type != 0) {
                        $source_type = $this->quote($tab[$index_source_type - 1]);
                        $source_type = trim($source_type);
                    }
                    if ($index_nom_stagiaire != 0) {
                        $nom_stagiaire = $this->quote($tab[$index_nom_stagiaire - 1]);
                        $nom_stagiaire = trim($nom_stagiaire);
                    }
                    if ($index_nom_doc != 0) {
                        $nom_doc = $this->quote($tab[$index_nom_doc - 1]);
                        $nom_doc = trim($nom_doc);
                    }
                    if ($index_rang != 0) {
                        $rang = $this->quote($tab[$index_rang - 1]);
                        $rang = trim($rang);
                    }
                    if ($index_lien != 0) {
                        $lien = $this->quote($tab[$index_lien - 1]);
                        $lien = trim($lien);
                    }
                    if ($index_secteur != 0) {
                        $secteur = $this->quote($tab[$index_secteur - 1]);
                        $secteur = trim($secteur);
                    }
                    if ($index_departement != 0) {
                        $departement = $this->quote($tab[$index_departement - 1]);
                        $departement = trim($departement);
                    }
                    if ($index_theme_eng != 0) {
                        $theme_eng = $this->quote($tab[$index_theme_eng - 1]);
                        $theme_eng = trim($theme_eng);
                    }
                    if ($index_fonction != 0) {
                        $fonction = $this->quote($tab[$index_fonction - 1]);
                        $fonction = trim($fonction);
                    }
                    if ($index_prototype != 0) {
                        $prototype = $this->quote($tab[$index_prototype - 1]);
                        $prototype = trim($prototype);
                    }
                    if ($index_phrase_source != 0) {
                        $phrase_source = $this->quote($tab[$index_phrase_source - 1]);
                        $phrase_source = trim($phrase_source);
                    }
                    if ($index_suffixe != 0) {
                        $suffixe = $this->quote($tab[$index_suffixe - 1]);
                        $suffixe = trim($suffixe);
                    }
                    if ($index_millesime != 0) {
                        $millesime = $this->quote($tab[$index_millesime - 1]);
                        $millesime = trim($millesime);
                    }
                    $id_vocabulaire = 0;
                    $id_source = 0;
                    $em = $this->getDoctrine()->getManager();
                    if ($langue_origine1 != "" && $langue_traduction1 != "") {

                        if ($source_type != "" || $nom_stagiaire != "" || $nom_doc != "" || $lien != "") {
                            //insertion source
                            $source = new Source();
                            $source->setSourceType($source_type);
                            $source->setSourceNomStagiaire($nom_stagiaire);
                            $source->setLienNomDoc($nom_doc);
                            $source->setLien($lien);

                            $em->persist($source);
                            $em->flush();

                            $id_source = $source->getId();

                        }

                        //verification existence vocabulaire
                        $langue_origine_avec_maj = $this->trim_ucfirst_strtolower_utf8($langue_origine1);

                        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                        $vocabulaire = $repositoryVocabulaire->findOneBy(array('langueOrigine' => $langue_origine_avec_maj, 'langueTraduction' => $langue_traduction1, 'language' => $id_language));

                        if ($vocabulaire != null) {

                            $vocabulaire->setDateModification(new \DateTime($date));
                            $em->merge($vocabulaire);
                            $em->flush();
                            $id_vocabulaire = $vocabulaire->getId();


                        } else {
                            $langue_origine_sans_modif = "";
                            $alphabet_min = range('a', 'z');
                            $alphabet_maj = range('A', 'Z');
                            $first_lettre = $langue_origine1[0];
                            if (!in_array($first_lettre, $alphabet_min) && !in_array($first_lettre, $alphabet_maj)) {
                                $langue_origine_sans_modif = $langue_origine1;
                            }
                            $nb_caract = strlen($langue_origine1);
                            $fixeNbreCaractreLigne = 47;

                            $langue_origine_clean = $this->cleanLangueOrigine($langue_origine1);
                            $nb_ligne = ($nb_caract / $fixeNbreCaractreLigne);
                            $partieEntiere = (int)($nb_caract / $fixeNbreCaractreLigne);
                            if ($partieEntiere < $nb_ligne) {
                                $partieEntiere = $partieEntiere + 1;
                            }

                            //insertion vocabulaire
                            $repositoryLanguage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Language');
                            $language = $repositoryLanguage->find($id_language);

                            $repositorySource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Source');
                            $source = $repositorySource->find($id_source);

                            if ($source != null && $language != null) {

                                $vocab = new Vocabulaire();
                                $vocab->setDateCreation(new \DateTime($date));
                                $vocab->setLangueOrigine($langue_origine_avec_maj);
                                $vocab->setLangueOrigineSansModif($langue_origine_sans_modif);
                                $vocab->setLangueTraduction($langue_traduction1);
                                $vocab->setRang($rang);
                                $vocab->setIsAffiche(1);
                                $vocab->setNbreCaractLo($nb_caract);
                                $vocab->setLanguage($language);
                                $vocab->setSource($source);
                                $vocab->setNbreLigneLo(0);
                                $em->persist($vocab);
                                $em->flush();
                                $id_vocabulaire = $vocab->getId();
                            }


                        }

                        if ($secteur != "") {
                            //verif secteur d'activité
                            $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
                            $sect = $repositorySecteur->findOneBy(array('libelleSecteur' => $secteur));

                            if ($sect != null) {
                                $id_secteur = $sect->getId();
                            } else {
                                $sect = new Secteur();
                                $sect->setLibelleSecteur($secteur);
                                $em->persist($sect);
                                $em->flush();
                                $id_secteur = $sect->getId();

                            }

                            $repositoryVocabulaireSecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSecteur');
                            $vocabulaireSecteur = $repositoryVocabulaireSecteur->findOneBy(array('secteur' => $id_secteur, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireSecteur == null) {

                                $vocabulaireSecteur = new VocabulaireSecteur();
                                $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
                                $sec = $repositorySecteur->find($id_secteur);
                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $sec != null) {
                                    $vocabulaireSecteur->setSecteur($sec);
                                    $vocabulaireSecteur->setVocabulaire($vocabulaire);
                                    $em->merge($vocabulaireSecteur);
                                    $em->flush();
                                }

                            }
                        }

                        if ($departement != "") {
                            //verif departement
                            $repositoryDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Departement');
                            $depart = $repositoryDepartement->findOneBy(array('libelleDepartement' => $departement));
                            $id_departement = 0;
                            if ($depart != null) {
                                $id_departement = $depart->getId();
                            } else {
                                $depart = new Departement();
                                $depart->setLibelleDepartement($departement);
                                $em->persist($depart);
                                $em->flush();
                                $id_departement = $depart->getId();
                            }
                            $repositoryVocabulaireDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireDepartement');
                            $vocabulaireDepartement = $repositoryVocabulaireDepartement->findOneBy(array('departement' => $id_departement, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireDepartement == null) {

                                $vocabulaireDepartement = new VocabulaireDepartement();
                                $repositoryDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Departement');
                                $depart = $repositoryDepartement->find($id_departement);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $depart != null) {
                                    $vocabulaireDepartement->setDepartement($depart);
                                    $vocabulaireDepartement->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireDepartement);
                                    $em->flush();
                                }


                            }

                        }

                        $id_theme = 0;

                        if ($theme != "") {
                            //verif theme
                            $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
                            $them = $repositoryTheme->findOneBy(array('libelleTheme' => $theme));
                            $id_theme = 0;
                            if ($them != null) {
                                $id_theme = $them->getId();
                            } else {
                                $them = new Theme();
                                $them->setLibelleTheme($theme);
                                $em->persist($them);
                                $em->flush();
                                $id_theme = $them->getId();
                            }
                            $repositoryVocabulaireTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireTheme');
                            $vocabulaireTheme = $repositoryVocabulaireTheme->findOneBy(array('theme' => $id_theme, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireTheme == null) {

                                $vocabulaireTheme = new VocabulaireTheme();
                                $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
                                $them = $repositoryTheme->find($id_theme);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                                if ($vocabulaire != null && $them != null) {
                                    $vocabulaireTheme->setTheme($them);
                                    $vocabulaireTheme->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireTheme);
                                    $em->flush();
                                }


                            }

                        }
                        if ($fonction != "") {
                            //verif fonction
                            $repositoryFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Fonction');
                            $depart = $repositoryFonction->findOneBy(array('libelleFonction' => $fonction));
                            $id_fonction = 0;
                            if ($depart != null) {
                                $id_fonction = $depart->getId();
                            } else {
                                $fonct = new Fonction();
                                $fonct->setLibelleFonction($fonction);
                                $em->persist($fonct);
                                $em->flush();
                                $id_fonction = $fonct->getId();
                            }
                            $repositoryVocabulaireFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireFonction');
                            $vocabulaireFonction = $repositoryVocabulaireFonction->findOneBy(array('fonction' => $id_fonction, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireFonction == null) {

                                $vocabulaireFonction = new VocabulaireFonction();
                                $repositoryFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Fonction');
                                $fonct = $repositoryFonction->find($id_fonction);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $fonct != null) {
                                    $vocabulaireFonction->setFonction($fonct);
                                    $vocabulaireFonction->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireFonction);
                                    $em->flush();
                                }


                            }

                        }
                        if ($environnement_usage != "") {
                            //verif environnement_usage
                            $repositoryEnvironnementUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:EnvironnementUsage');
                            $depart = $repositoryEnvironnementUsage->findOneBy(array('libelleEnvironnementUsage' => $environnement_usage));
                            $id_environnement_usage = 0;
                            if ($depart != null) {
                                $id_environnement_usage = $depart->getId();
                            } else {
                                $envir_usage = new EnvironnementUsage();
                                $envir_usage->setLibelleEnvironnementUsage($environnement_usage);
                                $em->persist($envir_usage);
                                $em->flush();
                                $id_environnement_usage = $envir_usage->getId();
                            }
                            $repositoryVocabulaireEnvironnementUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireEnvirUsage');
                            $vocabulaireEnvironnementUsage = $repositoryVocabulaireEnvironnementUsage->findOneBy(array('environnement_usage' => $id_environnement_usage, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireEnvironnementUsage == null) {

                                $vocabulaireEnvironnementUsage = new VocabulaireEnvirUsage();
                                $repositoryEnvironnementUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:EnvironnementUsage');
                                $envir_usage = $repositoryEnvironnementUsage->find($id_environnement_usage);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $envir_usage != null) {
                                    $vocabulaireEnvironnementUsage->setEnvironnementUsage($envir_usage);
                                    $vocabulaireEnvironnementUsage->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireEnvironnementUsage);
                                    $em->flush();
                                }


                            }

                        }

                        if ($id_traducteur != "" && $id_traducteur != 0) {
                            $vocabulaireTraducteur = new VocabulaireTraducteur();
                            $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
                            $trad = $repositoryTraducteur->find($id_traducteur);

                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                            if ($vocabulaire != null && $trad != null) {
                                $vocabulaireTraducteur->setTraducteur($trad);
                                $vocabulaireTraducteur->setVocabulaire($vocabulaire);
                                $em->persist($vocabulaireTraducteur);
                                $em->flush();
                            }
                        }

                        if ($prototype != "") {
                            //verif prototype
                            $repositoryPrototype = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Prototype');
                            $proto = $repositoryPrototype->findOneBy(array('libellePrototype' => $prototype));
                            $id_prototype = 0;
                            if ($proto != null) {
                                $id_prototype = $proto->getId();
                            } else {
                                $proto = new Prototype();
                                $proto->setLibellePrototype($prototype);
                                $em->persist($proto);
                                $em->flush();
                                $id_prototype = $proto->getId();

                                $date_today = date("Y-m-d");
                            }


                            $repositoryPrototype = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Prototype');
                            $proto_obj = $repositoryPrototype->find($id_prototype);

                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                            if ($vocabulaire != null && $proto_obj != null) {
                                $vocabulairePrototype = new VocabulairePrototype();
                                $vocabulairePrototype->setPrototype($proto_obj);
                                $vocabulairePrototype->setVocabulaire($vocabulaire);
                                $em->persist($vocabulairePrototype);
                                $em->flush();

                            }
                            $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
                            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                            $societe = $repositorySociete->find($id_societe);

                            $repositoryFormatEdition = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:FormatEdition');
                            $formatEdition = $repositoryFormatEdition->find(3);
                            $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
                            $trad = $repositoryTraducteur->find($id_traducteur);
                            $proto_access = $repositoryPrototypeAccess->findOneBy(array('type' => $prototype, 'societe' => $societe));

                            
                            $id_prot = 0;
                            if ($proto_access != null) {
                                $id_prot = $proto_access->getId();
                            } else if ($societe != null && $formatEdition != null && $trad != null) {

                                $date_today = date("Y-m-d H:i:s");
                                $proto_access = new PrototypeAccess();
                                $proto_access->setNumero(0);
                                $proto_access->setType($prototype);
                                $proto_access->setDate(new \DateTime($date_today));
                                $proto_access->setNbPage(0);
                                $proto_access->setStatut('');
                                $proto_access->setTraducteur($trad);
                                $proto_access->setMisAJour('');
                                $proto_access->setPhase('');
                                $proto_access->setPhaseEtat('');
                                $proto_access->setPrioriteType('');
                                $proto_access->setFormatEdition($formatEdition);
                                $proto_access->setSociete($societe);

                                $em->persist($proto_access);
                                $em->flush();
                                $id_prot = $proto_access->getId();

                            }

                            ////ajout dans la table lexique pour gerer les rangs des LE
                           if ($id_societe != 653) {
                                 $rang_lexique = $this->recupRangLE($id_societe, $id_theme, $id_prot);
                                 $rangLE = $this->getMaxRangLE($id_prot);
                                 $rangLE = $rangLE + 1;
                                 if ($rang_lexique == 0 || $rang_lexique == "") {

                                     $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
                                     $prototypeAccess = $repositoryPrototypeAccess->find($id_prot);

                                     $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
                                     $theme = $repositoryTheme->find($id_theme);

                                     $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                                     $societe = $repositorySociete->find($id_societe);

                                     if ($prototypeAccess != null && $theme != null && $societe != null) {
                                         $lexique = new Lexique();
                                         $lexique->setRang($rangLE);
                                         $lexique->setSociete($societe);
                                         $lexique->setPrototypeAccess($prototypeAccess);
                                         $lexique->setTheme($theme);
                                         $em->persist($lexique);
                                         $em->flush();
                                     }
                                 }
                             }

                            $prototype_access =  $repositoryPrototypeAccess->find($id_prot);
                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                            if($prototype_access  != null && $vocabulaire != null)
                            {
                                $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');
                                $vocabulairePrototypeAccess = $repositoryVocabulairePrototypeAccess->findOneBy(array('prototypeAccess' => $prototype_access,'vocabulaire' => $vocabulaire));
                                if($vocabulairePrototypeAccess == null && $vocabulaire != null && $prototype_access != null){
                                    $vocabulairePrototypeAccess = new VocabulairePrototypeAccess();
                                    $vocabulairePrototypeAccess->setVocabulaire($vocabulaire);
                                    $vocabulairePrototypeAccess->setPrototypeAccess($prototype_access);
                                    $em->persist($vocabulairePrototypeAccess);
                                    $em->flush();
                                }
                            }
                        }

                        $vocabulaireSociete = new VocabulaireSociete();
                        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                        $societe = $repositorySociete->find($id_societe);

                        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                        $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                        if ($vocabulaire != null && $societe != null) {
                            $vocabulaireSociete->setSociete($societe);
                            $vocabulaireSociete->setVocabulaire($vocabulaire);
                            $em->persist($vocabulaireSociete);
                            $em->flush();
                        }

                        if ($suffixe != "") {
                            //verif suffixe
                            //$suffixe_verif = strtolower($suffixe);
                            $repositorySuffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe');
                            $suffixe_obj = $repositorySuffixe->findOneBy(array('libelleSuffixe' => $suffixe, 'millesime' => $millesime));

                            if ($suffixe_obj != null) {
                                $id_suffixe = $suffixe_obj->getId();
                            } else {
                                $suffixe_obj = new Suffixe();
                                $suffixe_obj->setLibelleSuffixe($suffixe);
                                $suffixe_obj->setMillesime($millesime);
                                $em->persist($suffixe_obj);
                                $em->flush();
                                 $id_suffixe = $suffixe_obj->getId();
                            }
                            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                            $societe = $repositorySociete->find($id_societe);
                            $suffixe_obj = $repositorySuffixe->find($id_suffixe);
                            $repositorySuffixeSociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:SuffixeSociete');
                            if ($suffixe_obj != null && $societe) {
                                $suffixeSociete = $repositorySuffixeSociete->findOneBy(array('suffixe' => $suffixe_obj, 'societe' => $societe));
                                if ($suffixeSociete != null) {
                                    $suffixeSociete = new SuffixeSociete();
                                    $suffixeSociete->setSociete($societe);
                                    $suffixeSociete->setSuffixe($suffixe_obj);
                                }
                            }


                        }

                        //phrase source non vide
                        if ($index_phrase_source != null && $index_phrase_source != 0) {
                            $phrase_source = $this->quote($tab[$index_phrase_source]);
                            //verif phrase source
                            $repositoryPhraseSource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PhraseSource');
                            $phrase_source_obj = $repositoryPhraseSource->findOneBy(array('libellePhraseSource' => $phrase_source));

                            if ($phrase_source_obj != 0) {
                                $id_phrase_source = $phrase_source_obj->getId();
                            } else {
                                $phrase_source_obj = new PhraseSource();
                                $phrase_source_obj->setLibellePhraseSource($phrase_source);
                                $em->persist($phrase_source_obj);
                                $em->flush();
                            }
                            $phrase_source_obj = $repositoryPhraseSource->find($id_phrase_source);
                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                            if ($phrase_source_obj != null && $vocabulaire != null) {
                                $repositoryVocabulairePhraseSource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePhraseSource');
                                $vocabulairePhraseSource = $repositoryVocabulairePhraseSource->findOneBy(array('phrase_source' => $phrase_source_obj, 'vocabulaire' => $vocabulaire));
                                if ($vocabulairePhraseSource != null) {
                                    $vocabulairePhraseSource = new VocabulairePhraseSource();
                                    $vocabulairePhraseSource->setVocabulaire($vocabulaire);
                                    $vocabulairePhraseSource->setPhraseSource($phrase_source_obj);
                                    $em->persist($vocabulairePhraseSource);
                                    $em->flush();
                                }
                            }


                        }
                    }
                }

                if ($id_societe == 653) {
                    return $this->redirect($this->generateUrl('consulter_prototype'));
                } else {

                    return $this->redirectToRoute('modif_prototype', array('id' => $id_prot));
                }

            }
        } else {
            echo "<SCRIPT language=javascript> alert('Format de fichier non reconnu'); window.location = 'mise_a_jour_vocab';</SCRIPT>";
        }
        return $this->render('FormationVocabulaireBundle:Default:uploadExcel.html.twig', array());
		
    }




    /**
     * @Route("/import_prototype_LE_update", name="import_prototype_LE_update")
     */
    public function import_prototype_LE_updateAction(Request $request)
    {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');

        $id_societe = intval($request->get('societe'));
        $id_language = intval($request->get('langue'));
        $id_traducteur = intval($request->get('id_traducteur'));
        $id_prot = intval($request->get('id_prot'));

        $date = date("Y-m-d H:i:s");

        $colonneDroite = $request->get('colonneDroite');
        $colonneGauche = $request->get('colonneGauche');

        $colonneDroiteValue = explode(",", $colonneDroite);
        $colonneGaucheValue = explode(",", $colonneGauche);
        $index_theme = 0;
        $index_contexte_usage = 0;
        $index_langue_origine1 = 0;
        $index_langue_traduction1 = 0;
        $index_source_type = 0;
        $index_nom_stagiaire = 0;
        $index_nom_doc = 0;
        $index_lien = 0;
        $index_rang = 0;
        $index_secteur = 0;
        $index_departement = 0;
        $index_theme_eng = 0;
        $index_phrase_source = 0;
        $index_fonction = 0;
        $index_prototype = 0;
        $index_suffixe = 0;
        $index_millesime = 0;

        for ($index = 1; $index < count($colonneDroiteValue); $index++) {
            if ($colonneDroiteValue[$index] == 4) {
                $index_theme = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 5) {
                $index_contexte_usage = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 6) {
                $index_langue_origine1 = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 7) {
                $index_langue_traduction1 = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 8) {
                $index_source_type = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 9) {
                $index_nom_stagiaire = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 10) {
                $index_nom_doc = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 11) {
                $index_lien = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 12) {
                $index_rang = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 13) {
                $index_secteur = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 14) {
                $index_departement = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 15) {
                $index_theme_eng = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 16) {
                $index_phrase_source = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 17) {
                $index_fonction = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 18) {
                $index_prototype = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 19) {
                $index_suffixe = $colonneGaucheValue[$index];
            } else if ($colonneDroiteValue[$index] == 20) {
                $index_millesime = $colonneGaucheValue[$index];
            }
        }

        $fileType = pathinfo($_FILES['userfile2']['name'], PATHINFO_EXTENSION);
        //$uploadDir = 'C:/wamp/www' . $request->getBasePath() . '/uploads/'; # serveur dev
        $uploadDir = '/var/www/html' . $request->getBasePath() . '/uploads/'; # serveur prod
        $file = $request->files->get('userfile2');
        $fileName = $file->getClientOriginalName();
        $file->move($uploadDir, $fileName);

        if ($fileType == "xls" || $fileType == "xlsx" || $fileType == "ods") {

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($uploadDir . $fileName);
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                for ($row = 2; $row <= $highestRow; ++$row) {
                    $tab = array();
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getValue();
                        $tab[$col] = $val;
                    }
                    $theme = "";
                    $environnement_usage = "";
                    $langue_origine1 = "";
                    $langue_traduction1 = "";
                    $source_type = "";
                    $nom_stagiaire = "";
                    $nom_doc = "";
                    $rang = "";
                    $lien = "";
                    $secteur = "";
                    $departement = "";
                    $prototype = "";
                    $theme_eng = "";
                    $fonction = "";
                    $suffixe = "";
                    $millesime = "";

                    if ($index_theme != 0) {
                        $theme = $this->quote($tab[$index_theme - 1]);
                        $theme = trim($theme);

                    }
                    if ($index_contexte_usage != 0) {
                        $environnement_usage = $this->quote($tab[$index_contexte_usage - 1]);
                        $environnement_usage = trim($environnement_usage);
                    }
                    if ($index_langue_origine1 != 0) {
                        $langue_origine1 = $this->quote($tab[$index_langue_origine1 - 1]);
                        $langue_origine1 = trim($langue_origine1);
                    }
                    if ($index_langue_traduction1 != 0) {
                        $langue_traduction1 = $this->quote($tab[$index_langue_traduction1 - 1]);
                        $langue_traduction1 = trim($langue_traduction1);
                    }
                    if ($index_source_type != 0) {
                        $source_type = $this->quote($tab[$index_source_type - 1]);
                        $source_type = trim($source_type);
                    }
                    if ($index_nom_stagiaire != 0) {
                        $nom_stagiaire = $this->quote($tab[$index_nom_stagiaire - 1]);
                        $nom_stagiaire = trim($nom_stagiaire);
                    }
                    if ($index_nom_doc != 0) {
                        $nom_doc = $this->quote($tab[$index_nom_doc - 1]);
                        $nom_doc = trim($nom_doc);
                    }
                    if ($index_rang != 0) {
                        $rang = $this->quote($tab[$index_rang - 1]);
                        $rang = trim($rang);
                    }
                    if ($index_lien != 0) {
                        $lien = $this->quote($tab[$index_lien - 1]);
                        $lien = trim($lien);
                    }
                    if ($index_secteur != 0) {
                        $secteur = $this->quote($tab[$index_secteur - 1]);
                        $secteur = trim($secteur);
                    }
                    if ($index_departement != 0) {
                        $departement = $this->quote($tab[$index_departement - 1]);
                        $departement = trim($departement);
                    }
                    if ($index_theme_eng != 0) {
                        $theme_eng = $this->quote($tab[$index_theme_eng - 1]);
                        $theme_eng = trim($theme_eng);
                    }
                    if ($index_fonction != 0) {
                        $fonction = $this->quote($tab[$index_fonction - 1]);
                        $fonction = trim($fonction);
                    }
                    if ($index_prototype != 0) {
                        $prototype = $this->quote($tab[$index_prototype - 1]);
                        $prototype = trim($prototype);
                    }
                    if ($index_phrase_source != 0) {
                        $phrase_source = $this->quote($tab[$index_phrase_source - 1]);
                        $phrase_source = trim($phrase_source);
                    }
                    if ($index_suffixe != 0) {
                        $suffixe = $this->quote($tab[$index_suffixe - 1]);
                        $suffixe = trim($suffixe);
                    }
                    if ($index_millesime != 0) {
                        $millesime = $this->quote($tab[$index_millesime - 1]);
                        $millesime = trim($millesime);
                    }
                    $id_vocabulaire = 0;
                    $id_source = 0;
                    $em = $this->getDoctrine()->getManager();
                    if ($langue_origine1 != "" && $langue_traduction1 != "") {

                        if ($source_type != "" || $nom_stagiaire != "" || $nom_doc != "" || $lien != "") {
                            //insertion source
                            $source = new Source();
                            $source->setSourceType($source_type);
                            $source->setSourceNomStagiaire($nom_stagiaire);
                            $source->setLienNomDoc($nom_doc);
                            $source->setLien($lien);

                            $em->persist($source);
                            $em->flush();

                            $id_source = $source->getId();

                        }

                        //verification existence vocabulaire
                        $langue_origine_avec_maj = $this->trim_ucfirst_strtolower_utf8($langue_origine1);

                        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                        $vocabulaire = $repositoryVocabulaire->findOneBy(array('langueOrigine' => $langue_origine_avec_maj, 'langueTraduction' => $langue_traduction1, 'language' => $id_language));

                        if ($vocabulaire != null) {

                            $vocabulaire->setDateModification(new \DateTime($date));
                            $em->merge($vocabulaire);
                            $em->flush();
                            $id_vocabulaire = $vocabulaire->getId();


                        } else {
                            $langue_origine_sans_modif = "";
                            $alphabet_min = range('a', 'z');
                            $alphabet_maj = range('A', 'Z');
                            $first_lettre = $langue_origine1[0];
                            if (!in_array($first_lettre, $alphabet_min) && !in_array($first_lettre, $alphabet_maj)) {
                                $langue_origine_sans_modif = $langue_origine1;
                            }
                            $nb_caract = strlen($langue_origine1);
                            $fixeNbreCaractreLigne = 47;

                            $langue_origine_clean = $this->cleanLangueOrigine($langue_origine1);
                            $nb_ligne = ($nb_caract / $fixeNbreCaractreLigne);
                            $partieEntiere = (int)($nb_caract / $fixeNbreCaractreLigne);
                            if ($partieEntiere < $nb_ligne) {
                                $partieEntiere = $partieEntiere + 1;
                            }

                            //insertion vocabulaire
                            $repositoryLanguage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Language');
                            $language = $repositoryLanguage->find($id_language);

                            $repositorySource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Source');
                            $source = $repositorySource->find($id_source);

                            if ($source != null && $language != null) {

                                $vocab = new Vocabulaire();
                                $vocab->setDateCreation(new \DateTime($date));
                                $vocab->setLangueOrigine($langue_origine_avec_maj);
                                $vocab->setLangueOrigineSansModif($langue_origine_sans_modif);
                                $vocab->setLangueTraduction($langue_traduction1);
                                $vocab->setRang($rang);
                                $vocab->setIsAffiche(1);
                                $vocab->setNbreCaractLo($nb_caract);
                                $vocab->setLanguage($language);
                                $vocab->setSource($source);
                                $vocab->setNbreLigneLo(0);
                                $em->persist($vocab);
                                $em->flush();
                                $id_vocabulaire = $vocab->getId();
                            }


                        }

                        if ($secteur != "") {
                            //verif secteur d'activité
                            $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
                            $sect = $repositorySecteur->findOneBy(array('libelleSecteur' => $secteur));

                            if ($sect != null) {
                                $id_secteur = $sect->getId();
                            } else {
                                $sect = new Secteur();
                                $sect->setLibelleSecteur($secteur);
                                $em->persist($sect);
                                $em->flush();
                                $id_secteur = $sect->getId();

                            }

                            $repositoryVocabulaireSecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSecteur');
                            $vocabulaireSecteur = $repositoryVocabulaireSecteur->findOneBy(array('secteur' => $id_secteur, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireSecteur == null) {

                                $vocabulaireSecteur = new VocabulaireSecteur();
                                $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
                                $sec = $repositorySecteur->find($id_secteur);
                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $sec != null) {
                                    $vocabulaireSecteur->setSecteur($sec);
                                    $vocabulaireSecteur->setVocabulaire($vocabulaire);
                                    $em->merge($vocabulaireSecteur);
                                    $em->flush();
                                }

                            }
                        }

                        if ($departement != "") {
                            //verif departement
                            $repositoryDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Departement');
                            $depart = $repositoryDepartement->findOneBy(array('libelleDepartement' => $departement));
                            $id_departement = 0;
                            if ($depart != null) {
                                $id_departement = $depart->getId();
                            } else {
                                $depart = new Departement();
                                $depart->setLibelleDepartement($departement);
                                $em->persist($depart);
                                $em->flush();
                                $id_departement = $depart->getId();
                            }
                            $repositoryVocabulaireDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireDepartement');
                            $vocabulaireDepartement = $repositoryVocabulaireDepartement->findOneBy(array('departement' => $id_departement, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireDepartement == null) {

                                $vocabulaireDepartement = new VocabulaireDepartement();
                                $repositoryDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Departement');
                                $depart = $repositoryDepartement->find($id_departement);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $depart != null) {
                                    $vocabulaireDepartement->setDepartement($depart);
                                    $vocabulaireDepartement->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireDepartement);
                                    $em->flush();
                                }


                            }

                        }

                        $id_theme = 0;

                        if ($theme != "") {
                            //verif theme
                            $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
                            $them = $repositoryTheme->findOneBy(array('libelleTheme' => $theme));
                            $id_theme = 0;
                            if ($depart != null) {
                                $id_theme = $them->getId();
                            } else {
                                $them = new Theme();
                                $them->setLibelleTheme($theme);
                                $em->persist($them);
                                $em->flush();
                                $id_theme = $them->getId();
                            }
                            $repositoryVocabulaireTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireTheme');
                            $vocabulaireTheme = $repositoryVocabulaireTheme->findOneBy(array('theme' => $id_theme, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireTheme == null) {

                                $vocabulaireTheme = new VocabulaireTheme();
                                $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
                                $them = $repositoryTheme->find($id_theme);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                                if ($vocabulaire != null && $them != null) {
                                    $vocabulaireTheme->setTheme($them);
                                    $vocabulaireTheme->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireTheme);
                                    $em->flush();
                                }


                            }

                        }
                        if ($fonction != "") {
                            //verif fonction
                            $repositoryFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Fonction');
                            $depart = $repositoryFonction->findOneBy(array('libelleFonction' => $fonction));
                            $id_fonction = 0;
                            if ($depart != null) {
                                $id_fonction = $depart->getId();
                            } else {
                                $fonct = new Fonction();
                                $fonct->setLibelleFonction($fonction);
                                $em->persist($fonct);
                                $em->flush();
                                $id_fonction = $fonct->getId();
                            }
                            $repositoryVocabulaireFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireFonction');
                            $vocabulaireFonction = $repositoryVocabulaireFonction->findOneBy(array('fonction' => $id_fonction, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireFonction == null) {

                                $vocabulaireFonction = new VocabulaireFonction();
                                $repositoryFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Fonction');
                                $fonct = $repositoryFonction->find($id_fonction);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $fonct != null) {
                                    $vocabulaireFonction->setFonction($fonct);
                                    $vocabulaireFonction->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireFonction);
                                    $em->flush();
                                }


                            }

                        }
                        if ($environnement_usage != "") {
                            //verif environnement_usage
                            $repositoryEnvironnementUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:EnvironnementUsage');
                            $depart = $repositoryEnvironnementUsage->findOneBy(array('libelleEnvironnementUsage' => $environnement_usage));
                            $id_environnement_usage = 0;
                            if ($depart != null) {
                                $id_environnement_usage = $depart->getId();
                            } else {
                                $envir_usage = new EnvironnementUsage();
                                $envir_usage->setLibelleEnvironnementUsage($environnement_usage);
                                $em->persist($envir_usage);
                                $em->flush();
                                $id_environnement_usage = $envir_usage->getId();
                            }
                            $repositoryVocabulaireEnvironnementUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireEnvirUsage');
                            $vocabulaireEnvironnementUsage = $repositoryVocabulaireEnvironnementUsage->findOneBy(array('environnement_usage' => $id_environnement_usage, 'vocabulaire' => $id_vocabulaire));
                            if ($vocabulaireEnvironnementUsage == null) {

                                $vocabulaireEnvironnementUsage = new VocabulaireEnvirUsage();
                                $repositoryEnvironnementUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:EnvironnementUsage');
                                $envir_usage = $repositoryEnvironnementUsage->find($id_environnement_usage);

                                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                                $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                                if ($vocabulaire != null && $envir_usage != null) {
                                    $vocabulaireEnvironnementUsage->setEnvironnementUsage($envir_usage);
                                    $vocabulaireEnvironnementUsage->setVocabulaire($vocabulaire);
                                    $em->persist($vocabulaireEnvironnementUsage);
                                    $em->flush();
                                }


                            }

                        }

                        if ($id_traducteur != "" && $id_traducteur != 0) {
                            $vocabulaireTraducteur = new VocabulaireTraducteur();
                            $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
                            $trad = $repositoryTraducteur->find($id_traducteur);

                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                            if ($vocabulaire != null && $trad != null) {
                                $vocabulaireTraducteur->setTraducteur($trad);
                                $vocabulaireTraducteur->setVocabulaire($vocabulaire);
                                $em->persist($vocabulaireTraducteur);
                                $em->flush();
                            }
                        }

                        if ($prototype != "") {
                            //verif prototype
                            $repositoryPrototype = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Prototype');
                            $proto = $repositoryPrototype->findOneBy(array('libellePrototype' => $prototype));

                            if ($proto != null) {
                                $id_prototype = $proto->getId();
                            } else {
                                $proto = new Prototype();
                                $proto->setLibellePrototype($prototype);
                                $em->persist($proto);
                                $em->flush();
                                $id_prototype = $proto->getId();

                                $date_today = date("Y-m-d");
                            }


                            $repositoryPrototype = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Prototype');
                            $proto_obj = $repositoryPrototype->find($id_prototype);

                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                            if ($vocabulaire != null && $proto_obj != null) {
                                $vocabulairePrototype = new VocabulairePrototype();
                                $vocabulairePrototype->setPrototype($proto_obj);
                                $vocabulairePrototype->setVocabulaire($vocabulaire);
                                $em->persist($vocabulairePrototype);
                                $em->flush();

                            }
                            $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
                            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                            $societe = $repositorySociete->find($id_societe);

                            $repositoryFormatEdition = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:FormatEdition');
                            $formatEdition = $repositoryFormatEdition->find(3);
                            $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
                            $trad = $repositoryTraducteur->find($id_traducteur);
                            $proto_access = $repositoryPrototypeAccess->findOneBy(array('type' => $prototype, 'societe' => $societe));
                            if ($proto_access != null) {
                                $id_prot = $proto_access->getId();
                            } else if ($societe != null && $formatEdition != null && $trad != null) {

                                $date_today = date("Y-m-d H:i:s");
                                $proto_access = new PrototypeAccess();
                                $proto_access->setNumero(0);
                                $proto_access->setType($prototype);
                                $proto_access->setDate(new \DateTime($date_today));
                                $proto_access->setNbPage(0);
                                $proto_access->setStatut('');
                                $proto_access->setTraducteur($trad);
                                $proto_access->setMisAJour('');
                                $proto_access->setPhase('');
                                $proto_access->setPhaseEtat('');
                                $proto_access->setPrioriteType('');
                                $proto_access->setFormatEdition($formatEdition);
                                $proto_access->setSociete($societe);

                                $em->persist($proto_access);
                                $em->flush();
                                $id_prot = $proto_access->getId();

                            }

                            ////ajout dans la table lexique pour gerer les rangs des LE
                            if ($id_societe != 653) {
                                $rang_lexique = $this->recupRangLE($id_societe, $id_theme, $id_prot);
                                $rangLE = $this->getMaxRangLE($id_prot);
                                $rangLE = $rangLE + 1;
                                if ($rang_lexique == 0 || $rang_lexique == "") {

                                    $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
                                    $prototypeAccess = $repositoryPrototypeAccess->find($id_prot);

                                    $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
                                    $theme = $repositoryTheme->find($id_theme);

                                    $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                                    $societe = $repositorySociete->find($id_societe);

                                    if ($prototypeAccess != null && $theme != null && $societe != null) {
                                        $lexique = new Lexique();
                                        $lexique->setRang($rangLE);
                                        $lexique->setSociete($societe);
                                        $lexique->setPrototypeAccess($prototypeAccess);
                                        $lexique->setTheme($theme);
                                        $em->persist($lexique);
                                        $em->flush();
                                    }
                                }
                            }

                            $prototype_access =  $repositoryPrototypeAccess->find($id_prot);
                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                            if($prototype_access  != null && $vocabulaire != null)
                            {
                                $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');
                                $vocabulairePrototypeAccess = $repositoryVocabulairePrototypeAccess->findOneBy(array('prototypeAccess' => $prototype_access,'vocabulaire' => $vocabulaire));
                                if($vocabulairePrototypeAccess == null && $vocabulaire != null && $prototype_access != null){
                                    $vocabulairePrototypeAccess = new VocabulairePrototypeAccess();
                                    $vocabulairePrototypeAccess->setVocabulaire($vocabulaire);
                                    $vocabulairePrototypeAccess->setPrototypeAccess($prototype_access);
                                    $em->persist($vocabulairePrototypeAccess);
                                    $em->flush();
                                }
                            }
                        }

                        $vocabulaireSociete = new VocabulaireSociete();
                        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                        $societe = $repositorySociete->find($id_societe);

                        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                        $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);

                        if ($vocabulaire != null && $societe != null) {
                            $vocabulaireSociete->setSociete($societe);
                            $vocabulaireSociete->setVocabulaire($vocabulaire);
                            $em->persist($vocabulaireSociete);
                            $em->flush();
                        }

                        if ($suffixe != "") {
                            //verif suffixe
                            //$suffixe_verif = strtolower($suffixe);
                            $repositorySuffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe');
                            $suffixe_obj = $repositorySuffixe->findOneBy(array('libelle_suffixe' => $suffixe, 'millesime' => $millesime));

                            if ($suffixe_obj != null) {
                                $id_suffixe = $suffixe_obj->getId();
                            } else {
                                $suffixe_obj = new Suffixe();
                                $suffixe_obj->setLibelleSuffixe($suffixe);
                                $suffixe_obj->setMillesime($millesime);
                                $em->persist($suffixe_obj);
                                $em->flush();
                            }
                            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                            $societe = $repositorySociete->find($id_societe);
                            $suffixe_obj = $repositorySuffixe->find($id_suffixe);
                            $repositorySuffixeSociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:SuffixeSociete');
                            if ($suffixe_obj != null && $societe) {
                                $suffixeSociete = $repositorySuffixeSociete->findOneBy(array('suffixe' => $suffixe_obj, 'societe' => $societe));
                                if ($suffixeSociete != null) {
                                    $suffixeSociete = new SuffixeSociete();
                                    $suffixeSociete->setSociete($societe);
                                    $suffixeSociete->setSuffixe($suffixe_obj);
                                }
                            }


                        }

                        //phrase source non vide
                        if ($index_phrase_source != null && $index_phrase_source != 0) {
                            $phrase_source = $this->quote($tab[$index_phrase_source]);
                            //verif phrase source
                            $repositoryPhraseSource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PhraseSource');
                            $phrase_source_obj = $repositoryPhraseSource->findOneBy(array('libellePhraseSource' => $phrase_source));

                            if ($phrase_source_obj != 0) {
                                $id_phrase_source = $phrase_source_obj->getId();
                            } else {
                                $phrase_source_obj = new PhraseSource();
                                $phrase_source_obj->setLibellePhraseSource($phrase_source);
                                $em->persist($phrase_source_obj);
                                $em->flush();
                            }
                            $phrase_source_obj = $repositoryPhraseSource->find($id_phrase_source);
                            $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                            $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                            if ($phrase_source_obj != null && $vocabulaire != null) {
                                $repositoryVocabulairePhraseSource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePhraseSource');
                                $vocabulairePhraseSource = $repositoryVocabulairePhraseSource->findOneBy(array('phrase_source' => $phrase_source_obj, 'vocabulaire' => $vocabulaire));
                                if ($vocabulairePhraseSource != null) {
                                    $vocabulairePhraseSource = new VocabulairePhraseSource();
                                    $vocabulairePhraseSource->setVocabulaire($vocabulaire);
                                    $vocabulairePhraseSource->setPhraseSource($phrase_source_obj);
                                    $em->persist($vocabulairePhraseSource);
                                    $em->flush();
                                }
                            }


                        }
                    }
                }

                if ($id_societe == 653) {
                    return $this->redirect($this->generateUrl('consulter_prototype'));
                } else {

                    return $this->redirectToRoute('modif_prototype', array('id' => $id_prot));
                }

            }
        } else {
            echo "<SCRIPT language=javascript> alert('Format de fichier non reconnu'); window.location = 'mise_a_jour_vocab';</SCRIPT>";
        }
        return $this->render('FormationVocabulaireBundle:Default:uploadExcel.html.twig', array());

    }



    private function trim_ucfirst_strtolower_utf8($stri)
    { //Pour le champs adresse et adresse complémentaire
        //Met le premier caractère de la chaîne en majuscule et le reste de la chaîne en minuscule (accentué ou non)
        $stri = trim(($stri), " "); //On supprime les espases en début et fin de chaînes ..
        $stringMaj = strtoupper($stri);
        $stringMaj = str_replace(
            array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
            array('E', 'E', 'E', 'E', 'A', 'A', 'I', 'I', 'O', 'U', 'U'),
            $stringMaj
        );
        //$premierLettre =  substr($stringMaj[0],0,1);
        $premierLettre = mb_strtoupper(mb_substr($stringMaj[0], 0, 1, 'utf-8'), 'utf-8');
        $ucfirst = mb_strtoupper(mb_substr($stri, 0, 1, 'utf-8'), 'utf-8');
        $strtolower = mb_strtolower(mb_substr($stri, 1, 1000, 'utf-8'), 'utf-8');
        $stri = $premierLettre . $strtolower;
        //$stri = $ucfirst.$strtolower;
        return $stri;

    }

    private function cleanLangueOrigine($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        //return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/[^A-Za-z\-]/', '', $string); // Removes special chars.
    }

    private function recupRangLE($id_societe, $id_theme, $id_prototype_access)
    {
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $prototypeAccess = $repositoryPrototypeAccess->find($id_prototype_access);
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societe = $repositorySociete->find($id_societe);
        $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
        $theme = $repositoryTheme->find($id_theme);
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        $lexique = $repositoryLexique->findOneBy(array('theme' => $theme, 'prototypeAccess' => $prototypeAccess, 'societe' => $societe));
        if ($lexique != null) $lexique->getRang();
        return 0;
    }

    private function getMaxRangLE($id_prototype_access)
    {
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $prototypeAccess = $repositoryPrototypeAccess->find($id_prototype_access);
        $lexiques = array();
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        if($prototypeAccess != null)$lexiques = $repositoryLexique->findBy(array('prototypeAccess'=> $prototypeAccess ));

        $max = 0;
        foreach ($lexiques as $lexique) {

           if($max < $lexique->getRang() ) $max = $lexique->getRang();
        }

        return $max;

    }



}
