<?php

namespace Formation\VocabulaireBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * VocabulaireSocieteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VocabulaireSocieteRepository extends EntityRepository {

    public function getVocabulaireSocieteByVocabulaire($id_vocabulaire) {
        $query = $this
                ->createQueryBuilder('vs')
                ->select('v.id as id_vocabulaire, s.description as description')
                ->innerJoin('vs.vocabulaire', 'v')
                ->innerJoin('vs.societe', 's')
                ->where('v.id = :id_vocabulaire')
                ->setParameter('id_vocabulaire', $id_vocabulaire)
                ->getQuery();

        return $query->getResult();
    }

    public function modifyOneCritere($id_vocabulaire) {
        $query = $this
                ->createQueryBuilder('vs')
                ->select('distinct s.description as description')
                ->innerJoin('vs.vocabulaire', 'v')
                ->innerJoin('vs.societe', 's')
                ->where('v.id = :id_vocabulaire')
                ->setParameter('id_vocabulaire', $id_vocabulaire)
                ->groupBy("v.id")
                ->getQuery();

        return $query->getResult();
    }

}
