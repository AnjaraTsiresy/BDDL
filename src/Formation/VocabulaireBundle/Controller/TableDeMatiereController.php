<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\NbPage;
use Formation\VocabulaireBundle\Entity\TableDesMatieresProto;
use Formation\VocabulaireBundle\Entity\TempPdfLoaddatatheme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TableDeMatiereController extends Controller
{

    private function convert_utf8( $string ) {
        if ( mb_detect_encoding($string) != 'ASCII') {
            // echo $string.' =====> '.mb_detect_encoding($string).'<br>';
            return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
        }

        return $string;
    }
    /**
     * @Route("/table_matiere/{id}/{id_societe}", name="table_matiere")
     */
    public function impressionTableMatiereAction($id,$id_societe)
    {
        $snappy = $this->get('knp_snappy.pdf');
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

        foreach ($tabledesMatieresProto as $tab)
        {

            $em->remove($tab);
            $em->flush();
        }

        $nbPages = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:NbPage')->getAllNbPageByPrototypeAccess($id);

        foreach ($nbPages as $nbPage)
        {
            $em->remove($nbPage);
            $em->flush();
        }

        $dataThemeResult = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TempPdfLoaddatatheme')->getThemes($id);
        $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TempPdfLoaddatatheme')->bigSelect();

        $dataTheme = array();
        foreach ($dataThemeResult as $row)
        {
            $lib = $row["lib"];
            $idT = $row["idT"];
            $description = $row["description"];
            $id_soc= $row["id_societe"];
            $dataTheme[] = array($lib, $idT,$description,$id_soc);
        }


        $pdfNumpage = new \Formation\VocabulaireBundle\Model\PDFPage();
        $j = 1;
        $dataAll = array();

        foreach($dataTheme as $rowpips)
        {

            $loadDtaWithThemes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->getDataWithThemes($id, $rowpips['1']);
            $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TempPdfLoaddatatheme')->bigSelect();
            $dataNum = array();
            foreach ($loadDtaWithThemes as $row)
            {
                $langue_origine = $row["langue_origine"];
                $langue_traduction = $row["langue_traduction"];
                $langue_origine_sans_modif = $row["langue_origine_sans_modif"];
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
            if($prototypeAccess != null && $societe != null){
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
            $pdfNumpage->PrintChapter($dataNum,$rowpips[0], $id_societe, $id);
            $nb_page = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:NbPage')->getPages($id);

            $nbPage_obj = new NbPage();
            $nbPage_obj->setNbDepage($nb_page);
            $nbPage_obj->setPrototypeAccess($prototypeAccess);
            $em->persist($nbPage_obj);
            $em->flush();
            $j++;
        }
        /*fin*/

        $htme = "";

        $table_matiere1 = array();
        $table_matiere = array();
        $table_matiere3 = array();

        $tab_m1 = array();
        $tab_m2 = array();
        $tab_m3 = array();

        foreach($dataTheme as $row)
        {
            $id_soc = $row[3];

            if($id_societe == $id_soc){
                if ($htme!=$row[0]){

                    $numpge = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TableDesMatieresProto')->getMinOrdreSousTheme($row[0], $id_societe, $id);
                    $tableMatiereModel = new \Formation\VocabulaireBundle\Model\TableMatiere();
                    $tableMatiereModel->seTheme($row[0]);
                    $tableMatiereModel->setNbPage($numpge);
                    $table_matiere[] = $tableMatiereModel;
                    $tab_m1[] = "generique";

                }

                $htme = $row[0];
            }

            elseif($id_soc == 653){

                if ($htme!=$row[0]){
                    $numpge = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TableDesMatieresProto')->getMinOrdreSousTheme($row[0], $id_societe, $id);
                    $tableMatiereModel3 = new \Formation\VocabulaireBundle\Model\TableMatiere();
                    $tableMatiereModel3->seTheme($row[0]);
                    $tableMatiereModel3->setNbPage($numpge);
                    $table_matiere3[] = $tableMatiereModel3;
                    $tab_m3[] = "generique";
                }
                $htme = $row[0];
            }

            else{
                if ($htme!=$row[0]){
                    $numpge = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TableDesMatieresProto')->getMinOrdreSousTheme($row[0], $id_societe, $id);
                    $tableMatiereModel1 = new \Formation\VocabulaireBundle\Model\TableMatiere();
                    $tableMatiereModel1->seTheme($row[0]);
                    $tableMatiereModel1->setNbPage($numpge);
                    $table_matiere1[] = $tableMatiereModel1;
                    $tab_m3[] = "non generique";
                }
                $htme = $row[0];
            }


        }

        $tabletotal1 = array();

        foreach ($tab_m1 as $texte){
            $tabletotal1[] = $texte;
        }


        foreach ($table_matiere1 as $tm){
            $table_matiere3[] = $tm;
        }

        $tabletotal2 = array();

        foreach ($tab_m2 as $texte){
            $tabletotal2[] = $texte;
        }

        $tabletotal3 = array();

        foreach ($tab_m3 as $texte3){
            $tabletotal3[] = $texte3;
        }

        $nbreParTable = 8;
        $tablenbreParTable = array_chunk($tabletotal1, $nbreParTable);
        $nbreTableparTable = count ($tablenbreParTable);

        $nbreParTable1 = 8;
        $tablenbreParTable1 = array_chunk($tabletotal2, $nbreParTable1);
        $nbreTableparTable1 = count ($tablenbreParTable1);

        $nbreParTable3 = 8;
        $tablenbreParTable3 = array_chunk($tabletotal3, $nbreParTable3);
        $nbreTableparTable3 = count ($tablenbreParTable3);



        return $this->render('FormationVocabulaireBundle:Impression:impressionTableMatiere.html.twig', array(
            'table_matiere'=>$table_matiere,
            'table_matiere1'=>$table_matiere1,
            'table_matiere3'=>$table_matiere3,
            'tablenbreParTable' => $tablenbreParTable,
            'nbreTableparTable' => $nbreTableparTable,
            'tablenbreParTable1' => $tablenbreParTable1,
            'nbreTableparTable1' => $nbreTableparTable1,
            'tablenbreParTable3' => $tablenbreParTable3,
            'nbreTableparTable3' => $nbreTableparTable3,
            'prototypeTitle' => $prototypeTitle,
            'nom_societe' => $nom_societe
        ));
        /*         $html = $this->renderView('FormationVocabulaireBundle:Impression:impressionTableMatiere.html.twig', array(
                      'table_matiere'=>$table_matiere,
                      'table_matiere1'=>$table_matiere1,
                      'table_matiere3'=>$table_matiere3,
                      'tablenbreParTable' => $tablenbreParTable,
                      'nbreTableparTable' => $nbreTableparTable,
                      'tablenbreParTable1' => $tablenbreParTable1,
                      'nbreTableparTable1' => $nbreTableparTable1,
                      'tablenbreParTable3' => $tablenbreParTable3,
                      'nbreTableparTable3' => $nbreTableparTable3,
                      'prototypeTitle' => $prototypeTitle,
                      'nom_societe' => $nom_societe
                  ));

                  $filename = 'IndexGlossaire'.$nom_societe.''.$prototypeTitle;
                  $snappy->setOption('no-outline', true);
  $snappy->setOption('page-size','LETTER');
  $snappy->setOption('encoding', 'UTF-8');
                  return new Response(

                      $snappy->getOutputFromHtml($html,array(
                  'orientation' => 'landscape'
              )),
                      200,
                      array(

                          'Content-Type'          => 'application/pdf',

                          'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"',


                      )

                  );
  */
    }


    /**
     * @Route("/impression_table_matiere/{id}/{id_societe}", name="impression_table_matiere")
     */

    public function impressionTableMatiere1Action($id, $id_societe) {
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
        $filename = 'IndexGlossaire'.$nom_societe.''.$prototypeTitle;
        $link = $this->generateUrl(
            'table_matiere',
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
