<?php

namespace App\Repository;

use App\Entity\Kingdom;
use Doctrine\ORM\EntityRepository;

class KingdomBuildingRepository extends EntityRepository
{
    public function getBuildingsFromKingdom(Kingdom $kingdom)
    {
        $qb = $this
            ->createQueryBuilder('kb')
            ->join('kb.kingdom', 'k')
            ->addSelect('k')
            ->where('kb.kingdom = :kingdom')
            ->setParameter('kingdom', $kingdom);

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
