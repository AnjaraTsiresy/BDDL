<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\NbPage;
use Formation\VocabulaireBundle\Entity\TableDesMatieresProto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ImportationCorpsController extends Controller {


    private function convert_utf8( $string ) {
        return mb_convert_encoding($string, 'Cp1252', 'UTF-8');
    }
    
    /**
     * @Route("/generateCorpsGlossaire/{id}/{id_societe}", name="generateCorpsGlossaire")
     */
    public function generateCorpsGlossaireAction($id, $id_societe) {

        $id = intval($id);
        $id_societe = intval($id_societe);

        $prototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id);

        $ptte = new \Formation\VocabulaireBundle\Model\Propriete();
        $prototypeTitle = $ptte->getPrototypeTitle($prototypeAccess);
        $prototypeTitle = mb_strtoupper($prototypeTitle, 'UTF-8');

        $pdf = new \Formation\VocabulaireBundle\Model\PDF();
        $societe_obj = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe')->find($id_societe);
        $nom_societe = $pdf->getClient($societe_obj);
        $em = $this->getDoctrine()->getManager();
        // Chargement des données
        $tabledesMatieresProto = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TableDesMatieresProto')->getAllTableDesMatieresProtoBySocieteAndPrototype($id_societe, $id);

        foreach ($tabledesMatieresProto as $tab) {

            $em->remove($tab);
            $em->flush();
        }

        $nbPages = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:NbPage')->getAllNbPageByPrototypeAccess($id);

        foreach ($nbPages as $nbPage) {
            $em->remove($nbPage);
            $em->flush();
        }

        $dataThemeResult = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TempPdfLoaddatatheme')->LoadDataTheme($id);

        $dataTheme = array();
        foreach ($dataThemeResult as $row) {
            $lib = $this->convert_utf8($row["lib"]);
            $idT = $this->convert_utf8($row["idT"]);
            $description = $this->convert_utf8($row["description"]);
            $id_societe = $this->convert_utf8($row["id_societe"]);
            $dataTheme[] = array($lib, $idT, $description, $id_societe);
        }


        $pdfNumpage = new \Formation\VocabulaireBundle\Model\PDFPage();
        $j = 1;
        $dataAll = array();

        foreach ($dataTheme as $rowpips) {

            $loadDtaWithThemes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->LoadDtaWithTheme($id, $rowpips['1']);
            $dataNum = array();
            foreach ($loadDtaWithThemes as $row) {
                $langue_origine = $this->convert_utf8($row["langue_origine"]);
                $langue_traduction = $this->convert_utf8($row["langue_traduction"]);
                $langue_origine_sans_modif = $this->convert_utf8($row["langue_origine_sans_modif"]);

                $langue_origine = strtolower($langue_origine);
                $dataNum[] = array($langue_origine, $langue_traduction, $langue_origine_sans_modif);
            }

            $dataAll[] = $dataNum;

            $societe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe')->find($id_societe);
            $prototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id);
            $em = $this->getDoctrine()->getManager();
            $numero = $pdfNumpage->numero();
            if ($prototypeAccess != null && $societe != null) {
                $tableDesMatieresProto = new TableDesMatieresProto();
                $tableDesMatieresProto->setNoPrototype($prototypeAccess);
                $tableDesMatieresProto->setTheme($rowpips[0]);
                $tableDesMatieresProto->setOrdreTheme(1);
                $tableDesMatieresProto->setSousTheme("");
                $tableDesMatieresProto->setOrdreSousTheme($numero);
                $tableDesMatieresProto->setSociete($societe);
                $em->persist($tableDesMatieresProto);
                $em->flush();
            }
            $pdfNumpage->PrintChapter($dataNum, $rowpips[0], $id_societe, $id);

            $nb_page = $pdfNumpage->numpage;
            $nbPage_obj = new NbPage();
            $nbPage_obj->setNbDepage($nb_page);
            $nbPage_obj->setPrototypeAccess($prototypeAccess);
            $em->persist($nbPage_obj);
            $em->flush();
            $j++;
        }
        

        $nb_page = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:NbPage')->getNbPage($id);

        $i = 1;

        $sommaire_generique = "Termes generiques";
        $sommaire_generique = strtoupper($sommaire_generique);
        $htme = "";


        $x = 0;
        $i = 1;
        foreach ($dataTheme as $row) {
            $societe = $this->convert_utf8($row[3]);
            if ($societe == "FORMA2+") {
                $societe = "général";
            }
            $titre_theme = $this->convert_utf8($row[0]);
            $pdf->PrintChapter($i, $row[0], $titre_theme, $dataAll[$x], $societe, $nb_page);
            $i++;
            $x++;
        }

        $totaltableau = array();
        return $this->render('FormationVocabulaireBundle:Impression:impressionCorpsGlossaire.html.twig', array(
                    'id' => $id,
                    'totaltableau' => $totaltableau,
                    'id_societe' => $id_societe
        ));
    }

    
    /**
     * @Route("/impressionCorpsGlossaire/{id}/{id_societe}", name="impressionCorpsGlossaire")
     */
    public function impressionCorpsGlossaireAction($id, $id_societe) {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');
        $snappy = $this->get('knp_snappy.pdf');
        $snappy->getInternalGenerator()->setTimeout(1500);
        $societe_obj = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe')->find($id_societe);
        $pdf = new \Formation\VocabulaireBundle\Model\PDF();
        $nom_societe = $pdf->getClient($societe_obj);
        $ptte = new \Formation\VocabulaireBundle\Model\Propriete();
        $prototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id);
        $prototypeTitle = $ptte->getPrototypeTitle($prototypeAccess);
        $prototypeTitle = mb_strtoupper($prototypeTitle, 'UTF-8');
        $filename = 'CorpsGlossaire' . $nom_societe . '' . $prototypeTitle;
        $link = $this->generateUrl(
                'generateCorpsGlossaire', 
                    [
                        'id' => $id,
                        'id_societe' => $id_societe,
                    ], UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new Response($snappy->getOutput($link,array(
                'orientation' => 'landscape', 
                'enable-javascript' => true, 
                'javascript-delay' => 1000, 
                'no-stop-slow-scripts' => true, 
                'no-background' => false, 
                'lowquality' => false,
                'page-size' => 'A4',
                'page-height' => '21cm',
                'page-width'  => '29.7cm',
                'encoding' => 'utf-8',
                'images' => true,
                'cookie' => array(),
                'dpi' => 300,
                'image-dpi' => 300,
                'enable-external-links' => true,
                'enable-internal-links' => true
            )), 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
           )
        );
    }

}
