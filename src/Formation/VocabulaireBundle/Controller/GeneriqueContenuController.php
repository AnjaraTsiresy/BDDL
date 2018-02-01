<?php

namespace Formation\VocabulaireBundle\Controller;

use Formation\VocabulaireBundle\Entity\Vocabulaire;
use Formation\VocabulaireBundle\Entity\VocabulairePrototypeAccess;
use Formation\VocabulaireBundle\Entity\VocabulaireSociete;
use Formation\VocabulaireBundle\Entity\VocabulaireTheme;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->render('FormationVocabulaireBundle:Generique:gestion_contenu_LE_generique.html.twig', array(
            'vocabulaires' => $vocabulaires,
            'themes' => $themes,
            'nb_theme' => $nb_theme,
            'id_theme' => $id_theme,
            'terme' => $terme,
            'nb_vocab' => $nb_vocab,
            'langues_recherche' => $langues_recherche

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
                $langue_origine_verif = trim_ucfirst_strtolower_utf8($langue_origine);

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
                    $langue_origine1 = trim_ucfirst_strtolower_utf8($langue_origine1);
                    $nb_caract = strlen($langue_origine1);
                    $nb_caract_lt = strlen($langue_traduction);
                    $fixeNbreCaractreLigne = 44;
                    $langue_origine_clean = cleanLangueOrigine($langue_origine1);
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
                    $vocab_sql = "INSERT IGNORE INTO vocabulaire VALUES ('', '$date', '', '$langue_origine1', '$langue_origine_sans_modif', '$langue_traduction', '', '1', '', '1', '$nb_caract', '$partieEntiere', '$langue_origine_clean', '$nb_caract_lt', '$partieEntiere_lt')";
                    mysql_query($vocab_sql);
                    $id_vocabulaire = mysql_insert_id() ;

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

        return $this->redirectToRoute('modif_Gen',array('id_theme'=>$id_theme));
    }
}
