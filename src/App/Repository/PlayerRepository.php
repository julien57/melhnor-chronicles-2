<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PlayerRepository extends EntityRepository
{
    /**
     * @param $page
     * @param $nbMaxPerPage
     *
     * @return Paginator
     */
    public function allPlayers()
    {
        $query = $this
            ->createQueryBuilder('p')
            ->getQuery();

        return $query->getResult();
    }

    public function playersByPopulation()
    {
        $query = $this
            ->createQueryBuilder('p')
            ->join('p.kingdom', 'k')
            ->addSelect('k')
            ->orderBy('k.population', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    public function getKingdomAndRegion(Player $player)
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.kingdom', 'k')
            ->addSelect('k')
            ->join('k.region', 'r')
            ->addSelect('r')
            ->where('p = :player')
            ->setParameter('player', $player)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
