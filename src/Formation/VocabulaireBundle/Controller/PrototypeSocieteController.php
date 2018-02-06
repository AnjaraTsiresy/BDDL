<?php

namespace Formation\VocabulaireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PrototypeSocieteController extends Controller
{
     /**
     * @Route("/modif_prototype_le_soc/{id}/{id_societe}", name="modif_prototype_le_soc")
     */
    public function modifPrototypeLESocAction($id, $id_societe)
    {
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        $lexiques = $repositoryLexique->recherchePrototypeBySoc($id_societe);
        $compteur = count($lexiques);
     
        $lexiques_array = array();
        
        foreach ($lexiques as $l) {
            $le = new \Formation\VocabulaireBundle\Model\Lexique();
            $le->setIdLexique($repositoryLexique->getLexiqueBySocieteAndThemeAndPrototypeAccess($l['id_societe'], $l['id_theme'], $id));
            $le->setIdSociete($l['id_societe']);
            $le->setId_theme($l['id_theme']);
            $le->setLibelle_theme($l['libelle_theme']);
             $le->setTheme_eng($l['theme_eng']);
            $lexiques_array[] = $le;
        }
        $url = $this->generateUrl(
            'modif_contenu_le',
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        return $this->render('FormationVocabulaireBundle:Prototype:modif_prototype_le_soc.html.twig', array(
            'id' => $id,
            'id_societe' => $id_societe,
            'compteur' => $compteur,
            'lexiques' => $lexiques_array,
            'url' => $url
        ));

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

    /**
     * @Route("/modif_le_action", name="modif_le_action")
     */
    public function modifLeUpdateAction(Request $request){

        $id = intval($request->get('id'));
        $incr = intval($request->get('incr'));
        $id_theme = intval($request->get('id_theme'));
        $id_societe =intval($request->get('id_societe'));
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

        return $this->redirectToRoute('modif_contenu_le',array('id_theme'=>$id_theme,'id'=>$id,'id_societe'=>$id_societe));
    }
    /**
     * @Route("/modif_contenu_le", name="modif_contenu_le")
     */
    public function modifContenuLeAction(Request $request) {
        $id = 0;
        $id_societe = 0;
        $id_theme = 0;
         if ($request->get('id')) {
            $id = intval($request->get('id'));
        }
        if ($request->get('id_theme')) {
            $id_theme = intval($request->get('id_theme'));
        }
        if ($request->get('id_societe')) {
            $id_societe = intval($request->get('id_societe'));
        }
        $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');
        $sql = $repositoryVocabulairePrototypeAccess->getContenuLE($id, $id_societe, $id_theme);
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $nom_prototype = "";
        $prototypeAccess = $repositoryPrototypeAccess->find($id);
        if($prototypeAccess != null )
        $nom_prototype = $prototypeAccess->getType();
        $compteur = count($sql);
    
        return $this->render('FormationVocabulaireBundle:Prototype:modif_contenu_le.html.twig', array(
           'nom_prototype' => $nom_prototype,
           'compteur' => $compteur,
           'sql' => $sql,
           'id' => $id,
           'id_societe' => $id_societe,
           'id_theme' => $id_theme
        ));
    }

     /**
     * @Route("/modifPrototypeLEOtherSoc1", name="modifPrototypeLEOtherSoc1")
     */
    public function modifPrototypeLEOtherSoc1Action(Request $request) {
        $id = 0;
        $id_societe = 0;
        $id_secteur = 0;
        $id_suffixe = 0;
        $valuesIdtheme = "";
        $id_prototype_access = 0;

        if ($request->get('id')) {
            $id = $request->get('id');
        }
        if ($request->get('id_secteur')) {
            $id_secteur = $request->get('id_secteur');
        }
        if ($request->get('id_suffixe')) {
            $id_suffixe = $request->get('id_suffixe');
        }
        if ($request->get('id_societe')) {
            $id_societe = $request->get('id_societe');
        }

        $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
        $secteurs = $repositorySecteur->getSelecteurs();

        $repositorySuffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe');
        $suffixes = $repositorySuffixe->getSuffixesBySociete($id_societe);
        $requete = array();
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $compteur = 0;
        if($id_secteur > 0 || $id_suffixe > 0){
        $requete= $repositoryVocabulaire->rechercheLEBysuffixe1($id_secteur, $id_suffixe, $id_societe);

        $compteur =count($requete);
        }

        return $this->render('FormationVocabulaireBundle:Prototype:modifPrototypeLEOtherSoc1.html.twig', array(
           'id' => $id,
           'id_societe' => $id_societe,
           'secteurs' => $secteurs,
           'suffixes' => $suffixes,
           'id_secteur' => $id_secteur,
           'id_suffixe' => $id_suffixe,
           'requete' => $requete,
           'valuesIdtheme' => $valuesIdtheme,
           'id_prototype_access' => $id_prototype_access,
           'compteur' => $compteur
        ));
    }


    /**
     * @Route("/modifPrototypeLEOtherSoc", name="modifPrototypeLEOtherSoc")
     */
    public function modifPrototypeLEOtherSocAction(Request $request) {
        $id = 0;
        $id_societe = 0;
        $id_secteur = 0;
        $id_suffixe = 0;
        $valuesIdtheme = "";
        $id_prototype_access = 0;

        if ($request->get('id')) {
            $id = $request->get('id');
        }
        if ($request->get('id_secteur')) {
            $id_secteur = $request->get('id_secteur');
        }
        if ($request->get('id_suffixe')) {
            $id_suffixe = $request->get('id_suffixe');
        }
        if ($request->get('id_societe')) {
            $id_societe = $request->get('id_societe');
        }

        $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
        $secteurs = $repositorySecteur->getSelecteurs();

        $repositorySuffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe');
        $suffixes = $repositorySuffixe->getSuffixesBySociete($id_societe);
        $requete = array();
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $compteur = 0;
        if($id_secteur > 0 || $id_suffixe > 0){
            $requete= $repositoryVocabulaire->rechercheLEBysuffixe1($id_secteur, $id_suffixe, $id_societe);

            $compteur =count($requete);
        }

        return $this->render('FormationVocabulaireBundle:Prototype:modifPrototypeLEOtherSoc1.html.twig', array(
            'id' => $id,
            'id_societe' => $id_societe,
            'secteurs' => $secteurs,
            'suffixes' => $suffixes,
            'id_secteur' => $id_secteur,
            'id_suffixe' => $id_suffixe,
            'requete' => $requete,
            'valuesIdtheme' => $valuesIdtheme,
            'id_prototype_access' => $id_prototype_access,
            'compteur' => $compteur
        ));
    }
}
