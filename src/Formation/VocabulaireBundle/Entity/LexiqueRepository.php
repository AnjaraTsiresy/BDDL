<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * LexiqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LexiqueRepository extends EntityRepository {

    public function getDataTheme($id_prototype_access) {
        $query = $this
                ->createQueryBuilder('l')
                ->select('distinct t.libelleTheme as lib, t.id as idT, s.description as description, s.id as id_societe , pa.id as idProt')
                ->innerJoin('l.prototypeAccess', 'pa')
                ->innerJoin('l.theme', 't')
                ->innerJoin('l.societe', 's')
                ->where('pa.id = :id_prototype_access')
                ->setParameter('id_prototype_access', $id_prototype_access)
                ->getQuery();
        return $query->getResult();
    }

    public function getAllLexiqueByPrototypeAccess($id_prototype_access) {
        $query = $this
                ->createQueryBuilder('l')
                ->select('l')
                ->innerJoin('l.prototypeAccess', 'pa')
                ->where('pa.id = :id_prototype_access')
                ->setParameter('id_prototype_access', $id_prototype_access)
                ->getQuery();
        return $query->getResult();
    }

    public function getLexiqueBySocieteAndThemeAndPrototypeAccess($id_societe, $id_theme, $id_prototype_access) {
        $query = $this
                ->createQueryBuilder('l')
                ->select('l')
                ->innerJoin('l.prototypeAccess', 'pa')
                ->innerJoin('l.societe', 's')
                ->innerJoin('l.theme', 't')
                ->where('pa.id = :id_prototype_access AND s.id = :id_societe AND t.id = :id_theme')
                ->setParameter('id_prototype_access', $id_prototype_access)
                ->setParameter('id_societe', $id_societe)
                ->setParameter('id_theme', $id_theme)
                ->setMaxResults(1)
                ->getQuery();
        return $query->getSingleResult();
    }

    public function getAllLexiqueBySocieteAndThemeAndPrototypeAccess($id_societe, $id_theme, $id_prototype_access) {
        $query = $this
                ->createQueryBuilder('l')
                ->select('l')
                ->innerJoin('l.prototypeAccess', 'pa')
                ->innerJoin('l.societe', 's')
                ->innerJoin('l.theme', 't')
                ->where('pa.id = :id_prototype_access AND s.id = :id_societe AND t.id = :id_theme')
                ->setParameter('id_prototype_access', $id_prototype_access)
                ->setParameter('id_societe', $id_societe)
                ->setParameter('id_theme', $id_theme)
                ->getQuery();
        return $query->getResult();
    }

    public function findAllLE1($nom_prototype, $id_theme, $theme) {
        $nom_prototype = strtolower($nom_prototype);
        $nom_prototype = trim($nom_prototype);
        if ($theme != "") {
            $theme = strtolower($theme);
            $theme = trim($theme);
            if ($theme == "*") {

                $query = $this
                        ->createQueryBuilder('l')
                        ->select('distinct t.libelleTheme as libelle_theme,t.id as id_theme, s.id as id_societe, s.description as description')
                        ->innerJoin('l.societe', 's')
                        ->innerJoin('l.theme', 't')
                        ->where('s.id != 653')
                        ->getQuery();
                if($nom_prototype != ""){
                    $query = $this
                        ->createQueryBuilder('l')
                        ->select('distinct t.libelleTheme as libelle_theme,t.id as id_theme, s.id as id_societe, s.description as description')
                        ->innerJoin('l.societe', 's')
                        ->innerJoin('l.theme', 't')
                        ->where('s.id != 653')
                        ->innerJoin('l.prototypeAccess', 'pa')
                        ->andWhere('lower(pa.type) LIKE :nom_prototype ')
                        ->setParameter('nom_prototype','%'.$nom_prototype.'%')
                        ->getQuery();
                    
                   
                }
            } else {
                $query = $this
                        ->createQueryBuilder('l')
                        ->select('distinct t.libelleTheme as libelle_theme, t.id as id_theme,s.id as id_societe, s.description as description')
                        ->innerJoin('l.societe', 's')
                        ->innerJoin('l.theme', 't')
                        ->where('s.id != 653 AND lower(t.libelleTheme) LIKE :theme ')
                        ->setParameter('theme', '%' . $theme . '%')
                        ->getQuery();
                 if($nom_prototype != ""){
                     $query = $this
                        ->createQueryBuilder('l')
                        ->select('distinct t.libelleTheme as libelle_theme,t.id as id_theme, s.id as id_societe, s.description as description')
                        ->innerJoin('l.societe', 's')
                        ->innerJoin('l.theme', 't')
                        ->where('s.id != 653 AND lower(t.libelleTheme) LIKE :theme ')
                        ->setParameter('theme', '%' . $theme . '%')
                        ->innerJoin('l.prototypeAccess', 'pa')
                        ->andWhere('lower(pa.type) LIKE :nom_prototype ')
                        ->setParameter('nom_prototype','%'.$nom_prototype.'%')
                        ->getQuery();
                }
            }
        } else {
            if ($id_theme != "") {

                $query = $this
                        ->createQueryBuilder('l')
                        ->select('distinct t.libelleTheme  as libelle_theme, t.id as id_theme, s.id as id_societe, s.description as description')
                        ->innerJoin('l.societe', 's')
                        ->innerJoin('l.theme', 't')
                        ->where('s.id != 653 AND t.id = :id_theme')
                        ->setParameter('id_theme', $id_theme)
                        ->getQuery();
                if($nom_prototype != ""){
                    $query = $this
                        ->createQueryBuilder('l')
                        ->select('distinct t.libelleTheme  as libelle_theme, t.id as id_theme, s.id as id_societe, s.description as description')
                        ->innerJoin('l.societe', 's')
                        ->innerJoin('l.theme', 't')
                        ->where('s.id != 653 AND t.id = :id_theme')
                        ->setParameter('id_theme', $id_theme)
                        ->innerJoin('l.prototypeAccess', 'pa')
                        ->andWhere('lower(pa.type) LIKE :nom_prototype ')
                        ->setParameter('nom_prototype','%'.$nom_prototype.'%')
                        ->getQuery();
                }
            } else {
                $query = $this
                        ->createQueryBuilder('l')
                        ->select('distinct t.libelleTheme  as libelle_theme, t.id as id_theme, s.id as id_societe, s.description as description')
                        ->innerJoin('l.societe', 's')
                        ->innerJoin('l.theme', 't')
                        ->where('s.id != 653')
                        ->getQuery();
                
                if($nom_prototype != ""){
                     $query = $this
                        ->createQueryBuilder('l')
                        ->select('distinct t.libelleTheme  as libelle_theme, t.id as id_theme, s.id as id_societe, s.description as description')
                        ->innerJoin('l.societe', 's')
                        ->innerJoin('l.theme', 't')
                        ->where('s.id != 653')
                        ->setParameter('id_theme', $id_theme)
                        ->innerJoin('l.prototypeAccess', 'pa')
                        ->andWhere('lower(pa.type) LIKE :nom_prototype ')
                        ->setParameter('nom_prototype','%'.$nom_prototype.'%')
                        ->getQuery();
                }
            }
        }
        return $query->getResult();
    }

}
