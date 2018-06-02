<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PlayerRepository extends EntityRepository
{
    /**
     * @param $page
     * @param $nbMaxPerPage
     * @return Paginator
     */
    public function allPlayersWithPagination($page, $nbMaxPerPage): Paginator
    {
        $qb = $this->createQueryBuilder('p');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbMaxPerPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxPerPage);
        $paginator = new Paginator($query);

        return $paginator;
    }
}
