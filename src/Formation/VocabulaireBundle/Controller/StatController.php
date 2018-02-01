<?php

namespace Formation\VocabulaireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class StatController extends Controller {

    /**
     * @Route("/stat", name="stat")
     */
    public function statAction() {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');
        $repositoryPrototypeAccess = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess');
        $nb_prototypes = count($repositoryPrototypeAccess->findAll());   
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        $nb_LE = count($repositoryLexique->getNbLESoc());
        $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $nb_LE_gen = count($repositoryVocabulaire->getLEgenerique());
        $nb_vocab = count($repositoryVocabulaire->findAll());     
        $nb_societe = count($repositoryPrototypeAccess->getNbSocieteAvecPrototype());
        $prototypeSocietes = $repositoryPrototypeAccess->getNbSocieteAvecPrototype();
        $prototypeSociete_array = array();
        foreach ($prototypeSocietes as $p) {
            $pro = new \Formation\VocabulaireBundle\Model\ProtoType();
            $pro->setNom_societe($p['description']);
            $pro->setNbSoloc($repositoryPrototypeAccess->getNbProtoParSociete($p['id_societe']));
            $prototypeSociete_array[] = $pro;
        }
        return $this->render('FormationVocabulaireBundle:Default:stat.html.twig', array(
                    'nb_prototypes' => $nb_prototypes,
                    'nb_LE' => $nb_LE,
                    'nb_LE_gen' => $nb_LE_gen,
                    'nb_vocab' => $nb_vocab,
                    'nb_societe' => $nb_societe,
                    'prototypeSociete_array' => $prototypeSociete_array,
        ));
    }

    /**
     * @Route("/stat_generique", name="stat_generique")
     */
    public function statGeneriqueAction() {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');
         $repositoryVocabulaire = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire');
        $themes = $repositoryVocabulaire->getThemes();
        return $this->render('FormationVocabulaireBundle:Default:stat_generique.html.twig', array(
              'themes' => $themes
        ));
    }

    /**
     * @Route("/load_table_generique/{libelle_theme}/{nbOccurence}", name="load_table_generique")
     */
    public function loadTableGeneriqueAction($id_theme, $libelle_theme) {
        ini_set('max_execution_time', -1); //0=NOLIMIT
        ini_set('memory_limit', '2048M');
        $repositoryLexique = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique');
        $nbOccurence = $repositoryLexique->getNbOccurence($id_theme);
        return $this->render('FormationVocabulaireBundle:Default:load_table_generique.html.twig', array(
            'nbOccurence' => $nbOccurence,
            'libelle_theme' => $libelle_theme
        ));
    }


}
