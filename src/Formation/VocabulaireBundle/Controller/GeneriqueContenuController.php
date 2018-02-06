<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\Vocabulaire;
use Formation\VocabulaireBundle\Entity\VocabulairePrototypeAccess;
use Formation\VocabulaireBundle\Entity\VocabulaireSociete;
use Formation\VocabulaireBundle\Entity\VocabulaireTheme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GeneriqueContenuController extends Controller
{
    /**
     * @Route("/gestion_contenu_LE_generique", name="gestion_contenu_LE_generique")
     */
    public function gestiongeneriqueAction(Request $request){
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');
        $id_theme = 0;
        $terme = "";
        $langues_recherche = "";
        $dic = "";
        if ($request->get('id_theme')) {
            $id_theme = intval($request->get('id_theme'));
        }
        if ($request->get('dic')) {
            $dic = $request->get('dic');
        }
        if ($request->get('terme')) {
            $terme = $request->get('terme');
            $langues_recherche = $request->get('langues_recherche');
        }
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $vocabulaires = $repositoryVocabulaire->findTermeGen($id_theme, $terme, $langues_recherche,$dic);
        $themes = $repositoryVocabulaire->getThemes();
        $nb_theme = count($themes);
        $nb_vocab = count($vocabulaires);
        $url_delete_vocab_gen = $this->generateUrl(
            'delete_vocab_gen',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        return $this->render('FormationVocabulaireBundle:Generique:gestion_contenu_LE_generique.html.twig', array(
            'vocabulaires' => $vocabulaires,
            'themes' => $themes,
            'nb_theme' => $nb_theme,
            'id_theme' => $id_theme,
            'terme' => $terme,
            'nb_vocab' => $nb_vocab,
            'langues_recherche' => $langues_recherche,
            'url_delete_vocab_gen' => $url_delete_vocab_gen

        ));
    }

    /**
     * @Route("/modif_Gen/{id_theme}", name="modif_Gen")
     */
    public function modifGenAction($id_theme){
        $id_theme = intval($id_theme);
        $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
        $libelle_theme = $repositoryTheme->getLibelleTheme($id_theme);

        $url = $this->generateUrl(
            'gestion_contenu_LE_generique',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );



        $url_add_new_termes = $this->generateUrl(
            'add_new_termes',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->render('FormationVocabulaireBundle:Generique:modif_gen.html.twig', array(
            'libelle_theme' => $libelle_theme,
            'id_theme' => $id_theme,
            'url' => $url,
            'url_add_new_termes' => $url_add_new_termes

        ));
    }

    /**
     * @Route("/add_new_termes", name="add_new_termes")
     */
    public function addNewTermesAction(Request $request){
        $incr = intval($request->get('incr'));

        return $this->render('FormationVocabulaireBundle:Generique:add_new_termes.html.twig', array(
            'incr' => $incr
        ));
    }

    /**
     *
     * @Route("/modifierVocabulaireGen", name="modifierVocabulaireGen")
     */
    public function modifierVocabulaireGenAction(Request $request){
        $id = intval($request->get('id'));
        $langue_origine = "";
        $langue_traduction = "";
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $vocabulaire = $repositoryVocabulaire->find($id);
        if($vocabulaire != null)
        {
            $langue_origine = $vocabulaire->getLangueOrigine();
            $langue_traduction = $vocabulaire->getLangueTraduction();
        }
        return $this->render('FormationVocabulaireBundle:Generique:modifierVocabulaireGen.html.twig', array(
            'id' => $id,
            'vocabulaire' => $vocabulaire,
            'langue_origine' => $langue_origine,
            'langue_traduction' => $langue_traduction
        ));
    }

    /**
     *
     * @Route("/modifierVocabulaireGenaction", name="modifierVocabulaireGenaction")
     */
    public function modifierVocabulaireGe1Action(Request $request){
        $id = intval($request->get('id'));
        if($id > 0){
                $date = date("Y-m-d");
                $id_vocabulaire = intval($request->get('id'));
                $langue_origine = $request->get('langue_origine');
                $langue_traduction = $request->get('langue_traduction');
                $langue_origine_avec_maj = $this->trim_ucfirst_strtolower_utf8($langue_origine);
                $langue_origine_sans_modif = "";
                $alphabet_min = range('a', 'z');
                $alphabet_maj = range('A', 'Z');
                $first_lettre = $langue_origine[0];
                if(!in_array($first_lettre,$alphabet_min) && !in_array($first_lettre,$alphabet_maj)){
                    $langue_origine_sans_modif = $langue_origine;
                }

                $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
                $vocabulaire = $repositoryVocabulaire->find($id);
                if($vocabulaire != null)
                {
                    $vocabulaire->setLangueOrigine($langue_origine_avec_maj);
                    $vocabulaire->setLangueOrigineSansModif($langue_origine_sans_modif);
                    $vocabulaire->setLangueTraduction($langue_traduction);
                }
            }
            echo "<SCRIPT language=javascript> window.opener.location.reload(true); window.close();</SCRIPT>";

    }


    /**
     * @Route("/delete_vocab_gen", name="delete_vocab_gen")
     */
    public function deleteVocabGenAction(Request $request){
        $id = 0;
        $id_theme = 0;
        $langues_recherche = "";
        $terme = "";
        if($request->get('id_theme'))
        {
            $id_theme = intval($request->get('id_theme'));
        }
        if($request->get('id'))
        {
             $id = intval($request->get('id'));
        }

        if($request->get('terme'))
        {
            $terme = intval($request->get('terme'));
        }

        if($request->get('langues_recherche'))
        {
            $langues_recherche = $request->get('langues_recherche');
        }

        $em = $this->getDoctrine()->getManager();
        $repositoryVocabulaireSociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete');
        $repositoryVocabulaireTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireTheme');
        $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');


               $vocThemes = $repositoryVocabulaireTheme->getVocabulaireThemeByVocabulaireAndTheme2($id_theme, $id);
             $vocSocietes = $repositoryVocabulaireSociete->getVocSocBySocAndVoc2($id);
                  $vocProtoAs = $repositoryVocabulairePrototypeAccess->getVocabulaireProtoByProtoAccessAndVocabulaire2($id);
              echo count($vocThemes);
                 foreach ($vocThemes as $vT)
                {
                    $em->remove($vT);
                    $em->flush();
                }

                foreach ($vocSocietes as $vS)
                {
                    $em->remove($vS);

                }
                $em->flush();
                foreach ($vocProtoAs as $vP)
                {
                    $em->remove($vP);

                }
                $em->flush();

        return $this->redirectToRoute('gestion_contenu_LE_generique',array('id_theme'=>$id_theme,'terme'=>$terme,'langues_recherche'=>$langues_recherche));

    }

    /**
     * @Route("/modif_gen_insert", name="modif_gen_insert")
     */
    public function modifGenInsertAction(Request $request){

        $incr = intval($request->get('incr'));
        $id_theme = intval($request->get('id_theme'));
        $id_societe = 653;
        $date = date("Y-m-d H:i:s");
        $em = $this->getDoctrine()->getManager();
        $id_vocabulaire = 0;
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $repositoryVocabulaireSociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete');
        $repositoryVocabulaireTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireTheme');
        for($i=1; $i <= $incr; $i++){

            $langue_origine = $request->get("frs".$i);
            $langue_traduction = $request->get("eng".$i);

            if($langue_origine != "" && $langue_traduction != ""){
                //verification existence vocabulaire
                $langue_origine_verif = $this->trim_ucfirst_strtolower_utf8($langue_origine);

                $vocabulaires = $repositoryVocabulaire->getVocabulaireByLangueOrigineAndLangueTraduction($langue_origine_verif,$langue_traduction);
                foreach ($vocabulaires as $row1){
                if($row1['id_vocabulaire'] != 0 || $row1['id_vocabulaire'] != ""){
                    $id_vocabulaire = $row1['id_vocabulaire'];
                }else{
                    $langue_origine1 = trim($langue_origine);
                    $langue_origine_sans_modif = "";
                    $alphabet_min = range('a', 'z');
                    $alphabet_maj = range('A', 'Z');
                    $first_lettre = $langue_origine1[0];
                    if(!in_array($first_lettre,$alphabet_min) && !in_array($first_lettre,$alphabet_maj)){
                        $langue_origine_sans_modif = $langue_origine1;
                    }
                    $langue_origine1 = $this->trim_ucfirst_strtolower_utf8($langue_origine1);
                    $nb_caract = strlen($langue_origine1);
                    $nb_caract_lt = strlen($langue_traduction);
                    $fixeNbreCaractreLigne = 44;
                    $langue_origine_clean = $this->cleanLangueOrigine($langue_origine1);
                    $nb_ligne = ($nb_caract/$fixeNbreCaractreLigne);
                    $nb_ligne_lt = ($nb_caract_lt/$fixeNbreCaractreLigne);
                    $partieEntiere = (int)($nb_caract/$fixeNbreCaractreLigne);
                    $partieEntiere_lt = (int)($nb_caract_lt/$fixeNbreCaractreLigne);
                    if($partieEntiere < $nb_ligne){
                        $partieEntiere = $partieEntiere + 1;
                    }
                    if($partieEntiere_lt < $nb_ligne_lt){
                        $partieEntiere_lt = $partieEntiere_lt + 1;
                    }
                }
                    //insertion vocabulaire

                    $vocabulaire = new Vocabulaire();
                    $vocabulaire->setDateCreation(new \DateTime($date));
                    $vocabulaire->setLangueOrigine($langue_origine1);
                    $vocabulaire->setLangueOrigineSansModif($langue_origine_sans_modif);
                    $vocabulaire->setLangueTraduction($langue_traduction);
                    $vocabulaire->setNbreCaractLo($nb_caract);
                    $vocabulaire->setIsAffiche(1);
                    $em->persist($vocabulaire);
                    $em->flush();
                    $id_vocabulaire = $vocabulaire->getId();

                    if($id_vocabulaire != "" && $id_vocabulaire != 0){
                        //societe
                        $vocabulaireSociete = $repositoryVocabulaireSociete->getVocSocBySocAndVoc($id_societe, $id_vocabulaire);
                        foreach ($vocabulaireSociete as $row_test_societe ){
                        if($row_test_societe['id_vocabulaire_societe'] == 0 || $row_test_societe['id_vocabulaire_societe'] == ""){
                            $societe = $repositorySociete->find($id_societe);
                            $vocabulaire_obj = $repositoryVocabulaire->find($id_vocabulaire);
                            $vocabulaireSociete_obj = new VocabulaireSociete();
                            $vocabulaireSociete_obj->setSociete($societe);
                            $vocabulaireSociete_obj->setVocabulaire($vocabulaire_obj);
                            $em->persist($vocabulaireSociete_obj);
                            $em->flush();
                        }
                        //theme
                        if($id_theme != "" && $id_theme != 0){
                             $vocabulaireThemes = $repositoryVocabulaireTheme->etVocabulaireThemeByVocabulaireAndTheme($id_vocabulaire, $id_theme);
                            foreach ($vocabulaireThemes as $row_test_theme) {


                            if($row_test_theme['id_vocabulaire_theme'] == 0 || $row_test_theme['id_vocabulaire_theme'] == ""){
                                $theme= $repositoryTheme->find($id_theme);
                                $vocabulaire_obj = $repositoryVocabulaire->find($id_vocabulaire);
                                $vocab_theme_sql = new VocabulaireTheme();
                                $vocab_theme_sql->setVocabulaire($vocabulaire_obj);
                                $vocab_theme_sql->setTheme($theme);
                                $em->persist($vocab_theme_sql);
                                $em->flush();
                            }

                            }
                        }
                    }
                    }
                }
            }
        }

        return $this->redirectToRoute('gestion_contenu_LE_generique',array('id_theme'=>$id_theme));
    }

    private function convert_utf8( $string ) {
        return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
    }


    /**
     * @Route("/export_generique", name="export_generique")
     */
    public function exportGeneriqueAction(Request $request)
    {
        $id_theme = intval($request->get('id_theme'));
        $terme = $request->get('terme');
        $langues_recherche = $request->get('langues_recherche');

        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $vocabulaires = $repositoryVocabulaire->exportTermeGen($id_theme, $terme, $langues_recherche);


        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setTitle('PROTOTYPE')
            ->setSubject('PROTOTYPE');

        $sheet = $phpExcelObject->setActiveSheetIndex(0);

        $sheet->setCellValue('A1', 'Français');
        $sheet->setCellValue('B1', 'Anglais');
        $sheet->setCellValue('C1', 'Thème');

        $counter = 2;
        foreach ($vocabulaires as $v) {
            $sheet->setCellValue('A' . $counter, $this->convert_utf8($v['langue_origine']));
            $sheet->setCellValue('B' . $counter, $this->convert_utf8($v['langue_traduction']));
            $sheet->setCellValue('C' . $counter, $this->convert_utf8($v['libelle_theme']));
            $counter++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('GENERIQUE');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'generique.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;

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

    private function cleanLangueOrigine($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        //return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/[^A-Za-z\-]/', '', $string); // Removes special chars.
    }
}
