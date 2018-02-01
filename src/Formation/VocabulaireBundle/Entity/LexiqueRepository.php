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

    private function fetch($query)
    {
        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        $stmt->execute();
        return  $stmt->fetchAll();
    }

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

    public function rechercheContenuProtLE($id_prototype_access, $id_societe){
        $sql = "SELECT distinct societe.id_societe as id_societe, societe.description as description, theme.id_theme as id_theme, theme.libelle_theme as libelle_theme,
			theme.theme_eng as theme_eng FROM lexique
			INNER JOIN theme ON theme.id_theme = lexique.id_theme
			INNER JOIN societe ON societe.id_societe = lexique.id_societe
			WHERE lexique.id_prototype_access = '$id_prototype_access' AND lexique.id_societe = '$id_societe'
			ORDER BY theme.libelle_theme collate utf8_general_ci";

        return $this->fetch($sql);
    }

    public function getMaxRangLE($id_prototype_access){
        $rangLE = 0;
        $sql = "select max(rang) as rang from lexique where id_prototype_access='$id_prototype_access' ";
        $result = $this->fetch($sql);
        foreach ($result as $row) $rangLE = $row['rang'];
        return $rangLE;

    }

    function getNbOccurence($id_theme){
        $sql = "SELECT count(id_theme) AS nbOccurence FROM `lexique` WHERE id_theme = '$id_theme' AND id_societe = '653'";
        $nbOccurence = 0;
        $res = $this->fetch($sql);
        foreach ($res as $row) $nbOccurence = $row['nbOccurence'];
        return $nbOccurence;
    }

    public function recupIdLE($id_societe, $id_theme, $id_prototype_access){
        $id_lexique = 0;
        $sql = "select id_lexique from lexique where id_societe='$id_societe' AND id_theme='$id_theme' AND id_prototype_access='$id_prototype_access' ";
        $result = $this->fetch($sql);
        foreach($result as $row)
            $id_lexique = $row['id_lexique'];
        return $id_lexique;

    }
    public function getLexiaueByProtoTypeAndThemeAndSociete($id_prototype_access, $id_theme, $id_societe)
    {
       $sql =  "select * from lexique where id_societe='$id_societe' AND id_theme='$id_theme' AND id_prototype_access='$id_prototype_access' ";
       return $this->fetch($sql);
    }
      public function getNbLESoc() {
        $query = $this
                ->createQueryBuilder('l')
                ->select('l')
                ->innerJoin('l.societe', 's')
                ->where('s.id != 653')
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
        return $query->getResult();
    }

    public function recherchePrototypeBySoc($id_societe) {
        $query = $this
                ->createQueryBuilder('l')
                ->select('distinct s.id as id_societe, s.description as description, t.id as id_theme, t.libelleTheme as libelle_theme, 
			t.themeEng as theme_eng')
                ->innerJoin('l.societe', 's')
                ->innerJoin('l.theme', 't')
                ->where('s.id = :id_societe')
                ->setParameter('id_societe', $id_societe)
                ->orderBy('t.libelleTheme','asc')
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
