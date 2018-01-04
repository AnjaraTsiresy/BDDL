<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TempPdfLoaddatathemeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TempPdfLoaddatathemeRepository extends EntityRepository
{
    public function LoadDataTheme($id)
    {
        $query = $this
            ->createQueryBuilder('temp')
            ->select('temp.lib, th.id as idT, temp.description, s.id as id_societe')
             ->innerJoin('temp.prototypeAccess', 'pa')
            ->innerJoin('temp.societe', 's')
            ->innerJoin('temp.theme', 'th')
            ->where('pa.id = :id_prototype_access')
            ->setParameter('id_prototype_access', $id)
            ->getQuery();

        return $query->getResult();
    }

    public function getAllTempPdfLoaddatathemeByPrototypeAccess($id)
    {
        $query = $this
            ->createQueryBuilder('temp')
            ->select('temp')
            ->innerJoin('temp.prototypeAccess', 'pa')
            ->where('pa.id = :id_prototype_access')
            ->setParameter('id_prototype_access', $id)
            ->orderBy('temp.lib','asc')
            ->getQuery();

        return $query->getResult();
    }
}
