<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\Lexique;
use Formation\VocabulaireBundle\Entity\VocabulairePrototypeAccess;
use Formation\VocabulaireBundle\Model\Vocabulaire;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BasePrototypeController extends Controller
{

        private function convert_utf8($str) {
		$new2old = array(
 'á' => 'Ã¡',
 
 'À' => 'Ã€',
 'ä' => 'Ã¤',
 'Ä' => 'Ã„',
 'ã' => 'Ã£',
 'å' => 'Ã¥',
 'Å' => 'Ã…',
 'æ' => 'Ã¦',
 'Æ' => 'Ã†',
 'ç' => 'Ã§',
 'Ç' => 'Ã‡',
 'é' => 'Ã©',
 'É' => 'Ã‰',
 'è' => 'Ã¨',
 'È' => 'Ãˆ',
 'ê' => 'Ãª',
 'Ê' => 'ÃŠ',
 'ë' => 'Ã«',
 'Ë' => 'Ã‹',
 'í' => 'Ã-­­',
 'Í' => 'Ã',
 'ì' => 'Ã¬',
 'Ì' => 'ÃŒ',
 'î' => 'Ã®',
 'Î' => 'ÃŽ',
 'ï' => 'Ã¯',
 'Ï' => 'Ã',
 'ñ' => 'Ã±',
 'Ñ' => 'Ã‘',
 'ó' => 'Ã³',
 'Ó' => 'Ã“',
 'ò' => 'Ã²',
 'Ò' => 'Ã’',
 'ô' => 'Ã´',
 'Ô' => 'Ã”',
 'ö' => 'Ã¶',
 'Ö' => 'Ã–',
 'õ' => 'Ãµ',
 'Õ' => 'Ã•',
 'ø' => 'Ã¸',
 'Ø' => 'Ã˜',
 'œ' => 'Å“',
 'Œ' => 'Å’',
 'ß' => 'ÃŸ',
 'ú' => 'Ãº',
 'Ú' => 'Ãš',
 'ù' => 'Ã¹',
 'Ù' => 'Ã™',
 'û' => 'Ã»',
 'Û' => 'Ã›',
 'ü' => 'Ã¼',
 'Ü' => 'Ãœ',
 '€' => 'â‚¬',
 '’' => 'â€™',
 '‚' => 'â€š',
 'ƒ' => 'Æ’',
 '„' => 'â€ž',
 '…' => 'â€¦',
 '‡' => 'â€¡',
 'ˆ' => 'Ë†',
 '‰' => 'â€°',
 'Š' => 'Å ',
 '‹' => 'â€¹',
 'Ž' => 'Å½',
 '‘' => 'â€˜',
 '“' => 'â€œ',
 '•' => 'â€¢',
 '–' => 'â€“',
 '—' => 'â€”',
 '˜' => 'Ëœ',
 '™' => 'â„¢',
 'š' => 'Å¡',
 '›' => 'â€º',
 'ž' => 'Å¾',
 'Ÿ' => 'Å¸',
 '¡' => 'Â¡',
 '¢' => 'Â¢',
 '£' => 'Â£',
 '¤' => 'Â¤',
 '¥' => 'Â¥',
 '¦' => 'Â¦',
 '§' => 'Â§',
 '¨' => 'Â¨',
 '©' => 'Â©',
 'ª' => 'Âª',
 '«' => 'Â«',
 '¬' => 'Â¬',
 '®' => 'Â®',
 '¯' => 'Â¯',
 '°' => 'Â°',
 '±' => 'Â±',
 '²' => 'Â²',
 '³' => 'Â³',
 '´' => 'Â´',
 'µ' => 'Âµ',
 '¶' => 'Â¶',
 '·' => 'Â·',
 '¸' => 'Â¸',
 '¹' => 'Â¹',
 'º' => 'Âº',
 '»' => 'Â»',
 '¼' => 'Â¼',
 '½' => 'Â½',
 '¾' => 'Â¾',
 '¿' => 'Â¿',
 'à' => 'Ã ',
 '†' => 'â€ ',
 '”' => 'â€',
 'Á' => 'Ã',
 'â' => 'Ã¢',
 'Â' => 'Ã‚',
 'Ã' => 'Ãƒ',
);
 $new = array();
 $old = array();
foreach( $new2old as $key => $value ) {
   $new[] = $key;
   $old[] = $value;
}
return str_replace( $old, $new, $str );
    }
    /**
     * @Route("/consulter_prototype", name="consulter_prototype")
     */
    public function consulter_prototypeAction(Request $request)
    {
        $id_societe = 0;
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
        $prototype_accesss_array = array();

        if ($request->get('id_societe')) {
            $id_societe = $request->get('id_societe');
            $prototype_accesss = $repositoryPrototypeAccess->getPrototypeAccessBySociete($id_societe);
            $compteur = count($prototype_accesss);

        } else {

            $prototype_accesss = $repositoryPrototypeAccess->getPrototypeAccess();

            $compteur = count($prototype_accesss);

        }

        $societes = $repositoryPrototypeAccess->getSocietes();

        foreach ($prototype_accesss as $prototype_access) {
            $protoModel = new \Formation\VocabulaireBundle\Model\ProtoType();
            $protoModel->setNbSoloc($this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getLESocAssocies($prototype_access['id_societe'], $prototype_access['id_prototype_access']));
            $protoModel->setNbLeGen($this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getLEGenAssocies($prototype_access['id_prototype_access']));
            $nb_termes_array = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getTermesAssocies($prototype_access['id_prototype_access']);
            $nb_termes = $nb_termes_array['nb_termes'];
            $protoModel->setNb_termes($nb_termes);
            $protoModel->setId($prototype_access['id_prototype_access']);
            $societe = $repositorySociete->find($prototype_access['id_societe']);
            if ($societe != null) $protoModel->setSociete($this->convert_utf8($societe->getDescription()));

            $traducteur = $repositoryTraducteur->find($prototype_access['createur']);
            if ($traducteur != null) $protoModel->setTraducteur($this->convert_utf8($traducteur->getNom()));
            $protoModel->setType($this->convert_utf8($prototype_access['type']));
            $protoModel->setDate(new \DateTime($prototype_access['date']));
            $prototype_accesss_array [] = $protoModel;

        }

        return $this->render('FormationVocabulaireBundle:Default:consulter_prototype.html.twig', array(
            'compteur' => $compteur,
            'id_societe' => $id_societe,
            'societes' => $societes,
            'prototype_accesss_array' => $prototype_accesss_array
        ));
    }

    /**
     * @Route("/deletePrototype/{id}", name="deletePrototype")
     */
    public function deletePrototypeAction($id)
    {
        $id = intval($id);
        $sql="DELETE FROM prototype_access WHERE id_prototype_access = $id";
        $this->execute($sql);
        $sql1="DELETE FROM vocabulaire_prototype_access WHERE id_prototype_access = $id";
        $this->execute($sql1);
        $sql2="DELETE FROM lexique WHERE id_prototype_access = $id";
        $this->execute($sql2);
        return $this->redirect($this->generateUrl('consulter_prototype'));
    }


    /**
     * @Route("/modif_prot_LE/{id}/{id_societe}", name="modif_prot_LE")
     */
    public function modifPrototypeLEAction($id, $id_societe)
    {
        $valuesIdtheme = "";
        $id = intval($id);
        $id_societe = intval($id_societe);
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $prototypeAccesss = $repositoryPrototypeAccess->recherchePrototypeParSoc($id_societe, $id);
        $compteur = count($prototypeAccesss);
        foreach ($prototypeAccesss as $proto) {
            $valuesIdtheme = $valuesIdtheme . "," . $proto['id_prototype_access'];
        }
        $url = $this->generateUrl(
            'modif_contenu_prot_LE',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->render('FormationVocabulaireBundle:Default:modif_prot_LE.html.twig', array(
            'id' => $id,
            'url' => $url,
            'compteur' => $compteur,
            'id_societe' => $id_societe,
            'valuesIdtheme' => $valuesIdtheme,
            'prototypeAccesss' => $prototypeAccesss
        ));
    }

    /**
     * @Route("/deleteData", name="deleteData")
     */
    public function deleteDataAction(Request $request) {
        $id = intval($request->get("id"));
        $id_societe = intval($request->get("id_societe"));
        $id_theme = intval($request->get("id_theme"));
        $date_today = date("Y-m-d");

        //suppression du LE décoché
        $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');
        $repositoryVocabulaireLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        $sql = $repositoryVocabulairePrototypeAccess->getContenuLE($id, $id_societe, $id_theme);

        foreach ($sql as $donnees){
            $id_vocabulaire_to_delete = $donnees['id_vocabulaire'];
            $repositoryVocabulairePrototypeAccess->deleteVocab($id, $id_vocabulaire_to_delete);
            $repositoryVocabulaireLexique->deleteLexique($id_societe, $id_theme, $id);
            
        }
         return $this->render('FormationVocabulaireBundle:Prototype:deleteData.html.twig', array(
            
        ));   
    }

    /**
     * @Route("/modif_contenu_prot_le_table/{theme_eng}/{libelle_theme}/{id_societe}/{id_theme}/{id}/{i}/{id_prototype_access_origine}", name="modif_contenu_prot_le_table")
     */
    public function modif_contenu_prot_le_tableAction($theme_eng,$libelle_theme,$id_societe, $id_theme, $id, $i,$id_prototype_access_origine)
    {
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        $id_lexique = $repositoryLexique->recupIdLE($id_societe, $id_theme, $id);
        return $this->render('FormationVocabulaireBundle:Prototype:modif_contenu_prot_le_table.html.twig', array(
            'id' => $id,
            'i' => $i,
            'id_societe' => $id_societe,
            'id_theme' => $id_theme,
            'id_prototype_access_origine' => $id_prototype_access_origine,
            'libelle_theme' => $libelle_theme,
            'theme_eng' => $theme_eng,
            'id_lexique' => $id_lexique
        ));
    }

    /**
     * @Route("/modif_contenu_prot_LE", name="modif_contenu_prot_LE")
     */
    public function modifContenuPrototypeLEAction(Request $request)
    {

        $id_prototype_access_origine = intval($request->get("id_prototype_access"));
        $id_societe = intval($request->get("id_societe"));
        $id = intval($request->get("id"));
        $i = 0;
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');

        $lexiques = $repositoryLexique->rechercheContenuProtLE($id_prototype_access_origine, $id_societe);

        return $this->render('FormationVocabulaireBundle:Prototype:modif_contenu_prot_le.html.twig', array(
           'lexiques' => $lexiques,
            'id' => $id,
            'i' => $i,
            'id_prototype_access_origine' => $id_prototype_access_origine
        ));
    }


    /**
     * @Route("/modifPrototypeLESoc1Action", name="modifPrototypeLESoc1Action")
     */
    public function modifPrototypeLESoc1Action(Request $request)
    {

        $id_societe = intval($request->get("id_societe"));
        $id = intval($request->get("id"));
        $i = 0;
        $id_prototype_access_origine = $id;
        $sql = "SELECT theme.id_theme as id_theme, theme.libelle_theme as libelle_theme, theme.theme_eng as theme_eng, societe.id_societe as id_societe, societe.description as description FROM `vocabulaire`  
            INNER JOIN vocabulaire_theme ON vocabulaire_theme.id_vocabulaire = vocabulaire.id_vocabulaire 
            INNER JOIN theme ON theme.id_theme = vocabulaire_theme.id_theme
            INNER JOIN vocabulaire_societe ON vocabulaire_societe.id_vocabulaire = vocabulaire.id_vocabulaire AND vocabulaire_societe.id_societe = '653'
            INNER JOIN societe ON societe.id_societe = '653'  WHERE vocabulaire.date_modification = '0000-00-00 00:00:00' AND vocabulaire.isAffiche = '1' 
            GROUP BY theme.id_theme ORDER BY theme.libelle_theme collate utf8_general_ci";
    
        $lexiques = $this->fetch($sql);

        return $this->render('FormationVocabulaireBundle:Prototype:modifPrototypeLESoc1.html.twig', array(
           'lexiques' => $lexiques,
            'id' => $id,
            'id_societe' => $id_societe,
            'i' => $i,
            'id_prototype_access_origine' => $id_prototype_access_origine
        ));
    }

    private function fetch($query)
    {
        $stmt = $this->getDoctrine()->getEntityManager()->getConnection()->prepare($query);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    private function execute($query)
    {
        $stmt = $this->getDoctrine()->getEntityManager()->getConnection()->prepare($query);
        $stmt->execute();
    }

    /**
     * @Route("/send_data", name="send_data")
     */
    public function sendDataAction(Request $request)
    {
        $id_prototype_access_origine = intval($request->get("id_prototype_access_origine"));
        $id = intval($request->get("id"));
        $id_societe = intval($request->get("id_societe"));
        $id_theme = intval($request->get("id_theme"));
        $date_today = date("Y-m-d");

        $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');
        $sql = $repositoryVocabulairePrototypeAccess->getContenuLE($id_prototype_access_origine, $id_societe, $id_theme);
        $repositoryVocabulaireLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');

        echo count($sql);

        foreach ($sql as $donnees) {

            $id_vocabulaire = $donnees['id_vocabulaire'];
            $sql_test1 = "select * from vocabulaire_prototype_access where id_prototype_access='$id' and id_vocabulaire='$id_vocabulaire' ";
            $row_test1 = $this->fetch($sql_test1);

            if (count($row_test1) == 0) {

                    $vocab_soc_sql = "INSERT IGNORE INTO vocabulaire_prototype_access VALUES ('','$id_vocabulaire','$id')";

                    $this->execute($vocab_soc_sql);

                    //ajout dans la table lexique pour gerer les rangs des LE
                    $sql_verif1 = "select * from lexique where id_societe='$id_societe' AND id_theme='$id_theme' AND id_prototype_access='$id' ";
                    $query_verif1 = $this->fetch($sql_verif1);
                    $rangLE = $repositoryVocabulaireLexique->getMaxRangLE($id);
                    $rangLE = $rangLE + 1;
                    foreach($query_verif1 as $row1) {
                        if ($row1['id_lexique'] == 0 || $row1['id_lexique'] == "") {
                            $sql_insert1 = "INSERT IGNORE INTO lexique VALUES ('', '$id_societe', '$id_theme', '$id', '$rangLE')";

                            $this->execute($sql_insert1);
                        }
                    }
            }
        }
        return $this->render('FormationVocabulaireBundle:Prototype:deleteData.html.twig', array(

        ));
    }




    /**
     * @Route("/send_data1", name="send_data1")
     */
    public function sendData1Action(Request $request)
    {
        $id_prototype_access_origine = intval($request->get("id_prototype_access_origine"));
        $id = intval($request->get("id"));
        $id_societe = intval($request->get("id_societe"));
        $id_theme = intval($request->get("id_theme"));
        $date_today = date("Y-m-d");

         $query = "SELECT * FROM `vocabulaire`
		INNER JOIN vocabulaire_societe ON vocabulaire_societe.id_vocabulaire = vocabulaire.id_vocabulaire AND vocabulaire_societe.id_societe = '$id_societe'
		INNER JOIN societe ON societe.id_societe = '$id_societe' 
		INNER JOIN vocabulaire_theme ON vocabulaire_theme.id_vocabulaire = vocabulaire.id_vocabulaire AND vocabulaire_theme.id_theme = '$id_theme'
		INNER JOIN theme ON theme.id_theme = '$id_theme'";
        $repositoryVocabulaireLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');

        $sql = $this->fetch($query);
        foreach ($sql as $donnees) {

            $id_vocabulaire = $donnees['id_vocabulaire'];
            $sql_test1 = "select * from vocabulaire_prototype_access where id_prototype_access='$id' and id_vocabulaire='$id_vocabulaire' ";
            $row_test1 = $this->fetch($sql_test1);

            if (count($row_test1) == 0) {

                $vocab_soc_sql = "INSERT IGNORE INTO vocabulaire_prototype_access VALUES ('','$id_vocabulaire','$id')";

                $this->execute($vocab_soc_sql);

                //ajout dans la table lexique pour gerer les rangs des LE
                $sql_verif1 = "select * from lexique where id_societe='$id_societe' AND id_theme='$id_theme' AND id_prototype_access='$id' ";
                $query_verif1 = $this->fetch($sql_verif1);
                $rangLE = $repositoryVocabulaireLexique->getMaxRangLE($id);
                $rangLE = $rangLE + 1;
                if (count($query_verif1) == 0){
                        
                        $sql_insert1 = "INSERT IGNORE INTO lexique VALUES ('', '$id_societe', '$id_theme', '$id', '$rangLE')";

                        $this->execute($sql_insert1);
                   
                }
            }
        }
        return $this->render('FormationVocabulaireBundle:Prototype:deleteData.html.twig', array(

        ));
    }

    /**
     * @Route("/export_prototype", name="export_prototype")
     */
    public function export_prototypeAction(Request $request)
    {
        if ($request->get('id_societe')) {
            $id_societe = $request->get('id_societe');
            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
            $societe = $repositorySociete->find($id_societe);
            $prototype_accesss_array = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('societe' => $societe));


        } else {
            $prototype_accesss_array = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findAll();


        }

        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setTitle('PROTOTYPE')
            ->setSubject('PROTOTYPE');

        $sheet = $phpExcelObject->setActiveSheetIndex(0);

        $sheet->setCellValue('A1', 'Société');
        $sheet->setCellValue('B1', 'Prototype');
        $sheet->setCellValue('C1', 'Date de création');

        $counter = 2;
        foreach ($prototype_accesss_array as $prototype_accesss) {
            $sheet->setCellValue('A' . $counter, $prototype_accesss->getSociete()->getDescription());
            $sheet->setCellValue('B' . $counter, $prototype_accesss->getType());
            $sheet->setCellValue('C' . $counter, $prototype_accesss->getDate()->format('d/m/Y'));
            $counter++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('PROTOTYPE');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'liste_prototype.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;

    }


}

