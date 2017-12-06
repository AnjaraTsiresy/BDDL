<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\NbPage;
use Formation\VocabulaireBundle\Entity\TableDesMatieresProto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ImpressionController extends Controller
{
    /**
     * @Route("/impressionTableMatiere/{id}/{id_societe}", name="impressionTableMatiere")
     */
    public function impressionTableMatiereAction($id,$id_societe)
    {
        $snappy = $this->get('knp_snappy.pdf');
        $id = intval($id);
        $id_societe = intval($id_societe);

        $prototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id);

        $ptte = new Propriete();
        $prototypeTitle = $ptte->getPrototypeTitle($prototypeAccess);
        $prototypeTitle = mb_strtoupper($prototypeTitle, 'UTF-8');

       $pdf = new PDF();
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

                 $dataThemeResult = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TempPdfLoaddatatheme')->LoadDataTheme($id);

                $dataTheme = array();
                foreach ($dataThemeResult as $row)
                {
                    $lib = $row["lib"];
                    $idT = $row["idT"];
                    $description = $row["description"];
                    $id_societe = $row["id_societe"];
                    $dataTheme[] = array($lib, $idT,$description,$id_societe);
                }


                 $pdfNumpage = new PDFPage();
                 $j = 1;
                 $dataAll = array();
             foreach($dataTheme as $rowpips)
              {
                 // $dataNum = $pdf->LoadDtaWithTheme ($id, $rowpips['idT']);

                  $loadDtaWithThemes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess')->LoadDtaWithTheme($id, $rowpips['1']);
                  $dataNum = array();
                  foreach ($loadDtaWithThemes as $row)
                  {
                      $langue_origine = $row["langue_origine"];
                      $langue_traduction = $row["langue_traduction"];
                      $langue_origine_sans_modif = $row["langue_origine_sans_modif"];

                      $langue_origine = strtolower ( $langue_origine );
                      $dataNum[] = array($langue_origine, $langue_traduction, $langue_origine_sans_modif);
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

                  $nb_page = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:NbPage')->getNbPage($id);

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

        $tab_m1 = array();
        $tab_m2 = array();

        foreach($dataTheme as $row)
        {
            //$id_soc = $row[5];
            $id_soc = $row[3];
            if($id_societe == $id_soc){
                if ($htme!=$row[0]){

                    $numpge = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TableDesMatieresProto')->getMinOrdreSousTheme($row[0], $id_societe, $id);
                    $tableMatiereModel = new TableMatiereModel();
                    $tableMatiereModel->seTheme($row[0]);
                    $tableMatiereModel->setNbPage($numpge);
                    $table_matiere[] = $tableMatiereModel;
                    $tab_m1[] = "generique";

                }

                $htme = $row[0];
            }

            if($id_societe != $id_soc){
                if ($htme!=$row[3]){
                    $numpge = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:TableDesMatieresProto')->getMinOrdreSousTheme($row[0], $id_societe, $id);
                    $tableMatiereModel1 = new TableMatiereModel();
                    $tableMatiereModel1->seTheme($row[0]);
                    $tableMatiereModel1->setNbPage($numpge);
                    $table_matiere1[] = $tableMatiereModel1;
                    $tab_m2[] = "non generique";
                }
                $htme = $row[0];
            }
        }

        $tabletotal1 = array();

        foreach ($tab_m1 as $texte){
            $tabletotal1[] = $texte;
        }

        $tabletotal2 = array();

        foreach ($tab_m2 as $texte){
            $tabletotal2[] = $texte;
        }

        $nbreParTable = 8;
        $tablenbreParTable = array_chunk($tabletotal1, $nbreParTable);
        $nbreTableparTable = count ($tablenbreParTable);

        $tablenbreParTable1 = array_chunk($tabletotal2, $nbreParTable);
        $nbreTableparTable1 = count ($tablenbreParTable1);

      /*  return $this->render('FormationVocabulaireBundle:Impression:impressionTableMatiere.html.twig', array(
            'table_matiere'=>$table_matiere,
            'table_matiere1'=>$table_matiere1,
            'tablenbreParTable' => $tablenbreParTable,
            'nbreTableparTable' => $nbreTableparTable,
            'tablenbreParTable1' => $tablenbreParTable1,
            'nbreTableparTable1' => $nbreTableparTable1,
            'prototypeTitle' => $prototypeTitle,
            'nom_societe' => $nom_societe
        ));*/

        $html = $this->renderView('FormationVocabulaireBundle:Impression:impressionTableMatiere.html.twig', array(
            'table_matiere'=>$table_matiere,
            'table_matiere1'=>$table_matiere1,
            'tablenbreParTable' => $tablenbreParTable,
            'nbreTableparTable' => $nbreTableparTable,
            'tablenbreParTable1' => $tablenbreParTable1,
            'nbreTableparTable1' => $nbreTableparTable1,
            'prototypeTitle' => $prototypeTitle,
            'nom_societe' => $nom_societe
        ));

        $filename = 'IndexGlossaire'.$nom_societe.''.$prototypeTitle;

        return new Response(

            $snappy->getOutputFromHtml($html),
            200,
            array(

                'Content-Type'          => 'application/pdf',

                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"',


            )

        );

    }

    /**
     * @Route("/impressionCorpsGlossaire/{id}/{id_societe}", name="impressionCorpsGlossaire")
     */
    public function impressionCorpsGlossaireAction($id,$id_societe)
    {
        return $this->render('FormationVocabulaireBundle:Impression:impressionCorpsGlossaire.html.twig', array(
            'id' => $id,
            'id_societe' => $id_societe
        ));
    }

}

class TableMatiereModel
{
    private $theme;
    private $nbPage;

    public function  theme()
    {
        return $this->theme;
    }

    public function  seTheme($theme)
    {
        $this->theme = $theme;
    }

    public function  nbPage()
    {
        return $this->nbPage;
    }

    public function  setNbPage($nbPage)
    {
        $this->nbPage = $nbPage;
    }
}

class PDF
{
    // Chargement des données
    public $theme = "";
    public $dataS = array();
    public $numpage = 0;
    private $dataTheme;

    public function setLoadDataTheme($dataTheme)
    {
        $this->dataTheme = $dataTheme;
    }
    // Chargement des données
    function LoadDataTheme() {
        return $this->dataTheme;
    }

    function mb_ucfirst( $stringMaj )
    {

        $stringMaj = explode(" ", $stringMaj);
        $stringMaj = str_replace(
            array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
            array('E', 'E', 'E', 'E', 'A', 'A', 'I', 'I', 'O', 'U', 'U'),
            $stringMaj
        );
        return mb_strtoupper(mb_substr( $stringMaj, 0, 1 )).mb_substr( $stringMaj, 1 );
    }

    function strtoupperFrFirst($string) {
        $keywords = array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û');
        $string1 = $string[0];
        $hasString = true;
        $string = stripslashes(utf8_decode($string));
        $stringMaj = strtoupper($string);
        $stringMaj = str_replace(
            array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
            array('E', 'E', 'E', 'E', 'A', 'A', 'I', 'I', 'O', 'U', 'U'),
            $stringMaj
        );
        $premierLettre =  substr($stringMaj[0],0,1);
        $nombre_caract_a_suppr=1;
        $chaine=substr($string,-strlen($string)+$nombre_caract_a_suppr);
        $string =stripslashes($premierLettre.$chaine) ;
        $string = stripslashes(utf8_decode($string));
        return $string;
    }


    function trim_ucfirst_strtolower_utf8($stri) {
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

    function LoadDtaWithTheme ($id, $id_theme){
        return 0;
    }

    function PrintChapter($num, $theme, $title,$data,$societe,$nb_page)
    {
        $this->BasicTable($data, $title, $num, $theme,$societe,$nb_page);
    }

    function array_insert(&$array, $position, $insert)
    {
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos   = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }

    function nombreIsP1($tab){
        $isanyInterne = $i * $nbreParPage;
        $k = ($i-1)*$nbreParPage;
        $tabNbrePartieALaliggne = array();
        $totalNbrecaractData = 0;
        for ($j = $k; $j < $isanyInterne; $j ++){
            $kj = 1;
            $string = "";
            foreach ($data[$j] as $coco){
                $string = $string."".$coco;
                $kj ++;
            }
            $nbreCaractere = strlen($string);
            $totalNbrecaractData = $totalNbrecaractData + $nbreCaractere;
            //$fixeNbreCaractreLigne = 98;
            $fixeNbreCaractreLigne = 70;
            $partieEntiere = (int)($nbreCaractere/$fixeNbreCaractreLigne);
            $tabNbrePartieALaliggne[] = $partieEntiere;

            $tab1[] = $data[$j];
        }

        $i1 = $i;
        $isVoaloany = 0;


        $isP1 = 0;

        foreach ($tabNbrePartieALaliggne as $isaPart1){
            if ($isaPart1 != 0 && $isaPart1> 1){
                $isP1  = $isP1+$isaPart1 ;
            }
        }
        return $isP1;
    }

    // Tableau simple
    function BasicTable($data, $title, $num, $theme,$societe,$nb_page){

        $isany = count ($data);
        //echo "isany=".$isany;
        $nbreParPage = 16;
        $reste = 0;

        if ($isany > $nbreParPage){
            $modulo = $isany%$nbreParPage;
            $quotient = (int)($isany/$nbreParPage);
            if ($modulo != 0){
                $quotient = $quotient+1;
            }

            $tab1ivelany = array();
            $tab2ivelany = array();
            $tabSurplusivelany = array();

            for ($i = 1; $i<= $quotient; $i++){
                $tab1 = array();
                $tab2 = array();
                $isanyInterne = $i * $nbreParPage;
                $k = ($i-1)*$nbreParPage;
                $tabNbrePartieALaliggne = array();
                $this->numpage = $this->numpage+1;
                $totalNbrecaractData = 0;
                for ($j = $k; $j < $isanyInterne; $j ++){
                    $kj = 1;
                    $string = "";
                    foreach ($data[$j] as $coco){
                        $string = $string."".$coco;
                        $kj ++;
                    }
                    $nbreCaractere = strlen($string);
                    $totalNbrecaractData = $totalNbrecaractData + $nbreCaractere;
                    //$fixeNbreCaractreLigne = 98;
                    $fixeNbreCaractreLigne = 70;
                    $partieEntiere = (int)($nbreCaractere/$fixeNbreCaractreLigne);
                    $tabNbrePartieALaliggne[] = $partieEntiere;

                    $tab1[] = $data[$j];
                }
                //print_r($tabNbrePartieALaliggne);
                if ($i==1){
                    echo '<page size="A4">';
                    echo "<div class='conteneur'>";
                    echo "<div class='colonne1'>";
                    echo "<span style='position:absolute;
						font-size: 10pt;
						font-weight: bold;
					    left: 35%;
					    top: 50%;
					    width: 200px;
					    height: 200px;
					   	margin-left: 0;
						margin-top: 0; '>$title</span>";
                    //$this->numpage = $this->numpage +1;
                    echo "<div class='numpage1'>".$this->numpage."/".$nb_page."</div>";
                    echo '</div>';
                    echo "<div class='colonne2'>";
                    echo "<span style='position:absolute;
					font-size: 10pt;
					font-weight: bold;
					left: 35%;
					top: 50%;
					width: 200px;
					height: 200px;
					margin-left: 0;
					margin-top: 0; '>$title</span>";
                    echo "<div class='numpage2'>".$this->numpage."/".$nb_page."</div>";
                    //echo "<p style='font-size: 9.5pt;color: blue; margin : 0 auto;' >$title</p>";
                    echo '</div>';
                    echo '</div>';
                    echo '</page>';
                    $this->numpage = $this->numpage +1;
                }
                echo '<page size="A4">';
                echo "<div class='conteneur'>";
                echo "<div class='colonne1'>";
                $i1 = $i;
                $isVoaloany = 0;

                if ($i==1){
                    echo "<p style='font-size: 9.5pt;color: blue; margin-right : 10mm;' >$title</p>";

                    $elementsuprim1 = array_pop($tab1);
                    $tabAllSuppr[]  = $elementsuprim1;

                    $isVoaloany = 1;
                }
                $isP1 = 0;

                foreach ($tabNbrePartieALaliggne as $isaPart1){
                    if ($isaPart1 != 0 && $isaPart1> 1){
                        $isP1  = $isP1+$isaPart1 ;
                    }
                }

                $nbreMaj1 = $this->colonnedataNombre($tab1);
                $nbreMajintial1 = $nbreMaj1;

                $tabAllSuppr = array();

                /* Manomboka eto */


                $valeur = $isP1;

                if ($isP1 >=5){
                    //$valeur = 5;
                    $valeur = 4;
                    if($nbreMaj1 <= 3 && $totalNbrecaractData >= 1500 ){
                        $valeur = 2;
                    }
                    if($nbreMaj1 < 2 && $totalNbrecaractData > 1600 ){
                        $valeur = 3;
                    }
                }
                //Nbre Maj = 6 et Nbre Maj After = 5 Total caract : 1096 isP1 = 2

                if ($isP1 >=2 && $isP1 <=3){
                    if($nbreMaj1 >= 3 && $totalNbrecaractData >= 1050 ){
                        $valeur = 5;
                    }else if ($nbreMaj1 >= 5 && $totalNbrecaractData >= 1000 ){
                        $valeur = 5;
                    }else if($nbreMaj1 < 3 && $totalNbrecaractData >= 1425 ){
                        $valeur = 5;
                    }
                }

                if ($isP1 == 0){
                    if($nbreMaj1 <= 3 && $totalNbrecaractData < 600 ){
                        $valeur = $valeur + 1;
                    }
                    else if($nbreMaj1 > 3 && $nbreMaj1 <7 && $totalNbrecaractData < 600 ){
                        $valeur = $valeur + 2;
                    }
                    else if($nbreMaj1 >= 7 && $totalNbrecaractData < 600 ){
                        $valeur = $valeur + 4;
                    }
                    else if($nbreMaj1 <= 3 && $totalNbrecaractData >= 600 && $totalNbrecaractData < 850 ){
                        if ($nbreMaj1 < 3){
                            $valeur = $valeur + 2;
                        }
                        else {
                            $valeur = $valeur + 3;
                        }
                    }

                    else if($nbreMaj1 > 3 && $nbreMaj1 <=5 && $totalNbrecaractData >= 600 && $totalNbrecaractData < 850 ){
                        $valeur = $valeur + 3;
                    }
                    else if($nbreMaj1 > 5 && $totalNbrecaractData >= 600 && $totalNbrecaractData < 850 ){
                        $valeur = $valeur + 4;
                    }

                    else if($nbreMaj1 <= 2 && $totalNbrecaractData >= 850 && $totalNbrecaractData < 1250 ){
                        $valeur = $valeur + 2;
                    }
                    else if($nbreMaj1 == 3 && $totalNbrecaractData >= 850 && $totalNbrecaractData < 1001 ){
                        $valeur = $valeur + 3;
                    }
                    else if($nbreMaj1 == 3 && $totalNbrecaractData >= 1001 && $totalNbrecaractData < 1250 ){
                        $valeur = $valeur + 4;
                    }
                    else if($nbreMaj1 > 3 && $totalNbrecaractData >= 850 && $totalNbrecaractData < 1250 ){
                        if ($nbreMaj1 >=6){
                            $valeur = $valeur + 5;
                        }
                        else {
                            $valeur = $valeur + 4;
                        }
                    }
                    else if($nbreMaj1 <= 3 && $totalNbrecaractData >= 1250  ){
                        $valeur = $valeur + 4;
                    }
                    else if($nbreMaj1 > 3 && $totalNbrecaractData >= 1250 ){
                        $valeur = $valeur + 5;
                    }

                }

                for ($im1 = 0; $im1 < $valeur; $im1++){
                    $elementsuprim1 = array_pop($tab1);
                    $tabAllSuppr[]  = $elementsuprim1;
                }

                /* Mifarana eto */
                $nbreMajAfter1 = $this->colonnedataNombre($tab1);

                $totalNbrecaractDataAfter = 0;
                $nbrtab = count($tab1);

                foreach ($tab1 as $tabtab1){
                    $totalNbrecaractDataAfter = $totalNbrecaractDataAfter+$this->colonnedataNombreCaract($tabtab1);
                }

                //echo "Mija Nbre Maj = $nbreMajintial1 et Nbre Maj After = $nbreMajAfter1 tot caract after :$totalNbrecaractDataAfter Total caract : $totalNbrecaractData  isP1 = $isP1<br />";

                $valeur = 0;
                if($nbreMaj1 >= 2 && $nbreMajAfter1<=$nbreMaj1 && $totalNbrecaractData >= 1600 && $isP1 >= 16  ){
                    if ($totalNbrecaractDataAfter>=1300){
                        $valeur++;
                    }

                }
                //Valeur aprés
                if ($isP1 == 4 && $totalNbrecaractData > 1500 && $totalNbrecaractData < 1550){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if($isP1 <= 4 && $nbreMajAfter1 <= $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                    if($totalNbrecaractDataAfter >= 1000 && $totalNbrecaractDataAfter < 1050){
                        $valeur = $valeur + 2;
                    }else if($totalNbrecaractDataAfter > 1050 && $totalNbrecaractDataAfter < 1100){
                        $valeur = $valeur + 2;
                    }else if($totalNbrecaractDataAfter >= 1250){
                        $valeur = $valeur + 1;
                    }
                }
                if ($isP1 == 2 && $totalNbrecaractData > 1560 && $totalNbrecaractData < 1605){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 2 && $totalNbrecaractData > 1360 && $totalNbrecaractData < 1430){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 2 && $totalNbrecaractData > 1425 && $totalNbrecaractData < 1470){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 1 && $totalNbrecaractData > 1390 && $totalNbrecaractData < 1425){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 1;
                    }
                }
                if ($isP1 <= 2 && $nbreMajAfter1 < $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData && $totalNbrecaractDataAfter >= 550 && $totalNbrecaractDataAfter < 691){
                    $valeur = $valeur - 2;
                }
                else{
                    $valeur = $valeur - 1;
                }
                if ($isP1 <= 1 && $totalNbrecaractData > 570 && $totalNbrecaractData < 700){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur - 1;
                    }
                }
                if ($isP1 <= 1 && $nbreMajAfter1 < $nbreMaj1 && $totalNbrecaractData > 800 && $totalNbrecaractData < 950){
                    $valeur = $valeur - 2;
                }
                if ($isP1 == 8 && $totalNbrecaractData > 1590 && $totalNbrecaractData < 1640){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 8 && $totalNbrecaractData > 1000 && $totalNbrecaractData < 1200){
                    if($nbreMajAfter1 <= $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 >= 8 && $totalNbrecaractData > 1100 && $totalNbrecaractData < 2000){
                    if($nbreMajAfter1 <= $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData && $totalNbrecaractData > 1700){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 950 && $totalNbrecaractData < 1040){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 1;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1170 && $totalNbrecaractData < 1250){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 4 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1100 && $totalNbrecaractData < 1210){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1460 && $totalNbrecaractData < 1499){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1450 && $totalNbrecaractData < 1520){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1000 && $totalNbrecaractData < 1300){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 4 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 1020 && $totalNbrecaractData < 1075){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 950 && $totalNbrecaractData < 1000){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 800 && $totalNbrecaractData < 860){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 10 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 6;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 580 && $totalNbrecaractData < 640){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 10 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 530 && $totalNbrecaractData < 610){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 890 && $totalNbrecaractData < 1010){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 1;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 800 && $totalNbrecaractData < 900){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur - 1;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 800 && $totalNbrecaractData < 890){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 9 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 6;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 750 && $totalNbrecaractData < 850){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 720 && $totalNbrecaractData < 780){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 8 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 680 && $totalNbrecaractData < 750){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 9 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1400 && $totalNbrecaractData < 1450){
                    if ($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 6 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1175 && $totalNbrecaractData < 1235){
                    if ($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 700 && $totalNbrecaractData < 740){
                    if ($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                        $valeur = $valeur - 2;
                    }
                }
                if ($isP1 == 4 && $totalNbrecaractData > 1400 && $totalNbrecaractData < 1540){
                    if($nbreMajAfter1 == $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }else if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 4 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 6 && $totalNbrecaractData > 1640 && $totalNbrecaractData < 1690){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 4;
                    }
                }
                if ($isP1 == 6 && $totalNbrecaractData > 1560 && $totalNbrecaractData < 1650){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 4;
                    }
                }
                if ($isP1 == 6 && $totalNbrecaractData > 1400 && $totalNbrecaractData < 1460){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 6 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 4;
                    }
                }
                if ($isP1 == 4 && $totalNbrecaractData > 1550 && $totalNbrecaractData < 1690){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 4 && $totalNbrecaractData > 1285 && $totalNbrecaractData < 1335){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 10 && $totalNbrecaractData > 1600 && $totalNbrecaractData < 1650){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 4 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 4;
                    }
                }
                if ($isP1 == 11 && $totalNbrecaractData > 1250 && $totalNbrecaractData < 1330){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 11 && $totalNbrecaractData > 1300 && $totalNbrecaractData < 1380){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 12 && $totalNbrecaractData > 1320 && $totalNbrecaractData < 1400){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 5 && $totalNbrecaractData > 1640 && $totalNbrecaractData < 1680){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 7 && $totalNbrecaractData > 1300 && $totalNbrecaractData < 2000){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }else if ($isP1 >= 8 && $totalNbrecaractData >= 2000 ){
                    if ($totalNbrecaractDataAfter>=1100 ){
                        $valeur++;
                    }
                }
                if ($isP1 == 14 && $totalNbrecaractData > 1800 && $totalNbrecaractData < 1860){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 14 && $totalNbrecaractData > 1830 && $totalNbrecaractData < 1880){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 16 && $totalNbrecaractData > 1650 && $totalNbrecaractData < 1750){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 5;
                    }
                }
                for ($im1 = 0; $im1 < $valeur; $im1++){
                    $elementsuprim1 = array_pop($tab1);
                    $tabAllSuppr[]  = $elementsuprim1;
                }

                $tabaff2 = array();
                $tab2 = $tab1;
                $nbreMaj1 = $this->colonnedata($tab1);

                echo "<div class='numpage1'>".$this->numpage."/".$nb_page."</div>";
                echo "</div>";
                echo "<div class='colonne2'>";
                if ($i1==1){
                    echo "<p style='font-size: 9.5pt;color: blue; margin-right : 10mm;' >$title</p>";
                }
                $nbreMaj2 = $this->colonnedata($tab2);
                echo "<div class='numpage2'>".$this->numpage."/".$nb_page."</div>";
                echo "</div>";
                /*differnce surplus données */
                //$dataSurplus = array_reverse($tabAllSuppr);
                $dataSurplus = $tabAllSuppr;
                foreach ($dataSurplus as $plus){
                    array_unshift($data, $plus);
                }
                $reste = $reste + count($tab1);
                /*differnce surplus données */
                echo "</div>";
                echo '</page>';

                if ($i==$quotient){
                    /***debut surplus quotient ***/
                    //echo "Mande anty mimikely";
                    $isaSurplusQuotient = count ($dataSurplus);
                    if($isaSurplusQuotient > 0){
                        $tabdatadernier = array();
                        $i1 = $i;
                        $dataSurplus = array_reverse($dataSurplus);
                        $tabdatadernier = $dataSurplus;



                        $reste = $reste + count($dataSurplus);

                        $tabSurplusivelany = $data;

                        $dataSurplus = $tabSurplusivelany;

                        $isaSurplusQuotient = count ($dataSurplus);

                        $reste = $isany - $reste ;

                        $dataFarany = array_reverse($dataSurplus);

                        $dataReste = array();
                        for ($initial = 0; $initial < $reste; $initial++){
                            $elementsuprim1 = array_pop($dataSurplus);
                            $dataReste[]  = $elementsuprim1;
                        }

                        $dataReste = array_reverse($dataReste);
                        $tabdatadernier = array_reverse($tabdatadernier);

                        foreach ($tabdatadernier as $value){
                            array_unshift($dataReste, $value);
                        }


                        $isareste = count($dataReste);


                        if($isareste > 0){
                            //echo "<br/>Mijakely isareste" ;
                            $reteNbrepage = $nbreParPage - 4;
                            if ($isareste>$reteNbrepage){
                                $tabSeparate = array_chunk($dataReste, $reteNbrepage);
                                $tabAllSupprMimi = array();
                                foreach ( $tabSeparate as $tabkely ){
                                    $string = "";
                                    $nbreretire = count($tabAllSupprMimi);

                                    if ($nbreretire>0){
                                        foreach ($tabAllSupprMimi as $plus){
                                            array_unshift($tabkely, $plus);
                                            unset($tabAllSupprMimi[array_search($plus, $tabAllSupprMimi)]);
                                        }
                                    }

                                    foreach ($tabkely as $coco){
                                        foreach ($coco as $coco1){
                                            $string = $string."".$coco1;
                                        }
                                    }
                                    $nbreCara = strlen($string);
                                    $inc =1;
                                    if ($nbreCara>1050){
                                        $limite = $nbreretire + 3;
                                        for ($im1 = 0; $im1 < $limite; $im1++){
                                            $elementsuprim1 = array_pop($tabkely);
                                            $tabAllSupprMimi[]  = $elementsuprim1;
                                            $inc ++;
                                        }
                                    }
                                    else {

                                    }

                                    //echo "<br><b>$reteNbrepage : $nbreretire : $inc</b><br>";

                                    $nbreMajFin = $this->colonnedataNombre($tabAllSupprMimi);
                                    //echo "$string <br> nbreCaractere : $nbreCara -- nbreMaj : $nbreMajFin <br>";
                                    echo '<page size="A4">';
                                    echo "<div class='conteneur'>";
                                    echo "<div class='colonne1'>";
                                    $i1 = $i;
                                    $nbreMaj1 = $this->colonnedata($tabkely);
                                    $this->numpage = $this->numpage +1;
                                    echo "<div class='numpage1'>".$this->numpage."/".$nb_page."</div>";
                                    echo "</div>";
                                    echo "<div class='colonne2'>";
                                    $nbreMaj2 = $this->colonnedata($tabkely);
                                    echo "<div class='numpage2'>".$this->numpage."/".$nb_page."</div>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo '</page>';

                                }
                            }
                            else{
                                //echo "<br/>--debut TsMijakely isareste $reteNbrepage :: $nbreParPage" ;
                                //echo "<br><b>$reteNbrepage : $nbreretire : $inc</b><br>";

                                $nbreMajFin = $this->colonnedataNombre($dataReste);
                                //echo "$string <br> nbreCaractere : $nbreCara -- nbreMaj : $nbreMajFin <br>";
                                echo '<page size="A4">';
                                echo "<div class='conteneur'>";
                                echo "<div class='colonne1'>";
                                $i1 = $i;
                                $nbreMaj1 = $this->colonnedata($dataReste);
                                $this->numpage = $this->numpage +1;
                                echo "<div class='numpage1'>".$this->numpage."/".$nb_page."</div>";
                                echo "</div>";
                                echo "<div class='colonne2'>";
                                $nbreMaj2 = $this->colonnedata($dataReste);
                                echo "<div class='numpage2'>".$this->numpage."/".$nb_page."</div>";
                                echo "</div>";
                                echo "</div>";
                                echo '</page>';

                                //echo "<br/>--fin TsMijakely isareste" ;
                            }
                        }

                    }
                    /***fin surplus***/
                }
                else {

                    $isaSurplusQuotient = count ($dataSurplus);
                    if($isaSurplusQuotient > 0){

                        $i1 = $i;
                        $dataSurplus = array_reverse($dataSurplus);

                        foreach ($dataSurplus as $plus){
                            array_unshift($data, $plus);
                        }
                    }
                    $nbre = count ($dataSurplus);
                    $k = 0;
                    if ($isVoaloany==1){
                        $k = 1;
                    }

                    $tabdernier = array();

                    foreach ($dataSurplus as $plus){
                        if ($k!=$nbre){
                            if ($plus!="" || $plus!=" "){
                                $tabdernier = $plus;
                                $elementsuprim1 = array_shift($data);
                            }
                        }
                        $k++;
                    }
                    /***fin surplus***/
                }

            }


        }else{
            $i = 1;
            $nbreData = count($data);
            $nbreMajS = $this->colonnedataNombre($data);
            $dataSurplus = array();
            if($nbreMajS >=6 ){
                $nbrSurplus = $nbreMajS - 5;
                for ($t=0; $t<$nbrSurplus; $t++){
                    $elementsuprim = array_pop($data);
                    $dataSurplus[]  = $elementsuprim;
                }
            }
            $tab1 = array();
            $tab2 = array();
            $isanyInterne = $i * $nbreParPage;
            $k = ($i-1)*$nbreParPage;
            $tabNbrePartieALaliggne = array();
            $this->numpage = $this->numpage+1;
            $totalNbrecaractData = 0;
            for ($j = $k; $j < $isanyInterne; $j ++){
                $kj = 1;
                $string = "";
                foreach ($data[$j] as $coco){
                    $string = $string."".$coco;
                    $kj ++;
                }
                $nbreCaractere = strlen($string);
                $totalNbrecaractData = $totalNbrecaractData + $nbreCaractere;
                $fixeNbreCaractreLigne = 98;
                $partieEntiere = (int)($nbreCaractere/$fixeNbreCaractreLigne);
                $tabNbrePartieALaliggne[] = $partieEntiere;
                $tab1[] = $data[$j];
            }
            echo '<page size="A4">';
            echo "<div class='conteneur'>";
            echo "<div class='colonne1'> " ;
            $i1 = $i;
            if ($i==1){
                $elementsuprim1 = array_pop($tab1);
                $dataSurplus[]  = $elementsuprim1;
                echo "<p style='font-size: 9.5pt;color: blue; margin-right : 10mm;' >$title</p>";
            }
            $isP1 = 0;
            foreach ($tabNbrePartieALaliggne as $isaPart1){
                if ($isaPart1 != 0 && $isaPart1> 1){
                    $isP1  = $isP1+$isaPart1 ;
                }
            }
            for ($im1 = 0; $im1 < $isP1; $im1++){
                $elementsuprim1 = array_pop($tab1);
                $dataSurplus[]  = $elementsuprim;
            }

            $nbreMaj1 = $this->colonnedataNombre($tab1);
            $tabaff2 = array();

            //echo "Else Nbre Maj = $nbreMaj1 Total caract : $totalNbrecaractData  isP1 = $isP1<br />";

            if ($nbreMaj1>=4 && $nbreMaj1<6){
                if($totalNbrecaractData >850 && isP1 <= 3){
                    //echo "ato ian ty 1 tokony manala";
                    for ($ij1= 0; $ij1 < 4; $ij1++){
                        $elementsuprim1 = array_pop($tab1);
                        $dataSurplus[]  = $elementsuprim1;
                    }
                }
                else if($totalNbrecaractData >850 && isP1 >3){
                    for ($ij1= 0; $ij1 < 5; $ij1++){
                        $elementsuprim1 = array_pop($tab1);
                        $dataSurplus[]  = $elementsuprim1;
                    }
                }
            }

            else if ($nbreMaj1 >= 6){
                $max1 = 0;
                if ($totalNbrecaractData <=864 ){
                    $max1 = $nbreMaj1 - 7;
                }
                else {
                    $max1 = $nbreMaj1 - 6;
                }
                for ($ij1= 0; $ij1 < $max1; $ij1++){
                    $elementsuprim1 = array_pop($tab1);
                    $dataSurplus[]  = $elementsuprim1;
                }
            }

            $tab2=$tab1;

            $nbreMaj1 = $this->colonnedata($tab1);



            echo "<div class='numpage1'>".$this->numpage."/".$nb_page."</div>";
            echo "</div>";
            echo "<div class='colonne2'>";
            if ($i1==1){
                echo "<p style='font-size: 9.5pt;color: blue; margin-right : 10mm;' >$title</p>";
            }

            $nbreMaj2 = $this->colonnedata($tab2);
            echo "<div class='numpage2'>".$this->numpage."/".$nb_page."</div>";
            echo "</div>";
            echo "</div>";
            echo '</page>';

            /***debut surplus***/
            if($nbreMajS >6 ){
                echo '<page size="A4">';
                echo "<div class='conteneur'>";
                echo "<div class='colonne1'>";
                $i1 = $i;
                $dataSurplus = array_reverse($dataSurplus);
                $nbreMaj1 = $this->colonnedata($dataSurplus);
                $this->numpage = $this->numpage +1;
                echo "<div class='numpage1'>".$this->numpage."/".$nb_page."</div>";
                echo "</div>";
                echo "<div class='colonne2'>";
                $nbreMaj2 = $this->colonnedata($dataSurplus);
                echo "<div class='numpage2'>".$this->numpage."/".$nb_page."</div>";
                echo "</div>";
                echo "</div>";
                echo '</page>';
            }
            /***fin surplus***/
        }

    }

    function colonnedataNombre($data){
        $nbreMajuscule = 0;
        $tab = array();
        $initiallettre = "";
        foreach($data as $row)
        {
            $premierLettre = substr($row[0],0,1);

            if ($premierLettre!=$initiallettre){
                $premierLettre = $premierLettre;
                $premierLettre = stripslashes(utf8_decode($premierLettre));

                $maj = strtoupper($premierLettre);
                $nbreMajuscule = $nbreMajuscule + 1;
                $tab[] = $maj;
            }
            $initiallettre = $premierLettre;

        }
        return $nbreMajuscule;
    }


    function colonnedataNombreCaract($data){
        $nbreCaract = 0;
        $tab = array();
        foreach($data as $row)
        {
            $nbre = strlen($row);
            $nbreCaract = $nbreCaract + $nbre;

        }
        return $nbreCaract;
    }

    function colonnedata($data){
        $nbreMajuscule = 0;
        $tab = array();
        $initiallettre = "";
        echo "<table>";
        foreach($data as $row)
        {
           $premierLettre = substr($row[0],0,1);

            if ($premierLettre!=$initiallettre){
                $premierLettre = $premierLettre;
                $premierLettre = stripslashes(utf8_decode($premierLettre));

                $maj = strtoupper($premierLettre);
                $nbreMajuscule = $nbreMajuscule + 1;
                $tab[] = $maj;
                if($maj != ""){
                    echo "<tr><td colspan='2' style='padding-top:2mm;'><b>$maj</b></td></tr>";
                }else{
                    echo "<tr><td colspan='2'></td></tr>";
                }
            }
            $i=1;
            echo "<tr>";
            foreach($row as $col){
                $col = stripslashes(html_entity_decode($col));
                if ($i%2){
                    if($i == 1){
                        if($row[2] != ""){
                            echo "<td style='width:70mm; padding-top:2mm; vertical-align:top;'><b>$row[2]</b></td>";
                        }else{
                            echo "<td style='width:70mm; padding-top:2mm; vertical-align:top;'><b>".$row["titre_theme"]."</b></td>";
                        }
                    }
                }
                else {
                    if($col != ""){
                        echo "<td style='width:70mm; padding-top:2mm; vertical-align:top;'><i>$col</i></td>";
                    }else{
                        echo "<td></td>";
                    }

                }
                $i++;
            }
            echo "</tr>";
            $initiallettre = $premierLettre;

        }
        echo "</table>";
        return $nbreMajuscule;
        //return $tab;
    }

    function getClient($societe_obj)
    {
        $societe = "Aucun";
        if($societe_obj != null)
            $societe = $societe_obj->getDescription();
        return $societe;
    }
}

class PDFPage
{
    // Chargement des données
    public $theme = "";
    public $dataS = array();
    public $numpage = 0;


    function PrintChapter($data,$theme, $id_societe, $id)
    {

        $this->BasicTable($data,$theme, $id_societe, $id);
    }

    function colonnedataNombreCaract($data){
        $nbreCaract = 0;
        $tab = array();
        foreach($data as $row)
        {
            $nbre = strlen($row);
            $nbreCaract = $nbreCaract + $nbre;

        }
        return $nbreCaract;
    }
    function numero()
    {
        return $this->numpage +1;
    }
    // tstoto
    function BasicTable($data,$theme, $id_societe, $id){

        $numero = $this->numpage +1;
        $theme = htmlspecialchars($theme);
        $nb_page = 0;

        $isany = count ($data);
        $nbreParPage = 16;
        $reste = 0;

        if ($isany > $nbreParPage){
            $modulo = $isany%$nbreParPage;
            $quotient = (int)($isany/$nbreParPage);
            if ($modulo != 0){
                $quotient = $quotient+1;
            }

            $tab1ivelany = array();
            $tab2ivelany = array();
            $tabSurplusivelany = array();

            for ($i = 1; $i<= $quotient; $i++){
                $tab1 = array();
                $tab2 = array();
                $isanyInterne = $i * $nbreParPage;
                $k = ($i-1)*$nbreParPage;
                $tabNbrePartieALaliggne = array();
                $this->numpage = $this->numpage+1;
                $totalNbrecaractData = 0;
                for ($j = $k; $j < $isanyInterne; $j ++){
                    $kj = 1;
                    $string = "";

                    foreach ($data[$j] as $coco){
                        $string = $string."".$coco;
                        $kj ++;
                    }
                    $nbreCaractere = strlen($string);
                    $totalNbrecaractData = $totalNbrecaractData + $nbreCaractere;
                    //$fixeNbreCaractreLigne = 98;
                    $fixeNbreCaractreLigne = 70;
                    $partieEntiere = (int)($nbreCaractere/$fixeNbreCaractreLigne);
                    $tabNbrePartieALaliggne[] = $partieEntiere;

                    $tab1[] = $data[$j];
                }
                //print_r($tabNbrePartieALaliggne);
                if ($i==1){
                    //$this->numpage = $this->numpage +1;
                    //echo "<p style='font-size: 9.5pt;color: blue; margin : 0 auto;' >$title</p>";
                    $this->numpage = $this->numpage +1;
                }
                $i1 = $i;
                $isVoaloany = 0;

                if ($i==1){
                    //echo "<p style='font-size: 9.5pt;color: blue; margin-right : 10mm;' >$title</p>";

                    $elementsuprim1 = array_pop($tab1);
                    $tabAllSuppr[]  = $elementsuprim1;

                    $isVoaloany = 1;
                }
                $isP1 = 0;

                foreach ($tabNbrePartieALaliggne as $isaPart1){
                    if ($isaPart1 != 0 && $isaPart1> 1){
                        $isP1  = $isP1+$isaPart1 ;
                    }
                }

                $nbreMaj1 = $this->colonnedataNombre($tab1);
                $nbreMajintial1 = $nbreMaj1;

                $tabAllSuppr = array();

                /* Manomboka eto */


                $valeur = $isP1;

                if ($isP1 >=5){
                    //$valeur = 5;
                    $valeur = 4;
                    if($nbreMaj1 <= 3 && $totalNbrecaractData >= 1500 ){
                        $valeur = 2;
                    }
                    if($nbreMaj1 < 2 && $totalNbrecaractData > 1600 ){
                        $valeur = 3;
                    }
                }
                //Nbre Maj = 6 et Nbre Maj After = 5 Total caract : 1096 isP1 = 2

                if ($isP1 >=2 && $isP1 <=3){
                    if($nbreMaj1 >= 3 && $totalNbrecaractData >= 1050 ){
                        $valeur = 5;
                    }else if ($nbreMaj1 >= 5 && $totalNbrecaractData >= 1000 ){
                        $valeur = 5;
                    }else if($nbreMaj1 < 3 && $totalNbrecaractData >= 1425 ){
                        $valeur = 5;
                    }
                }

                if ($isP1 == 0){
                    if($nbreMaj1 <= 3 && $totalNbrecaractData < 600 ){
                        $valeur = $valeur + 1;
                    }
                    else if($nbreMaj1 > 3 && $nbreMaj1 <7 && $totalNbrecaractData < 600 ){
                        $valeur = $valeur + 2;
                    }
                    else if($nbreMaj1 >= 7 && $totalNbrecaractData < 600 ){
                        $valeur = $valeur + 4;
                    }
                    else if($nbreMaj1 <= 3 && $totalNbrecaractData >= 600 && $totalNbrecaractData < 850 ){
                        if ($nbreMaj1 < 3){
                            $valeur = $valeur + 2;
                        }
                        else {
                            $valeur = $valeur + 3;
                        }
                    }

                    else if($nbreMaj1 > 3 && $nbreMaj1 <=5 && $totalNbrecaractData >= 600 && $totalNbrecaractData < 850 ){
                        $valeur = $valeur + 3;
                    }
                    else if($nbreMaj1 > 5 && $totalNbrecaractData >= 600 && $totalNbrecaractData < 850 ){
                        $valeur = $valeur + 4;
                    }

                    else if($nbreMaj1 <= 2 && $totalNbrecaractData >= 850 && $totalNbrecaractData < 1250 ){
                        $valeur = $valeur + 2;
                    }
                    else if($nbreMaj1 == 3 && $totalNbrecaractData >= 850 && $totalNbrecaractData < 1001 ){
                        $valeur = $valeur + 3;
                    }
                    else if($nbreMaj1 == 3 && $totalNbrecaractData >= 1001 && $totalNbrecaractData < 1250 ){
                        $valeur = $valeur + 4;
                    }
                    else if($nbreMaj1 > 3 && $totalNbrecaractData >= 850 && $totalNbrecaractData < 1250 ){
                        if ($nbreMaj1 >=6){
                            $valeur = $valeur + 5;
                        }
                        else {
                            $valeur = $valeur + 4;
                        }
                    }
                    else if($nbreMaj1 <= 3 && $totalNbrecaractData >= 1250  ){
                        $valeur = $valeur + 4;
                    }
                    else if($nbreMaj1 > 3 && $totalNbrecaractData >= 1250 ){
                        $valeur = $valeur + 5;
                    }

                }

                for ($im1 = 0; $im1 < $valeur; $im1++){
                    $elementsuprim1 = array_pop($tab1);
                    $tabAllSuppr[]  = $elementsuprim1;
                }

                /* Mifarana eto */
                $nbreMajAfter1 = $this->colonnedataNombre($tab1);

                $totalNbrecaractDataAfter = 0;
                $nbrtab = count($tab1);

                foreach ($tab1 as $tabtab1){
                    $totalNbrecaractDataAfter = $totalNbrecaractDataAfter+$this->colonnedataNombreCaract($tabtab1);
                }

                //echo "Mija Nbre Maj = $nbreMajintial1 et Nbre Maj After = $nbreMajAfter1 tot caract after :$totalNbrecaractDataAfter Total caract : $totalNbrecaractData  isP1 = $isP1<br />";

                $valeur = 0;
                if($nbreMaj1 >= 2 && $nbreMajAfter1<=$nbreMaj1 && $totalNbrecaractData >= 1600 && $isP1 >= 16  ){
                    if ($totalNbrecaractDataAfter>=1300){
                        $valeur++;
                    }

                }
                //Valeur aprés
                if ($isP1 == 4 && $totalNbrecaractData > 1500 && $totalNbrecaractData < 1550){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if($isP1 <= 4 && $nbreMajAfter1 <= $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                    if($totalNbrecaractDataAfter >= 1000 && $totalNbrecaractDataAfter < 1050){
                        $valeur = $valeur + 2;
                    }else if($totalNbrecaractDataAfter > 1050 && $totalNbrecaractDataAfter < 1100){
                        $valeur = $valeur + 2;
                    }else if($totalNbrecaractDataAfter >= 1250){
                        $valeur = $valeur + 1;
                    }
                }
                if ($isP1 == 2 && $totalNbrecaractData > 1560 && $totalNbrecaractData < 1605){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 2 && $totalNbrecaractData > 1360 && $totalNbrecaractData < 1430){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 2 && $totalNbrecaractData > 1425 && $totalNbrecaractData < 1470){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 1 && $totalNbrecaractData > 1390 && $totalNbrecaractData < 1425){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 1;
                    }
                }
                if ($isP1 <= 2 && $nbreMajAfter1 < $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData && $totalNbrecaractDataAfter >= 550 && $totalNbrecaractDataAfter < 691){
                    $valeur = $valeur - 2;
                }
                else{
                    $valeur = $valeur - 1;
                }
                if ($isP1 <= 1 && $totalNbrecaractData > 570 && $totalNbrecaractData < 700){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur - 1;
                    }
                }
                if ($isP1 <= 1 && $nbreMajAfter1 < $nbreMaj1 && $totalNbrecaractData > 800 && $totalNbrecaractData < 950){
                    $valeur = $valeur - 2;
                }
                if ($isP1 == 8 && $totalNbrecaractData > 1590 && $totalNbrecaractData < 1640){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 8 && $totalNbrecaractData > 1000 && $totalNbrecaractData < 1200){
                    if($nbreMajAfter1 <= $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 >= 8 && $totalNbrecaractData > 1100 && $totalNbrecaractData < 2000){
                    if($nbreMajAfter1 <= $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData && $totalNbrecaractData > 1700){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 950 && $totalNbrecaractData < 1040){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 1;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1170 && $totalNbrecaractData < 1250){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 4 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1100 && $totalNbrecaractData < 1210){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1460 && $totalNbrecaractData < 1499){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1450 && $totalNbrecaractData < 1520){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1000 && $totalNbrecaractData < 1300){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 4 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 1020 && $totalNbrecaractData < 1075){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 950 && $totalNbrecaractData < 1000){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 800 && $totalNbrecaractData < 860){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 10 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 6;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 580 && $totalNbrecaractData < 640){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 10 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 530 && $totalNbrecaractData < 610){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 890 && $totalNbrecaractData < 1010){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 1;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData >= 800 && $totalNbrecaractData < 900){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur - 1;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 800 && $totalNbrecaractData < 890){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 9 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 6;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 750 && $totalNbrecaractData < 850){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 720 && $totalNbrecaractData < 780){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 8 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 680 && $totalNbrecaractData < 750){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 9 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1400 && $totalNbrecaractData < 1450){
                    if ($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 6 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 1175 && $totalNbrecaractData < 1235){
                    if ($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 0 && $totalNbrecaractData > 700 && $totalNbrecaractData < 740){
                    if ($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData){
                        $valeur = $valeur - 2;
                    }
                }
                if ($isP1 == 4 && $totalNbrecaractData > 1400 && $totalNbrecaractData < 1540){
                    if($nbreMajAfter1 == $nbreMaj1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }else if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 4 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 6 && $totalNbrecaractData > 1640 && $totalNbrecaractData < 1690){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 4;
                    }
                }
                if ($isP1 == 6 && $totalNbrecaractData > 1560 && $totalNbrecaractData < 1650){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 4;
                    }
                }
                if ($isP1 == 6 && $totalNbrecaractData > 1400 && $totalNbrecaractData < 1460){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 6 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 4;
                    }
                }
                if ($isP1 == 4 && $totalNbrecaractData > 1550 && $totalNbrecaractData < 1690){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 4 && $totalNbrecaractData > 1285 && $totalNbrecaractData < 1335){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 5 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 10 && $totalNbrecaractData > 1600 && $totalNbrecaractData < 1650){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 4 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 4;
                    }
                }
                if ($isP1 == 11 && $totalNbrecaractData > 1250 && $totalNbrecaractData < 1330){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 11 && $totalNbrecaractData > 1300 && $totalNbrecaractData < 1380){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 12 && $totalNbrecaractData > 1320 && $totalNbrecaractData < 1400){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 5 && $totalNbrecaractData > 1640 && $totalNbrecaractData < 1680){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 7 && $totalNbrecaractData > 1300 && $totalNbrecaractData < 2000){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 1 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }else if ($isP1 >= 8 && $totalNbrecaractData >= 2000 ){
                    if ($totalNbrecaractDataAfter>=1100 ){
                        $valeur++;
                    }
                }
                if ($isP1 == 14 && $totalNbrecaractData > 1800 && $totalNbrecaractData < 1860){
                    if($nbreMajAfter1 < $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 3;
                    }
                }
                if ($isP1 == 14 && $totalNbrecaractData > 1830 && $totalNbrecaractData < 1880){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 2 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 2;
                    }
                }
                if ($isP1 == 16 && $totalNbrecaractData > 1650 && $totalNbrecaractData < 1750){
                    if($nbreMajAfter1 == $nbreMaj1 && $nbreMaj1 == 3 && $totalNbrecaractDataAfter < $totalNbrecaractData ){
                        $valeur = $valeur + 5;
                    }
                }
                for ($im1 = 0; $im1 < $valeur; $im1++){
                    $elementsuprim1 = array_pop($tab1);
                    $tabAllSuppr[]  = $elementsuprim1;
                }

                $tabaff2 = array();
                $tab2 = $tab1;
                //$nbreMaj1 = $this->colonnedata($tab1);

                if ($i1==1){
                    //echo "<p style='font-size: 9.5pt;color: blue; margin-right : 10mm;' >$title</p>";
                }
                //$nbreMaj2 = $this->colonnedata($tab2);
                /*differnce surplus données */
                //$dataSurplus = array_reverse($tabAllSuppr);
                $dataSurplus = $tabAllSuppr;
                foreach ($dataSurplus as $plus){
                    array_unshift($data, $plus);
                }
                $reste = $reste + count($tab1);
                /*differnce surplus données */

                if ($i==$quotient){
                    /***debut surplus quotient ***/
                    //echo "Mande anty mimikely";
                    $isaSurplusQuotient = count ($dataSurplus);
                    if($isaSurplusQuotient > 0){
                        $tabdatadernier = array();
                        $i1 = $i;
                        $dataSurplus = array_reverse($dataSurplus);
                        $tabdatadernier = $dataSurplus;



                        $reste = $reste + count($dataSurplus);

                        $tabSurplusivelany = $data;

                        $dataSurplus = $tabSurplusivelany;

                        $isaSurplusQuotient = count ($dataSurplus);

                        $reste = $isany - $reste ;

                        $dataFarany = array_reverse($dataSurplus);

                        $dataReste = array();
                        for ($initial = 0; $initial < $reste; $initial++){
                            $elementsuprim1 = array_pop($dataSurplus);
                            $dataReste[]  = $elementsuprim1;
                        }

                        $dataReste = array_reverse($dataReste);
                        $tabdatadernier = array_reverse($tabdatadernier);

                        foreach ($tabdatadernier as $value){
                            array_unshift($dataReste, $value);
                        }


                        $isareste = count($dataReste);


                        if($isareste > 0){
                            //echo "<br/>Mijakely isareste" ;
                            $reteNbrepage = $nbreParPage - 4;
                            if ($isareste>$reteNbrepage){
                                $tabSeparate = array_chunk($dataReste, $reteNbrepage);
                                $tabAllSupprMimi = array();
                                foreach ( $tabSeparate as $tabkely ){
                                    $string = "";
                                    $nbreretire = count($tabAllSupprMimi);

                                    if ($nbreretire>0){
                                        foreach ($tabAllSupprMimi as $plus){
                                            array_unshift($tabkely, $plus);
                                            unset($tabAllSupprMimi[array_search($plus, $tabAllSupprMimi)]);
                                        }
                                    }

                                    foreach ($tabkely as $coco){
                                        foreach ($coco as $coco1){
                                            $string = $string."".$coco1;
                                        }
                                    }
                                    $nbreCara = strlen($string);
                                    $inc =1;
                                    if ($nbreCara>1050){
                                        $limite = $nbreretire + 3;
                                        for ($im1 = 0; $im1 < $limite; $im1++){
                                            $elementsuprim1 = array_pop($tabkely);
                                            $tabAllSupprMimi[]  = $elementsuprim1;
                                            $inc ++;
                                        }
                                    }
                                    else {

                                    }

                                    //echo "<br><b>$reteNbrepage : $nbreretire : $inc</b><br>";

                                    $nbreMajFin = $this->colonnedataNombre($tabAllSupprMimi);
                                    //echo "$string <br> nbreCaractere : $nbreCara -- nbreMaj : $nbreMajFin <br>";
                                    $i1 = $i;
                                    //$nbreMaj1 = $this->colonnedata($tabkely);
                                    $this->numpage = $this->numpage +1;
                                    //$nbreMaj2 = $this->colonnedata($tabkely);

                                }
                            }
                            else{
                                //echo "<br/>--debut TsMijakely isareste $reteNbrepage :: $nbreParPage" ;
                                //echo "<br><b>$reteNbrepage : $nbreretire : $inc</b><br>";

                                $nbreMajFin = $this->colonnedataNombre($dataReste);
                                //echo "$string <br> nbreCaractere : $nbreCara -- nbreMaj : $nbreMajFin <br>";
                                $i1 = $i;
                                //$nbreMaj1 = $this->colonnedata($dataReste);
                                $this->numpage = $this->numpage +1;
                                //$nbreMaj2 = $this->colonnedata($dataReste);

                                //echo "<br/>--fin TsMijakely isareste" ;
                            }
                        }

                    }
                    /***fin surplus***/
                }
                else {

                    $isaSurplusQuotient = count ($dataSurplus);
                    if($isaSurplusQuotient > 0){

                        $i1 = $i;
                        $dataSurplus = array_reverse($dataSurplus);

                        foreach ($dataSurplus as $plus){
                            array_unshift($data, $plus);
                        }
                    }
                    $nbre = count ($dataSurplus);
                    $k = 0;
                    if ($isVoaloany==1){
                        $k = 1;
                    }

                    $tabdernier = array();

                    foreach ($dataSurplus as $plus){
                        if ($k!=$nbre){
                            if ($plus!="" || $plus!=" "){
                                $tabdernier = $plus;
                                $elementsuprim1 = array_shift($data);
                            }
                        }
                        $k++;
                    }
                    /***fin surplus***/
                }

            }


        }else{
            $i = 1;
            $nbreData = count($data);
            $nbreMajS = $this->colonnedataNombre($data);
            $dataSurplus = array();
            if($nbreMajS >=6 ){
                $nbrSurplus = $nbreMajS - 5;
                for ($t=0; $t<$nbrSurplus; $t++){
                    $elementsuprim = array_pop($data);
                    $dataSurplus[]  = $elementsuprim;
                }
            }
            $tab1 = array();
            $tab2 = array();
            $isanyInterne = $i * $nbreParPage;
            $k = ($i-1)*$nbreParPage;
            $tabNbrePartieALaliggne = array();
            $this->numpage = $this->numpage+1;
            $totalNbrecaractData = 0;
            for ($j = $k; $j < $isanyInterne; $j ++){
                $kj = 1;
                $string = "";
                foreach ($data[$j] as $coco){
                    $string = $string."".$coco;
                    $kj ++;
                }
                $nbreCaractere = strlen($string);
                $totalNbrecaractData = $totalNbrecaractData + $nbreCaractere;
                $fixeNbreCaractreLigne = 98;
                $partieEntiere = (int)($nbreCaractere/$fixeNbreCaractreLigne);
                $tabNbrePartieALaliggne[] = $partieEntiere;
                $tab1[] = $data[$j];
            }
            $i1 = $i;
            if ($i==1){
                $elementsuprim1 = array_pop($tab1);
                $dataSurplus[]  = $elementsuprim1;
                //echo "<p style='font-size: 9.5pt;color: blue; margin-right : 10mm;' >$title</p>";
            }
            $isP1 = 0;
            foreach ($tabNbrePartieALaliggne as $isaPart1){
                if ($isaPart1 != 0 && $isaPart1> 1){
                    $isP1  = $isP1+$isaPart1 ;
                }
            }
            for ($im1 = 0; $im1 < $isP1; $im1++){
                $elementsuprim1 = array_pop($tab1);
                $dataSurplus[]  = $elementsuprim;
            }

            $nbreMaj1 = $this->colonnedataNombre($tab1);
            $tabaff2 = array();

            //echo "Else Nbre Maj = $nbreMaj1 Total caract : $totalNbrecaractData  isP1 = $isP1<br />";

            if ($nbreMaj1>=4 && $nbreMaj1<6){
                if($totalNbrecaractData >850 && isP1 <= 3){
                    //echo "ato ian ty 1 tokony manala";
                    for ($ij1= 0; $ij1 < 4; $ij1++){
                        $elementsuprim1 = array_pop($tab1);
                        $dataSurplus[]  = $elementsuprim1;
                    }
                }
                else if($totalNbrecaractData >850 && isP1 >3){
                    for ($ij1= 0; $ij1 < 5; $ij1++){
                        $elementsuprim1 = array_pop($tab1);
                        $dataSurplus[]  = $elementsuprim1;
                    }
                }
            }

            else if ($nbreMaj1 >= 6){
                $max1 = 0;
                if ($totalNbrecaractData <=864 ){
                    $max1 = $nbreMaj1 - 7;
                }
                else {
                    $max1 = $nbreMaj1 - 6;
                }
                for ($ij1= 0; $ij1 < $max1; $ij1++){
                    $elementsuprim1 = array_pop($tab1);
                    $dataSurplus[]  = $elementsuprim1;
                }
            }

            $tab2=$tab1;

            //$nbreMaj1 = $this->colonnedata($tab1);

            if ($i1==1){
                //echo "<p style='font-size: 9.5pt;color: blue; margin-right : 10mm;' >$title</p>";
            }

            //$nbreMaj2 = $this->colonnedata($tab2);

            /***debut surplus***/
            if($nbreMajS >6 ){
                $i1 = $i;
                $dataSurplus = array_reverse($dataSurplus);
                //$nbreMaj1 = $this->colonnedata($dataSurplus);
                $this->numpage = $this->numpage +1;
                //$nbreMaj2 = $this->colonnedata($dataSurplus);
            }
            /***fin surplus***/
        }
        $nb_page = $this->numpage;
    }
    // fin tstoto

    function colonnedataNombre($data){
        $nbreMajuscule = 0;
        $tab = array();
        $initiallettre = "";
        foreach($data as $row)
        {

           $premierLettre = substr($row[0],0,1);

            if ($premierLettre!=$initiallettre){
                $premierLettre = $premierLettre;
                $premierLettre = stripslashes(utf8_decode($premierLettre));

                $maj = strtoupper($premierLettre);
                $nbreMajuscule = $nbreMajuscule + 1;
                $tab[] = $maj;
            }
            $initiallettre = $premierLettre;

        }
        return $nbreMajuscule;
    }

    function getNbPage($id){
        return 0;

    }
}

class Propriete
{
    public $largeur = "561.2602px";
    public $longueur = "793.7013px";

    public function getFormatEdition($prototypeAccess){
        $id_format_edition = 0;
        if($prototypeAccess != null)
            $id_format_edition = $prototypeAccess->getFormatEdition()->getId();
        return $id_format_edition;
    }
    public function getPrototypeTitle($prototypeAccess){
        $type = '';
        if($prototypeAccess != null)
            $type = $prototypeAccess->getType();
        return $type;
    }

}
