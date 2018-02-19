<?php

namespace Formation\VocabulaireBundle\Controller;


use Formation\VocabulaireBundle\Model\PDF;
use Formation\VocabulaireBundle\Model\PDFPage;
use Formation\VocabulaireBundle\Model\Propriete;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class GlossaireController extends Controller
{




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
      //  return $this->getDoctrine()->getManager()->getConnection()->lastInsertId();
    }

    private function quote($str)
    {
        return $this->getDoctrine()->getManager()->getConnection()->quote($str);
    }


    /**
     * @Route("/glossaire/{id}/{id_societe}", name="glossaire")
     */
    public function glossaireAction($id, $id_societe) {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');

        $id = intval($id);
        $id_societe = intval($id_societe);
        $cnx = $this->getDoctrine()->getManager()->getConnection();
        $ptte = new Propriete($cnx);

        $pdf = new PDF($cnx);
        // Chargement des données
        $sqlvidage = "DELETE FROM `table_des_matieres_proto` WHERE id_societe='$id_societe' and No_prototype='$id'";
        $this->execute($sqlvidage);

        $sqlvidage1 = "DELETE FROM `nb_page` WHERE id_prototype_access='$id'";
        $this->execute($sqlvidage1);

        $dataTheme = $pdf->LoadDataTheme($id);
        /*population table*/
        $pdfNumpage = new PDFPage($cnx);
        $j = 1;
        $dataAll = array();
        $titre_theme = "";
        foreach($dataTheme as $rowpips)
        {
            $dataNum = $pdf->LoadDtaWithTheme ($id, $rowpips[1]);

            $dataAll[] = $dataNum;
            $pdfNumpage->PrintChapter($j,$rowpips[0],$titre_theme,$dataNum,$id, $id_societe);

            $j++;
        }
        /*fin*/
        $nb_page = $pdfNumpage->getNbPage($id);
        $i = 1;

        $x = 0;
        foreach($dataTheme as $row)
        {
            $societe = $row[3];
            if($societe == "FORMA2+"){
                $societe = "général";
            }
            $titre_theme = $row[0];


            $pdf->PrintChapter($i,$row[0],$titre_theme,$dataAll[$x],$societe,$nb_page);
            $i++;
            $x++;
        }

        return $this->render('FormationVocabulaireBundle:Impression:impressionCorpsGlossaire.html.twig', array(

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
        $id = intval($id);
        $id_societe = intval($id_societe);
        $cnx = $this->getDoctrine()->getManager()->getConnection();
        $ptte = new Propriete($cnx);

        $prototypeTitle = $ptte->getPrototypeTitle($id);
        $prototypeTitle = strtoupper($prototypeTitle);
        $pdf = new PDF($cnx);
        $nom_societe = $pdf->getClient($id_societe);
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
