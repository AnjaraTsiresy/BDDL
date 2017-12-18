<?php

namespace Formation\VocabulaireBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Formation\VocabulaireBundle\Entity\Source;

class ProducteurController extends Controller {

    /**
     * @Route("/recherche_thematique", name="recherche_thematique")
     */
    public function recherche_thematiqueAction(Request $request) {
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
            foreach ($suffixes_array as $suf) {
                foreach ($suffixes as $suffi) {
                    if ($suffi == $suf) {
                        $has_list = 1;
                    }
                }
                if ($has_list == 0) {
                    $suffixes[] = $suf;
                }
            }
        }
        foreach ($vocabulaires as $voc) {
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
    public function recherche_leAction(Request $request) {
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
        } else
            $themes = $repositoryTheme->findAllTheme();
        $has_data = 0;
        $valuesIdtheme = "";
        if ($nom_prototype == "" && $id_theme == "" && $theme == "") {
            $themes_array = [];
        } else {
            $themes_array = $repositoryLexique->findAllLE1($nom_prototype, $id_theme, $theme);

            $nb_LE = count($themes_array);
            foreach ($themes_array as $the) {
                // $id_theme = $the['id_theme'];
                $id_societe = $the['id_societe'];
                $valuesIdtheme = $valuesIdtheme . "," . $the['id_theme'];
            }
            $has_data = 1;
        }

        return $this->render('FormationVocabulaireBundle:Default:recherche_le.html.twig', array(
                    'themes' => $themes,
                    'id_societe' => $id_societe,
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
    public function consulterContenuLeAction($id_societe, $id_theme) {
        $id_societe = intval($id_societe);
        $id_theme = intval($id_theme);
        $vocabulaires = array();
        $nb_termes = 0;
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');

        if ($id_societe == "" && $id_theme == "") {
            $vocabulaires = [];
        } else {
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
     * @Route("/modif_vocabulaire", name="modif_vocabulaire")
     */
    public function modifVocabulaireAction(Request $request) {
        ini_set('memory_limit', '2048M');
        $id = 0;
        $id_source = 0;
        $nb_societe = 0;
        $nb_env_usg = 0;
        $nb_theme = 0;
        $nb_dpt = 0;
        $nb_sect = 0;
        $langue_origine = "";
        $critereSociete = "";
        $critereEnvirUsage = "";
        $critereTheme = "";
        $critereDepartement = "";
        $critereSecteur = "";
        $langue_traduction = "";
        $description_societe = "";
        $libelle_env_usage = "";
        $libelle_theme = "";
        $libelle_departement = "";
        $libelle_secteur = "";
        $source_type = "";
        $source_nom_stagiaire = "";
        $lien_nom_doc = "";
        $lien = "";

        $rang = "";
        if ($request->get('id')) {
            $id = $request->get('id');
        }

        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $repositoryVocabulaireSociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete');
        $repositoryVocabulaireTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireTheme');
        $repositoryVocabulaireEnvirUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireEnvirUsage');
        $repositoryVocabulaireDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireDepartement');
        $repositoryVocabulaireSecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSecteur');

        $vocabulaire = $repositoryVocabulaire->find($id);
        if ($vocabulaire != null) {

            $langue_origine = $vocabulaire->getLangueOrigine();
            $langue_traduction = $vocabulaire->getLangueTraduction();
            $rang = $vocabulaire->getRang();
            if ($vocabulaire->getSource()->getId() > 0) {
                $id_source = $vocabulaire->getSource()->getId();
                $source_type = $vocabulaire->getSource()->getSourceType();
                $lien_nom_doc = $vocabulaire->getSource()->getLienNomDoc();
                $lien = $vocabulaire->getSource()->getLien();
                $source_nom_stagiaire = $vocabulaire->getSource()->getSourceNomStagiaire();
            }
            //societe
            $vocabulaireSocietes = $repositoryVocabulaireSociete->getVocabulaireSocieteByVocabulaire($id);
            $nb_societe = count($vocabulaireSocietes);
            $critereSociete = "Société";
            if ($nb_societe == 1) {
                $vSmodifyOneCritere = $repositoryVocabulaireSociete->modifyOneCritere($id);
                foreach ($vSmodifyOneCritere as $vs) {
                    $description_societe = $vs['description'];
                }
            }

            //theme
            $vocabulaireThemes = $repositoryVocabulaireTheme->getVocabulaireThemeByVocabulaire($id);
            $nb_theme = count($vocabulaireThemes);
            $critereTheme = "Thème";
            if ($nb_theme == 1) {
                $vTmodifyOneCritere = $repositoryVocabulaireTheme->modifyOneCritere($id);
                foreach ($vTmodifyOneCritere as $vt) {
                    $libelle_theme = $vt['libelle_theme'];
                }
            }

            //Environnement usage
            $vocabulaireEnvirUsages = $repositoryVocabulaireEnvirUsage->getVocabulaireEnvirUsageByVocabulaire($id);
            $nb_env_usg = count($vocabulaireEnvirUsages);
            $critereEnvirUsage = "Environnement d'usage";
            if ($nb_env_usg == 1) {
                $vEnvmodifyOneCritere = $repositoryVocabulaireEnvirUsage->modifyOneCritere($id);
                foreach ($vEnvmodifyOneCritere as $vt) {
                    $libelle_env_usage = $vt['libelle_env_usage'];
                }
            }

            //Departement
            $vocabulaireDepartements = $repositoryVocabulaireDepartement->getVocabulaireDepartementByVocabulaire($id);
            $nb_dpt = count($vocabulaireDepartements);
            $critereDepartement = "Département";
            if ($nb_dpt == 1) {
                $vDmodifyOneCritere = $repositoryVocabulaireDepartement->modifyOneCritere($id);
                foreach ($vDmodifyOneCritere as $vd) {
                    $libelle_departement = $vd['libelle_departement'];
                }
            }

            //Secteur
            $vocabulaireSecteurs = $repositoryVocabulaireSecteur->getVocabulaireSecteurByVocabulaire($id);
            $nb_sect = count($vocabulaireSecteurs);
            $critereSecteur = "Secteur";
            if ($nb_sect == 1) {
                $vSmodifyOneCritere = $repositoryVocabulaireSecteur->modifyOneCritere($id);
                foreach ($vSmodifyOneCritere as $vs) {
                    $libelle_secteur = $vs['libelle_secteur'];
                }
            }
        }

        return $this->render('FormationVocabulaireBundle:Default:modif_vocabulaire.html.twig', array(
                    'id' => $id,
                    'rang' => $rang,
                    'critereSociete' => $critereSociete,
                    'description_societe' => $description_societe,
                    'critereTheme' => $critereTheme,
                    'libelle_theme' => $libelle_theme,
                    'source_nom_stagiaire' => $source_nom_stagiaire,
                    'critereDepartement' => $critereDepartement,
                    'libelle_departement' => $libelle_departement,
                    'critereSecteur' => $critereSecteur,
                    'libelle_secteur' => $libelle_secteur,
                    'critereEnvirUsage' => $critereEnvirUsage,
                    'libelle_env_usage' => $libelle_env_usage,
                    'langue_origine' => $langue_origine,
                    'langue_traduction' => $langue_traduction,
                    'nb_societe' => $nb_societe,
                    'source_type' => $source_type,
                    'lien_nom_doc' => $lien_nom_doc,
                    'lien' => $lien,
                    'vocabulaireSocietes' => $vocabulaireSocietes,
                    'nb_dpt' => $nb_dpt,
                    'vocabulaireDepartements' => $vocabulaireDepartements,
                    'nb_theme' => $nb_theme,
                    'nb_sect' => $nb_sect,
                    'vocabulaireEnvirUsages' => $vocabulaireEnvirUsages,
                    'nb_env_usg' => $nb_env_usg,
                    'vocabulaireThemes' => $vocabulaireThemes,
                    'vocabulaireSecteurs' => $vocabulaireSecteurs,
                    'id_source' => $id_source
        ));
    }

    /**
     * @Route("/modifier_vocabulaire_update", name="modifier_vocabulaire_update")
     */
    public function modifVocabulaireUpdateAction(Request $request) {
        $id = 0;
        $id_source = 0;
        $langue_origine = "";
        $langue_traduction = "";
        $source_type = "";
        $rang = "";
        $source_nom_stagiaire = "";
        $lien_nom_doc = "";
        $lien = "";
        if ($request->get('id')) {
            $id = $request->get('id');
        }
        if ($request->get('id_source')) {
            $id_source = $request->get('id_source');
        }
        if ($id > 0) {
            $id = intval($id);
            $id_source = intval($id_source);
        

            if ($request->get('langue_origine')) {
                $langue_origine = $request->get('langue_origine');
            }
            if ($request->get('langue_traduction')) {
                $langue_traduction = $request->get('langue_traduction');
            }

            if ($request->get('source_type')) {
                $source_type = $request->get('source_type');
            }
            if ($request->get('rang')) {
                $rang = $request->get('rang');
            }
            if ($request->get('source_nom_stagiaire')) {
                $source_nom_stagiaire = $request->get('source_nom_stagiaire');
            }
            if ($request->get('lien_nom_doc')) {
                $lien_nom_doc = $request->get('lien_nom_doc');
            }
            if ($request->get('lien')) {
                $lien = $request->get('lien');
            }
            $em = $this->getDoctrine()->getManager();
            $vocab = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->find($id);
            if ($vocab != null) {

                $vocab->setLangueOrigine($langue_origine);
                $vocab->setLangueTraduction($langue_traduction);
                $vocab->setRang($rang);
                $em->merge($vocab);
                $em->flush();

                if ($id_source > 0) {
                    
                    $source = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Source')->find($id_source);
                    $source->setSourceType($source_type);
                    $source->setSourceNomStagiaire($source_nom_stagiaire);
                    $source->setLienNomDoc($lien_nom_doc);
                    $source->setLien($lien);

                    $em->merge($source);
                    $em->flush();
                } else {
                    
                    $source = new Source();
                    $source->setSourceType($source_type);
                    $source->setSourceNomStagiaire($source_nom_stagiaire);
                    $source->setLienNomDoc($lien_nom_doc);
                    $source->setLien($lien);
                    $em->persist($source);
                    $em->flush();
                    $vocab->setSource($source);
                    $em->merge($vocab);
                    $em->flush();
                }
            }
        }
        echo "<SCRIPT language=javascript> window.opener.location.reload(true); window.close();</SCRIPT>";

        return "";
    }

    private function getduplicaterows($a) {
        $array_ = array();
        foreach ($a as $c) {
            $array[] = $c['id_vocabulaire'];
            $array_ = array_unique(array_diff_assoc($array, array_unique($array)));
        }
        return $array_;
    }

    private function curPageURL() {
        $url = "";
        if (isset($_SERVER["HTTPS"]) && !empty($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] != 'on' )) {
            $url = 'https://' . $_SERVER["SERVER_NAME"]; //https url
        } else {
            $url = 'http://' . $_SERVER["SERVER_NAME"]; //http url
        }
        if (( $_SERVER["SERVER_PORT"] != 80)) {
            $url .= $_SERVER["SERVER_PORT"];
        }
        $url .= $_SERVER["REQUEST_URI"];
        return $url;
    }

    /**
     * @Route("/consulter_vocabulaire_par_theme", name="consulter_vocabulaire_par_theme")
     */
    public function consulter_vocabulaire_par_themeAction(Request $request) {
        ini_set('memory_limit', '2048M');
        $terme = "";
        $sql = "";
        $dic = "";
        $nb_vocab = 0;
        $vocabulaires = array();
        $langues_recherche = "";

        $url = $this->generateUrl(
                'modif_vocabulaire', [
                ], UrlGeneratorInterface::ABSOLUTE_URL
        );

        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');

        if ($request->get('terme')) {
            $terme = $request->get('terme');
        }
        if ($request->get('langues_recherche')) {
            $langues_recherche = $request->get('langues_recherche');
        }
        if ($request->get('dic')) {
            $dic = $request->get('dic');
        }

        $vocabulaires = $repositoryVocabulaire->findTermes($terme, $langues_recherche, $dic);
        $nb_vocab = count($vocabulaires);

        return $this->render('FormationVocabulaireBundle:Default:consulter_vocabulaire_par_theme.html.twig', array(
                    'terme' => $terme,
                    'url' => $url,
                    'sql' => $sql,
                    'nb_vocab' => $nb_vocab,
                    'vocabulaires' => $vocabulaires,
                    'langues_recherche' => $langues_recherche
        ));
    }

    /**
     * @Route("/delete_vocab/{id}/{langues_recherche}/{terme}/{id_theme}", name="delete_vocab")
     */
    public function deleteVocabAction($id, $langues_recherche, $terme, $id_theme) {

        $em = $this->getDoctrine()->getManager();
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
        $theme = $repositoryTheme->find($id_theme);
        $vocabulaire = $repositoryVocabulaire->find($id);

        if ($theme != null && $vocabulaire != null) {

            $repositoryVocabulaireTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireTheme');
            $vocabulaireTheme = $repositoryVocabulaireTheme->findBy(array('vocabulaire' => $vocabulaire, 'theme' => $theme));
            foreach ($vocabulaireTheme as $vt) {
                $em->remove($vt);
                $em->flush();
            }
        }
        if ($vocabulaire != null) {

            $repositoryVocabulaireSecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSecteur');
            $vocabulaireSecteur = $repositoryVocabulaireSecteur->findBy(array('vocabulaire' => $vocabulaire));
            foreach ($vocabulaireSecteur as $vs) {
                $em->remove($vs);
                $em->flush();
            }
        }

        if ($vocabulaire != null) {
            $repositoryVocabulaireDepartement = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireDepartement');
            $vocabulaireDepartement = $repositoryVocabulaireDepartement->findBy(array('vocabulaire' => $vocabulaire));
            foreach ($vocabulaireDepartement as $vd) {
                $em->remove($vd);
                $em->flush();
            }
        }
        if ($vocabulaire != null) {
            $repositoryVocabulaireFonction = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireFonction');
            $vocabulaireFonction = $repositoryVocabulaireFonction->findBy(array('vocabulaire' => $vocabulaire));
            foreach ($vocabulaireFonction as $vf) {
                $em->remove($vf);
                $em->flush();
            }
        }
        if ($vocabulaire != null) {
            $repositoryVocabulaireEnvirUsage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireEnvirUsage');
            $vocabulaireEnvirUsage = $repositoryVocabulaireEnvirUsage->findBy(array('vocabulaire' => $vocabulaire));
            foreach ($vocabulaireEnvirUsage as $ve) {
                $em->remove($ve);
                $em->flush();
            }
        }
        if ($vocabulaire != null) {
            $repositoryVocabulaireTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireTraducteur');
            $vocabulaireTraducteur = $repositoryVocabulaireTraducteur->findBy(array('vocabulaire' => $vocabulaire));
            foreach ($vocabulaireTraducteur as $vtra) {
                $em->remove($vtra);
                $em->flush();
            }
        }
        if ($vocabulaire != null) {
            $repositoryVocabulairePrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePrototypeAccess');
            $vocabulairePrototypeAccess = $repositoryVocabulairePrototypeAccess->findBy(array('vocabulaire' => $vocabulaire));
            foreach ($vocabulairePrototypeAccess as $vps) {
                $em->remove($vps);
                $em->flush();
            }
        }
        if ($vocabulaire != null) {
            $repositoryVocabulaireSociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete');
            $vocabulaireSociete = $repositoryVocabulaireSociete->findBy(array('vocabulaire' => $vocabulaire));
            foreach ($vocabulaireSociete as $vs) {
                $em->remove($vs);
                $em->flush();
            }
        }
        if ($vocabulaire != null) {
            $repositoryVocabulairePhraseSource = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulairePhraseSource');
            $vocabulairePhraseSource = $repositoryVocabulairePhraseSource->findBy(array('vocabulaire' => $vocabulaire));
            foreach ($vocabulairePhraseSource as $vps) {
                $em->remove($vps);
                $em->flush();
            }
        }
        if ($vocabulaire != null) {

            $em->remove($vocabulaire);
            $em->flush();
        }
        return $this->redirectToRoute('consulter_vocabulaire_par_theme', array('terme' => $terme, 'langues_recherche' => $langues_recherche));
    }

    /**
     * @Route("/liste_prototype", name="liste_prototype")
     */
    public function listePrototypeAction(Request $request) {
        $id_secteur = 0;
        $nb_societe = 0;
        $prototype_access_array = array();
        if ($request->get('id_secteur'))
            $id_secteur = $request->get('id_secteur');

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

    private function getLESocAssocies($id_societe, $id_prototype_access) {

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
    public function listeLEAction(Request $request) {
        $id_secteur = 0;
        $lexiques_array = array();
        if ($request->get('id_secteur'))
            $id_secteur = $request->get('id_secteur');

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
    public function export_leAction(Request $request) {
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
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'contenuLE.xls'
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
    public function exportLeRechercheTermeAction(Request $request) {
        $id_societe = intval($request->get('id_societe'));
        $id_theme = intval($request->get('id_theme'));
        $vocabulaires = array();
        $nb_termes = 0;
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');

        if ($id_societe == "" && $id_theme == "") {
            $vocabulaires = [];
        } else {
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
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'contenuLE.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
    
    /**
     * @Route("/export_terme", name="export_terme")
     */
    public function exportTermeAction(Request $request) {
        $terme = "";
        $langues_recherche = "";
         if ($request->get('terme')) {
            $terme = $request->get('terme');
        }
        if ($request->get('langues_recherche')) {
            $langues_recherche = $request->get('langues_recherche');
        }
        $vocabulaires = array();
        $nb_termes = 0;
        $dic = "";
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');

        if ($terme == "" && $langues_recherche == "") {
            $vocabulaires = [];
        } else {
            $vocabulaires = $repositoryVocabulaire->findTermes($terme, $langues_recherche, $dic);
            $nb_termes = count($vocabulaires);
        }
        // ask the service for a Excel5
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("liuggio")
                ->setTitle('Vocabulaire')
                ->setSubject('Vocabulaire');

        $sheet = $phpExcelObject->setActiveSheetIndex(0);

        $sheet->setCellValue('A1', 'Francais');
        $sheet->setCellValue('B1', 'Anglais');
        $sheet->setCellValue('C1', 'Thème en français');
        $sheet->setCellValue('D1', 'Thème en anglais');

        $counter = 2;
        foreach ($vocabulaires as $v) {
            $sheet->setCellValue('A' . $counter, $v['libelle_theme']);
            $sheet->setCellValue('B' . $counter, $v['theme_eng']);
            $sheet->setCellValue('C' . $counter, $v['langue_origine']);
            $sheet->setCellValue('D' . $counter, $v['langue_traduction']);
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
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'contenuLE.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

}
