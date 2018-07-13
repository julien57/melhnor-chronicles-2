<?php

namespace App\Repository;

use App\Entity\Kingdom;
use Doctrine\ORM\EntityRepository;

class KingdomResourceRepository extends EntityRepository
{
    public function findByKingdom(Kingdom $kingdom)
    {
        $qb = $this
            ->createQueryBuilder('kr')
            ->join('kr.resource', 'r')
            ->addSelect('r')
            ->where('kr.kingdom = :kingdom')
            ->setParameter('kingdom', $kingdom)
        ;

        return $qb->getQuery()->getResult();
    }

    public function getKingdomExistingResource($kingdom, $resource)
    {
        $qb = $this
            ->createQueryBuilder('kr')
            ->where('kr.kingdom = :kingdom')
            ->setParameter('kingdom', $kingdom)
            ->andWhere('kr.resource = :resource')
            ->setParameter('resource', $resource)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
