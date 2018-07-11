<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class MarketRepository extends EntityRepository
{
    public function getKingdomResources()
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.kingdomResource', 'kr')
            ->addSelect('kr');

        return $qb->getQuery()->getResult();
    }
}
