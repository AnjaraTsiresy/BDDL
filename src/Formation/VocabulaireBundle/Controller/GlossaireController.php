<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\NbPage;
use Formation\VocabulaireBundle\Entity\TableDesMatieresProto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class GlossaireController extends Controller
{

    private function convert_utf8( $string ) {
       if ( mb_detect_encoding($string) != 'ASCII') {
         // echo $string.' =====> '.mb_detect_encoding($string).'<br>';
           return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
       }

       return $string;
    }


    /**
     * @Route("/glossaire/{id}/{id_societe}", name="glossaire")
     */
    public function glossaireAction($id, $id_societe) {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');

        echo '<style>
page[size="A4"] {
    background: white;
    /*
    height: 21cm;
    width: 29.7cm;
    */
    /*height: 21.035cm;*/
    /*height: 21cm;*/
    height: 21.010cm;
    width: 29.7cm;
    display: block;
    margin: 0 auto;
    margin-top: 0 cm;
	vertical-align:top;
}
@media print {
  body, page[size="A4"] {
    margin: 0;
    box-shadow: 0;
  }
}

.colonnedata td {
  	width:90mm; padding-top:2mm; vertical-align:top;
  	/*font-variant-position: sub;*/
}

.colonnedata {
  	width:480px;
}

.titremangamanga {
	font-size: 9.5pt; margin-right : 10mm;
	margin-top: 1mm;
}

</style>';

        echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
        echo '<link rel="stylesheet" type="text/css" href="../../../css/pdf1_tato1.css">';

        $id = intval($id);
        $id_societe = intval($id_societe);

        $prototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id);

        $ptte = new \Formation\VocabulaireBundle\Model\Propriete();
        $prototypeTitle = $ptte->getPrototypeTitle($prototypeAccess);
        $prototypeTitle = strtoupper($prototypeTitle);

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
        $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TempPdfLoaddatatheme')->bigSelect();
        $dataThemeResult = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TempPdfLoaddatatheme')->getThemes($id);

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

            $loadDtaWithThemes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getDataWithThemes($id, $rowpips['1']);
            $dataNum = array();
            foreach ($loadDtaWithThemes as $row) {
                $langue_origine = $this->convert_utf8($row["langue_origine"]);
                $langue_traduction = $row["langue_traduction"];
                $langue_origine_sans_modif = $this->convert_utf8($row["langue_origine_sans_modif"]);
                $nbreLigneLo = $row["nbreLigneLo"];
                $nbreCaractLo = $row["nbreCaractLo"];
                $langue_origine = strtolower ( $langue_origine );
                $dataNum[] = array($langue_origine, $langue_traduction, $langue_origine_sans_modif,$nbreCaractLo,$nbreLigneLo);
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
            $titre_theme =$row[0];

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
     * @Route("/impression_glossaire/{id}/{id_societe}", name="impression_glossaire")
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
            'glossaire',
            [
                'id' => $id,
                'id_societe' => $id_societe,
            ], UrlGeneratorInterface::ABSOLUTE_URL
        );
        return new Response($snappy->getOutput($link,array(
            'orientation' => 'landscape'
        )), 200, array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
            )
        );
    }


}
