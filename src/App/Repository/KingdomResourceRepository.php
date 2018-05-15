<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class KingdomResourceRepository extends EntityRepository
{
    public function getKingdomExistingResource($kingdom, $resource)
    {
        $qb = $this
            ->createQueryBuilder('k')
            ->where('k.kingdom = :kingdom')
            ->setParameter('kingdom', $kingdom)
            ->andWhere('k.resource = :resource')
            ->setParameter('resource', $resource)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
