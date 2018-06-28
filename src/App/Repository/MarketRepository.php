<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class MarketRepository extends EntityRepository
{
    public function countSales()
    {
        $qb = $this
            ->createQueryBuilder('m')
            ->select('COUNT(m)');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
