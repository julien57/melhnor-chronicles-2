<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class BuildingResourceRepository extends EntityRepository
{
    public function getBuildingsWithResources()
    {
        $qb = $this
            ->createQueryBuilder('br')
            ->join('br.resource', 'resource')
            ->addSelect('resource')
            ->join('br.building', 'building')
            ->addSelect('building')
        ;

        return $qb->getQuery()->getResult();
    }
}