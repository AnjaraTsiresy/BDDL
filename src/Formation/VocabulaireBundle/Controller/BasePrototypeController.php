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
            if ($societe != null) $protoModel->setSociete($societe->getDescription());

            $traducteur = $repositoryTraducteur->find($prototype_access['createur']);
            if ($traducteur != null) $protoModel->setTraducteur($traducteur->getNom());
            $protoModel->setType($prototype_access['type']);
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

        foreach ($sql as $donnees) {

            $id_vocabulaire = $donnees['id_vocabulaire'];
            $sql_test1 = "select * from vocabulaire_prototype_access where id_prototype_access='$id' and id_vocabulaire='$id_vocabulaire' ";
            $query_test1 = $this->fetch($sql_test1);
            foreach ($query_test1 as $row_test1) {
                if ($row_test1['id_vocabulaire_prototype_acces'] == 0 || $row_test1['id_vocabulaire_prototype_acces'] == "") {
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

