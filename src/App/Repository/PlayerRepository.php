<?php

namespace App\Repository;

use App\Entity\Kingdom;
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

    public function findByKingdom(Kingdom $kingdom)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->where('p.kingdom = :kingdom')
            ->setParameter('kingdom', $kingdom)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function countPlayersConnected()
    {
        $date5min = new \DateTime('5 minutes ago');

        $query = $this
            ->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.lastConnection > :date5min')
            ->setParameter('date5min', $date5min)
            ->getQuery()
        ;

        $count = $query->getSingleScalarResult();

        return $count;
    }

    public function countAllPlayers()
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->select('COUNT(p)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getChiefArmy()
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.username = :username')
            ->setParameter('username', 'chef des armées')
            ->getQuery();

        return $qb->getOneOrNullResult();
    }

    public function allPlayersWithoutAdmin()
    {
        $query = $this
            ->createQueryBuilder('p')
            ->where('p.username != :admin')
            ->setParameter('admin', 'admin')
            ->andWhere('p.username != :chef')
            ->setParameter('chef', 'chef des armées')
            ->orderBy('p.username', 'DESC')
            ->getQuery();

        return $query->getResult();
    }
}
