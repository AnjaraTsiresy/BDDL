<?php

namespace Formation\VocabulaireBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProducteurController extends Controller
{
    /**
     * @Route("/recherche_thematique", name="recherche_thematique")
     */
    public function recherche_thematiqueAction(Request $request)
    {
        $id_secteur = 0;
        $id_suffixe = 0;
        $libelle_secteur = '';
        $repositorySecteur = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Secteur');
        $vocabulaireSecteurs = array();
        $suffixes = array();
        $societes = array();
        $secteurs = $repositorySecteur->findAll();
        if ($request->get('id_secteur')) {
            $id_secteur = $request->get('id_secteur');
            $secteur = $repositorySecteur->find($id_secteur);

            if ($secteur != null) {
                $libelle_secteur = $secteur->getLibelleSecteur();
                 $vocabulaireSecteurs = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSecteur')->findBy(array('secteur' => $secteur));
                if ($request->get('id_suffixe')) {
                   
                    $id_suffixe = $request->get('id_suffixe');
                    $suffixe = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Suffixe')->find($id_suffixe);
                    if ($suffixe != null){
                        $i=0;
		 $list_id_suffixe = array();
        foreach ($vocabulaireSecteurs as $voc) {
            //if($i > 1) break;
            if ($voc->getVocabulaire() != null) {
                if ($voc->getVocabulaire()->getIsAffiche() != null) {
                if ($voc->getVocabulaire()->getIsAffiche() == 1) {
                    $vocabulaireSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete')->findBy(array('vocabulaire' => $voc->getVocabulaire()));
                    foreach ($vocabulaireSocietes as $vocSoc) {
                        $societes[] = $vocSoc->getSociete();

                        $suffixeSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:SuffixeSociete')->findBy(array('suffixe' => $suffixe));
                        foreach ($suffixeSocietes as $suffixeSociete) {
                            if (count($list_id_suffixe) > 0) {
                                $j = 0;
                                foreach ($list_id_suffixe as $id) {
                                    if ($id == $suffixeSociete->getSuffixe()->getId()) {
                                        $j = 1;
                                        break;
                                    }
                                }
                                if ($j == 0) $suffixes[] = $suffixeSociete->getSuffixe();
                            } else {
                                $list_id_suffixe [] = $suffixeSociete->getSuffixe()->getId();
                                $suffixes[] = $suffixeSociete->getSuffixe();
                            }
                        }

                    }
                }
            }
        }
            ++$i;

            }
        }

                }
                else {
                    $i=0;
		 $list_id_suffixe = array();
        foreach ($vocabulaireSecteurs as $voc) {
			//if($i > 1) break;
          if($voc->getVocabulaire() != null){
              if ($voc->getVocabulaire()->getIsAffiche() != null) {
                  if ($voc->getVocabulaire()->getIsAffiche() == 1) {
                      $vocabulaireSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:VocabulaireSociete')->findBy(array('vocabulaire' => $voc->getVocabulaire()));
                      foreach ($vocabulaireSocietes as $vocSoc) {
                          $societes[] = $vocSoc->getSociete();

                          $suffixeSocietes = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:SuffixeSociete')->findBy(array('societe' => $vocSoc->getSociete()));
                          foreach ($suffixeSocietes as $suffixeSociete) {
                              if (count($list_id_suffixe) > 0) {
                                  $j = 0;
                                  foreach ($list_id_suffixe as $id) {
                                      if ($id == $suffixeSociete->getSuffixe()->getId()) {
                                          $j = 1;
                                          break;
                                      }
                                  }
                                  if ($j == 0) $suffixes[] = $suffixeSociete->getSuffixe();
                              } else {
                                  $list_id_suffixe [] = $suffixeSociete->getSuffixe()->getId();
                                  $suffixes[] = $suffixeSociete->getSuffixe();
                              }
                          }

                      }
                  }
              }
			++$i;
        }
     }
                }

            }
        }
		
        return $this->render('FormationVocabulaireBundle:Default:recherche_thematique.html.twig', array(
            'secteurs' => $secteurs,
            'societes' => $societes,
            'id_secteur' => $id_secteur,
			'id_suffixe'=> $id_suffixe,
            'suffixes' => $suffixes,
            'libelle_secteur' => $libelle_secteur,
            'nb_societe' => count($societes),
        ));
    }

    /**
     * @Route("/recherche_le", name="recherche_le")
     */
    public function recherche_leAction(Request $request)
    {

        $id_societe = 0;
        $id_theme = 0;
        $theme = "";
        $nom_prototype = "";

        if ($request->get('id_theme')) {
            $id_theme = $request->get('id_theme');
        }
        if ($request->get('theme')) {
            $id_theme = $request->get('theme');
        }
        if ($request->get('nom_prototype')) {
            $nom_prototype = $request->get('nom_prototype');
            $nom_prototype = strtolower($nom_prototype);
            $nom_prototype = trim($nom_prototype);
        }
        $themes = array();
        $repositoryTheme = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Theme');
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
        } else $themes = $repositoryTheme->findAll();
        if ($nom_prototype == "" && $theme == "" && $id_theme > 0) {
            $sql = "";
        }
        return $this->render('FormationVocabulaireBundle:Default:recherche_le.html.twig', array(
            'themes' => $themes,
            'id_theme' => $id_theme,
            'theme' => $theme,
            'nom_prototype' => $nom_prototype
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
        if( $request->get('id_secteur') ) $id_secteur = $request->get('id_secteur');

        if( $request->get('val') ) 
        {
            $valeurs = $request->get('val');
            $val = explode(',', $valeurs);
	        $nb_societe = count($val);
        }
        for($i = 0; $i < $nb_societe; $i++) {
			$id_societe = intval($val[$i]);
			if($id_societe != "" && $id_societe != 0){
                $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
                $societe = $repositorySociete->find($id_societe);
				$prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('societe' => $societe));
            	foreach($prototype_access as $p)
                {
                    $protoModel = new ProtoTypeModel;
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

    /**
     * @Route("/list_le", name="list_le")
     */
    public function listeLEAction(Request $request)
    {
        $nb_prototype = 0;
        $valeursProt = '';
        $id_secteur = 0;
        if( $request->get('id_secteur') ) $id_secteur = $request->get('id_secteur');

        if( $request->get('valProt') )
        {
            $valeursProt = $request->get('valProt');
            $val = explode(',', $valeursProt);
            $nb_prototype = count($val);
            $lexiques_array = array();
            for($i = 0; $i < $nb_prototype; $i++) {
                $id_prototype_access = intval($val[$i]);
                if($id_prototype_access != "" && $id_prototype_access != 0)
                {
                    $prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->find($id_prototype_access);
                    $lexiques = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Lexique')->findBy(array('prototypeAccess' => $prototype_access));
                    foreach($lexiques as $le)
                    {
                        $protoModel = new LexiqueModel();
                        $protoModel->setId_theme($le->getTheme()->getId());
                        $protoModel->setIdSociete($le->getSociete()->getId());
                        $protoModel->setLibelle_theme($le->getTheme()->getLibelleTheme());
                        $protoModel->setNom_societe($le->getSociete()->getDescription());
                        $protoModel->setNb_termes($this->getLESocAssocies($le->getSociete()->getId(), $id_prototype_access));
                        $lexiques_array[] = $protoModel;
                    }
                }
            }
        }


        return $this->render('FormationVocabulaireBundle:Default:liste_le.html.twig', array(
            'lexiques' => $lexiques_array,
            'id_secteur' => $id_secteur,
        ));
    }

    private function getLESocAssocies($id_societe, $id_prototype_access)
    {

        $repositorySociete = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:Societe');
        $societe = $repositorySociete->find($id_societe);
        if($societe != null)
        {
            $prototype_access = $this->getDoctrine()->getRepository('FormationVocabulaireBundle:PrototypeAccess')->findBy(array('id' => $id_prototype_access, 'societe' => $societe));
            return count($prototype_access);
        }

        return 0;
    }
}

class ProtoTypeModel
{
    private $id_societe;
    private $id_theme;
    private $libelle_theme;
    private $nb_termes;
    private $nom_societe;
    private $nb_LE;
    private $id_prototype_access;
    private $prototype_access;
    public function id_societe()
    {
        return $this->id_societe;
    }

    public function setIdSociete($id_societe)
    {
        $this->id_societe = $id_societe;
    }

    public function id_theme()
    {
        return $this->id_theme;
    }

    public function setId_theme($id_theme)
    {
        $this->id_theme = $id_theme;
    }

    public function libelle_theme()
    {
        return $this->libelle_theme;
    }

    public function setLibelle_theme($libelle_theme)
    {
        $this->libelle_theme = $libelle_theme;
    }

    public function nb_termes()
    {
        return $this->nb_termes;
    }

    public function setNb_termes($nb_termes)
    {
        $this->nb_termes = $nb_termes;
    }

    public function nom_societe()
    {
        return $this->nom_societe;
    }

    public function setNom_societe($nom_societe)
    {
        $this->nom_societe = $nom_societe;
    }
    public function id_prototype_access()
    {
        return $this->id_prototype_access;
    }

    public function setId_prototype_access($id_prototype_access)
    {
        $this->id_prototype_access = $id_prototype_access;
    }

    public function nb_LE()
    {
        return $this->nb_LE;
    }

    public function setNb_LE($nb_LE)
    {
        $this->nb_LE = $nb_LE;
    }
    public function prototype_access()
    {
        return $this->prototype_access;
    }

    public function setPrototype_access($prototype_access)
    {
        $this->prototype_access = $prototype_access;
    }

}



class LexiqueModel
{
    private $prototype_access;
    private $id_prototype_access;
    private $nb_LE;
    private $id_theme;

    private $id_societe;
    private $libelle_theme;
    private $nb_termes;
    private $nom_societe;

    public function id_societe()
    {
        return $this->id_societe;
    }

    public function setIdSociete($id_societe)
    {
        $this->id_societe = $id_societe;
    }

    public function id_theme()
    {
        return $this->id_theme;
    }

    public function setId_theme($id_theme)
    {
        $this->id_theme = $id_theme;
    }

    public function libelle_theme()
    {
        return $this->libelle_theme;
    }

    public function setLibelle_theme($libelle_theme)
    {
        $this->libelle_theme = $libelle_theme;
    }

    public function nb_termes()
    {
        return $this->nb_termes;
    }

    public function setNb_termes($nb_termes)
    {
        $this->nb_termes = $nb_termes;
    }

    public function nom_societe()
    {
        return $this->nom_societe;
    }

    public function setNom_societe($nom_societe)
    {
        $this->nom_societe = $nom_societe;
    }

    public function prototype_access()
    {
        return $this->prototype_access;
    }

    public function setPrototype_access($prototype_access)
    {
        $this->prototype_access = $prototype_access;
    }

    public function id_prototype_access()
    {
        return $this->id_prototype_access;
    }

    public function setId_prototype_access($id_prototype_access)
    {
        $this->id_prototype_access = $id_prototype_access;
    }

    public function nb_LE()
    {
        return $this->nb_LE;
    }

    public function setNb_LE($nb_LE)
    {
        $this->nb_LE = $nb_LE;
    }

}

