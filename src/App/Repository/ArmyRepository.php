<?php

namespace App\Repository;

use App\Entity\Kingdom;
use Doctrine\ORM\EntityRepository;

class ArmyRepository extends EntityRepository
{
    public function findByKingdom(Kingdom $kingdom)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.kingdom = :kingdom')
            ->setParameter('kingdom', $kingdom)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}