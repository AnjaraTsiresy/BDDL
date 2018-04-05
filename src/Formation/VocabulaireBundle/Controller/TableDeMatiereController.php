<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Model\PDF1;
use Formation\VocabulaireBundle\Model\PDFPage1;
use Formation\VocabulaireBundle\Model\Propriete;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TableDeMatiereController extends Controller
{

    private function fetch($query)
    {
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($query);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

    private function convert_utf8( $str ) {

        if (strpos($str, 'é') !== false) {
            return $str;
        }
        if (strpos($str, 'è') !== false) {
            return $str;
        }
        if (strpos($str, 'à') !== false) {
            return $str;
        }
        if (strpos($str, 'ç') !== false) {
            return $str;
        }
        if (strpos($str, 'î') !== false) {
            return $str;
        }
        if (strpos($str, 'ê') !== false) {
            return $str;
        }
        if (strpos($str, 'ë') !== false) {
            return $str;
        }
        if (strpos($str, 'â') !== false) {
            return $str;
        }
        if (strpos($str, 'ï') !== false) {
            return $str;
        }
        if (strpos($str, 'ô') !== false) {
            return $str;
        }
        if (strpos($str, 'ù') !== false) {
            return $str;
        }
        if (strpos($str, 'û') !== false) {
            return $str;
        }
        if (strpos($str, '’') !== false) {
            return $str;
        }
        if (strpos($str, '”') !== false) {
            return $str;
        }
        if (strpos($str, '“') !== false) {
            return $str;
        }
        if (strpos($str, 'É') !== false) {
            return $str;
        }
        if (strpos($str, 'applications') !== false) {
            return $str;
        }
        if (strpos($str, 'intervenants') !== false) {
            return $str;
        }
        if (strpos($str, 'phase') !== false) {
            return $str;
        }

        $decoded = str_replace(" ", "/////", $str);
        $decoded = str_replace("â€™", "<<<<<<<<<<", $decoded);
        $decoded = str_replace("â€", "wwwwwwwwwwwwwwwwwww", $decoded);
        $decoded = str_replace("â€œ", "??", $decoded);
        $decoded = str_replace("â€¦â€", "++++", $decoded);
        $decoded = str_replace("â€¦", ">>>>>>>>>>>>>>>>>>>", $decoded);
        $decoded = str_replace("â€", "----", $decoded);
        $decoded = str_replace("Å“", "======", $decoded);
        $decoded = str_replace("Ã‰", "@@@@@@@@@@@@@@@@@@@@@@@@@@", $decoded);



        $decoded = mb_convert_encoding($decoded, 'ISO-8859-1', 'UTF-8');

        $decoded = str_replace("/////", " ", $decoded);
        $decoded = str_replace("??", "'", $decoded);
        $decoded = str_replace("wwwwwwwwwwwwwwwwwww", "'", $decoded);
        $decoded = str_replace("<<<<<<<<<<", "'", $decoded);
        $decoded = str_replace("----", "“", $decoded);
        $decoded = str_replace("++++", "…”", $decoded);
        $decoded = str_replace(">>>>>>>>>>>>>>>>>>>", "…", $decoded);
        $decoded = str_replace("======", "œ", $decoded);
        $decoded = str_replace("@@@@@@@@@@@@@@@@@@@@@@@@@@", "É", $decoded);

        return $decoded;
        // return $str;
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
     * @Route("/table_matiere/{id}/{id_societe}", name="table_matiere")
     */
    public function impressionTableMatiereAction($id,$id_societe)
    {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');

        $id = intval($id);
        $id_societe = intval($id_societe);
        $cnx = $this->getDoctrine()->getManager()->getConnection();
        $ptte = new Propriete($cnx);
        $id_format_edition = $ptte->getFormatEdition($id);
        $prototypeTitle = $ptte->getPrototypeTitle($id);
        $prototypeTitle = strtoupper($prototypeTitle);
        $pdf = new PDF1($cnx);
        $nom_societe = $pdf->getClient($id_societe);
        // Chargement des données
        $sqlvidage = "DELETE FROM `table_des_matieres_proto` WHERE id_societe='$id_societe' and No_prototype='$id'";
        $this->execute($sqlvidage);

        $sqlvidage1 = "DELETE FROM `nb_page` WHERE id_prototype_access='$id'";
        $this->execute($sqlvidage1);

        $dataTheme = $pdf->LoadDataTheme($id);
        /*population table*/
        $pdfNumpage = new PDFPage1($cnx);
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
        $sommaire_generique = "Termes generiques";
        // $sommaire_generique = strtoupper($sommaire_generique);
        $htme = "";

        $datagenerique = array();
        $datanongenerique = array();
        foreach($dataTheme as $row)
        {
            //$id_soc = $row[5];
            $id_soc = $row[3];
            if($id_societe == $id_soc){
                if ($htme!=$row[0]) {
                    $numpp = 0;
                    $sqlpp = 'select min(ordre_sous_theme) as numpge from table_des_matieres_proto where theme="' . $row[0]. '" and id_societe="' . $id_societe . '" and No_prototype="' . $id . '"';

                    $resulttheme = $this->fetch($sqlpp);
                  //  echo "<h1>".count($resulttheme)."</h1><br />";
                    foreach ($resulttheme as $rowpp)
                        {
                            //echo "<h1>".$sqlpp."</h1><br />";

                        $datagenerique[] = "<span style='float:left;'>".$this->convert_utf8($row[0]) ."</span><span class='pointpoint'>&nbsp; </span><span style='margin-right:60px;float:right;'>".$rowpp['numpge']."</span></br>";

                        }
                }
                $htme = $row[0];
            }
        }
       // die();

        $texteGenerique =  "<span style='float:left;'></span><span class='pointpoint'>&nbsp; </span><span style='margin-right:60px;float:right;'></span></br>";
        $texteGenerique = $texteGenerique."<span style='float:left;'><b>".$sommaire_generique."</b></span><span class='pointpoint'>&nbsp; </span><span style='margin-right:60px;float:right;'></span></br>";
        $texteGenerique = $texteGenerique."<span style='float:left;'></span><span class='pointpoint'>&nbsp; </span><span style='margin-right:60px;float:right;'></span></br>";

        foreach($dataTheme as $row)
        {
            //$id_soc = $row[5];
            $id_soc = $row[3];
            if($id_societe != $id_soc){
                if ($htme!=$row[0]){

                    $numpp = 0;

                    $sqlpp = 'select min(ordre_sous_theme) as numpge from table_des_matieres_proto where theme="'.$this->convert_utf8($row[0]).'" and id_societe="'.$id_societe.'" and No_prototype="'.$id.'"';
                    $resulttheme =  $this->fetch($sqlpp);
                    foreach ($resulttheme as $rowpp)
                        $datanongenerique[] = "<span style='float:left;'>".$this->convert_utf8($row[0]) ."</span><span class='pointpoint'>&nbsp; </span><span style='margin-right:60px;float:right;'>".$rowpp['numpge']."</span></br>";
                }
                $htme = $row[0];
            }

        }




        $countdatagenerique = count($datagenerique);
        $countdatanongenerique = count($datanongenerique);

        $tabletotal = array();

        foreach ($datagenerique as $texte){
            $tabletotal[] = $texte;
        }

        $nbreParTable = 8;
        $tablenbreParTable = array_chunk($tabletotal, $nbreParTable);
        $nbreTableparTable = count ($tablenbreParTable);
        $k = 1;
        $k2 = 1;
        for ($i = 0; $i< $nbreTableparTable; $i++){
            echo '<page size="A4">';
            echo "<div class='conteneur'>";
            echo "<div class='colonne1 tableMatiere'>";
            $tabmatiere = "<p style='margin-top:50px;font-size: 18pt;color: blue; font-style : italic;' >TABLE DES MATIERES</p><br/><br/>";
            echo $tabmatiere;
            if ($i==0){
                echo "<b>".$nom_societe."</b>";
                echo "<br/><br/>";
            }
            foreach ($tablenbreParTable[$i] as $table){
                echo $table."<br />";
            }
            echo "<div class='nomprototype1'>".$this->convert_utf8($prototypeTitle)."</div>";
            echo "</div>";
            echo "<div class='colonne2 tableMatiere'>";
            $tabmatiere = "<p style='margin-top:50px;font-size: 18pt;color: blue; font-style : italic;' >TABLE DES MATIERES</p><br/><br/>";
            echo $tabmatiere;
            if ($i==0){
                echo "<b>".$nom_societe."</b>";
                echo "<br/><br/>";
            }
            foreach ($tablenbreParTable[$i] as $table){
                echo $table."<br />";
            }
            echo "<div class='nomprototype2'>".$this->convert_utf8($prototypeTitle)."</div>";
            echo "</div>";
            echo "</div>";
            echo '</page>';
        }



//non generiques
        $tabletotal = array();
        foreach ($datanongenerique as $texte){
            $tabletotal[] = $texte;
        }
        $nbreParTable = 8;
        $tablenbreParTable = array_chunk($tabletotal, $nbreParTable);
        $nbreTableparTable = count ($tablenbreParTable);
        $k = 1;
        $k2 = 1;
        for ($i = 0; $i< $nbreTableparTable; $i++){
            echo '<page size="A4">';
            echo "<div class='conteneur'>";
            echo "<div class='colonne1 tableMatiere'>";
            $tabmatiere = "<p style='margin-top:50px;font-size: 18pt;color: blue; font-style : italic;' >TABLE DES MATIERES</p><br/><br/>";
            echo $tabmatiere;
            if ($i==0){
                //echo "<b>".$nom_societe."</b>";
                echo "<br/><br/>";
            }
            foreach ($tablenbreParTable[$i] as $table){
                echo $table."<br />";
            }
            echo "<div class='nomprototype1'>".$this->convert_utf8($prototypeTitle)."</div>";
            echo "</div>";
            echo "<div class='colonne2 tableMatiere'>";
            $tabmatiere = "<p style='margin-top:50px;font-size: 18pt;color: blue; font-style : italic;' >TABLE DES MATIERES</p><br/><br/>";
            echo $tabmatiere;
            if ($i==0){
                //echo "<b>".$nom_societe."</b>";
                echo "<br/><br/>";
            }
            foreach ($tablenbreParTable[$i] as $table){
                echo $table."<br />";
            }
            echo "<div class='nomprototype2'>".$this->convert_utf8($prototypeTitle)."</div>";
            echo "</div>";
            echo "</div>";
            echo '</page>';
        }
//fin non generique

        return $this->render('FormationVocabulaireBundle:Impression:impressionTableMatiere.html.twig', array(

        ));

    }


    /**
     * @Route("/impression_table_matiere/{id}/{id_societe}", name="impression_table_matiere")
     */

    public function impressionTableMatiere1Action($id, $id_societe) {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');
        $snappy = $this->get('knp_snappy.pdf');
        $snappy->getInternalGenerator()->setTimeout(1500);
        $id = intval($id);
        $id_societe = intval($id_societe);
        $cnx = $this->getDoctrine()->getManager()->getConnection();
        $ptte = new Propriete($cnx);
        $id_format_edition = $ptte->getFormatEdition($id);
        $prototypeTitle = $ptte->getPrototypeTitle($id);
        $prototypeTitle = strtoupper($prototypeTitle);
        $pdf = new PDF1($cnx);
        $nom_societe = $pdf->getClient($id_societe);
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
