<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * VocabulaireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VocabulaireRepository extends EntityRepository {

   
    public function getLEgenerique()
    {
        $query = $this
                ->createQueryBuilder('v')
                ->select('t.id as id_theme, t.libelleTheme as libelle_theme, t.themeEng as theme_eng, s.id as id_societe, s.description as description')
                ->innerJoin('v.vocabulaireThemes', 'vt')
                ->innerJoin('vt.theme', 't')
                ->innerJoin('v.vocabulaireSocietes', 'vs')
                ->innerJoin('vs.societe', 's')
                ->where('s.id = 653 AND v.dateModification = \'0000-00-00 00:00:00\' AND v.isAffiche = 1')
                ->getQuery();
        return $query->getResult();
    }

    public function findLEParThematiqueBySecteur($id_secteur) {
        $query = $this
                ->createQueryBuilder('v')
                ->select('distinct s.id as id_societe, s.description as description, sect.libelleSecteur as libelle_secteur')
                ->innerJoin('v.vocabulaireSocietes', 'vs')
                ->innerJoin('vs.societe', 's')
                ->innerJoin('v.vocabulaireSecteurs', 'vsect')
                ->innerJoin('vsect.secteur', 'sect')
                ->where('sect.id = :id_secteur AND v.dateModification = \'0000-00-00 00:00:00\' AND v.isAffiche = 1')
                ->setParameter('id_secteur', $id_secteur)
                ->getQuery();
        return $query->getResult();
    }

    public function rechercheTerme($id_societe, $id_theme) {
        $query = $this
                ->createQueryBuilder('v')
                ->select('distinct v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme')
                ->innerJoin('v.vocabulaireThemes', 'vt')
                ->innerJoin('vt.theme', 't')
                ->innerJoin('v.vocabulaireSocietes', 'vs')
                ->innerJoin('vs.societe', 's')
                ->where('t.id = :id_theme AND s.id = :id_societe')
                ->setParameter('id_theme', $id_theme)
                ->setParameter('id_societe', $id_societe)
                ->getQuery();
        return $query->getResult();
    }

    public function exportRechercheTerme($id_societe, $id_theme) {
        $query = $this
                ->createQueryBuilder('v')
                ->select('distinct v.id as id_vocabulaire,s.description as description, t.libelleTheme as libelle_theme, t.themeEng as theme_eng,  v.langueTraduction as langue_traduction,
		so.sourceType as source_type,v.langueOrigine as langue_origine, so.sourceNomStagiaire as source_nom_stagiaire, so.lienNomDoc as lien_nom_doc, so.lien as lien')
                ->innerJoin('v.vocabulaireThemes', 'vt')
                ->innerJoin('vt.theme', 't')
                ->innerJoin('v.vocabulaireSocietes', 'vs')
                ->innerJoin('vs.societe', 's')
                ->innerJoin('v.source', 'so')
                ->where('t.id = :id_theme AND s.id = :id_societe')
                ->setParameter('id_theme', $id_theme)
                ->setParameter('id_societe', $id_societe)
                ->getQuery();
        return $query->getResult();
    }

    public function findLEParThematiqueBySecteurAndSuffixe($id_secteur, $id_suffixe) {
        $query = $this
                ->createQueryBuilder('v')
                ->select('distinct s.id as id_societe, s.description as description, sect.libelleSecteur as libelle_secteur')
                ->innerJoin('v.vocabulaireSocietes', 'vs')
                ->innerJoin('vs.societe', 's')
                ->innerJoin('v.vocabulaireSecteurs', 'vsect')
                ->innerJoin('vsect.secteur', 'sect')
                ->innerJoin('s.suffixeSocietes', 'sfs')
                ->innerJoin('sfs.suffixe', 'sf')
                ->where('sect.id = :id_secteur AND sf.id = :id_suffixe AND v.dateModification =\'0000-00-00 00:00:00\' AND v.isAffiche = 1')
                ->setParameter('id_secteur', $id_secteur)
                ->setParameter('id_suffixe', $id_suffixe)
                ->getQuery();

        return $query->getResult();
    }

    public function getNBTermesParLE($id_theme, $id_prototype_access) {
        $query = $this
                ->createQueryBuilder('v')
                ->select('v.id')
                ->innerJoin('v.vocabulaireThemes', 'vt')
                ->innerJoin('vt.theme', 't')
                ->innerJoin('v.vocabulairePrototypeAccesss', 'vpa')
                ->innerJoin('vpa.prototypeAccess', 'pa')
                ->where('t.id = :id_theme AND pa.id = :id_prototype_access ')
                ->setParameter('id_theme', $id_theme)
                ->setParameter('id_prototype_access', $id_prototype_access)
                ->getQuery();
        return count($query->getResult());
    }

    public function exportLE($id_theme, $id_prototype_access, $id_societe, $id_secteur) {
        $query = $this
                ->createQueryBuilder('v')
                ->select('s.description, sect.libelleSecteur as libelle_secteur, pa.type as type, t.libelleTheme as libelle_theme, t.themeEng as theme_eng,  v.langueTraduction as langue_traduction,
		so.sourceType as source_type,v.langueOrigine as langue_origine, so.sourceNomStagiaire as source_nom_stagiaire, so.lienNomDoc as lien_nom_doc, so.lien as lien')
                ->innerJoin('v.vocabulaireThemes', 'vt')
                ->innerJoin('v.source', 'so')
                ->innerJoin('vt.theme', 't')
                ->innerJoin('v.vocabulaireSocietes', 'vs')
                ->innerJoin('vs.societe', 's')
                ->innerJoin('v.vocabulairePrototypeAccesss', 'vpa')
                ->innerJoin('vpa.prototypeAccess', 'pa')
                ->where('t.id = :id_theme AND pa.id = :id_prototype_access AND sect.id = :id_secteur AND s.id = :id_societe')
                ->innerJoin('v.vocabulaireSecteurs', 'vsect')
                ->innerJoin('vsect.secteur', 'sect')
                ->setParameter('id_theme', $id_theme)
                ->setParameter('id_prototype_access', $id_prototype_access)
                ->setParameter('id_secteur', $id_secteur)
                ->setParameter('id_societe', $id_societe)
                ->getQuery();
        return $query->getResult();
    }

    public function recupNbTermes($id_prototype_access, $id_societe, $id_theme) {

        $query = $this
                ->createQueryBuilder('v')
                ->select('distinct v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme')
                ->innerJoin('v.vocabulaireSocietes', 'vs')
                ->innerJoin('v.vocabulairePrototypeAccesss', 'vpa')
                ->innerJoin('vs.societe', 's')
                ->innerJoin('v.vocabulaireThemes', 'vt')
                ->innerJoin('vt.theme', 't')
                ->innerJoin('vpa.prototypeAccess', 'pa')
                ->where('pa.id = :id_prototype_access AND s.id = :id_societe')
                ->setParameter('id_prototype_access', $id_prototype_access)
                ->setParameter('id_societe', $id_societe)
                ->getQuery();
        if ($id_theme != "") {
            $query = $this
                    ->createQueryBuilder('v')
                    ->select('distinct v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme')
                    ->innerJoin('v.vocabulaireSocietes', 'vs')
                    ->innerJoin('v.vocabulairePrototypeAccesss', 'vpa')
                    ->innerJoin('vs.societe', 's')
                    ->innerJoin('v.vocabulaireThemes', 'vt')
                    ->innerJoin('vt.theme', 't')
                    ->innerJoin('vpa.prototypeAccess', 'pa')
                    ->where('t.id = :id_theme AND pa.id = :id_prototype_access AND s.id = :id_societe')
                    ->setParameter('id_theme', $id_theme)
                    ->setParameter('id_prototype_access', $id_prototype_access)
                    ->setParameter('id_societe', $id_societe)
                    ->getQuery();
        }
        return count($query->getResult());
    }

    public function getVocabulaireByPrototypeAccessAndSocieteAndTheme($id_prototype_access, $id_societe, $id_theme) {

        $query = $this
                ->createQueryBuilder('v')
                ->select('v.id as id_vocabulaire')
                ->innerJoin('v.vocabulaireSocietes', 'vs')
                ->innerJoin('v.vocabulairePrototypeAccesss', 'vpa')
                ->innerJoin('vs.societe', 's')
                ->innerJoin('vpa.prototypeAccess', 'pa')
                ->where('pa.id = :id_prototype_access AND s.id = :id_societe')
                ->setParameter('id_prototype_access', $id_prototype_access)
                ->setParameter('id_societe', $id_societe)
                ->getQuery();
        if ($id_theme != "") {
            $query = $this
                    ->createQueryBuilder('v')
                    ->select('v.id as id_vocabulaire')
                    ->innerJoin('v.vocabulaireSocietes', 'vs')
                    ->innerJoin('v.vocabulairePrototypeAccesss', 'vpa')
                    ->innerJoin('vs.societe', 's')
                    ->innerJoin('v.vocabulaireThemes', 'vt')
                    ->innerJoin('vt.theme', 't')
                    ->innerJoin('vpa.prototypeAccess', 'pa')
                    ->where('t.id = :id_theme AND pa.id = :id_prototype_access AND s.id = :id_societe')
                    ->setParameter('id_theme', $id_theme)
                    ->setParameter('id_prototype_access', $id_prototype_access)
                    ->setParameter('id_societe', $id_societe)
                    ->getQuery();
        }
        return $query->getResult();
    }

    public function findTermes($terme, $langues_recherche, $dic) {
        if ($terme == "") {
            $query = array();
        } else {

            if ($terme != "") {
                $terme = strtolower($terme);
                if ($langues_recherche == "francais") {
                    if ($dic != "") {
                        if ($dic == "fr1") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->andWhere('lower(v.langueOrigine) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueOrigine', 'asc')
                                    ->getQuery();
                        } else if ($dic == "en1") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->andWhere('lower(v.langueOrigine) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueTraduction', 'asc')
                                    ->getQuery();
                        } else if ($dic == "en2") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->andWhere('lower(v.langueOrigine) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueTraduction', 'desc')
                                    ->getQuery();
                        } else if ($dic == "fr2") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->andWhere('lower(v.langueOrigine) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueOrigine', 'desc')
                                    ->getQuery();
                        }
                    } else {
                        $query = $this
                                ->createQueryBuilder('v')
                                ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                ->innerJoin('v.vocabulaireThemes', 'vt')
                                ->innerJoin('vt.theme', 't')
                                ->andWhere('lower(v.langueOrigine) LIKE :terme')
                                ->setParameter('terme', '%' . trim($terme) . '%')
                                ->getQuery();
                    }
                } else if ($langues_recherche == "anglais") {
                    if ($dic != "") {
                        if ($dic == "fr1") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->andWhere('lower(v.langueTraduction) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueOrigine', 'asc')
                                    ->getQuery();
                        } else if ($dic == "en1") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->andWhere('lower(v.langueTraduction) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueTraduction', 'asc')
                                    ->getQuery();
                        } else if ($dic == "en2") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->andWhere('lower(v.langueTraduction) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueTraduction', 'desc')
                                    ->getQuery();
                        } else if ($dic == "fr2") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->andWhere('lower(v.langueTraduction) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueOrigine', 'desc')
                                    ->getQuery();
                        }
                    } else {
                        $query = $this
                                ->createQueryBuilder('v')
                                ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                ->innerJoin('v.vocabulaireThemes', 'vt')
                                ->innerJoin('vt.theme', 't')
                                ->andWhere('lower(v.langueTraduction) LIKE :terme')
                                ->setParameter('terme', '%' . trim($terme) . '%')
                                ->getQuery();
                     
                    }
                } else {
                    if ($dic != "") {
                        if ($dic == "fr1") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->orWhere('lower(v.langueOrigine) LIKE :terme')
                                    ->orWhere('lower(v.langueTraduction) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueOrigine', 'asc')
                                    ->getQuery();
                        } else if ($dic == "en1") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->orWhere('lower(v.langueOrigine) LIKE :terme')
                                    ->orWhere('lower(v.langueTraduction) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueTraduction', 'asc')
                                    ->getQuery();
                        } else if ($dic == "en2") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->orWhere('lower(v.langueOrigine) LIKE :terme')
                                    ->orWhere('lower(v.langueTraduction) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueTraduction', 'desc')
                                    ->getQuery();
                        } else if ($dic == "fr2") {
                            $query = $this
                                    ->createQueryBuilder('v')
                                    ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                    ->innerJoin('v.vocabulaireThemes', 'vt')
                                    ->innerJoin('vt.theme', 't')
                                    ->orWhere('lower(v.langueOrigine) LIKE :terme')
                                    ->orWhere('lower(v.langueTraduction) LIKE :terme')
                                    ->setParameter('terme', '%' . trim($terme) . '%')
                                    ->orderBy('v.langueOrigine', 'desc')
                                    ->getQuery();
                        }
                    } else {
                        $query = $this
                                ->createQueryBuilder('v')
                                ->select('v.id as id_vocabulaire, v.langueTraduction as langue_traduction, v.langueOrigine as langue_origine, t.libelleTheme as libelle_theme, t.themeEng as theme_eng')
                                ->innerJoin('v.vocabulaireThemes', 'vt')
                                ->innerJoin('vt.theme', 't')
                                ->orWhere('lower(v.langueOrigine) LIKE :terme')
                                ->orWhere('lower(v.langueTraduction) LIKE :terme')
                                ->setParameter('terme', '%' . trim($terme) . '%')
                                ->getQuery();
                    }
                }
            }
            return $query->getResult();
        }
        return $query;
    }

}
