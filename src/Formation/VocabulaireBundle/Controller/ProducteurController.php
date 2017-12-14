<?php

namespace Formation\VocabulaireBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProducteurController extends Controller
{
    /**
     * @Route("/recherche_thematique", name="recherche_thematique")
     */
    public function recherche_thematiqueAction(Request $request)
    {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');
        
        $id_secteur = 0;
        $id_suffixe = 0;
        $libelle_secteur = '';
        $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
        $secteurs = $repositorySecteur->findAll();
        $repositorySuffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe');
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $suffixes = array();
        $vocabulaires = array();
        $vocabulaires_array = array();
        if ($request->get('id_secteur')) {
            $id_secteur = $request->get('id_secteur');
            if ($request->get('id_suffixe')) {
                $id_suffixe = $request->get('id_suffixe');
                $vocabulaires = $repositoryVocabulaire->findLEParThematiqueBySecteurAndSuffixe($id_secteur, $id_suffixe);
               
                
            } else {
                $vocabulaires = $repositoryVocabulaire->findLEParThematiqueBySecteur($id_secteur);
            }
        }
        
        $has_voc = 0;     
        $vocabulaires_sect = $repositoryVocabulaire->findLEParThematiqueBySecteur($id_secteur);
        foreach ($vocabulaires_sect as $voc) {
            $has_voc = 1;
            $has_list = 0;
            $id_soc = $voc['id_societe'];
            $suffixes_array = $repositorySuffixe->findSuffixeThematiqueBySociete($id_soc);
            foreach ($suffixes_array as $suf)
            {
               foreach($suffixes as $suffi)
               {
                    if($suffi == $suf)
                    {
                        $has_list = 1;
                    }
               }
               if($has_list == 0)
               {
                   $suffixes[] = $suf;
               }
            }
                
        }
        foreach($vocabulaires as $voc)
        {
            $vocab = new \Formation\VocabulaireBundle\Model\Vocabulaire();
            $vocab->set_id_societe($voc['id_societe']);
            $vocab->set_libelle_secteur($voc['libelle_secteur']);
            $vocab->set_description($voc['description']);
            $vocab->set_prototypes($repositoryPrototypeAccess->getNbProtoParSociete($voc['id_societe']));
            $vocabulaires_array[] = $vocab;
        }
        
        return $this->render('FormationVocabulaireBundle:Default:recherche_thematique.html.twig', array(
            'secteurs' => $secteurs,
            'societes' => $vocabulaires_array,
            'id_secteur' => $id_secteur,
            'id_suffixe' => $id_suffixe,
            'suffixes' => $suffixes,
            'libelle_secteur' => $libelle_secteur,
            'nb_societe' => count($vocabulaires),
            'has_voc' => $has_voc,
        ));

    }

    /**
     * @Route("/recherche_le", name="recherche_le")
     */
    public function recherche_leAction(Request $request)
    {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');

        $id_societe = 0;
        $id_theme = 0;
        $theme = "";
        $nom_prototype = "";
        $themes_array = array();
        $nb_LE = 0;

        if ($request->get('id_theme')) {
            $id_theme = $request->get('id_theme');
        }
        if ($request->get('theme')) {
            $theme = $request->get('theme');
        }
        if ($request->get('nom_prototype')) {
            $nom_prototype = $request->get('nom_prototype');
            $nom_prototype = strtolower($nom_prototype);
            $nom_prototype = trim($nom_prototype);
        }
        $themes = array();
        $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        if ($request->get('id_societe')) {
            $id_societe = $request->get('id_societe');
            $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
            $societe = $repositorySociete->find($id_societe);
            if ($societe != null) {
                $lexiques = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique')->findBy(array('societe' => $societe));
                foreach ($lexiques as $lexique) {
                    $themes[] = $lexique->getTheme();
                }
            }
        } else $themes = $repositoryTheme->findAllTheme();
        $has_data = 0;
        $valuesIdtheme = "";
        if ($nom_prototype == "" && $id_theme == "" && $theme == "") {
            $themes_array = [];
        }
        else{
            $themes_array = $repositoryLexique->findAllLE1($nom_prototype, $id_theme, $theme);
          
            $nb_LE = count($themes_array);
            foreach ($themes_array as $the)
            {
               // $id_theme = $the['id_theme'];
                $id_societe = $the['id_societe'];
                $valuesIdtheme = $valuesIdtheme.",".$the['id_theme'];
            }
            $has_data = 1;
        }
        
        return $this->render('FormationVocabulaireBundle:Default:recherche_le.html.twig', array(
            'themes' => $themes,
            'id_societe'=> $id_societe,
            'id_theme' => $id_theme,
            'theme' => $theme,
            'nom_prototype' => $nom_prototype,
            'themes_array' => $themes_array,
            'nb_LE' => $nb_LE,
            'has_data' => $has_data,
            'valuesIdtheme' => $valuesIdtheme
        ));
    }
    
     /**
     * @Route("/consulter_contenu_le/{id_societe}/{id_theme}", name="consulter_contenu_le")
     */
    public function consulterContenuLeAction($id_societe, $id_theme)
    {
        $id_societe = intval($id_societe);
        $id_theme = intval($id_theme);
        $vocabulaires = array();
        $nb_termes = 0;
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
       
        if($id_societe == "" && $id_theme == ""){
		$vocabulaires = [];
	}else{
            $vocabulaires = $repositoryVocabulaire->rechercheTerme($id_societe, $id_theme);
            $nb_termes = count($vocabulaires);
            
        }
        
        return $this->render('FormationVocabulaireBundle:Default:consulter_contenu_le.html.twig', array(
            'id_societe' => $id_societe,
            'id_theme' => $id_theme,
            'nb_termes' => $nb_termes,
            'vocabulaires' => $vocabulaires
        ));
    }
    
    /**
     * @Route("/vocabulaire_par_theme", name="vocabulaire_par_theme")
     */
    public function vocabulaire_par_themeAction(Request $request)
    {

        $id_secteur = 0;
        $id_suffixe = 0;
        $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
        $vocabulaires = array();
        $suffixes = array();
        $societes = array();
        $secteurs = $repositorySecteur->findAll();
        if ($request->get('id_secteur')) {
            $id_secteur = $request->get('id_secteur');
            $secteur = $repositorySecteur->find($id_secteur);
            if ($secteur != null) {
                if ($request->get('id_suffixe')) {
                    $id_suffixe = $request->get('id_suffixe');
                    $suffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe')->find($id_suffixe);
                    if ($suffixe != null)
                        $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->findBy(array('secteur' => $secteur, 'suffixe' => $suffixe));
                    else $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->findBy(array('secteur' => $secteur));
                }
            }
        }
        foreach ($vocabulaires as $voc) {

            $vocabulaireSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete')->findBy(array('vocabulaire' => $voc));
            foreach ($vocabulaireSocietes as $vocSoc) {
                $suffixeSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:SuffixeSociete')->findBy(array('societe' => $vocSoc->getSociete()));
                foreach ($suffixeSocietes as $suffixeSociete) {
                    $suffixes[] = $suffixeSociete->getSuffixe();
                    $societes[] = $suffixeSociete->getSociete();
                }
            }
        }
        return $this->render('FormationVocabulaireBundle:Default:recherche_thematique.html.twig', array(
            'secteurs' => $secteurs,
            'societes' => $societes,
            'id_secteur' => $id_secteur,
            'suffixes' => $suffixes,
            'nb_societe' => count($vocabulaires),
        ));
    }

    /**
     * @Route("/liste_prototype", name="liste_prototype")
     */
    public function listePrototypeAction(Request $request)
    {
        $id_secteur = 0;
        $nb_societe = 0;
        $prototype_access_array = array();
        if ($request->get('id_secteur')) $id_secteur = $request->get('id_secteur');

        if ($request->get('val')) {
            $valeurs = $request->get('val');
            $val = explode(',', $valeurs);
            $nb_societe = count($val);
        }
        for ($i = 0; $i < $nb_societe; $i++) {
            $id_societe = intval($val[$i]);
            if ($id_societe != "" && $id_societe != 0) {
                $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                $societe = $repositorySociete->find($id_societe);
                $prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('societe' => $societe));
                foreach ($prototype_access as $p) {
                    $protoModel = new \Formation\VocabulaireBundle\Model\ProtoType();
                    $protoModel->setNb_LE($this->getLESocAssocies($p->getSociete()->getId(), $p->getId()));
                    $protoModel->setId_prototype_access($p->getId());
                    $protoModel->setPrototype_access($p->getType());
                    $prototype_access_array[] = $protoModel;
                }
            }
        }

        return $this->render('FormationVocabulaireBundle:Default:lister_prototype.html.twig', array(
            'id_secteur' => $id_secteur,
            'nb_societe' => $nb_societe,
            'prototype_access_array' => $prototype_access_array
        ));
    }

    private function getLESocAssocies($id_societe, $id_prototype_access)
    {

        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $societe = $repositorySociete->find($id_societe);
        $prototype_access = $repositoryPrototypeAccess->find($id_prototype_access);
        if ($societe != null && $prototype_access != null) {
            $lexiques = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique')->findBy(array('prototypeAccess' => $prototype_access, 'societe' => $societe));
            return count($lexiques);
        }

        return 0;
    }

    /**
     * @Route("/list_le", name="list_le")
     */
    public function listeLEAction(Request $request)
    {
        $id_secteur = 0;
        $lexiques_array = array();
        if ($request->get('id_secteur')) $id_secteur = $request->get('id_secteur');

        if ($request->get('valProt')) {
            $valeursProt = $request->get('valProt');
            $val = explode(',', $valeursProt);
            $nb_prototype = count($val);

            for ($i = 0; $i < $nb_prototype; $i++) {
                $id_prototype_access = intval($val[$i]);
                if ($id_prototype_access != "" && $id_prototype_access != 0) {
                    $lexiques = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme')->findThemeThematiqueByPrototypeAccess($id_prototype_access);
                    foreach ($lexiques as $le) {
                        $protoModel = new \Formation\VocabulaireBundle\Model\Lexique();
                        $protoModel->setId_theme($le['id_theme']);
                        $protoModel->setIdSociete($le['id_societe']);
                        $protoModel->setLibelle_theme($le['libelle_theme']);
                        $protoModel->setNom_societe($le['societe']);
                        $protoModel->setId_prototype_access($id_prototype_access);
                        $protoModel->setNb_termes($this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->getNBTermesParLE($le['id_theme'], $id_prototype_access));
                        $lexiques_array[] = $protoModel;
                    }
                }
            }
        }


        return $this->render('FormationVocabulaireBundle:Default:liste_le.html.twig', array(
            'lexiques' => $lexiques_array,
            'id_secteur' => $id_secteur,
            'nb_termes' => 0,
        ));
    }

    /**
     * @Route("/export_le", name="export_le")
     */
    public function export_leAction(Request $request)
    {
        $vocabulaires = array();
        if ($request->get('id_societe') && $request->get('id_secteur') && $request->get('id_theme') && $request->get('id_prototype_access')) {
            $id_prototype_access = $request->get('id_prototype_access');
            $id_societe = $request->get('id_societe');
            $id_secteur = $request->get('id_secteur');
            $id_theme = $request->get('id_theme');
            $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->exportLE($id_theme, $id_prototype_access, $id_societe, $id_secteur);
        }
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setTitle('Vocabulaire')
            ->setSubject('Vocabulaire');

        $sheet = $phpExcelObject->setActiveSheetIndex(0);

        $sheet->setCellValue('A1', 'Société');
        $sheet->setCellValue('B1', 'Secteur');
        $sheet->setCellValue('C1', 'Prototype');
        $sheet->setCellValue('D1', 'Thème en français');
        $sheet->setCellValue('E1', 'Thème en anglais');
        $sheet->setCellValue('F1', 'Francais');
        $sheet->setCellValue('G1', 'Anglais');
        $sheet->setCellValue('H1', 'Source(Type)');
        $sheet->setCellValue('I1', 'Source (Nom stagiaire)');
        $sheet->setCellValue('J1', 'Titre du document/de l article');
        $sheet->setCellValue('K1', 'Lien');

        $counter = 2;
        foreach ($vocabulaires as $v) {
            $sheet->setCellValue('A' . $counter, $v['description']);
            $sheet->setCellValue('B' . $counter, $v['libelle_secteur']);
            $sheet->setCellValue('C' . $counter, $v['type']);
            $sheet->setCellValue('D' . $counter, $v['libelle_theme']);
            $sheet->setCellValue('E' . $counter, $v['theme_eng']);
            $sheet->setCellValue('F' . $counter, $v['langue_origine']);
            $sheet->setCellValue('G' . $counter, $v['langue_traduction']);
            $sheet->setCellValue('H' . $counter, $v['source_type']);
            $sheet->setCellValue('I' . $counter, $v['source_nom_stagiaire']);
            $sheet->setCellValue('J' . $counter, $v['lien_nom_doc']);
            $sheet->setCellValue('K' . $counter, $v['lien']);
            $counter++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('Vocabulaire');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'contenuLE.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;

    }
    
    
    /**
     * @Route("/export_le_recherche_terme", name="export_le_recherche_terme")
     */
    public function exportLeRechercheTermeAction(Request $request)
    {
        $id_societe = intval($request->get('id_societe'));
        $id_theme = intval($request->get('id_theme'));
        $vocabulaires = array();
        $nb_termes = 0;
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
       
        if($id_societe == "" && $id_theme == ""){
		$vocabulaires = [];
	}else{
            $vocabulaires = $repositoryVocabulaire->exportRechercheTerme($id_societe, $id_theme);
            $nb_termes = count($vocabulaires);
            
        }
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("liuggio")
            ->setTitle('Vocabulaire')
            ->setSubject('Vocabulaire');

        $sheet = $phpExcelObject->setActiveSheetIndex(0);

        $sheet->setCellValue('A1', 'Société');
        $sheet->setCellValue('B1', 'Francais');
        $sheet->setCellValue('C1', 'Anglais');
        $sheet->setCellValue('D1', 'Thème en français');
        $sheet->setCellValue('E1', 'Thème en anglais');
        $sheet->setCellValue('F1', 'Source(Type)');
        $sheet->setCellValue('G1', 'Source (Nom stagiaire)');
        $sheet->setCellValue('H1', 'Titre du document/de l article');
        $sheet->setCellValue('I1', 'Lien');

        $counter = 2;
        foreach ($vocabulaires as $v) {
            $sheet->setCellValue('A' . $counter, $v['description']);
            $sheet->setCellValue('B' . $counter, $v['libelle_theme']);
            $sheet->setCellValue('C' . $counter, $v['theme_eng']);
            $sheet->setCellValue('D' . $counter, $v['langue_origine']);
            $sheet->setCellValue('E' . $counter, $v['langue_traduction']);
            $sheet->setCellValue('F' . $counter, $v['source_type']);
            $sheet->setCellValue('G' . $counter, $v['source_nom_stagiaire']);
            $sheet->setCellValue('H' . $counter, $v['lien_nom_doc']);
            $sheet->setCellValue('I' . $counter, $v['lien']);
            $counter++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('Vocabulaire');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'contenuLE.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;

    }

}
