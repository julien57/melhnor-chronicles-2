<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class MarketRepository extends EntityRepository
{
    public function getKingdomResources()
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.kingdomResource', 'kr')
            ->addSelect('kr')
            ->join('kr.kingdom', 'k')
            ->addSelect('k')
        ;

        return $qb->getQuery()->getResult();
    }

    public function countSales()
    {
        $qb = $this
            ->createQueryBuilder('m')
            ->select('COUNT(m)');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
