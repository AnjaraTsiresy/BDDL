<?php

namespace Formation\VocabulaireBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProducteurControllerController extends Controller
{
    /**
     * @Route("/recherche_thematique", name="recherche_thematique")
     */
    public function recherche_thematiqueAction(Request $request)
    {
        $id_secteur = 0;
        $id_suffixe = 0;
        $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
        $vocabulaires = array();
        $suffixes = array();
        $societes = array();
        $secteurs = $repositorySecteur->findAll();
        if($request->get('id_secteur')){
            $id_secteur = $request->get('id_secteur');
            $secteur = $repositorySecteur->find($id_secteur);
            if($secteur != null)
            {
                if($request->get('id_suffixe'))
                {   $id_suffixe = $request->get('id_suffixe');
                    $suffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe')->find($id_suffixe);
                    if($suffixe!=null)
                        $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->findBy(array('secteur' => $secteur,'suffixe' => $suffixe));
                    else $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->findBy(array('secteur' => $secteur));
                }
            }
        }
        foreach ($vocabulaires as $voc)
        {

           $vocabulaireSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete')->findBy(array('vocabulaire' => $voc));
           foreach ($vocabulaireSocietes as $vocSoc)
           {
               $suffixeSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:SuffixeSociete')->findBy(array('societe' => $vocSoc->getSociete()));
               foreach ($suffixeSocietes as $suffixeSociete)
               {
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
     * @Route("/recherche_thematique", name="recherche_le")
     */
    public function recherche_leAction(Request $request)
    {

        $id_secteur = 0;
        $id_suffixe = 0;
        $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
        $vocabulaires = array();
        $suffixes = array();
        $societes = array();
        $secteurs = $repositorySecteur->findAll();
        if($request->get('id_secteur')){
            $id_secteur = $request->get('id_secteur');
            $secteur = $repositorySecteur->find($id_secteur);
            if($secteur != null)
            {
                if($request->get('id_suffixe'))
                {   $id_suffixe = $request->get('id_suffixe');
                    $suffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe')->find($id_suffixe);
                    if($suffixe!=null)
                        $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->findBy(array('secteur' => $secteur,'suffixe' => $suffixe));
                    else $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->findBy(array('secteur' => $secteur));
                }
            }
        }
        foreach ($vocabulaires as $voc)
        {

            $vocabulaireSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete')->findBy(array('vocabulaire' => $voc));
            foreach ($vocabulaireSocietes as $vocSoc)
            {
                $suffixeSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:SuffixeSociete')->findBy(array('societe' => $vocSoc->getSociete()));
                foreach ($suffixeSocietes as $suffixeSociete)
                {
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
        if($request->get('id_secteur')){
            $id_secteur = $request->get('id_secteur');
            $secteur = $repositorySecteur->find($id_secteur);
            if($secteur != null)
            {
                if($request->get('id_suffixe'))
                {   $id_suffixe = $request->get('id_suffixe');
                    $suffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe')->find($id_suffixe);
                    if($suffixe!=null)
                        $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->findBy(array('secteur' => $secteur,'suffixe' => $suffixe));
                    else $vocabulaires = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Vocabulaire')->findBy(array('secteur' => $secteur));
                }
            }
        }
        foreach ($vocabulaires as $voc)
        {

            $vocabulaireSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete')->findBy(array('vocabulaire' => $voc));
            foreach ($vocabulaireSocietes as $vocSoc)
            {
                $suffixeSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:SuffixeSociete')->findBy(array('societe' => $vocSoc->getSociete()));
                foreach ($suffixeSocietes as $suffixeSociete)
                {
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

        $repositoryLanguage = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Language');
        $languages = $repositoryLanguage->findAll();

        $repositoryTraducteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Traducteur');
        $traducteurs = $repositoryTraducteur->findAll();

        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societes = $repositorySociete->findAll();

        return $this->render('FormationVocabulaireBundle:Default:lister_prototype.html.twig', array(
            'languages' => $languages,
            'traducteurs' => $traducteurs,
            'societes' => $societes,
        ));
    }
}
