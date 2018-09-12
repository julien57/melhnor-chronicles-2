<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class KingdomEventRepository extends EntityRepository
{
    public function getKingdomEvent($kingdom, $event)
    {
        $qb = $this
            ->createQueryBuilder('ke')
            ->where('ke.kingdom = :kingdom')
            ->setParameter('kingdom', $kingdom)
            ->andWhere('ke.event = :event')
            ->setParameter('event', $event)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}