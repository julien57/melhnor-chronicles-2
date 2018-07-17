<?php

namespace App\Repository;

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
}
