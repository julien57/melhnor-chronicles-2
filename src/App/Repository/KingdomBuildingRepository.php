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

    public function findLevelBuildingUp($kingdomId, $buildingId, $level)
    {
        $qb = $this
            ->createQueryBuilder('b')
            ->where('b.kingdom = :kingdom')
            ->setParameter('kingdom', $kingdomId)
            ->andWhere('b.building = :building')
            ->setParameter('building', $buildingId)
            ->andWhere('b.level != :level')
            ->setParameter('level', $level)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
