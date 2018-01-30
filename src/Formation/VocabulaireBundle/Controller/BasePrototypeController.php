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
     * @Route("/modif_contenu_prot_LE", name="modif_contenu_prot_LE")
     */
    public function modifContenuPrototypeLEAction(Request $request)
    {
        $id_prototype_access_origine = intval($request->get("id_prototype_access"));
        $id_societe = intval($request->get("id_societe"));
        $id = intval($request->get("id"));

        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');

        $lexiques = $repositoryLexique->rechercheContenuProtLE($id_prototype_access_origine, $id_societe);

        echo '<table>';
        $i = 0;
        foreach ($lexiques as $row) {
            $id_lexique = $repositoryLexique->recupIdLE($row['id_societe'], $row['id_theme'], $id);
            $i++;

            echo '<tr>';
            echo '<td>';
            echo '<input type="checkbox" name="choix"';
            if ($id_lexique != "" && $id_lexique != null) {
                echo "checked='checked'";
            }
            echo 'onclick="sendData(this.checked, ' . $i . ', ' . $row['id_societe'] . ', ' . $row['id_theme'] . ',' . $id_prototype_access_origine . ',' . $id . ');"> </td>';
            echo '<td><p class="terme_fr sous_titre_fr" >'.html_entity_decode($row['libelle_theme'])."/".html_entity_decode($row['theme_eng']).'</p></td>';
            echo '<td><input type="button" id="bouton'.$i.'"';
            if ($id_lexique != "" && $id_lexique != null) { echo "enabled='enabled'" ; } else {echo "disabled=disabled style='background:#808080;'";} echo   'value="Modifier" onclick="modifLE('.$id.','.$row['id_societe'].','.$row['id_theme'].')"></td>';

echo '</tr>';

}

        echo '</table>';

        $url_send_data = $this->generateUrl(
            'send_data',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->render('FormationVocabulaireBundle:Prototype:modif_contenu_prot_le.html.twig', array(
            'url_send_data' => $url_send_data

        ));
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

        $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');

        $em = $this->getDoctrine()->getManager();

        $vocabulairePrototypeAccess = $repositoryVocabulairePrototypeAccess->getContenuLE($id_prototype_access_origine, $id_societe, $id_theme);


        foreach ($vocabulairePrototypeAccess as $donnees)
        {
            $id_vocabulaire = $donnees['id_vocabulaire'];
            $vocProtos = $repositoryVocabulairePrototypeAccess->getVocabulaireProtoByProtoAccessAndVocabulaire($id, $id_vocabulaire);
            foreach ($vocProtos as $row_test1)
            {
                if($row_test1['id_vocabulaire_prototype_acces'] == 0 || $row_test1['id_vocabulaire_prototype_acces'] == "")
                {
                    $vocabProtoAccess = new VocabulairePrototypeAccess();
                    $vocabulaire = $repositoryVocabulaire->find($id_vocabulaire);
                    $prototypeAccess = $repositoryPrototypeAccess->find($id);
                    $vocabProtoAccess->setPrototypeAccess($prototypeAccess);
                    $vocabProtoAccess->setVocabulaire($vocabulaire);
                    $em->persist($vocabulairePrototypeAccess);
                    $em->flush();
                    $lexiques = $repositoryLexique->getLexiaueByProtoTypeAndThemeAndSociete($id, $id_theme, $id_societe);
                    $rangLE = $repositoryLexique->getMaxRangLE($id);
		            $rangLE = $rangLE+1;
                    foreach($lexiques as $row1)
                    {
                        if($row1['id_lexique'] == 0 || $row1['id_lexique'] == ""){
                            $societe = $repositorySociete->find($id_societe);
                            $theme = $repositoryTheme->find($id_theme);
                            $lexique = new Lexique();
                            $lexique->setPrototypeAccess($prototypeAccess);
                            $lexique->setRang($rangLE);
                            $lexique->setSociete($societe);
                            $lexique->setTheme($theme);
                            $em->persist($lexique);
                            $em->flush();
                        }
                    }
                }
            }
        }
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

