<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class KingdomBuildingRepository extends EntityRepository
{
    public function getBuildingsFromKingdom($kingdomUser)
    {
        $qb = $this
            ->createQueryBuilder('b')
            ->where('b.kingdom = :kingdom')
            ->setParameter('kingdom', $kingdomUser);

        return $qb->getQuery()->getResult();
    }
}
