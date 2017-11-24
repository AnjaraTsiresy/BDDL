<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\Source;
use Formation\VocabulaireBundle\Entity\Vocabulaire;
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
     * @Route("/uploadExcel", name="uploadExcel")
     */
    public function uploadExcelAction(Request $request)
    {

        $fileType = pathinfo($_FILES['userfile']['name'],PATHINFO_EXTENSION);
        $uploadDir = 'C:/wamp/www'.$request->getBasePath().'/uploads/'; #a modifier sous le serveur prod
        $file = $request->files->get('userfile');
        $fileName = $file->getClientOriginalName();
        $file->move($uploadDir,$fileName);

        if($fileType == "xls" || $fileType == "xlsx" || $fileType == "ods") {
            $repositoryListColumn = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:ListColumn');
            $listColumns = $repositoryListColumn->findAll();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($uploadDir . $fileName);
            foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                $worksheetTitle     = $worksheet->getTitle();
                echo $worksheetTitle ;
                $highestRow         = $worksheet->getHighestRow();
                $highestColumn      = $worksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                for ($row = 1; $row <= $highestRow; ++ $row) {
                    $tab = array();
                    echo "<input type='hidden' id='nbreCol' value='$highestColumnIndex'/>";
                    echo "<table id='tableResult'>";
                    for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                        if($row == 1){
                            $valCol = $col+1;
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $val = $cell->getValue();
                            $tab[$col] = $val;
                            echo '<tr><td>'.$val.'<input type="hidden" id="valueCol_'.$valCol.'" value="'.$valCol.'"/></td><td>';

                            echo '<select name="colonne'.$valCol.'" id="colonne'.$valCol.'">';
                            echo '<option value="0">Ne pas importer</option>';
                                foreach($listColumns as $listColumn){
                                    $nom_column = $this->texteUnicode($listColumn->getNomColumn());
                                    $selected = '';
                                    if ($nom_column==$val){
                                        $selected = 'selected=selected';
                                    }
                                    echo'<option  value="'.$listColumn->getId().'"'.$selected.'">"'.$nom_column.'"</option>';
                                }
                            echo '</select>';
                        }
                    }
                    echo '</table>';
                }
            }
        }
        return $this->render('FormationVocabulaireBundle:Default:uploadExcel.html.twig', array(

        ));
    }

    private function texteUnicode($texte){
        $tab2 = array("à","è","é","ê","ù","»","«","°","œ","oeil", "ç");
        $tab1 = array("&agrave;","&egrave;","&eacute;","&ecirc;","&ugrave;","&raquo;","&laquo;","&deg;","œ","œil", "&ccedil");
        $texte = str_replace($tab1,$tab2,$texte);

        return $texte;
    }

    private function cleanLangueOrigine($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        //return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/[^A-Za-z\-]/', '', $string); // Removes special chars.
    }

    private function trim_ucfirst_strtolower_utf8($stri) { //Pour le champs adresse et adresse complémentaire
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

    /**
     * @Route("/mise_a_jour_vocab_action", name="mise_a_jour_vocab_action")
     */
    public function mise_a_jour_vocabAction(Request $request)
    {

        $id_societe = intval($request->get('societe'));
        $id_language = intval($request->get('langue'));
        $id_traducteur = intval($request->get('id_traducteur'));

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

        for ($index = 1; $index < count($colonneDroiteValue); $index++){
            if($colonneDroiteValue[$index] == 4){
                $index_theme = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 5){
                $index_contexte_usage = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 6){
                $index_langue_origine1 = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 7){
                $index_langue_traduction1 = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 8){
                $index_source_type = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 9){
                $index_nom_stagiaire = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 10){
                $index_nom_doc = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 11){
                $index_lien = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 12){
                $index_rang = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 13){
                $index_secteur = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 14){
                $index_departement = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 15){
                $index_theme_eng = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 16){
                $index_phrase_source = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 17){
                $index_fonction = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 18){
                $index_prototype = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 19){
                $index_suffixe = $colonneGaucheValue[$index];
            }
            else if ($colonneDroiteValue[$index] == 20){
                $index_millesime = $colonneGaucheValue[$index];
            }
        }

        $fileType = pathinfo($_FILES['userfile2']['name'],PATHINFO_EXTENSION);
        $uploadDir = 'C:/wamp/www'.$request->getBasePath().'/uploads/'; #a modifier sous le serveur prod
        $file = $request->files->get('userfile2');
        $fileName = $file->getClientOriginalName();
        $file->move($uploadDir,$fileName);

        if($fileType == "xls" || $fileType == "xlsx" || $fileType == "ods") {

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
                    $suffixe = "";
                    $millesime = "";

                    if ($index_theme != 0) {
                        $theme = htmlspecialchars($tab[$index_theme - 1]);
                        $theme = trim($theme);
                    }
                    if ($index_contexte_usage != 0) {
                        $contexte_usage = htmlspecialchars($tab[$index_contexte_usage - 1]);
                        $contexte_usage = trim($contexte_usage);
                    }
                    if ($index_langue_origine1 != 0) {
                        $langue_origine1 = htmlspecialchars($tab[$index_langue_origine1 - 1]);
                        $langue_origine1 = trim($langue_origine1);
                    }
                    if ($index_langue_traduction1 != 0) {
                        $langue_traduction1 = htmlspecialchars($tab[$index_langue_traduction1 - 1]);
                        $langue_traduction1 = trim($langue_traduction1);
                    }
                    if ($index_source_type != 0) {
                        $source_type = htmlspecialchars($tab[$index_source_type - 1]);
                        $source_type = trim($source_type);
                    }
                    if ($index_nom_stagiaire != 0) {
                        $nom_stagiaire = htmlspecialchars($tab[$index_nom_stagiaire - 1]);
                        $nom_stagiaire = trim($nom_stagiaire);
                    }
                    if ($index_nom_doc != 0) {
                        $nom_doc = htmlspecialchars($tab[$index_nom_doc - 1]);
                        $nom_doc = trim($nom_doc);
                    }
                    if ($index_rang != 0) {
                        $rang = htmlspecialchars($tab[$index_rang - 1]);
                        $rang = trim($rang);
                    }
                    if ($index_lien != 0) {
                        $lien = htmlspecialchars($tab[$index_lien - 1]);
                        $lien = trim($lien);
                    }
                    if ($index_secteur != 0) {
                        $secteur = htmlspecialchars($tab[$index_secteur - 1]);
                        $secteur = trim($secteur);
                    }
                    if ($index_departement != 0) {
                        $departement = htmlspecialchars($tab[$index_departement - 1]);
                        $departement = trim($departement);
                    }
                    if ($index_theme_eng != 0) {
                        $theme_eng = htmlspecialchars($tab[$index_theme_eng - 1]);
                        $theme_eng = trim($theme_eng);
                    }
                    if ($index_fonction != 0) {
                        $fonction = htmlspecialchars($tab[$index_fonction - 1]);
                        $fonction = trim($fonction);
                    }
                    if ($index_prototype != 0) {
                        $prototype = htmlspecialchars($tab[$index_prototype - 1]);
                        $prototype = trim($prototype);
                    }
                    if ($index_phrase_source != 0) {
                        $phrase_source = htmlspecialchars($tab[$index_phrase_source - 1]);
                        $phrase_source = trim($phrase_source);
                    }
                    if ($index_suffixe != 0) {
                        $suffixe = htmlspecialchars($tab[$index_suffixe - 1]);
                        $suffixe = trim($suffixe);
                    }
                    if ($index_millesime != 0) {
                        $millesime = htmlspecialchars($tab[$index_millesime - 1]);
                        $millesime = trim($millesime);
                    }

                    if ($langue_origine1 != "" && $langue_traduction1 != "") {
                        $id_source = 0;
                        if ($source_type != "" || $nom_stagiaire != "" || $nom_doc != "" || $lien != "") {
                            //insertion source
                            $source = new Source();
                            $source->setSourceType($source_type);
                            $source->setSourceNomStagiaire($nom_stagiaire);
                            $source->setLienNomDoc($nom_doc);
                            $source->setLien($lien);
                            $em = $this->getDoctrine()->getManager();
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
                            if (!$language ) {
                                throw $this->createNotFoundException(
                                    'Aucun language trouvé pour cet id : '.$id_language
                                );
                            }

                            $repositorySource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Source');
                            $sourcce = $repositorySource->find($id_source);
                            if (!$source ) {
                                throw $this->createNotFoundException(
                                    'Aucun source trouvé pour cet id : '.$id_source
                                );
                            }

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
                            $em->merge($vocab);
                            $em->flush();
                            die();
                            $id_vocabulaire = $vocab->getId();
                        }

                       if($secteur != ""){
                            //verif secteur d'activité
                            $sql_secteur ="select * from secteur where libelle_secteur='$secteur' ";
                            $query_secteur= mysql_query($sql_secteur) or die(mysql_error());
                            $row_secteur = mysql_fetch_array($query_secteur);
                            if($row_secteur['id_secteur'] != 0){
                                $id_secteur = $row_secteur['id_secteur'];
                            }else{
                                $secteur_sql = "INSERT IGNORE INTO secteur VALUES ('', '$sect eur')";
                                mysql_query($secteur_sql);
                                $id_secteur = mysql_insert_id() ;
                            }
                            $sql_test_secteur ="select * from vocabulaire_secteur where id_secteur='$id_secteur' AND id_vocabulaire='$id_vocabulaire' ";
                            $query_test_secteur= mysql_query($sql_test_secteur) or die(mysql_error());
                            $row_test_secteur = mysql_fetch_array($query_test_secteur);
                            if($row_test_secteur['id_vocabulaire_secteur'] == 0 || $row_test_secteur['id_vocabulaire_secteur'] == ""){
                                $vocab_secteur_sql = "INSERT IGNORE INTO vocabulaire_secteur VALUES ('','$id_secteur','$id_vocabulaire')";
                                mysql_query($vocab_secteur_sql);
                            }
                        }

                        /* if($departement != ""){
                            //verif departement
                            $sql_departement ="select * from departement where libelle_departement='$departement' ";
                            $query_departement= mysql_query($sql_departement) or die(mysql_error());
                            $row_departement = mysql_fetch_array($query_departement);
                            if($row_departement['id_departement'] != 0){
                                $id_departement = $row_departement['id_departement'];
                            }else{
                                $departement_sql = "INSERT IGNORE INTO departement VALUES ('', '$departement')";
                                mysql_query($departement_sql);
                                $id_departement = mysql_insert_id() ;
                            }
                            $sql_test_dept ="select * from vocabulaire_departement where id_departement='$id_departement' AND id_vocabulaire='$id_vocabulaire' ";
                            $query_test_dept= mysql_query($sql_test_dept) or die(mysql_error());
                            $row_test_dept = mysql_fetch_array($query_test_dept);
                            if($row_test_dept['id_vocabulaire_departement'] == 0 || $row_test_dept['id_vocabulaire_departement'] == ""){
                                $vocab_dept_sql = "INSERT IGNORE INTO vocabulaire_departement VALUES ('','$id_departement','$id_vocabulaire')";
                                mysql_query($vocab_dept_sql);
                            }
                        }

                        $id_theme = 0;
                        if($theme != ""){
                            //verif theme
                            $sql_theme ="select * from theme where libelle_theme='$theme' ";
                            $query_theme= mysql_query($sql_theme) or die(mysql_error());
                            $row_theme = mysql_fetch_array($query_theme);
                            if($row_theme['id_theme'] != 0){
                                $id_theme = $row_theme['id_theme'];
                            }else{
                                $theme_sql = "INSERT IGNORE INTO theme VALUES ('', '$theme', '$theme_eng')";
                                mysql_query($theme_sql);
                                $id_theme = mysql_insert_id() ;
                            }
                            $sql_test_theme ="select * from vocabulaire_theme where id_theme='$id_theme' AND id_vocabulaire='$id_vocabulaire' ";
                            $query_test_theme= mysql_query($sql_test_theme) or die(mysql_error());
                            $row_test_theme = mysql_fetch_array($query_test_theme);
                            if($row_test_theme['id_vocabulaire_theme'] == 0 || $row_test_theme['id_vocabulaire_theme'] == ""){
                                $vocab_theme_sql = "INSERT IGNORE INTO vocabulaire_theme VALUES ('','$id_theme','$id_vocabulaire')";
                                mysql_query($vocab_theme_sql);
                            }
                        }

                        if($fonction != ""){
                            //verif fonction
                            $sql_fonction ="select * from fonction where libelle_fonction='$fonction' ";
                            $query_fonction= mysql_query($sql_fonction) or die(mysql_error());
                            $row_fonction = mysql_fetch_array($query_fonction);
                            if($row_fonction['id_fonction'] != 0){
                                $id_fonction = $row_fonction['id_fonction'];
                            }else{
                                $fonction_sql = "INSERT IGNORE INTO fonction VALUES ('', '$fonction')";
                                mysql_query($fonction_sql);
                                $id_fonction = mysql_insert_id() ;
                            }
                            $sql_test_fonction ="select * from vocabulaire_fonction where id_fonction='$id_fonction' AND id_vocabulaire='$id_vocabulaire' ";
                            $query_test_fonction= mysql_query($sql_test_fonction) or die(mysql_error());
                            $row_test_fonction = mysql_fetch_array($query_test_fonction);
                            if($row_test_fonction['id_vocabulaire_fonction'] == 0 || $row_test_fonction['id_vocabulaire_fonction'] == ""){
                                $vocab_fonction_sql = "INSERT IGNORE INTO vocabulaire_fonction VALUES ('','$id_fonction','$id_vocabulaire')";
                                mysql_query($vocab_fonction_sql);
                            }
                        }

                        if($contexte_usage != ""){
                            //verif env usage
                            $sql_env_usage ="select * from environnement_usage where libelle_env_usage='$contexte_usage' ";
                            $query_env_usage= mysql_query($sql_env_usage) or die(mysql_error());
                            $row_env_usage = mysql_fetch_array($query_env_usage);
                            if($row_env_usage['id_environnement_usage'] != 0){
                                $id_env_usage = $row_env_usage['id_environnement_usage'];
                            }else{
                                $env_usage_sql = "INSERT IGNORE INTO environnement_usage VALUES ('', '$contexte_usage')";
                                mysql_query($env_usage_sql);
                                $id_env_usage = mysql_insert_id() ;
                            }
                            $sql_test_envir_usage ="select * from vocabulaire_envir_usage where id_environnement_usage='$id_env_usage' AND id_vocabulaire='$id_vocabulaire' ";
                            $query_test_envir_usage= mysql_query($sql_test_envir_usage) or die(mysql_error());
                            $row_test_envir_usage = mysql_fetch_array($query_test_envir_usage);
                            if($row_test_envir_usage['id_vocabulaire_envir_usage'] == 0 || $row_test_envir_usage['id_vocabulaire_envir_usage'] == ""){
                                $vocab_envir_usage_sql = "INSERT IGNORE INTO vocabulaire_envir_usage VALUES ('','$id_env_usage','$id_vocabulaire')";
                                mysql_query($vocab_envir_usage_sql);
                            }
                        }

                        if($id_traducteur != "" && $id_traducteur != 0){
                            $sql_test_traducteur ="select * from vocabulaire_traducteur where id_traducteur='$id_traducteur' AND id_vocabulaire='$id_vocabulaire' ";
                            $query_test_traducteur= mysql_query($sql_test_traducteur) or die(mysql_error());
                            $row_test_traducteur = mysql_fetch_array($query_test_traducteur);
                            if($row_test_traducteur['id_vocabulaire_traducteur'] == 0 || $row_test_traducteur['id_vocabulaire_traducteur'] == ""){
                                $vocab_traducteur_sql = "INSERT IGNORE INTO vocabulaire_traducteur VALUES ('','$id_traducteur','$id_vocabulaire')";
                                mysql_query($vocab_traducteur_sql);
                            }
                        }

                        if($prototype != ""){
                            //verif prototype
                            $sql_prototype ="select * from prototype where libelle_prototype='$prototype' ";
                            $query_prototype= mysql_query($sql_prototype) or die(mysql_error());
                            $row_prototype = mysql_fetch_array($query_prototype);
                            if($row_prototype['id_prototype'] != 0){
                                $id_prototype = $row_prototype['id_prototype'];
                            }else{
                                $prototype_sql = "INSERT IGNORE INTO prototype VALUES ('', '$prototype')";
                                mysql_query($prototype_sql);
                                $id_prototype = mysql_insert_id() ;
                                $date_today = date("Y-m-d");
                            }
                            $sql_test_prototype ="select * from vocabulaire_prototype where id_prototype='$id_prototype' AND id_vocabulaire='$id_vocabulaire' ";
                            $query_test_prototype= mysql_query($sql_test_prototype) or die(mysql_error());
                            $row_test_prototype = mysql_fetch_array($query_test_prototype);
                            if($row_test_prototype['id_vocabulaire_prototype'] == 0 || $row_test_prototype['id_vocabulaire_prototype'] == ""){
                                $vocab_dept_sql = "INSERT IGNORE INTO vocabulaire_prototype VALUES ('','$id_prototype','$id_vocabulaire')";
                                mysql_query($vocab_dept_sql);
                            }

                            $sql_test_prot_access ="select * from prototype_access where type='$prototype' AND id_societe='$id_societe' ";
                            $query_test_prot_access= mysql_query($sql_test_prot_access) or die(mysql_error());
                            $row_test_prot_access = mysql_fetch_array($query_test_prot_access);
                            if($row_test_prot_access['id_prototype_access'] == 0 || $row_test_prot_access['id_prototype_access'] == ""){
                                $sql="INSERT IGNORE INTO `prototype_access` (`id_prototype_access`, `numero`, `type`, `date`, `nb_page`, `statut`, `createur`, `mis_a_jour`, `phase`, `phase_etat`, `priorite_type`, `id_format_edition`, `id_societe`) 
								VALUES (NULL, '', '$prototype', '$date_today', '', '', '$id_traducteur', '', '', '', '', '3', '$id_societe');";
                                mysql_query($sql) or die(mysql_error());
                                $id_prot = mysql_insert_id() ;
                            }else{
                                $id_prot = $row_test_prot_access['id_prototype_access'] ;
                            }
                            ////ajout dans la table lexique pour gerer les rangs des LE
                            if($id_societe != 653){
                                $rang_lexique = recupRangLE($id_societe, $id_theme, $id_prot);
                                $rangLE = getMaxRangLE($id_prot);
                                $rangLE = $rangLE+1;
                                if($rang_lexique == 0 || $rang_lexique == ""){
                                    $sql_insert1="INSERT IGNORE INTO lexique VALUES ('', '$id_societe', '$id_theme', '$id_prot', '$rangLE')";
                                    mysql_query($sql_insert1) or die(mysql_error());
                                }
                            }
                            $sql_test_prototype_access ="select * from vocabulaire_prototype_access where id_vocabulaire='$id_vocabulaire' AND id_prototype_access='$id_prot' ";
                            $query_test_prototype_access= mysql_query($sql_test_prototype_access) or die(mysql_error());
                            $row_test_prototype_access = mysql_fetch_array($query_test_prototype_access);
                            if($row_test_prototype_access['id_vocabulaire_prototype_access'] == 0 || $row_test_prototype_access['id_vocabulaire_prototype_access'] == ""){
                                $sql_insert="INSERT IGNORE INTO vocabulaire_prototype_access VALUES ('', '$id_vocabulaire', '$id_prot')";
                                mysql_query($sql_insert) or die(mysql_error());
                            }
                        }

                        $sql_test_societe ="select * from vocabulaire_societe where id_societe='$id_societe' AND id_vocabulaire='$id_vocabulaire' ";
                        $query_test_societe= mysql_query($sql_test_societe) or die(mysql_error());
                        $row_test_societe = mysql_fetch_array($query_test_societe);
                        if($row_test_societe['id_vocabulaire_societe'] == 0 || $row_test_societe['id_vocabulaire_societe'] == ""){
                            $vocab_soc_sql = "INSERT IGNORE INTO vocabulaire_societe VALUES ('','$id_societe','$id_vocabulaire')";
                            mysql_query($vocab_soc_sql);
                        }

                        if($suffixe != ""){
                            //verif suffixe
                            //$suffixe_verif = strtolower($suffixe);
                            $sql_suffixe ="select * from suffixe where libelle_suffixe='$suffixe' and millesime='$millesime' ";
                            $query_suffixe= mysql_query($sql_suffixe) or die(mysql_error());
                            $row_suffixe = mysql_fetch_array($query_suffixe);
                            if($row_suffixe['id_suffixe'] != 0){
                                $id_suffixe = $row_suffixe['id_suffixe'];
                            }else{
                                $suffixe_sql = "INSERT IGNORE INTO suffixe VALUES ('', '$suffixe', '$millesime')";
                                mysql_query($suffixe_sql);
                                $id_suffixe = mysql_insert_id() ;
                            }
                            $sql_test_suffixe ="select * from suffixe_societe where id_suffixe='$id_suffixe' AND id_societe='$id_societe' ";
                            $query_test_suffixe= mysql_query($sql_test_suffixe) or die(mysql_error());
                            $row_test_suffixe = mysql_fetch_array($query_test_suffixe);
                            if($row_test_suffixe['id_suffixe_societe'] == 0 || $row_test_suffixe['id_suffixe_societe'] == ""){
                                $vocab_sfx_sql = "INSERT IGNORE INTO suffixe_societe VALUES ('','$id_suffixe','$id_societe')";
                                mysql_query($vocab_sfx_sql);
                            }
                        }

                        //phrase source non vide
                        if($index_phrase_source != null && $index_phrase_source != 0){
                            $phrase_source = htmlspecialchars($tab[$index_phrase_source]);
                            //verif phrase source
                            $sql_phrase_source ="select * from phrase_source where libelle_phrase_source='$phrase_source' ";
                            $query_phrase_source= mysql_query($sql_phrase_source) or die(mysql_error());
                            $row_phrase_source = mysql_fetch_array($query_phrase_source);
                            if($row_phrase_source['id_phrase_source'] != 0){
                                $id_phrase_source = $row_phrase_source['id_phrase_source'];
                            }else{
                                $phrase_source_sql = "INSERT IGNORE INTO phrase_source VALUES ('', '$phrase_source')";
                                mysql_query($phrase_source_sql);
                                $id_phrase_source = mysql_insert_id() ;
                            }
                            $sql_test_phrase_source ="select * from vocabulaire_phrase_source where id_phrase_source='$id_phrase_source' AND id_vocabulaire='$id_vocabulaire' ";
                            $query_test_phrase_source= mysql_query($sql_test_phrase_source) or die(mysql_error());
                            $row_test_phrase_source = mysql_fetch_array($query_test_phrase_source);
                            if($row_test_phrase_source['id_vocabulaire_phrase_source'] == 0 || $row_test_phrase_source['id_vocabulaire_phrase_source'] == ""){
                                $vocab_phrase_source_sql = "INSERT IGNORE INTO vocabulaire_phrase_source VALUES ('','$id_phrase_source','$id_vocabulaire')";
                                mysql_query($vocab_phrase_source_sql);
                            }
                        }*/
                    }
                }

                if($id_societe == 653){
                    echo "<SCRIPT language=javascript> window.location = 'consulterVocabulaire.php';</SCRIPT>";
                }else{
                    //echo "<SCRIPT language=javascript> window.location = 'modifPrototype.php?id=$id_prot';</SCRIPT>";
                }

            }
        }
        else{
            echo "<SCRIPT language=javascript> alert('Format de fichier non reconnu'); window.location = 'mise_a_jour_vocab';</SCRIPT>";
        }

        return $this->render('FormationVocabulaireBundle:Default:uploadExcel.html.twig', array(

        ));
    }
}
