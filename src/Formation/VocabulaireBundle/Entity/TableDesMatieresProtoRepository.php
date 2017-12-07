<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TableDesMatieresProtoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TableDesMatieresProtoRepository extends EntityRepository
{
    public function getAllTableDesMatieresProtoBySocieteAndPrototype($id_societe, $id)
    {
        $query = $this
            ->createQueryBuilder('tm')
            ->select('tm')
            ->innerJoin('tm.noPrototype', 'pa')
            ->innerJoin('tm.societe', 's')
            ->where('pa.id = :id_prototype_access AND s.id = :id_societe')
            ->setParameter('id_societe', $id_societe)
            ->setParameter('id_prototype_access', $id)
            ->getQuery();

        return $query->getResult();
    }

    public function getMinOrdreSousTheme($theme, $id_societe, $id)
    {
        $query = $this
            ->createQueryBuilder('tm')
            ->select(' tm.ordreSousTheme as min_ordre')
            ->innerJoin('tm.noPrototype', 'pa')
            ->innerJoin('tm.societe', 's')
            ->where('pa.id = :id_prototype_access AND s.id = :id_societe AND tm.theme = :theme')
            ->setParameter('id_societe', $id_societe)
            ->setParameter('id_prototype_access', $id)
            ->setParameter('theme', $theme)
            ->getQuery();

        $result = $query->getResult();
        $numpge = 0;
        foreach ($result as $res)
        {

            $numpge = $res['min_ordre'];
        }
        return $numpge;
    }
}
